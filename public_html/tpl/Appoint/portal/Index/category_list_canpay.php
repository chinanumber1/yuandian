<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>{pigcms{$cat_info.cat_name} - {pigcms{$config.appoint_site_name}</title>
<meta name="keywords" content="{pigcms{$config.appoint_seo_keywords}" />
<meta name="description" content="{pigcms{$config.appoint_seo_description}" />
<link href="{pigcms{$static_path}css/style.css" type="text/css" rel="stylesheet" />
<link href="{pigcms{$static_path}css/ys.css" type="text/css" rel="stylesheet" />
</head>
<body>
<div class="header"> <include file="Public:header_top"/>
  <div class="breadcrumb">
    <p class="c_c"> <i class="breadcrumb-icon"></i> <a href="{pigcms{$config.appoint_site_url}">首页</a> &gt;<a href="{pigcms{$config.appoint_list_url}"> 分类列表</a> &gt;<a href="{pigcms{$config.appoint_list_url}#{pigcms{$f_cat_info.cat_url}">{pigcms{$f_cat_info.cat_name}</a> &gt;<a href="{pigcms{$cat_info.url}">{pigcms{$cat_info.cat_name}</a> </p>
    </p>
  </div>
</div>
<div class="wrapper clearfix" <if condition="$config['appoint_son_category_bgimg']">style="background-image:url({pigcms{$config.appoint_son_category_bgimg});"</if>>
<div class="wra-b">
  <div class="lead">
    <h1>{pigcms{$cat_info.cat_name}</h1>
    <div>{pigcms{$cat_info.desc}</div>
  </div> 
  <!-- left start -->
  <div class="container clearfix">
    <if condition='$cat_info["pc_content"]'>
      <div class="left fl">
        <volist name='cat_info["pc_content"]' id='vo'> 
          <div class="block bgfff" id="{pigcms{$cat_info['cat_url']}_{pigcms{$key}" <if condition="$i eq 1">style="margin-top:0px;"</if>>
            <div class="title "> <i></i>{pigcms{$cat_info['pc_title'][$key]} </div>
            <div class="content-list"> {pigcms{$vo|html_entity_decode} </div>
          </div>
        </volist>
      </div>
    </if>
    <!-- left end  --> 
    
	<include file="Public:right_form_canpay"/> 
      <!-- right end  --> 
      
      <!-- 侧边菜单 -->
      <div class="pin-wrapper">
        <div id="elevator" class="elevator stuckMenu">
          <ul>
		 <if condition='$cat_info["pc_content"]'>
            <volist name='cat_info["pc_title"]' id='vo'>
              <li class="menuItem <if condition='$key eq 0'>active</if>"><a class="etitle" href="#{pigcms{$cat_info['cat_url']}_{pigcms{$key}">{pigcms{$vo}</a></li>
            </volist>
			</if>
            <li><a id="upward" href="javascript:void(0)"></a></li>
          </ul>
        </div>
      </div>
      <!-- end --> 
    </div>
  </div>
</div>

<include file="Public:footer"/> 
<script src="{pigcms{:C('JQUERY_FILE')}"></script> 
<script src="{pigcms{$static_path}js/jquery.flexslider.js"></script> 
<script src="{pigcms{$static_public}js/layer/layer.js"></script> 
<script src="{pigcms{$static_path}js/common.js"></script> 
<script src="{pigcms{$static_path}js/pin.js"></script> 
<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script src="{pigcms{$static_path}js/category_list.js"></script>
</body>
</html>
