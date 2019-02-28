<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8"/>
        <title>{pigcms{$village_info.village_name}-单元列表</title>
        <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no,width=device-width"/>
        <meta http-equiv="pragma" content="no-cache"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name='apple-touch-fullscreen' content='yes'/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
        <meta name="format-detection" content="telephone=no"/>
        <meta name="format-detection" content="address=no"/>
        <link href="{pigcms{$static_path}village_list/css/pigcms.css" rel="stylesheet"/>
    </head>
    <body>
        <section class="binding">
            <div class="bind_list">
                <div class="bind_top">
                    <div class="p25 link-url" data-url="{pigcms{:U('empty_village_list')}">
                        <h2>{pigcms{$village_info.village_name}</h2>
                        <p>{pigcms{$village_info.village_address}</p>
                    </div>
                </div>
                <ul>
                	<if condition="$unit_list">
                    <volist name="unit_list" id="vo">
                    <li class="link-url" data-url="{pigcms{:U('empty_village_room_list',array('floor_id'=>$vo['floor_id']))}">
                        <a href="javascript:viod(0);">{pigcms{$vo.floor_layer}#{pigcms{$vo.floor_name}</a>
                    </li>
                    </volist>
                    <else />
                    <li>
                        <a href="javascript:viod(0);">暂无单元，联系管理员添加！</a>
                    </li>
                    </if>
                    
                </ul>
            </div>
        </section>

        <script src="{pigcms{$static_path}js/jquery-1.8.3.min.js"></script>
        <script src="{pigcms{$static_path}village_list/js/common.js"></script>
    </body>
</html>
<script>
    $(".bind_list ul").height($(window).height()-$(".bind_top").innerHeight());
</script>