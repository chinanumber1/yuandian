<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8"/>
        <title>{pigcms{$find_info.floor_layer}{pigcms{$find_info.floor_name}-{pigcms{$find_info.village_name}</title>
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
                    <div class="p25 link-url" data-url="{pigcms{:U('empty_village_unit_list',array('village_id'=>$find_info['village_id']))}">
                        <h2>{pigcms{$find_info.village_name}-{pigcms{$find_info.floor_layer}{pigcms{$find_info.floor_name}</h2>
                        <p>{pigcms{$find_info.village_address}</p>
                    </div>
                </div>
                <dl>
                    <dd>
                    	<if condition="$vacancy_list">
                    	<volist name="vacancy_list" id="vo">
                        <a href="javascript:viod(0);" class="link-url" data-url="{pigcms{:U('empty_village_room_info',array('pigcms_id'=>$vo['pigcms_id']))}">{pigcms{$vo.layer}/{pigcms{$vo.room}室</a>
                        </volist>
                        <else />
                        <a href="javascript:viod(0);">暂无房间，请联系管理员上传！</a>
                        </if>
                        
                    </dd>
                    
                </dl>
            </div>
        </section>

        <script src="{pigcms{$static_path}js/jquery-1.8.3.min.js"></script>
        <script src="{pigcms{$static_path}village_list/js/common.js"></script>
    </body>
</html>
<script>
    $(".bind_list dl").height($(window).height()-$(".bind_top").innerHeight());
</script>


