<?php
//APP客户端下载页面
class ClientController extends AppController {

	var $name = 'Client';

	//APP下载引导页
	function index(){}

	//拷贝下载链接
	function link(){

		if(getBrowser()=='ios'){
			$link = C('comm', 'app_download_ios');
		}else{
			$link = C('comm', 'app_download_android');
		}
		$this->set('link', $link);
		$this->set('hide_nav', true);
		$this->set('hide_footer', true);
	}
}
?>