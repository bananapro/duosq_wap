<?php

//特卖商品详情页
function promoGoodsUrl($sp, $goods_id){

	if(!$sp || !$goods_id)return '/';
	return MY_HOMEPAGE_URL.'/item-'.$sp.'-'.$goods_id;
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
?>