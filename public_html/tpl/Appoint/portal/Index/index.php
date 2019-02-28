<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>{pigcms{$config.appoint_seo_title}</title>
<meta name="keywords" content="{pigcms{$config.appoint_seo_keywords}" />
<meta name="description" content="{pigcms{$config.appoint_seo_description}" />
<link href="{pigcms{$static_path}css/style.css" type="text/css" rel="stylesheet" />
<link href="{pigcms{$static_path}css/home.css" type="text/css" rel="stylesheet"/>
</head>
<body>
<div class="header"> <include file="Public:header_top"/> </div>
<div class="wrapper"> 
  <!-- banner start -->
  <div class="banner"> 
    <!-- menu start -->
    <div class="menu-wrap">
      <div class="menu">
        <volist name="all_category_list" id="vo">
          <div class="item jz <if condition="$vo['cat_count'] gt 1">first-item
            </if>
            ">
            <h2> <i style="background-image:url({pigcms{$vo.cat_pic});background-size:100%;"></i> <a href="{pigcms{$vo.url}">{pigcms{$vo.cat_name}</a>
              <if condition="$vo['cat_count'] gt 1"> <em></em> </if>
            </h2>
            <if condition="$vo['cat_count'] gt 1">
              <div class="list-item animated-3 slide-in-down animated-item">
                <ul>
                  <volist name="vo['category_list']" id="voo" key="k">
                  <li class="item hover"> <i class="dolt"></i> <a href="{pigcms{$voo.url}">{pigcms{$voo.cat_name}</a> </li>
                  <if condition="$k gt 5 || $k gt $vo['cat_count']/2 || ($vo['cat_count']%2 eq 0 && $k egt $vo['cat_count']/2)">
                </ul>
                <ul>
                  </if>
                  </volist>
                </ul>
              </div>
            </if>
            <if condition="$vo['cat_count'] gt 1"> <i class="jt"></i> </if>
          </div>
        </volist>
      </div>
    </div>
    <div class="flexslider">
      <ul class="slides">
        <pigcms:adver cat_key="appoint_index" limit="6" var_name="appoint_index">
          <li style=" background:url({pigcms{$vo.pic}) 50% 0px no-repeat scroll transparent;background-color:{pigcms{$vo.bg_color}"> <a style="display:block;width:100%;height:100%;" target="_blank" href="{pigcms{$vo.url}"></a> </li>
        </pigcms:adver>
      </ul>
    </div>
    <!-- banner end --> 
  </div>
  <div class="coupons">
    <ul>
      <pigcms:coupon type="appoint" cat_id="0" limit="3" is_new="-1" var_name="coupons">
        <li class="coupon coupon{pigcms{$i}"> <a href="{pigcms{$vo.url}" target="_blank" rel="nofollow"> <span class="left"> <span class="coupon-img"><img src="{pigcms{$vo.img}"/></span> <span class="coupon-name">{pigcms{$vo.name}</span> </span> <span class="right"> <span class="coupon-price">{pigcms{$vo.discount}</span> </span> </a> </li>
      </pigcms:coupon>
    </ul>
  </div>
  <div class="main">
    <div class="bgfff clearfix" style="position:relative;">
      <div class="w599 fl">
        <div class="title"><b>热门品类</b></div>
        <div class="column" style="height:560px">
          <ul>
          
          <volist name='s_appoint_category_list' id='vo'>
            <li> <a href="{pigcms{$config.site_url}/appoint/category/{pigcms{$vo.cat_url}"> <span style="display:block; margin-bottom:5px"><img src="{pigcms{$config.site_url}/upload/system/{pigcms{$vo.cat_pic}" width="50" height="50" /></span>
              <p>{pigcms{$vo.cat_name}</p>
              </a>
            </li>
          </volist>
          </ul>
        </div>
      </div>
      <div class="w300 fl">
        <div class="title"> <b>大家说 </b> </div>
        <div class="cols">
          <div class="col1">
            <div class="flex-viewport" style="overflow: hidden; position: relative;">
              <ul class="slides" style="width: 1200%; transition-duration: 0s; transform: translate3d(-708px, 0px, 0px);">
              <volist name='reply_list' id='vo'>
              	<if condition='$key % 5 eq 0'><li style="display: block; width: 354px; float: left;" class="clone" aria-hidden="true"></if>
                      <div class="item"> 
                      <if condition='$vo["avatar"]'>
                     	 <img src="{pigcms{$vo.avatar}" width="70px;" height="70px;" draggable="false">
                      <else />
                      	<if condition='$vo["sex"]'>
                        	<img src="{pigcms{$static_path}/images/woman.png" width="70px;" height="70px;" draggable="false">
                        <else />
                        	<img src="{pigcms{$static_path}/images/men.png" width="70px;" height="70px;" draggable="false">
                        </if>
                      </if>
                        <div class="say-content">
                          <div class="username">{pigcms{$vo.nickname}
                            说 </div>
                          <div class="content">{pigcms{$vo.comment|msubstr=0,34,true,'utf-8'}</div>
                        </div>
                      </div>
                  <if condition='($key % 5 eq 4) OR (($key % 5 neq 4) && ($i eq count($reply_list)))'></li></if>
              </volist>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="bgfff clearfix">
      <div class="title"> <b>新闻大事记</b> </div>
      <div class="w550 fl">
        <div class="side-box">
          <div class="catalog-scroller fl">
            <volist name='news_list["list"]' id='vo'>
              <dl>
                <dt>
                  <p>{pigcms{$vo.publish_time|date='d',###}</p>
                  <h6>{pigcms{$vo.publish_time|date='Y.m',###}</h6>
                </dt>
                <dd class="pointer">点</dd>
              </dl>
            </volist>
          </div>
          <div class="side-bar fl"> <span></span> </div>
          <div class="para fl">
            <volist name='news_list["list"]' id='vo'>
              <dl>
                <dt> <a href="{pigcms{$config.site_url}/appoint/article/{pigcms{$vo.id}" target="_blank">{pigcms{$vo.title}</a> </dt>
                <dd>
                  <if condition='$vo["desc"]'>
                    <if condition='mb_strlen($vo["desc"],"utf-8") gt 50'>{pigcms{$vo.desc|mb_substr=0,50,'utf-8'}...
                      <else />
                      {pigcms{$vo.desc}</if>
                  </if>
                </dd>
              </dl>
            </volist>
          </div>
        </div>
      </div>
      <div class="w255 fl">
        <ul class="video styfff">
          <pigcms:adver cat_key="appoint_video" limit="6" var_name="appoint_video">
            <li url="{pigcms{$vo.url}"> <a href="javascript:;"> <img src="{pigcms{$vo.pic}" alt="{pigcms{$vo.name}"/> <i>透明层</i>
              <p> <b>{pigcms{$vo.name}</b> </p>
              <em>视频</em> </a> </li>
          </pigcms:adver>
        </ul>
      </div>
    </div>
  </div>
  <include file="Public:footer"/> </div>
<script src="{pigcms{:C('JQUERY_FILE')}"></script> 
<script src="{pigcms{$static_path}js/jquery.flexslider.js"></script> 
<script src="{pigcms{$static_public}js/layer/layer.js"></script> 
<script src="{pigcms{$static_path}js/common.js"></script> 
<script src="{pigcms{$static_path}js/home.js"></script>
</body>
</html>
