<?php if (!defined('THINK_PATH')) exit(); if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8"/>
        <title><?php echo ($find_info["floor_layer"]); echo ($find_info["floor_name"]); ?>-<?php echo ($find_info["village_name"]); ?></title>
        <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no,width=device-width"/>
        <meta http-equiv="pragma" content="no-cache"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name='apple-touch-fullscreen' content='yes'/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
        <meta name="format-detection" content="telephone=no"/>
        <meta name="format-detection" content="address=no"/>
        <link href="<?php echo ($static_path); ?>village_list/css/pigcms.css" rel="stylesheet"/>
    </head>
    <body>
        <section class="binding">
            <div class="bind_list">
                <div class="bind_top">
                    <div class="p25 link-url" data-url="<?php echo U('empty_village_unit_list',array('village_id'=>$find_info['village_id']));?>">
                        <h2><?php echo ($find_info["village_name"]); ?>-<?php echo ($find_info["floor_layer"]); echo ($find_info["floor_name"]); ?></h2>
                        <p><?php echo ($find_info["village_address"]); ?></p>
                    </div>
                </div>
                <dl>
                    <dd>
                    	<?php if($vacancy_list): if(is_array($vacancy_list)): $i = 0; $__LIST__ = $vacancy_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="javascript:viod(0);" class="link-url" data-url="<?php echo U('empty_village_room_info',array('pigcms_id'=>$vo['pigcms_id']));?>"><?php echo ($vo["layer"]); ?>/<?php echo ($vo["room"]); ?>室</a><?php endforeach; endif; else: echo "" ;endif; ?>
                        <?php else: ?>
                        <a href="javascript:viod(0);">暂无房间，请联系管理员上传！</a><?php endif; ?>
                        
                    </dd>
                    
                </dl>
            </div>
        </section>

        <script src="<?php echo ($static_path); ?>js/jquery-1.8.3.min.js"></script>
        <script src="<?php echo ($static_path); ?>village_list/js/common.js"></script>
    </body>
</html>
<script>
    $(".bind_list dl").height($(window).height()-$(".bind_top").innerHeight());
</script>