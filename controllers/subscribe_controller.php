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
		}

		$this->set('hide_footer', true);
		$this->set('in_subscribe', true);
	}
}
?>