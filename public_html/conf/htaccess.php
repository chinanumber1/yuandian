<?php
@libxml_disable_entity_loader(true);
return array(
	'URL_ROUTER_ON'   => true, //开启路由
	'URL_ROUTE_RULES' => array( //定义路由规则
	
		'/^scenic_api\/CheckCode$/'=>'g=Appapi&c=Scenic_zj&a=check_ticket',
		'/^scenic_api\/IsConnect$/'=>'g=Appapi&c=Scenic_zj&a=heart',
		'/^scenic_api\/DateResultById$/'=>'g=Appapi&c=Scenic_zj&a=ticket_num_id',
		'/^scenic_api\/DateResultAll$/'=>'g=Appapi&c=Scenic_zj&a=ticket_num_all',

		'/^topic\/(\w+)$/'=>'g=Index&c=Topic&a=:1',
		'/^intro\/(\d+)$/'=>'g=Index&c=Intro&a=index&id=:1',

		//平台公告
		'/^news\/(\d+)$/' => 'g=Index&c=News&a=index&id=:1',
		'/^news\/cat-(\d+)$/' => 'g=Index&c=News&a=index&category_id=:1',
		'/^news$/' => array('Index/News/index'),

		//平台优惠券
		'/^coupon\/(\d+)$/' =>'g=Index&c=Coupon&a=show&coupon_id=:1',

		//团购
		'/^group\/(\d+)$/'=> 'g=Group&c=Detail&a=index&group_id=:1',
		'/^group\/buy\/(\d+)$/'=> 'g=Group&c=Detail&a=buy&group_id=:1',
		'/^category\/(\w+)\/(\w+)\/(.*)$/'=>'g=Group&c=Index&a=index&cat_url=:1&area_url=:2&order=:3',
		'/^category\/(\w+)\/(\w+)$/'=>'g=Group&c=Index&a=index&cat_url=:1&area_url=:2',
		'/^category\/(\w+)$/'=>'g=Group&c=Index&a=index&cat_url=:1',
		'/^group\/around$/'=>'g=Group&c=Around&a=index',
		'/^group\/around\/(.*)$/'=>'g=Group&c=Around&a=around&order=:1',
		'/^search\/group\/(.*)\/(.*)$/'=>'g=Group&c=Search&a=index&w=:1&order=:2',

		//预约
		'/^appoint\/(\d+)$/'=>'g=Appoint&c=Detail&a=index&appoint_id=:1',
//			'/^appoint\/order\/(\d+)$/'=>'g=Appoint&c=Detail&a=order&appoint_id=:1',
		'/^appoint\/category\/(\w+)\/(\w+)\/(.*)$/'=>'g=Appoint&c=Index&a=category_list&cat_url=:1&area_url=:2&order=:3',
		'/^appoint\/category\/(\w+)\/(\w+)$/'=>'g=Appoint&c=Index&a=category_list&cat_url=:1&area_url=:2',
		'/^appoint\/category\/(\w+)$/'=>'g=Appoint&c=Index&a=category_list&cat_url=:1',
		'/^appoint\/category$/'=>'g=Appoint&c=Index&a=category',
		'/^appoint\/article\/(\w+)$/'=>'g=Appoint&c=Index&a=article&id=:1',
//			'/^appoint\/around\/(.*)$/'=>'g=Appoint&c=Around&a=around&order=:1',
//			'/^appoint\/around$/'=>'g=Appoint&c=Around&a=around',
		'/^appoint$/'=>'g=Appoint&c=Index&a=index',

		//快店
		'/^meal\/(\d+)\/(\d+)$/'=>'g=Meal&c=Detail&a=index&sort_type=:1&store_id=:2',
		'/^meal\/(\d+)$/'=>'g=Meal&c=Detail&a=index&store_id=:1',
		'/^meal\/order\/(\d+)$/'=>'g=Meal&c=Order&a=index&store_id=:1',
		'/^meal\/reply\/(\d+)$/'=>'g=Meal&c=Reply&a=index&store_id=:1',
		'/^meal\/info\/(\d+)$/'=>'g=Meal&c=Info&a=index&store_id=:1',
		'/^meal\/(\w+)\/(\w+)\/(.*)$/'=>'g=Meal&c=Index&a=index&cat_url=:1&area_url=:2&order=:3',
		'/^meal\/(\w+)\/(\w+)$/'=>'g=Meal&c=Index&a=index&cat_url=:1&area_url=:2',
		'/^meal\/(\w+)$/'=>'g=Meal&c=Index&a=index&cat_url=:1',
		'/^kd\/(\w+)\/(\w+)\/(.*)$/'=>'g=Meal&c=Kuaidian&a=index&cat_url=:1&area_url=:2&order=:3',
		'/^kd\/(\w+)\/(\w+)$/'=>'g=Meal&c=Kuaidian&a=index&cat_url=:1&area_url=:2',
		'/^kd\/(\w+)$/'=>'g=Meal&c=Kuaidian&a=index&cat_url=:1',
		
		'/^shop\/change$/'=>'g=Shop&c=Index&a=index',
		'/^shop$/'=>'g=Shop&c=Store&a=index',
		'/^shop\/(\d+)$/'=>'g=Shop&c=Store&a=detail&store_id=:1',
		'/^shop\/order\/(\d+)$/'=>'g=Meal&c=Shoporder&a=index&store_id=:1',
		'/^shop\/comment\/(\d+)$/'=>'g=Shop&c=Store&a=comment&store_id=:1',
		'/^shop\/comment\/(\d+)\/(\w+)$/'=>'g=Shop&c=Store&a=comment&store_id=:1&tab=:2',
		'/^shop\/auth\/(\d+)$/'=>'g=Shop&c=Store&a=auth&store_id=:1',
		'/^shop\/(\w+)\/(\w+)\/(.*)$/'=>'g=Shop&c=Store&a=index&cat_url=:1&sort_url=:2&type_url=:3',
		'/^shop\/(\w+)\/(\w+)$/'=>'g=Shop&c=Store&a=index&cat_url=:1&sort_url=:2',
		'/^shop\/(\w+)$/'=>'g=Shop&c=Store&a=index&cat_url=:1',
// 			'/^shop\/(\w+)$/'=>'g=Shop&c=Store&a=index&cat_url=:1',
		
// 			'/^shop$/'=>'g=Meal&c=Shop&a=index&cat_url=all',
// 			'/^shop\/order\/(\d+)$/'=>'g=Meal&c=Shoporder&a=index&store_id=:1',
// 			'/^shop\/(\d+)$/'=>'g=Meal&c=Shopdetail&a=index&store_id=:1',
// 			'/^shop\/around$/'=>'g=Meal&c=Shop&a=around&cat_url=all',
// 			'/^shop\/(\w+)\/(\w+)\/(.*)$/'=>'g=Meal&c=Shop&a=index&cat_url=:1&type_url=:2&order=:3',
// 			'/^shop\/(\w+)\/(\w+)$/'=>'g=Meal&c=Shop&a=index&cat_url=:1&type_url=:2',
// 			'/^shop\/(\w+)$/'=>'g=Meal&c=Shop&a=index&cat_url=:1',

		//活动
		'/^lottery\/(\w+)\/(\w+)\/(.*)$/'=>'g=Lottery&c=Index&a=index&cat_url=:1&area_url=:2&order=:3',
		'/^lottery\/(\w+)\/(\w+)$/'=>'g=Lottery&c=Index&a=index&cat_url=:1&area_url=:2',
		'/^lottery\/(\w+)$/'=>'g=Lottery&c=Index&a=index&cat_url=:1',

		//分类信息
		'/^classify\/list-(\d+)(.*)$/'=>'g=Release&c=Classify&a=Lists&cid=:1:2',
		'/^classify\/(\d+)$/'=>'g=Release&c=Classify&a=ShowDetail&vid=:1',
		'/^classify\/select2sub-(\d+)$/'=>'g=Release&c=Classify&a=Select2Sub&cid=:1',
		'/^classify\/Select2Sub-(\d+)$/'=>'g=Release&c=Classify&a=Select2Sub&cid=:1',
		'/^classify\/selectsub$/'=>'g=Release&c=Classify&a=SelectSub',
		'/^classify\/SelectSub$/'=>'g=Release&c=Classify&a=SelectSub',

		'/^classify\/fabu-(\d+)-(\d+)$/'=>'g=Release&c=Classify&a=fabu&cid=:1&fcid=:2',
		'/^classify\/edit-(\d+)$/'=>'g=Release&c=Classify&a=edit&id=:1',
		'/^classify\/subdirectory-(\d+)$/'=>'g=Release&c=Classify&a=Subdirectory&cid=:1',
		'/^classify\/searchlist-(\d+)-(\d+)(.*)$/'=>'g=Release&c=Classify&a=searchList&cid=:1&subdir=:2:3',
		'/^classify\/searchlist(.*)$/'=>'g=Release&c=Classify&a=searchList:1',
		'/^classify\/userindex$/'=>'g=User&c=Index&a=index',
		'/^classify\/userlogout$/'=>'g=Index&c=Login&a=logout',
		'/^classify\/mycenter$/'=>'g=Release&c=Classify&a=myCenter',
		'/^classify\/myfabu-(\d+)(.*)$/'=>'g=Release&c=Classify&a=myfabu&uid=:1:2',
		'/^classify\/myfabu(.*)$/'=>'g=Release&c=Classify&a=myfabu:1',
		'/^classify\/mycollect-(\d+)$/'=>'g=Release&c=Classify&a=myCollect&uid=:1',
		'/^classify\/mycollect$/'=>'g=Release&c=Classify&a=myCollect',
		'/^classify\/list-(\d+)-(\d+)(.*)$/'=>'g=Release&c=Classify&a=Lists&cid=:1&sub3dir=:2:3',
		'/^classify$/'=>array('Release/Classify/index'),

		'/^merindex\/(\d+)$/'=>'g=Index&c=Merchant&a=index&merid=:1',
		'/^merintroduce\/(\d+)$/'=>'g=Index&c=Merchant&a=merintroduce&merid=:1',
		'/^mernews\/(\d+)(.*)$/'=>'g=Index&c=Merchant&a=mernews&merid=:1:2',
		'/^newsdetail\/(\d+)(.*)$/'=>'g=Index&c=Merchant&a=newsdetail&merid=:1:2',
		'/^mergallery\/(\d+)$/'=>'g=Index&c=Merchant&a=mergallery&merid=:1',
		'/^merclient\/(\d+)$/'=>'g=Index&c=Merchant&a=merclient&merid=:1',
		'/^merjoin\/(\d+)$/'=>'g=Index&c=Merchant&a=merjoin&merid=:1',
		'/^mermap\/(\d+)(.*)$/'=>'g=Index&c=Merchant&a=mermap&merid=:1:2',

		'/^merreviews\/(\d+)(.*)$/'=>'g=Index&c=Merchant&a=merreviews&merid=:1:2',
		'/^meractivity\/(\d+)$/'=>'g=Index&c=Merchant&a=meractivity&merid=:1',
		'/^mergoods\/(\d+)$/'=>'g=Index&c=Merchant&a=mergoods&merid=:1:2',
		'/^mergoods\/(\d+)\/(\d+)$/'=>'g=Index&c=Merchant&a=mergoods&merid=:1&sid=:2:3',

		//平台活动
		'/^activity\/(\w+)\/(\w+)\/(.*)$/'=>'c=Activity&a=index&type=:1&area=:2&page=:3',
		'/^activity\/(\w+)\/(\w+)$/'=>'c=Activity&a=index&type=:1&area=:2',
		'/^activity\/(\d+)$/'=>'c=Activity&a=detail&id=:1',
		'/^activity$/'=>'c=Activity&a=index',
		
		//微信验证
	    '/^MP_verify_(\w+)\.txt$/'=>'c=Mpverify&a=index&code=:1',
	    '/^mall$/'=>'?g=Mall&c=Index&a=index',
	    
	    '/^mall\/(\d+)$/'=>'g=Mall&c=Index&a=detail&catefid=:1',
	    '/^mall\/(\d+)\/(\d+)$/'=>'g=Mall&c=Index&a=detail&catefid=:1&cateid=:2',
	    '/^mall\/goods\/(\d+)$/'=>'g=Mall&c=Index&a=goods&goodsid=:1',
	    '/^mall\/shop\/(\d+)$/'=>'g=Mall&c=Index&a=shop&store_id=:1',
	    '/^mall\/search\/(\d+)$/'=>'g=Mall&c=Index&a=search&search_type=:1',
	    '/^mall\/shop\/(\d+)\/(\d+)$/'=>'g=Mall&c=Index&a=shop&store_id=:1&sort_id=:2',
	    '/^mall\/scomment\/(\d+)$/'=>'g=Mall&c=Index&a=scomment&store_id=:1',
	    '/^mall\/scomment\/(\d+)\/(\w+)$/'=>'g=Mall&c=Index&a=scomment&store_id=:1&tab=:2',
	    '/^mall\/comment\/(\d+)$/'=>'g=Mall&c=Index&a=comment&goodsid=:1',
	    '/^mall\/comment\/(\d+)\/(\w+)$/'=>'g=Mall&c=Index&a=comment&goodsid=:1&tab=:2',
	    '/^mall\/order\/(\d+)$/'=>'g=Meal&c=Shoporder&a=mall&store_id=:1',
	    '/^mall\/cart$/'=>'g=Mall&c=Index&a=cart',
	),

);
?>