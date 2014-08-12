<?php
//特卖页面
class PromotionController extends AppController {

	var $name = 'Promotion';
	var $components = array('Pagination');
	var $cacheAction = array('cat'=>600,'midcat'=>600,'detail'=>600);

	//特卖分类商品列表
	function cat($cat){

		if(!$cat)
			$cat = '服装鞋子';
		else
			$cat = urldecode($cat);

		$all_goods_cat = D('promotion')->getCatConfig(true);
		$cond = array();
		$cond['cat'] = $cat;

		$lists = D('promotion')->getList($this->Pagination, $cond, C('comm', 'wap_promo_cat_goods_pre_page'), false);

		$this->set('title', '今日'.$cat.'特卖');

		$this->set('lists', $lists);
		$this->set('cat', $cat);
		$this->set('all_goods_cat', $all_goods_cat);
		$this->set('stat', D('promotion')->getStat());
	}

	//特卖中分类商品列表
	function midcat($midcat){

		$this->action = 'cat';

		$midcat = urldecode($midcat);
		if(!$midcat)$this->redirect('/', 301);

		$cat = D('promotion')->midcat2cat($midcat);
		$all_goods_cat = D('promotion')->getCatConfig(true);
		$cond = array();
		$cond['cat'] = $cat;
		$cond['subcat'] = D('promotion')->midcat2subcat($midcat);

		$lists = D('promotion')->getList($this->Pagination, $cond, C('comm', 'wap_promo_cat_goods_pre_page'), false);

		if($midcat){
			$this->set('title', '今日'.$midcat.'特卖');
		}else{
			$this->set('title', '今日'.$cat.'特卖');
		}

		$this->set('lists', $lists);
		$this->set('cat', $cat);
		$this->set('midcat', $midcat);
		$this->set('all_goods_cat', $all_goods_cat);
		$this->set('stat', D('promotion')->getStat());
	}

	//促销商品详情页
	function detail($id_str){

		if(!preg_match('/([0-9a-z]+)-([0-9]+)/', $id_str, $m)){
			$this->flash('参数错误，系统自动返回首页', '/', 2);
		}
		$sp = $m[1];
		$goods_id = intval($m[2]);
		if(!$sp || !$goods_id)$this->flash('参数错误，系统自动返回首页', '/', 2);

		$promo = D('promotion')->promoDetail($sp, $goods_id);
		$goods = D('promotion')->goodsDetail($sp, $goods_id);
		if(!$goods)$this->redirect('/', 301);

		$promo = D('promotion')->renderPromoDetail(array($promo));
		$promo = array_pop($promo);
		$this->set('promo', $promo);

		if($promo){
			$this->set('title', '【'.D('shop')->getName($sp).'今日降'.rate_diff($promo['price_now'], $promo['price_avg']).'%】正品'.$promo['name'].'特卖 - 特卖订阅');
		}else{
			$this->set('title', '【'.D('shop')->getName($sp).'正品】'.$promo['name'].'特卖 - 特卖订阅');
		}

		$this->set('all_goods_cat', D('promotion')->getCatConfig(true));

		$this->set('meta_keywords', join('特卖', explode(' ', $promo['name'])));
		$this->set('meta_description', D('shop')->getName($sp).'正品特卖,'.join('促销', explode(' ', $promo['name'])).' 今日仅售'.$promo['price_now'].'元包邮,价格当天有效');
	}
}
?>