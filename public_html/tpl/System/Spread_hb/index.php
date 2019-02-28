<include file="Public:header"/>

<style type="text/css">
    a {
        color: #444444;
        text-decoration: none;
    }
    a, a:hover {
        text-decoration: none;
    }
    .platform-tag {
        display: inline-block;
        vertical-align: middle;
        padding: 3px 7px 3px 7px;
        background-color: #f60;
        color: #fff;
        font-size: 12px;
        line-height: 14px;
        border-radius: 2px;
    }
</style>
<script type="text/javascript">
    //删除
    $('.js-cancel-to-fx').live('click', function(e){
        var pigcms_id = $(this).data('id');
        if (!confirm("记录将不可恢复，确定删除？")) {
            return false;
        }
        $.post("<?php echo U('Spread_hb/detach_promote')?>", {'pigcms_id': pigcms_id}, function(data){
            if (data.err_code == 0) {
                window.top.msg(1, data.message);
                setTimeout('history.go(0)',1000);//延时1秒
            } else {
                window.top.msg(1, data.message);
                setTimeout('history.go(0)',1000);//延时1秒
            }
        },'json');
    });

    //开启海报
    $('.js-enable-up').live('click', function(e){
        var pigcms_id = $(this).data('id');
        var type = 1;
        if (!confirm("确认开启")) {
            return false;
        }
        $.post('<?php echo  U("Spread_hb/enable")?>', {'pigcms_id': pigcms_id,'type': type}, function(data){
            if (data.err_code == 0) {
                window.top.msg(1, data.message);
                setTimeout('history.go(0)',1000);//延时1秒
            } else {
                window.top.msg(1, data.message);
                setTimeout('history.go(0)',1000);//延时1秒
            }
        },'json');
    });

    //关闭海报
    $('.js-enable-down').live('click', function(e){
        var pigcms_id = $(this).data('id');
        var type = 0;
        if (!confirm("确认关闭")) {
            return false;
        }
        $.post('<?php echo U("Spread_hb/enable")?>', {'pigcms_id': pigcms_id, 'type': type}, function(data){
            if (data.error == 0) {
                window.top.msg(1, data.message);
                setTimeout('history.go(0)',1000);//延时1秒
            } else {
                window.top.msg(1, data.message);
                setTimeout('history.go(0)',1000);//延时1秒
            }
        },'json');
    });


    $('.js-enable-up').live('mouseover', function(e){
        var text = $(this).text();
        if(text == '未启用')
        {
            $(this).text('启　用');
        }
    });

    $('.js-enable-up').live('mouseout',function(){
        var text = $(this).text();
        if(text == '启　用'){
            $(this).text('未启用');
        }
    });

    $('.js-enable-down').live('mouseover', function(e){
        var text = $(this).text();
        if(text == '已启用')
        {
            $(this).text('关　闭');
        }
    });

    $('.js-enable-down').live('mouseout',function(){
        var text = $(this).text();
        if(text == '关　闭'){
            $(this).text('已启用');
        }
    });

    $('.js-search-btn').live('click', function(){
        keyword = $.trim($('.filter-box-search').val());           /* 海报名称 */
        load_page('.app__content', load_url, {page:'promotional_list','keyword': keyword}, '', function(){
            if(keyword != ''){
                $('.filter-box-search').val(keyword);
            }
        });
    });
</script>
<div class="mainbox">
    <div id="nav" class="mainnav_title">
        <ul>
            <a href="{pigcms{:U('Spread_hb/index')}" class="on">海报列表</a>
            <a href="{pigcms{:U('Spread_hb/add')}" >添加推广海报</a>
        </ul>
    </div>
    <table class="search_table" width="100%">
        <tr>
            <td>
                <form action="{pigcms{:U('Spread_hb/index')}" method="post">
                    <input type="hidden" name="c" value="Spread_hb"/>
                    <input type="hidden" name="a" value="index"/>
                    <input type="text" name="keyword" placeholder="海报名称" class="input-text" value="<?php echo $name;?>"/>
                    <input type="submit" value="查询" class="button"/>
                </form>
            </td>
        </tr>
    </table>
    <form name="myform" id="myform" action="" method="post">
        <div class="table-list">
            <style>
                .table-list td{line-height:22px;padding-top:5px;padding-bottom:5px;}
            </style>
            <table width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>编号</th>
                    <th class="textcenter">海报名称</th>
                    <th>修改时间</th>
                    <th>海报类型</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($promote_list)) {?>
                    <?php foreach($promote_list as $list) {?>
                        <tr>
                        <td class="goods-meta"> <?php echo $list['pigcms_id']; ?></td>
                        <td class="textcenter"> <?php echo $list['name']; ?></td>
                        <td>
                            <?php echo date("Y-m-d H:i:s",$list['update_time']);?>
                        </td>
                        <td>
                            <?php if($list['poster_type'] == 1) {?>
                        横式模板
                    <?php } elseif ($list['poster_type'] == 2) {?>
                        竖式模板
                    <?php } elseif ($list['poster_type'] == 3) {?>
                        正方形模板
                    <?php } ?>
                        </td>
                        <td>
                            <?php echo $list['status'] == 1 ? '有效' : '<span style="color:red;">已删除</span>';?>
                        </td>

                        <td>
                            <?php if($list['type']){?>
                        <a href="javascript:;" data-id="<?php echo $list['pigcms_id']; ?>" class="js-enable-down">已启用</a>&nbsp;|&nbsp;
                    <?php } elseif (empty($list['type'])) {?>
                        <a href="javascript:;" data-id="<?php echo $list['pigcms_id']; ?>" class="js-enable-up">未启用</a>&nbsp;|&nbsp;
                    <?php }?>
                            <a href="<?php echo U('Spread_hb/edit' ,array('pigcms_id'=>$list['pigcms_id']));?>">编辑</a>&nbsp;|&nbsp;
                            <a href="javascript:;" data-id="<?php echo $list['pigcms_id']; ?>" class="js-cancel-to-fx">删除</a>
                        </td>
                <?php }?>
                    <tr><td class="textcenter pagebar" <?php if(in_array($my_version,array(4,8))) {?>colspan="11"  <?php }else{?>colspan="10"<?php }?>>{pigcms{$page}</td></tr>
                <?php }?>
                </tbody>
            </table>
        </div>
    </form>
</div>
<include file="Public:footer"/>