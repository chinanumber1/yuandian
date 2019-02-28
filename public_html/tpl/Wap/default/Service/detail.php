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
    <link href="{pigcms{$static_path}service/css/basic.css" rel="stylesheet" type="text/css">
    <script src='{pigcms{$static_path}service/js/jquery-2.1.4.js?t=58a16a34'></script>
    <script src='{pigcms{$static_path}service/js/json2.js?t=58a16a34'></script>
    <script src='{pigcms{$static_path}service/js/basic.js?t=58d24290'></script>
    <script src='{pigcms{$static_path}service/js/md5.min.js?t=58a16a34'></script>
    <script src='{pigcms{$static_path}service/js/newcode-src.js?t=58a16a34'></script>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
    <title>{pigcms{$config.site_name}</title>
    <link href="{pigcms{$static_path}service/css/quote.css" rel="stylesheet" type="text/css">
    <link href="{pigcms{$static_path}service/css/quote_1.css" rel="stylesheet" type="text/css">
    <link href="{pigcms{$static_path}service/css/demand.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
    <!--m站 nav end-->
    <div class="pagewrap" id="mainpage">
        <div class="clear"></div>
        <div class="main bg-gray padd-wrap1 quote-page-s" style="margin-bottom: 0px;">
            <div id="page-con-box">
                <div class="clear"></div>
                <div id="need_detail_summary_wrap" style="min-height: 193px;">
                    <div class="need-user-detail need-user-new" id="need_detail_summary">
                        <div class="need-user-detail-con">
                            <div class="detail-info-block1">
                                <div class="user_name">
                                    <span class="name">{pigcms{$publishInfo.nickname}</span>
                                    <if condition="$publishInfo['phone']"><a class="phone js_phone_ico" href="javascript:;">{pigcms{$publishInfo.phone}</a></if>
                                </div>
                            </div>
                            <div class="detail-info-block2">
                                <div class="cate-desc tips2"><i></i>急需<span class="fc-orange">{pigcms{$publishInfo.cat_name}</span>，仅有 <span class="fc-orange">{pigcms{$publishInfo.offer_sum}</span>人发送报价</div>
                            </div>
                            <div class="detail-info-block3" style="width: 100%; text-align: center;">
                                <div class="l-demand-data clearfix">
                                    <div class="l-data-con" style="width: 50%;">
                                        <div class="l-data-txt">
                                            <i class="ico ico-user"></i>
                                            <span class="l-txt">{pigcms{$publishInfo.offer_sum}</span>
                                        </div>
                                        <div class="l-type-data">收到报价</div>
                                    </div>
                                    <div class="l-data-con" style="width: 50%;">
                                        <div class="l-data-txt">
                                            <i class="ico ico-time"></i>
                                            <span class="l-txt">{pigcms{$publishInfo.add_time|date="Y-m-d H:i:s",###}</span>
                                        </div>
                                        <div class="l-type-data">发布时间</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="need_detail_summary_sit"></div>
                <!-- 需求详情 -->
                <div class="ebox-1 demandtpl-wrap need-detail demandtpl-wrap-level">
                    <div class="tab-s3">
                        <span class="title-s2">
                            需求详情
                            <i></i>
                        </span>
                    </div>

                    <div class="content-box">
                        <ul class="ul-list1 ul-list3 js_demandtpl_list_Inner">
                            <volist name="publishInfo['cat_field']" id="vo">
                                <if condition="$vo['type'] eq 6">
                                    <li class="li">
                                        <div class="ulli ulli-6">{pigcms{$vo.alias_name}：</div>
                                        <div class="ulli ulli-flex1">{pigcms{$vo.value.address}</div>
                                    </li>
                                <elseif condition="$vo['type'] eq 3"/>
                                    <li class="li">
                                        <div class="ulli ulli-6">{pigcms{$vo.alias_name}：</div>
                                        <volist name="vo.value" id="vvo">
                                            <div class="ulli ulli-flex1">{pigcms{$vvo}</div>
                                        </volist>
                                    </li>
                                <elseif condition="$vo['type'] eq 2"/>
                                    <li class="li">
                                        <div class="ulli ulli-6">{pigcms{$vo.alias_name}：</div>
                                        <if condition="$vo['value'] eq 'inputdesc'">
                                            <div class="ulli ulli-flex1">{pigcms{$vo.desc}</div>
                                        <elseif condition="$vo['value'] eq 'time'"/>
                                            <div class="ulli ulli-flex1">{pigcms{$vo.date}{pigcms{$vo.minute}</div>
                                        <else/>
                                            {pigcms{$vo.value}
                                        </if>
                                    </li>
                                <elseif condition="$vo['type'] eq 4"/>
                                    <li class="li">
                                        <div class="ulli ulli-6">{pigcms{$vo.alias_name}：</div>
                                        <div class="ulli ulli-flex1">{pigcms{$vo.value.time_start} {pigcms{$vo.value.time_end}</div>
                                    </li>
                                <elseif condition="$vo['type'] eq 7"/>
                                    <li class="li">
                                        <div class="ulli ulli-6">{pigcms{$vo.alias_name}：</div>
                                        <div class="ulli ulli-flex1">
                                            起点：<span class="orange js_need_address address_start">{pigcms{$vo['value']['address_start']}</span>
                                            <br/>到达点：<span class="orange js_need_address address_end">{pigcms{$vo['value']['address_end']}</span>
                                            <div class="drive-distance">
                                                <p>
                                                    <i class="ico ico-drive"></i> 参考行车距离：
                                                    <span id="service_need_distance"></span>
                                                    <br> (平台距离按直线距离计算)
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="js_coordinate_ele">
                                        <div style="width: 100%;height: 200px;overflow: hidden;margin:0;font-family:"微软雅黑";" id="allmap"></div>
                                    </li>
                                <else/>
                                    <li class="li">
                                        <div class="ulli ulli-6">{pigcms{$vo.alias_name}：</div>
                                        <div class="ulli ulli-flex1">{pigcms{$vo.value}</div>
                                    </li>
                                </if>
                            </volist>
                        </ul>
                    </div>
                </div>
                <!-- 参考报价 -->
                <div class="ebox-1 js-goto js_quote_box">
                    <div class="tab-s3 l-tab-s3-pt">
                        <span class="title-s2"> 参考报价 <i></i> </span>
                    </div>
                    <div class="content-box" id="priceContent">
                        <div class="quote-price">
                            <!--报价模板 start -->
                            <div class="offer-modular">
                                <div class="proxyinput_group l_proxyinput_group s2 js_modular_proxyinput_group">
                                    <div class="clear"></div>
                                    <div class="custom-price    no-tpl  ">
                                        <label class="proxyinput proxy-radio checked">
                                            <div class="price-li">
                                                <div class="l-price-form-control">
                                                    <input class="form-control" type="tel" pattern="[0-9]*" placeholder="  请输入参考价  " name="price" id="price" onkeyup="value=value.replace(/[^\d.]/g,'')" >
                                                    <span class="l-yuan js_price_unit">元</span>
                                                </div>
                                            </div>
                                        </label>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>

                            <div class="quote-price-oper">
                                <div class="l-offer">
                                    <div class="l-flex">
                                       <a  class=" l-offer-btn l-offer-orange btn-quote-normal" onclick="send_offer_submit()" href="javascript:;">发送报价</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="message_content"></div>
                    </div>
                </div>
                <div class="js_quickQuoteBarSit"></div>
            </div>
        </div>
    </div>
<input type="hidden" name="publish_id" value="{pigcms{$_GET['publish_id']}" id="publish_id"/>
<script>
    function send_offer_submit(){
        var publish_id = $("#publish_id").val();
        var price = parseFloat($("#price").val()).toFixed(2);
        if(isNaN(price) || price<=0){
            layer.open({
                content: '请输入正确的金额！'
                ,skin: 'msg'
                ,time: 2 
            });
            return false;
        }
                                                        
        var send_offer_url = "{pigcms{:U('Service/send_offer')}";
        $.post(send_offer_url,{'publish_id':publish_id,'price':price},function(data){
            if(data.error == 1){
                layer.open({
                    content: data.msg
                    ,skin: 'msg'
                    ,time: 2 
                });
                location.href = "{pigcms{:U('Service/trade_contact')}";
            }else{
                layer.open({
                    content: data.msg
                    ,skin: 'msg'
                    ,time: 2 
                });
            }
        },'json');
    }
</script>

</body>

<script type="text/javascript">
    var map = new BMap.Map("allmap");
    var p1 = new BMap.Point("{pigcms{$publishInfo['address_start_lng']}","{pigcms{$publishInfo['address_start_lat']}");
    var p2 = new BMap.Point("{pigcms{$publishInfo['address_end_lng']}","{pigcms{$publishInfo['address_end_lat']}");
    var output = "";
    var searchComplete = function (results){
        var plan = results.getPlan(0);
        output = plan.getDistance(true);
    }
    var transit = new BMap.DrivingRoute(map, {renderOptions: {map: map}, onSearchComplete: searchComplete, onPolylinesSet: function(){
        $("#service_need_distance").html(output);
    }});
    transit.search(p1, p2);
</script>

</html>