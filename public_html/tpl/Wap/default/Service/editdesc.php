<!doctype html>
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
<script src='{pigcms{$static_path}service/js/jquery.validate.min.js?t=58a16a34'></script>
<script src='{pigcms{$static_path}service/js/md5.min.js?t=58a16a34'></script>
<script src='{pigcms{$static_path}service/js/newcode-src.js?t=58a16a34'></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
    <title>修改服务介绍</title>
<link href='{pigcms{$static_path}service/css/demand.css?t=58d8bf20' rel='stylesheet' type='text/css' />    
<link href='{pigcms{$static_path}service/css/home_edit.css?t=58d8bf20' rel='stylesheet' type='text/css' /></head>
<body>

<div class="pagewrap" id="mainpage">
  <div class="clear"></div>
<!--m站 header end-->     <!-- show_top_bar -->
    <div class="show-top-bar-wrap show-top-bar-s2">
         <div class="show-top-bar js_topfixed js_map_topfixed">
            <div class="show-bar">完善商户资料，将大幅提高接单率<i class="ico ico-play2"></i></div>
        </div>
    </div>
    <div class="main">
        <div class="service-form" id="demand_form">
            <form action="" id="publish_demand_form" method="post">
                <div class="service-edit-box1">
                    <div class="form-list1">
                        <div class="li">
                            <label class="lab-title"><span class="validate-title">商户介绍:</span></label>
                            <input type="hidden" name="pid" value="{pigcms{$providerInfo.pid}">
                            <div class="ele-wrap">
                                <textarea class="form-control js_validate" placeholder="展示您的商户资历、服务优势，会获得更多客户青睐哦！" id="descInfo" name="describe" maxlength="200">{pigcms{$providerInfo.describe}</textarea>
                                <div class="content-wrong"><span class="textarea-error" default_txt="（限20—200个字符）">（限20—200个字符）</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pop_action pop_action_s2 js_bottomfixed">
                    <a actioncolse="false" href="{pigcms{:U('Service/provider_home')}"class="btn btn-blue btn-s2"><i class="ico ico-goback hidden"></i>取消</a>
                    <button type="submit" id="js_publish_demand_submit" class="btn btn-orange btn-s2"><i class="ico ico-sure"></i>确定</button>
                </div>
            </form>

            <div class="service-edit-box2">
                <div class="form-list1">
                    <div class="li">
                        <label class="lab-title"><span class="validate-title">服务分类：</span><a href="{pigcms{:U('search_cate')}" style="float:right;font-size:1rem;">添加</a></label>
                        <div class="ele-wrap">
                            <div class="desc-wrap">
                                <if condition="$addresList">
                                    <div class="cate-tag">
                                        <ul class="ullist-tag">
                                            <volist name="addresList" id="vo">
                                                <li class="city-li">
                                                    <div class="city-title select-city">{pigcms{$vo.sname}<a href="{pigcms{:U('Service/edit_service_publish',array('paid'=>$vo['paid']))}" class="ico ico-edit"></a></div>
                                                    <div class="tag-list1-wrap">
                                                        <div class="tag-list1 clearfix">
                                                            <volist name="vo.catList" id="vovo">
                                                                <a href="javascript:;" rel_id="{pigcms{$vovo.cid}" class="btn-desc">{pigcms{$vovo.cat_name}</a>
                                                            </volist>
                                                            <div class="clear"></div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </volist>
                                        </ul>
                                    </div>
                                <else/>
                                    <div style="text-align: center; font-size: 20px; padding: 30px 0px 30px 0px;"><span>暂无分类</span></div>
                                </if>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script src='{pigcms{$static_path}service/js/bl_jsvalidate.js?t=58d24290'></script>
<script type="text/javascript">
$("#publish_demand_form").submit(function(){
    var descInfoSum = $("#descInfo").val().length;
    if(descInfoSum < 20 || descInfoSum>200){
        $(".textarea-error").addClass("err");
        layer.open({
            content: '请输入符合范围的内容'
            ,btn: ['确定']
        });
        return false;
    }
    
})

//textarea验证
textareaValidateFun({
    'valiEle':'#descInfo',
    'valiPele':'.li',
    'minlength':[20,'内容小于20个字'],
    'maxlength':[200,'已超过字数限制']
});

//publish_demand_form 验证 说明
PublishFormCheck({
    "formId":"#publish_demand_form",//form id
    "parentEleTagName":".li",//提示语append位置
    "errorMethod":"validatePop",//可选项  第一个条目报错 用小层弹出 3秒后消失
    "validateOption":{
       "descInfo":{
          "requiredCheck":{"msg":"必填项不能为空"},//必填项不能为空 必选项必须选择一项
          "lengthCheck":{"rangelength":[20,200,"商户介绍小于20个字","已超过字数限制"]}
       }
    }
});

</script>
</body>
</html>