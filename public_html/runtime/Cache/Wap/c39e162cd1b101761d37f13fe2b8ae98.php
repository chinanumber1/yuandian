<?php if (!defined('THINK_PATH')) exit(); if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8"/>
        <title><?php echo ($village_info["village_name"]); ?>-单元列表</title>
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
                    <div class="p25 link-url" data-url="<?php echo U('empty_village_list');?>">
                        <h2><?php echo ($village_info["village_name"]); ?></h2>
                        <p><?php echo ($village_info["village_address"]); ?></p>
                    </div>
                </div>
                <ul>
                	<?php if($unit_list): if(is_array($unit_list)): $i = 0; $__LIST__ = $unit_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="link-url" data-url="<?php echo U('empty_village_room_list',array('floor_id'=>$vo['floor_id']));?>">
                        <a href="javascript:viod(0);"><?php echo ($vo["floor_layer"]); ?>#<?php echo ($vo["floor_name"]); ?></a>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    <?php else: ?>
                    <li>
                        <a href="javascript:viod(0);">暂无单元，联系管理员添加！</a>
                    </li><?php endif; ?>
                    
                </ul>
            </div>
        </section>

        <script src="<?php echo ($static_path); ?>js/jquery-1.8.3.min.js"></script>
        <script src="<?php echo ($static_path); ?>village_list/js/common.js"></script>
    </body>
</html>
<script>
    $(".bind_list ul").height($(window).height()-$(".bind_top").innerHeight());
</script>