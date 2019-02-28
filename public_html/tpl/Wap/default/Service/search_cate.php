<html lang="en">
<head>
    <meta charset="UTF-8" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="yes" name="apple-touch-fullscreen" />
<meta content="telephone=no" name="format-detection" />
<meta content="black" name="apple-mobile-web-app-status-bar-style" />
<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, minimum-scale=1.0,user-scalable=no"/>
<meta name="baidu-site-verification" content="Rp99zZhcYy" />
<meta name="keywords" content=""/>
<meta name="description" content=""/>
<link href='{pigcms{$static_path}service/css/basic.css?t=58da05f1' rel='stylesheet' type='text/css' /><!-- Public js-->
<script src='{pigcms{$static_path}service/js/jquery-2.1.4.js?t=58a16a34'></script>
<script src='{pigcms{$static_path}service/js/json2.js?t=58a16a34'></script>
<script src='{pigcms{$static_path}service/js/basic.js?t=58d24290'></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
<title>选择您能提供的服务分类</title>
<link href='{pigcms{$static_path}service/css/demand.css?t=58d8bf20' rel='stylesheet' type='text/css' /></head>
<body>
<div class="pagewrap" id="mainpage">
    <div class="main">
        <form id="search_cate" action="{pigcms{:U('Service/service_publish')}" method="post">
            <div class="side_popUp " id="select_pop_id" style="top: 0px; position: fixed;">
                <span class="close"></span>
                <div class="spop_body">
                    <div class="popTitle">
                        <span class="title">您能提供哪些分类的服务？</span>
                        <span class="sub">（多选）</span>
                    </div>
                    <div class="spop-wrap">
                        <div class="spop-lv1-box" style="width: 165px;">
                            <ul class="spop-lv1 spop_hot"></ul>
                            <ul class="spop-lv1 spop_normal">
                                <volist name="catList" id="vo">
                                    <li class="parent_node cur" id="parent_selectCateArr_{pigcms{$vo.cid}">
                                        <a href="javascript:;" rel="{pigcms{$vo.cid}">
                                            {pigcms{$vo.cat_name}
                                            <span class="num"></span>
                                        </a>
                                    </li>
                                </volist>
                            </ul>
                        </div>
                        <div class="spop-lv2-box proxyinput_group" maxselectcount="10000" style="width: 247px;">
                            <volist name="catList" id="volist">
                                <ul class="spop-lv2 childlist_node" id="childlist_selectCateArr_{pigcms{$volist.cid}" curmaxselect="undefined" style="width: 247px;">
                                    <volist name="volist['catList']" id="vvv">
                                        <li>
                                            <label for="child_value_selectCateArr_{pigcms{$vvv.cid}" class="proxyinput proxy-checkbox">
                                                <span class="checkbox-hidden">
                                                    <input valtext="{pigcms{$vvv.cat_name}" type="checkbox" name="selectCateArr[]" class="cls_child" id="child_value_selectCateArr_{pigcms{$vvv.cid}" value="{pigcms{$vvv.cid}" rel="{pigcms{$vvv.fcid}"></span>
                                                {pigcms{$vvv.cat_name}
                                            </label>
                                        </li>
                                    </volist>
                                </ul>
                            </volist>
                        </div>
                    </div>
                    <div class="pop_action">
                        <a href="javascript:void(0);" onclick="history.go(-1);" style="padding-right: 50px;">取消</a>
                        <a style="width: 150px;font-size:0.86rem;" class="btn btn-orange btn-s2" href="javascript:;" id="pop_comfirm" actioncolse="false"> <i class="ico ico-sure"></i> 确定 </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>

<script type="text/javascript">
    $(".spop_normal li.parent_node").click(function(){
        var index = $(this).index();
        $(this).addClass("cur").siblings().removeClass("cur");
        $(".spop-lv2-box .spop-lv2").eq(index).removeClass("hidden").siblings(".spop-lv2").addClass("hidden");
    }).eq(0).trigger('click');

    $(function(){
        $('#select_pop_id').height($(window).height());
        $(".pagewrap").on("click",'#pop_comfirm',function(event){
            if($('#select_pop_id .spop-lv2-box [type="checkbox"]:checked').length>0){
                $('#search_cate').submit()
            }else{
                layer.open({
                    content: '选择服务分类子分类'
                    ,btn: ['确定']
                });
            }
        });
    });
</script>
