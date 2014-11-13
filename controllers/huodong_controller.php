<?php
//活动页面
class HuodongController extends AppController {

	var $name = 'Huodong';

	//显示抽奖界面
	function index(){

		//中奖配置
		$config = array(
			1=>0,
			2=>30,
			3=>20,
			4=>1,
			5=>100,
			6=>1000,
		);

		//如果为推广来源，抽奖10个集分宝
		if(isset($_GET['code']) && $_GET['code'] && ($_GET['code'] == md5(date('Ymd').'kkey')) && !D('myuser')->islogined()){
			$prize = 3;
		}elseif(isset($_GET['code']) && $_GET['code'] && ($_GET['code'] == md5(date('Ymd', time()-DAY).'kkey')) && !D('myuser')->islogined()){
			$prize = 3;
		}else{
			$prize = 4;
			if(date('Ymd') == '20141109' || date('Ymd') == '20141110' || date('Ymd') == '20141111'){
				$rand = rand(1,10);
				if($rand < 2){
					$prize = 3;
				}
			}
		}

		//提交出现错误，仍然记住上次集分宝
		if(isset($_GET['hint']) && $_GET['hint']){
			//出现错误提示，不进行重复抽奖
		}else{
			//设置奖金
			D('myuser')->newgift($config[$prize], false);
		}

		$this->set('prize', $prize);
		$this->set('jfb', $config[$prize]);
		if(D('vcode')->need()){
			$this->set('need_vcode', true);
		}

		$this->set('title', '集分宝抽奖,集分宝签到,集分宝抽奖');
		$this->set('meta_keywords', '集分宝,集分宝签到,淘宝集分宝,支付宝集分宝,集分宝怎么用');
		$this->set('meta_description', '每日赠送1w集分宝，大量赠送集分宝，永久有效，希望免费领集分宝的朋友赶紧来吧');
	}

	//领取奖励
	function getPrize(){

		$this->layout = 'hint';

		$need = D('vcode')->need();
		if($need){
			$code = $_GET['vcode'];
			if(!$code || !D('vcode')->verify($code)){
				$this->redirect('/huodong?hint=请在下面填入验证码&alipay='.$_GET['alipay']);
			}
		}

		if(!D('myuser')->islogined()){

			if($_GET['alipay']){

				if(!valid($_GET['alipay'], 'email') && !valid($_GET['alipay'], 'mobile')){
					$this->redirect('/huodong?hint=支付宝错误，是手机号或邮箱才对哟!&alipay='.$_GET['alipay']);
				}

				//进行登录
				$ret = D('myuser')->saveAlipay($_GET['alipay'], $err);
				if(!$ret)$this->redirect('/huodong?hint='.$err.'&alipay='.$_GET['alipay']);

				$exist = $ret['exist'];

				$ret = D('myuser')->login($ret['user_id']);
				if(!$ret)$this->redirect('/huodong?hint=系统登录错误，请重试&alipay='.$_GET['alipay']);

				//判断是否恶意注册
				if(!$exist)D('protect')->attackReg();
				D('vcode')->record();

			}else{
				$this->redirect('/huodong?hint=您尚未输入支付宝，请重新输入！');
			}

			//如果支付宝无效，则不进行增加资产
			if(D('myuser')->getAlipayValid() == \DAL\User::ALIPAY_VALID_ERROR)$ch_alipay = true;

		}elseif(D('myuser')->islogined() && @$_GET['ch_alipay']){

			//如果支付宝无效，则不进行增加资产
			if(D('myuser')->getAlipayValid() == \DAL\User::ALIPAY_VALID_ERROR)$ch_alipay = true;

			$ch_alipay = true;
			$ret = D('myuser')->changeAlipay($_GET['alipay'], $err);
			if(!$ret)$this->redirect('/huodong?hint='.$err.'&alipay='.$_GET['alipay']);
		}

		if(!D('myuser')->canGetCashgift()){
			$this->flash('您已经抽过奖，请将机会让给更多的人！', '/huodong', 3);
		}

		//判断是否已有积分
		$amount_exist = D('myuser')->newgift(0, true);

		if(!$amount_exist || $amount_exist> 10000){
			$this->flash('抽奖无效，请重新抽奖！', '/huodong', 3);
		}

		//进行打款，捕获支付状态，显示提示，并提醒手机APP可多次抽奖，并提示状态
		$ret = D('order')->redis('lock')->getlock(\Redis\Lock::LOCK_LOTTERY_ADD, D('myuser')->getId());
		if($ret){

			$amount = D('myuser')->newgift(0, true);

			if(!@$ch_alipay){
				//插入新人抽奖集分宝红包订单
				D('order')->db('order_cashgift');
				$ret = D('order')->addCashgift(D('myuser')->getId(), \DB\OrderCashgift::GIFTTYPE_LOTTERY, $amount);
				if(!$ret){
					D('order')->redis('lock')->unlock(\Redis\Lock::LOCK_LOTTERY_ADD, D('myuser')->getId());
					$this->flash('抽奖无效，请返回重试！', '/huodong', 3);
				}
				D('log')->action(1220, 1, array('data1'=>$ret['o_id'], 'data2'=>$ret['amount'], 'data3'=>'wap'));
			}

			//走接口支付
			D()->api('interal')->pay(D('myuser')->getId());
			D()->db('order_reduce');
			$hit = D('order')->getSubList('reduce', array('user_id'=>D('myuser')->getId(), 'createdate'=>date('Y-m-d'), 'status'=>\DB\OrderReduce::STATUS_PAY_DONE), 'o_id DESC');

			if($hit){
				$this->set('message', '已支付，请登录支付宝查收~<br /><a href="'.C('comm', 'app_downlaod_auto').'">下载APP每天额外抽奖(100%中)！</a>');
			}

			if(D('myuser')->getAlipayValid() == \DAL\User::ALIPAY_VALID_ERROR){
				D('order')->redis('lock')->unlock(\Redis\Lock::LOCK_LOTTERY_ADD, D('myuser')->getId());
				$this->redirect('/huodong?hint=alipay_not_exist&alipay='.$_GET['alipay']);
			}

			if(!$hit){
				$this->set('message', '3小时内到账支付宝，注意查收~<br /><a href="'.C('comm', 'app_downlaod_auto').'">下载APP每天额外抽奖(100%中)！</a>');
			}

			//清除中奖信息
			D('myuser')->newgift();

		}else{
			$this->set('message', '今日已抽过奖了，请明天再来~<br /><a href="'.C('comm', 'app_downlaod_auto').'">下载APP每天额外抽奖(100%中)！</a>');
		}
	}
}
?>