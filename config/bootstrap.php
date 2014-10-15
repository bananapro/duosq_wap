<?php

//特卖商品详情页
function promoGoodsUrl($sp, $goods_id, $tc=''){

	if(!$sp || !$goods_id)return '/';
	if($tc){
		return MY_HOMEPAGE_URL.'/item-'.$sp.'-'.$goods_id.'?tc='.$tc;
	}else{
		return MY_HOMEPAGE_URL.'/item-'.$sp.'-'.$goods_id;
	}
}

//特卖大分类url
function promoCatUrl($cat){

	if(!$cat)return '/';
	return MY_HOMEPAGE_URL.'/promo-cat-'.urlencode($cat);
}

//特卖中分类url
function promoMidcatUrl($midcat){

	if(!$midcat)return '/';
	return MY_HOMEPAGE_URL.'/promo-midcat-'.urlencode($midcat);
}

//转换外链
function promoUrl($sp, $goods_id, $url, $tc='wap'){

	if(!$sp || !$goods_id){
		return "javascript:alert('链接错误，请稍后尝试！');\" target='_self'";
	}

	//if(isset($_GET['tc']))$tc = $_GET['tc'];

	//获取跳转驱动
	if(D('go')->getDriver($sp)){

		if($sp == 'tmall' || $sp == 'taobao'){
			//淘宝移动端hack跳转跟单
			$url = MY_WWW_URL .'/item-'.$sp.'-'.$goods_id.'?tc='.$tc;
		}else{
			$url = MY_WWW_URL . "/go/{$sp}?tc={$tc}&t=".urlencode($url);
		}
	}

	return $url;
}
?>