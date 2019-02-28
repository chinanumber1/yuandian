<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>【{pigcms{$now_group.merchant_name}预约】{pigcms{$now_group.appoint_name}预约 - {pigcms{$config.site_name}</title>
<meta name="keywords" content="{pigcms{$config.appoint_seo_keywords}" />
<meta name="description" content="{pigcms{$config.appoint_seo_description}" />
<link href="{pigcms{$static_path}css/style.css" type="text/css" rel="stylesheet" />
<link href="{pigcms{$static_path}css/ys.css" type="text/css" rel="stylesheet" />
</head>
<script src="{pigcms{:C('JQUERY_FILE')}"></script> 
<script type="text/javascript">
var site_url = "{pigcms{$config.site_url}";var store_long="{pigcms{$now_group.store_list.0.long}";var store_lat="{pigcms{$now_group.store_list.0.lat}";
var get_reply_url="{pigcms{:U('ajax_get_list',array('order_type'=>2,'parent_id'=>$_GET['appoint_id']))}";
</script>

<body>
<div class="header"> <include file="Public:header_top"/>
  <div class="breadcrumb">
    <p class="c_c"> <i class="breadcrumb-icon"></i> <a href="{pigcms{$config.appoint_site_url}">首页</a> &gt;<a href="{pigcms{$config.appoint_list_url}"> 分类列表</a>
    
    &gt;<a href="{pigcms{$config.appoint_list_url}#{pigcms{$f_category.cat_url}">{pigcms{$f_category.cat_name}</a> &gt;<a href="{pigcms{$s_category.url}">{pigcms{$s_category.cat_name}</a> </p>
  </div>
</div>
<div class="wrapper clearfix" 
<if condition="$config['appoint_son_category_bgimg']">style="background-image:url({pigcms{$config.appoint_son_category_bgimg});"</if>
>
<div class="wra-b">
  <div class="lead">
    <h1>{pigcms{$now_group['appoint_name']}</h1>
    <div>{pigcms{$now_group['appoint_content']}</div>
  </div>
  <!-- left start -->
  <div class="container clearfix">
    <div class="left fl">
      <div class="block bgfff" id="anchor-detail" style="margin-top:0px;">
        <div class="title "> <i></i>预约详情 </div>
        <div class="content-list">
          <div>
            {pigcms{$now_group.appoint_pic_content}</div>
        </div>
      </div>
      
      
      <div class="block bgfff margin10" id="business-info">
        <div class="title"> <i></i>商家位置 </div>
        <div class="content-list ">
          <div class="map clearfix">
            <div class="map_map">
							<div class="map_map_img">
								<div id="map-canvas" map_point="{pigcms{$now_group.store_list.0.long},{pigcms{$now_group.store_list.0.lat}" store_name="{pigcms{$now_group.store_list.0.name}" store_adress="{pigcms{$now_group.store_list.0.area_name}{pigcms{$now_group.store_list.0.adress}" store_phone="{pigcms{$now_group.store_list.0.phone}" frame_url="{pigcms{:U('Map/frame_map')}"></div>
								<div class="map_icon J-view-full"><img src="{pigcms{$static_path}images/xiangqing_31.png"/></div>
							</div>
						</div>
            <div class="map_txt">
              <volist name="now_group['store_list']" id="vo">
								<div class="biz-info <if condition="$i eq 1">biz-info--open biz-info--first</if> <if condition="count($now_group['store_list']) eq 1">biz-info--only</if>">
									<div class="biz-info__title">
										<div class="shop_name">{pigcms{$vo.name}</div>
										<i class="F-glob F-glob-caret-down-thin down-arrow"></i>
									</div>
									<div class="biz-info__content">
										<div class="shop_add"><span>地址：</span>{pigcms{$vo.area_name}{pigcms{$vo.adress}</div>
										<div class="shop_map"><a class="view-map" href="javascript:void(0)" map_point="{pigcms{$vo.long},{pigcms{$vo.lat}"  store_name="{pigcms{$vo.name}" store_adress="{pigcms{$vo.area_name}{pigcms{$vo.adress}" store_phone="{pigcms{$vo.phone}" frame_url="{pigcms{:U('Group/Map/frame_map')}">查看地图</a>&nbsp;&nbsp;&nbsp;<a class="search-path" href="javascript:void(0)" shop_name="{pigcms{$vo.adress}">公交/驾车去这里</a></div>
										<div class="shop_ip"><span>电话：</span>{pigcms{$vo.phone}</div>
									</div>
								</div>
							</volist></div>
              
          </div>
        </div>
      </div>
      
      
      
      
      <!--div class="block bgfff margin10" id="anchor-bizinfo">
        <div class="title"> <i></i>商家介绍 </div>
        <div class="content-list ">
          <div class="introduce_title">{pigcms{$now_group.merchant_name}</div>
          <div class="introduce_txt">{pigcms{$now_group.txt_info}</div>
          <div class="introduce_img"> 
          <volist name="now_group['merchant_pic']" id="vo">
          <img src="{pigcms{$vo}" alt="{pigcms{$now_group.merchant_name}" class="standard-image">
          </volist>
          </div>
        </div>
      </div-->
      <div class="block bgfff margin10" id="anchor-reviews">
         <div class="title"> <i></i>消费评价<if condition='count($reply_list["list"])'><span>({pigcms{:count($reply_list["list"])})</span></if> </div>
        <div class="content-list ">
          <div class="reply_tab clearfix">
            <div class="tab_title rate-filter__item"> <a href="javascript:;" class="on" data-tab="all">全部</a> <a href="javascript:;" data-tab="high">好评</a> <a href="javascript:;" data-tab="mid">中评</a> <a href="javascript:;" data-tab="low">差评</a> <a href="javascript:;" data-tab="withpic">有图</a> </div>
            <div class="tab_form">
              <div class="form_sec">
                <select name="时间排序" class="select J-filter-ordertype">
                  <option value="default">默认排序</option>
                  <option value="time">时间排序</option>
                  <option value="score">好评排序</option>
                </select>
              </div>
            </div>
          </div>
          <div class="appraise_li-list">
            <dl class="J-rate-list">
            <if condition='$reply_list["list"]'>
                <volist name='reply_list["list"]' id='vo'>
                  <dd class="clearfix">
                    <div class="appraise_li-list_img">
                      <div class="appraise_li-list_icon"><if condition='$vo["avatar"]'><img src="{$vo['avatar']}"/><else /><img src="{pigcms{$static_path}images/meal_default_avatar.png"/></if></div>
                    </div>
                    <div class="appraise_li-list_right clearfix">
                      <div class="appraise_li-list_top clearfix">
                        <p class="nickname">{pigcms{$vo.nickname}</p>
                        <div class="appraise_li-list_top_icon">
                            <div><span style="width:{pigcms{$vo['score'] * 20}%"></span></div>
                        </div>
                        <if condition='$vo["score"] egt 4 '>
                            <div class="appraise_li-list_top_icon_txt">好评</div>
                        <elseif condition='$vo["score"] egt 3' />
                             <div class="appraise_li-list_top_icon_txt middle">中评</div>
                        <else />
                            <div class="appraise_li-list_top_icon_txt bad">差评</div>
                        </if>
                        <div class="appraise_li-list_data">{pigcms{$vo.add_time}</div>
                      </div>
                      <div class="appraise_li-list_txt">{pigcms{$vo.comment}</div>
                      <p class="biz-reply">商家回复：{pigcms{$vo.merchant_reply_content}</p>
                    </div>
                  </dd>
                  </volist>
              <else />
              	<li class="norate-tip">暂无该类型评价</li><strong></strong>
              </if>
            </dl>
          </div>
          <if condition='$reply_list["page"]'>
              <div class="page J-rate-paginator clearfix">
                {pigcms{$reply_list['page']}
              </div>
              
          </if>
        </div>
      </div>
    </div>
    <!-- left end  --> 
    
    <!-- right start -->
    <include file="Public:right_form"/> 
    <!-- right end  --> 
    <!-- 侧边菜单 -->
    <div class="pin-wrapper">
      <div id="elevator" class="elevator stuckMenu">
        <ul>
          <li class="menuItem active"><a class="etitle" href="#anchor-detail">预约详情</a></li>
          <li class="menuItem"><a class="etitle" href="#business-info">商家位置</a></li>
          <!--li class="menuItem"><a class="etitle" href="#anchor-bizinfo">商家介绍</a></li-->
          <li class="menuItem"><a class="etitle" href="#anchor-reviews">消费评价</a></li>
          <li><a id="upward" href="javascript:void(0)"></a></li>
        </ul>
      </div>
    </div>
    <!-- end --> 
  </div>
</div>
</div>
<include file="Public:footer"/> 
<script src="{pigcms{$static_path}js/jquery.flexslider.js"></script> 
<script src="{pigcms{$static_public}js/layer/layer.js"></script> 
<script src="{pigcms{$static_path}js/common.js"></script> 
<script src="{pigcms{$static_path}js/pin.js"></script> 
<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
<script type="text/javascript" src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1" charset="utf-8"></script>
<script src="{pigcms{$static_path}js/detail.js"></script>
</body>
</html>
