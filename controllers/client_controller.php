<?php
//APP客户端下载页面
class ClientController extends AppController {

	var $name = 'Client';

	function index(){}

	function link(){

		$this->set('hide_nav', true);
		$this->set('hide_footer', true);
	}
}
?>