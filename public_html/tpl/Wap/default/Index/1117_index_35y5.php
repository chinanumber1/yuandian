﻿<!doctype html>
<html xmlns="http://www.w3.org/1999/html">
      <head>
       <if condition="$zd['status'] eq 1">
            {pigcms{$zd['code']}
        </if>
    <meta charset="utf-8">
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta name="format-detection" content="telephone=no">
    <meta content="telephone=no" name="format-detection">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>{pigcms{$tpl.wxname}</title>
    <link type="text/css" rel="stylesheet" href="{pigcms{$static_path}tpl/com/css/comstyle.css"/>
    <link type="text/css" rel="stylesheet" href="{pigcms{$static_path}tpl/com/css/font-awesome.css"/>
    <link href="{pigcms{$static_path}tpl/1117/css/index36.css" media="screen" rel="stylesheet" type="text/css" />
    <script src="{pigcms{$static_path}tpl/com/js/comjs.js" type="text/javascript"></script>
    <meta content="authenticity_token" name="csrf-param" />
	<style>
		html,body {
			height:90%;
		}
	</style>
  </head>

  <body>

    <div class="html">
      <div class="stage" id="stage">
        <section id="sec-index">
          
          <div class="body">
            

  <div class="mod-slider slider-ver" id="index">
    <ul class="slider-list">
	<volist name="flashbg" id="so">    
		<li>
			<img alt="{pigcms{$so.info}" src="{pigcms{$so.img}">
		</li>
	</volist>
    </ul>
    </div>
		
          </div>
        </section>

        
    <section class="mod-navLine navLine14">
      <div class="navLine-menu" id="navLine-menu">
        <ul class="p1">
          <li class="s1"><a>菜单</a>
            <ul class="p2">
				<volist name="info" id="vo" offset="0" length="6">
                <li class="s2">

                    <a href="<if condition="empty($vo['sub'])"><if condition="$vo['url'] eq ''">{pigcms{:U('Wap/Index/lists',array('classid'=>$vo['id'],'token'=>$vo['token']))}<else/>{pigcms{$vo.url|htmlspecialchars_decode}</if><else />#</if>"><span>{pigcms{$vo.name}</span></a>
                    <ul class="p3 a3">
						<volist name="vo['sub']" id="res" offset="0" length="3">
							<li>
								  <a href="<if condition="$res['url'] eq ''">{pigcms{:U('Wap/Index/lists',array('classid'=>$res['id'],'token'=>$vo['token']))}<else/>{pigcms{$res.url|htmlspecialchars_decode}</if>">{pigcms{$res.name}</a>

							</li>
						</volist>
                    </ul>
                </li>
				</volist>

            </ul>
          </li>
        </ul>
      </div>
    </section>


      </div><!--.stage end-->
	  
    </div><!--.html end-->

    <script type="text/javascript">
      $(document).ready(function(){

                indexSwipe("index", ["", "", ""]);      
  navLineSwipe=divSwipe("navLine-menu");


        showBtnUp(100);

      });
	  
    </script>
 <div id="insert2"></div>


 <div style="display:none"> </div>


<include file="Index:styleInclude"/>
<include file="$cateMenuFileName"/>
<!-- share -->
<include file="Index:share" />

  </body>
</html>