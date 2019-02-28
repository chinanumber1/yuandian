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
    <title>需求详情</title>
    <link href="{pigcms{$static_path}service/css/quote.css" rel="stylesheet" type="text/css">
    <link href="{pigcms{$static_path}service/css/quote_1.css" rel="stylesheet" type="text/css">
    <link href="{pigcms{$static_path}service/css/demand.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
    <style>
        .orders_list li{ margin-top: 12px; padding: 0 10px; }
        .orders_list li .time{ color: 999999; font-size: 12px; padding-left: 18px; background: url({pigcms{$static_path}service/images/2ddzt_03.png) left center no-repeat; background-size: 11px; line-height: 1; margin-bottom: 8px; }
        .orders_list li .p18{ padding: 5px 0px 5px 22px; position: relative;  }
        .orders_list li .p18:after{ content: ''; height: 100%; width: 1px; background: #dcdcdc; position: absolute; left: 5px; top: 0px; z-index: 1 }

        .orders_list li .p18 .con{ background: #fff; padding: 13px; border-radius: 5px; box-shadow: 0px 0px 8px 2px #e3f0ec; position: relative; }
        .orders_list li .p18 .con:after{    content: "";width: 0;height: 0;display: block;border-top: 10px solid #ffffff;border-left: 8px solid transparent;border-right: 8px solid transparent;border-bottom: 0;position: absolute;top: 50%; left: -9px; margin-top: -5px; -webkit-transform: rotate(90deg);  }

        .orders_list li .p18:before{content: "";width: 8px;height: 8px;display: block; border-radius: 100%; background: #06c1ae; position: absolute; left: 2px; top: 50%; margin-top: -4px; z-index: 2}
        .orders_list li .p18 .con h2{ color: #06c1ae; font-size: 14px; font-weight: bold; padding-bottom: 8px; }

        .public {
            height: 44px;
            line-height: 44px;
            background: #06c1ae;
            color: #fff;
            width: 100%;
            top: 0px;
            left: 0px;
            z-index: 880;
        }
        .public .content {
            text-align: center;
            font-size: 16px;
        }
        .public .return {
            position: absolute;
            width: 50px;
            height: 44px;
            left: 0px;
            top: 0px;
        }
    </style>
    <!--m站 nav end-->
    <div class="pagewrap" id="mainpage">

        <section class="public pageSliderHide">
            <a href=""><div class="return"></div></a>
            <div class="content">需求详情</div>
        </section>

        <section class="g_details">
            <div class="orders_list">
                <ul>
                    <volist name="offer_record_list" id="vo">
                        <li>
                            <div class="time">{pigcms{$vo.add_time|date="Y-m-d H:i:s",###}</div>
                            <div class="p18">
                                <div class="con">
                                    <h2>{pigcms{$vo.remarks}</h2>
                                </div>
                            </div>
                        </li>
                    </volist>
                </ul>
            </div>
        </section>
        <div style=" margin-top: 15px; "></div>
        <div class="main bg-gray padd-wrap1 quote-page-s" style="margin-bottom: 0px;">
            <div id="page-con-box">
                <div class="clear"></div>
                
                <div id="need_detail_summary_sit"></div>
                <!-- 需求详情 -->
                <div class="ebox-1 demandtpl-wrap need-detail demandtpl-wrap-level">
                    <div class="tab-s3">
                        <span class="title-s2">
                            需求详情
                            <i></i>
                        </span>
                    </div>



                    <if condition="$publishInfo.catgory_type eq 2">
                        <div class="ebox-1 demandtpl-wrap demand-info-c s3 demandtpl-wrap-level" id="needStatus">
                            <div class="content-box">
                                <ul class="ul-list1 ul-list3 js_demandtpl_list_Inner" needid="326621">
                                    <li class="li">
                                        <div class="ulli ulli-6">商品要求：</div>
                                        <div class="ulli ulli-flex1">{pigcms{$cat_field_info.goods_remarks}</div>
                                    </li>
                                    <li class="li">
                                        <div class="ulli ulli-6">购买类型：</div>
                                        <div class="ulli ulli-flex1"><if condition="$cat_field_info.buy_type eq 1">就近购买<else/>指定地址</if></div>
                                    </li>
                                    <if condition="$cat_field_info.buy_type eq 2">
                                        <li class="li">
                                            <div class="ulli ulli-6">指定地址：</div>
                                            <div class="ulli ulli-flex1">{pigcms{$cat_field_info.address}</div>
                                        </li>
                                    </if>

                                    <li class="li">
                                        <div class="ulli ulli-6">送达地址：</div>
                                        <div class="ulli ulli-flex1">{pigcms{$cat_field_info.end_adress_name}</div>
                                    </li>
                                    <li class="li">
                                        <div class="ulli ulli-6">送达时间：</div>
                                        <div class="ulli ulli-flex1">预计在 {pigcms{$cat_field_info.arrival_time}（分钟） 内送达</div>
                                    </li>
                                    <li class="li">
                                        <div class="ulli ulli-6">预估商品费用：</div>
                                        <div class="ulli ulli-flex1">{pigcms{$cat_field_info.estimate_goods_price}</div>
                                    </li>
                                    <li class="li">
                                        <div class="ulli ulli-6">基础配送费：</div>
                                        <div class="ulli ulli-flex1">{pigcms{$cat_field_info.basic_distance_price}</div>
                                    </li>
                                    <li class="li">
                                        <div class="ulli ulli-6">超出基础配送距离费用：</div>
                                        <div class="ulli ulli-flex1">{pigcms{$cat_field_info.distance_price}</div>
                                    </li>
                                    <li class="li">
                                        <div class="ulli ulli-6">小费：</div>
                                        <div class="ulli ulli-flex1">{pigcms{$cat_field_info.tip_price}</div>
                                    </li>
                                    <li class="li">
                                        <div class="ulli ulli-6">总价：</div>
                                        <div class="ulli ulli-flex1">{pigcms{$cat_field_info.total_price}</div>
                                    </li>
                                    <if condition="$cat_field_info['img']">
                                        <li class="li">
                                            <div class="ulli ulli-6">商品图片：</div>
                                            <div class="ulli ulli-flex1">
                                                <volist name="cat_field_info['img']" id="vo">
                                                    <a href="javascript:void(0);" onclick='showImg("{pigcms{$vo}")'><img src="{pigcms{$vo}" style="float:left; padding-left: 2px; width: 70px; height: 70px;" alt=""></a>
                                                </volist>
                                            </div>
                                        </li>
                                    </if>
                                </ul>
                            </div>
                        </div>
                    <elseif condition="$publishInfo.catgory_type eq 3"/>
                        <div class="ebox-1 demandtpl-wrap demand-info-c s3 demandtpl-wrap-level" id="needStatus">
                            <div class="content-box">
                                <ul class="ul-list1 ul-list3 js_demandtpl_list_Inner" needid="326621">
                                    <li class="li">
                                        <div class="ulli ulli-6">商品分类：</div>
                                        <div class="ulli ulli-flex1">{pigcms{$cat_field_info.goods_catgory}</div>
                                    </li>
                                    <li class="li">
                                        <div class="ulli ulli-6">物品重量：</div>
                                        <div class="ulli ulli-flex1">{pigcms{$cat_field_info.weight}（KG）</div>
                                    </li>
                                    <li class="li">
                                        <div class="ulli ulli-6">物品价值：</div>
                                        <div class="ulli ulli-flex1">{pigcms{$cat_field_info.price}</div>
                                    </li>
                                    <li class="li">
                                        <div class="ulli ulli-6">取件地址：</div>
                                        <div class="ulli ulli-flex1">{pigcms{$cat_field_info.start_adress_name}</div>
                                    </li>
                                    <li class="li">
                                        <div class="ulli ulli-6">收货地址：</div>
                                        <div class="ulli ulli-flex1">{pigcms{$cat_field_info.end_adress_name}</div>
                                    </li>
                                    <li class="li">
                                        <div class="ulli ulli-6">取件时间：</div>
                                        <div class="ulli ulli-flex1"><if condition="$cat_field_info['fetch_time']">{pigcms{$cat_field_info.fetch_time}<else/>立即取件</if></div>
                                    </li>
                                    <li class="li">
                                        <div class="ulli ulli-6">备注：</div>
                                        <div class="ulli ulli-flex1">{pigcms{$cat_field_info.remarks}</div>
                                    </li>
                                    
                                    <li class="li">
                                        <div class="ulli ulli-6">重量费用：</div>
                                        <div class="ulli ulli-flex1">{pigcms{$cat_field_info.weight_price}</div>
                                    </li>
                                    <li class="li">
                                        <div class="ulli ulli-6">基础配送费：</div>
                                        <div class="ulli ulli-flex1">{pigcms{$cat_field_info.basic_distance_price}</div>
                                    </li>
                                    <li class="li">
                                        <div class="ulli ulli-6">超出基础配送费：</div>
                                        <div class="ulli ulli-flex1">{pigcms{$cat_field_info.distance_price}</div>
                                    </li>
                                    <li class="li">
                                        <div class="ulli ulli-6">小费：</div>
                                        <div class="ulli ulli-flex1">{pigcms{$cat_field_info.tip_price}</div>
                                    </li>
                                    <li class="li">
                                        <div class="ulli ulli-6">总价：</div>
                                        <div class="ulli ulli-flex1">{pigcms{$cat_field_info.total_price}</div>
                                    </li>
                                    <if condition="$cat_field_info['img']">
                                        <li class="li">
                                            <div class="ulli ulli-6">商品图片：</div>
                                            <div class="ulli ulli-flex1">
                                                <volist name="cat_field_info['img']" id="vo">
                                                    <a href="javascript:void(0);" onclick='showImg("{pigcms{$vo}")'><img src="{pigcms{$vo}" style="float:left; padding-left: 2px; width: 70px; height: 70px;" alt=""></a>
                                                </volist>
                                            </div>
                                        </li>
                                    </if>
                                    
                                    <li class="li">
                                        <div class="ulli ulli-6">参考行车：</div>
                                        <div class="ulli ulli-flex1">
                                            <p>
                                                <i class="ico ico-drive"></i> 参考行车距离：
                                                <span id="service_need_distance"></span>
                                                <br> (平台距离按直线距离计算)
                                            </p>
                                        </div>
                                    </li>
                                    <li class="js_coordinate_ele">
                                        <div style="width: 100%;height: 200px;overflow: hidden;margin:0;font-family:"微软雅黑";" id="allmap"></div>
                                    </li>

                                    <script type="text/javascript">
                                        var map = new BMap.Map("allmap");
                                        var p1 = new BMap.Point("{pigcms{$cat_field_info['start_adress_lng']}","{pigcms{$cat_field_info['start_adress_lat']}");
                                        var p2 = new BMap.Point("{pigcms{$cat_field_info['end_adress_lng']}","{pigcms{$cat_field_info['end_adress_lat']}");
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



                                </ul>
                            </div>
                        </div>
                        
                    </if>


                </div>


                <!-- 参考报价 -->
                <if condition="is_array($offer_info)">
                    <if condition="$offer_info['status'] eq 8">
                        <div class="quote-price-oper ebox-1 js-goto js_quote_box">
                            <div class="l-flex">
                                <a  class=" l-offer-btn l-offer-orange" onclick="offer_save_status(9)" href="javascript:;">我要取货</a>
                            </div>
                        </div>
                    <elseif condition="$offer_info['status'] eq 9"/>
                        <div class="quote-price-oper ebox-1 js-goto js_quote_box">
                            <div class="l-flex">
                                <a  class=" l-offer-btn l-offer-orange" onclick="offer_save_status(4)" href="javascript:;">我要配送</a>
                            </div>
                        </div>
                    </if>
                <else/>
                    <div class="quote-price-oper ebox-1 js-goto js_quote_box">
                        <div class="l-flex">
                            <a  class=" l-offer-btn l-offer-orange" onclick="send_offer_submit()" href="javascript:;">我来接单</a>
                        </div>
                    </div>
                </if>

                

            </div>
        </div>
    </div>
<input type="hidden" name="publish_id" value="{pigcms{$_GET['publish_id']}" id="publish_id"/>
<script>
    function send_offer_submit(){
        var publish_id = $("#publish_id").val();

        var send_offer_url = "{pigcms{:U('Service/special_add_offer')}";
        $.post(send_offer_url,{'publish_id':publish_id},function(data){
            if(data.error == 1){
                layer.open({
                    content: data.msg
                    ,btn: ['确定']
                    ,yes: function(index){
                        location.href = "{pigcms{:U('Service/trade_contact')}";
                    }
                });
            }else{
                layer.open({
                    content: data.msg
                    ,skin: 'msg'
                    ,time: 2 
                });
            }
        },'json');
    }

    function offer_save_status(status){
        var send_offer_url = "{pigcms{:U('Service/offer_save_status')}";
        var publish_id = $("#publish_id").val();
        $.post(send_offer_url,{'publish_id':publish_id,'status':status},function(data){
            if(data.error == 1){
                layer.open({
                    content: data.msg
                    ,btn: ['确定']
                    ,yes: function(index){
                        location.href = location.href;
                    }
                });
            }else{
                layer.open({
                    content: data.msg
                    ,btn: ['确定']
                });
            }
        },'json');

    }
</script>
<script>
    function showImg(img_url){
        $('.mask').show();
        $('.img_show').prop('src',img_url);
        $('.content_img').show();
        var pageii = layer.open({
          type: 1
          ,content: '<div class="playli" style=""><img src="'+img_url+'" alt="" style="width: 100%;"></div>'
          ,anim: 'up'
          ,style: 'border: none; -webkit-animation-duration: .5s; animation-duration: .5s;'
        });
        $('.playli img').click(function(e){
           $('.laymshade').hide();
           $('.layermmain').hide();
            
        });
    }
</script>

</body>


</html>