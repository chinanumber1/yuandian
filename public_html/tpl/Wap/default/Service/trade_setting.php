<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="telephone=no" name="format-detection">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, minimum-scale=1.0,user-scalable=no">
    <meta name="baidu-site-verification" content="Rp99zZhcYy">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link href='{pigcms{$static_path}service/css/basic.css?t=58da05f1' rel='stylesheet' type='text/css' />
    <script src='{pigcms{$static_path}service/js/jquery-2.1.4.js?t=58a16a34'></script>
    <script src='{pigcms{$static_path}service/js/json2.js?t=58a16a34'></script>
    <script src='{pigcms{$static_path}service/js/basic.js?t=58d24290'></script>
    <script src='{pigcms{$static_path}service/js/md5.min.js?t=58a16a34'></script>
    <script src='{pigcms{$static_path}service/js/newcode-src.js?t=58a16a34'></script>
</head>
<body class="android">
    <title>生意机会</title>
    <link href="{pigcms{$static_path}service/css/demand.css" rel="stylesheet" type="text/css">
<!-- <include file="Service:right_nav"/> -->
<div class="pagewrap" id="mainpage">
    <!-- <include file="Service:header_top"/> -->
    <div class="main padd-wrap1">
        <form method="post" action="{pigcms{:U('Service/trade_setting')}" id="settingSubmit">
            <div class="filter-setting-list">
                <div class="form-list1">
                    <div class="li" style="margin-top: -50px;">
                        <label class="lab-title">
                            <span class="validate-title">请设置快速筛选的分类</span>
                            <span class="vali-mark">(最多可选3项)</span>
                        </label>
                        <div class="ele-wrap">
                            <ul class="cate_ul" id="cate_ul">
                                <volist name="categoryList" id="vo">
                                    <li class="cate_li <if condition="$vo.status eq 1">current</if>" rel="{pigcms{$vo.cid}">
                                        <a>{pigcms{$vo.cat_name}</a>
                                    </li>
                                </volist>
                            </ul>
                            <div class="clear"></div>
                            <input type="hidden" id="select_cate_ids" name="select_cate_ids" value="{pigcms{$cidlist}"></div>
                    </div>
                </div>
                <div class="btn-wrap1 v-center js_bottomfixed">
                    <input class="btn btn-orange control-lg " type="submit" value="确定">
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(function(){
        if($('#sitBottom').length>0||$('.js_bottomfixed').length>0||$('.js_topfixed').length>0){
            if(isExitsFunction('sitBottomFun')){
                sitBottomFun();
            }
        }
        $('.cate_li').click(function(){
            var rel = $(this).attr('rel');
            var current_class = 'current';
            var select_cate_ids = $('#select_cate_ids').val().split(',');
            if ($(this).hasClass(current_class)) {
                //已选择，反选
                $(this).removeClass(current_class);
                select_cate_ids = $.grep(select_cate_ids, function(value) {
                    return value != rel;
                });
                $('#select_cate_ids').val(select_cate_ids.join(','));
            } else {
                // 未选择
                if ($('#cate_ul .'+current_class).length >= 3) {
                    //小提示层;
                    validatePop({
                        "popconMsg":"最多选择3项"
                    });
                } else {
                    //可追加
                    $(this).addClass(current_class);
                    select_cate_ids.push(rel)
                    $('#select_cate_ids').val(select_cate_ids.join(','));
                }
            }
        })
    })

</script>

</body>
</html>