<?php
//订阅页面
class SubscribeController extends AppController {

	var $name = 'Subscribe';

	//订阅设置
	function index(){

		$this->set('title', '特卖订阅设置');

		$email = D('myuser')->getSubscribeEmail();

		if(!$email){

			$this->set('error', '<div class="notice">登陆已失效，请重新从我们的邮件，重新进入！</div>');

		}else{

			$sess_id = D('subscribe')->sessCreate();
			if(!$sess_id){
				$this->set('error', '发生错误，请返回上一界面，重新进入！');
			}else{
				$this->set('sess_id', $sess_id);
			}

			$setting = D('subscribe')->getSetting($email);
			D('subscribe')->sessInit($sess_id, $setting);

			$this->set('all_goods_cat', $all_goods_cat = D('promotion')->getCatConfig(true));
			$this->set('setting', $setting);

			$default_midcat = array();

			if(@$_GET['default_cat']){
				$default_midcat = D('promotion')->midcat($_GET['default_cat']);
			}

			if(@$_GET['default_midcat']){
				$default_midcat = array($_GET['default_midcat']);
			}

			$this->set('default_midcat', $default_midcat);

			//新订阅或者订阅禁止状态，允许直接提交
			if( !$setting || $setting['status'] == \DB\Subscribe::STATUS_STOP){
				$this->set('enable_submit', true);
			}
		}

		$this->set('hide_footer', true);
		$this->set('in_subscribe', true);
	}

	//意见反馈
	function feedback(){

		$this->set('friend_links', true);
		$this->set('title', '订阅反馈建议');
		$this->set('meta_keywords', '订阅反馈建议');
		$this->set('meta_description', '订阅反馈建议');

		//提交数据
		if($this->data){
			$data = $this->data['subscribe_feedback'];
			if(D('myuser')->getSubscribeEmail()){
				$data['account'] = D('myuser')->getSubscribeEmail();
				$data['channel'] = 'email';
			}
			$ret = D('subscribe')->addFeedback($data);

			if($ret){
				$this->flash('感谢您的建议，我们会认真查看并给您反馈！', '/subscribe', 5);
			}
		}
	}
}
?>