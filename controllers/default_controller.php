<?php
//WAP首页
class DefaultController extends AppController {

	var $name = 'Default';
	var $components = array('Pagination');
	var $cacheAction = array('index'=>600);

	function index(){

		//模板需要用到常量
		$this->set('all_goods_cat', D('promotion')->getCatConfig(true));
		$this->set('stat', D('promotion')->getStat());
		$this->set('home', true);
	}
}
?>