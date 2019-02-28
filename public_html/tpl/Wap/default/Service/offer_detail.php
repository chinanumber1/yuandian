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
    <script src="{pigcms{$static_path}service/js/jquery-2.1.4.js"></script>
    <script src="{pigcms{$static_path}service/js/json2.js"></script>
    <script src="{pigcms{$static_path}service/js/basic.js"></script>
    <script src="{pigcms{$static_path}service/js/jquery.validate.min.js"></script>
    <script src="{pigcms{$static_path}service/js/md5.min.js"></script>
    <script src="{pigcms{$static_path}service/js/newcode-src.js"></script>
    <script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
    <script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
</head>
<body class="bg-gray android">
    <title>生意机会</title>
    <link href="{pigcms{$static_path}service/css/quote.css" rel="stylesheet" type="text/css">
    <link href="{pigcms{$static_path}service/css/quote_1.css" rel="stylesheet" type="text/css">
    <link href="{pigcms{$static_path}service/css/demand.css" rel="stylesheet" type="text/css">
    <div class="pagewrap" id="mainpage">
        <div class="main bg-gray padd-wrap1 quote-page-s" style="margin-bottom: 0px;">
            <div id="page-con-box">
                <div id="need_detail_summary_wrap">
                    <div class="need-user-detail need-user-new " id="need_detail_summary">
                        <div class="need-user-detail-con">
                            <div class="detail-info-block1">
                                <div class="user_name">
                                    <span class="name">{pigcms{$publishInfo.nickname}</span>
                                    <if condition="$publishInfo['phone']">
                                        <a class="phone js_phone_ico has-phone" href="tel:{pigcms{$publishInfo.phone}"> 
                                        <i class="ico ico-tel-5"></i>
                                            {pigcms{$publishInfo.phone}
                                        </a>
                                    </if>
                                    <span class="blue quote-state ">
                                        请您尽快联系Ta <i class="ico ico-title-s2"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="detail-info-block2">

                                <if condition="$publishInfo['phone']">
                                    <div class="cate-desc tips2">
                                        <i></i>感谢您的报价，我觉得不错，想和您详细沟通一下。上面是我的联系方式，麻烦尽快与我联系，谢谢！
                                    </div>
                                <else/>
                                    <div class="cate-desc tips2">
                                        <i></i>感谢您的报价，我想和您详细沟通一下，请您尽快与我联系，谢谢!
                                    </div>
                                </if>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ebox-1 js-goto js_quote_box">
                    <div class="tab-s3 l-tab-s3-pt">
                        <span class="title-s2">
                            参考报价
                            <i></i>
                        </span>
                    </div>
                    <div class="content-box" id="priceContent">
                        <div class="l-reference-offer-content ">
                            <div class="l-reference-offer">
                                <div class="l-offer-hd">
                                    <span class="hd-title">报价金额：</span> 
                                    <em class="l-offer-unmber"><strong class="l-color-orange">{pigcms{$publishInfo.price}</strong> 元</em>
                                </div>
                                <p class="price-range-tips" style="margin-top: 5px;">价格可能上下浮动</p>
                                <if condition="$publishInfo['status'] eq 1">
                                    <div class="keep-modular">
                                        <div class="offer-modular">
                                            <div class="proxyinput_group l_proxyinput_group s2 js_modular_proxyinput_group">
                                                <label class="proxyinput proxy-radio">
                                                        <div class="l-price-form-control">
                                                            <input class="form-control" type="tel" placeholder="价格不合适，输入新价格 " id="save_price" value=""   name="price">        
                                                            <span class="l-yuan js_price_unit">元</span>
                                                        </div>
                                                </label>
                                                <div class="btn-custom-msg-wrap">
                                                    <input type="button" class="btn btn-blue2 send-custom-msg" id="save_price_btn" value="变 更" style="font-size:0.86rem;"/>
                                                </div>
                                                <script>
                                                    $("#save_price_btn").click(function(){
                                                        var save_price_url = "{pigcms{:U('Service/save_price')}";
                                                        var save_price = parseFloat($("#save_price").val()).toFixed(2);

                                                        if(isNaN(save_price) || save_price<=0){
                                                            layer.open({
                                                                content: '请输入正确的金额！'
                                                                ,btn: ['确定']
                                                            });
                                                            return false;
                                                        }
                                                        var offer_id = "{pigcms{$publishInfo['offer_id']}";
                                                        $.post(save_price_url,{'price':save_price,'offer_id':offer_id},function(data){
                                                            if(data.error == 1){
                                                                layer.open({
                                                                    content: data.msg
                                                                    ,btn: ['确定']
                                                                    ,yes: function(index){
                                                                        location.href = location.href;
                                                                        layer.close(index);
                                                                    }
                                                                });
                                                            }else{
                                                                layer.open({
                                                                    content: data.msg
                                                                    ,btn: ['确定']
                                                                });
                                                            }
                                                        },'json')
                                                    })
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                <elseif condition="$publishInfo['status'] eq 2"/>
                                    <div class="keep-modular">
                                        <div>用户已支付等待服务中</div>
                                         <div class="btn-custom-msg-wrap">
                                            <input type="button" class="btn btn-blue2 send-custom-msg" onclick='confirmService("{pigcms{$publishInfo.offer_id}")' value="已服务" style="font-size:0.86rem;"/>
                                        </div>
                                    </div>
                                    <script>
                                        function confirmService(offer_id){
                                            //询问框
                                            layer.open({
                                                content: '确定已完成服务内容？'
                                                ,btn: ['确定', '取消']
                                                ,yes: function(index){
                                                    var confirm_service_url = "{pigcms{:U('Service/confirm_service')}";
                                                    $.post(confirm_service_url,{'offer_id':offer_id},function(data){
                                                        if(data.error == 1){
                                                            layer.open({
                                                                content: data.msg
                                                                ,btn: ['确定']
                                                                ,yes: function(index){
                                                                    location.href = location.href;
                                                                    layer.close(index);
                                                                }
                                                            });
                                                        }else{
                                                            layer.open({
                                                                content: data.msg
                                                                ,btn: ['确定']
                                                            });
                                                        }
                                                    },'json')
                                                }
                                            });
                                        }
                                    </script>

                                <elseif condition="$publishInfo['status'] eq 5"/>
                                    <div class="keep-modular">
                                        <div>用户申请退款：{pigcms{$publishInfo['reason']}</div>
                                         <div class="btn-custom-msg-wrap">
                                            <input type="button" class="btn btn-blue2 send-custom-msg" onclick='refundReply("{pigcms{$publishInfo.offer_id}",1)' value="同 意" style="font-size:0.86rem;"/>
                                            <input type="button" class="btn btn-blue2 send-custom-msg" onclick='refundReply("{pigcms{$publishInfo.offer_id}",2)' value="拒 绝" style="font-size:0.86rem;"/>
                                        </div>
                                    </div>

                                    <script>
                                        function refundReply(offer_id,type){
                                            var refund_reply_url = "{pigcms{:U('Service/refund_reply')}";
                                            if(type == 1){
                                                var status = 6;
                                            }else{
                                                var status = 2;
                                            }
                                            $.post(refund_reply_url,{'offer_id':offer_id,'type':type,'status':status},function(data){
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
                                            },'json')
                                        }
                                    </script>
                                <elseif condition="$publishInfo['status'] eq 6"/>
                                    <div class="keep-modular">
                                        <div>订单已关闭</div>
                                    </div>
                                <elseif condition="$publishInfo['status'] eq 3"/>
                                    <div class="keep-modular">
                                        <div>已完成服务，等待用户确认</div>
                                    </div>
                                <elseif condition="$publishInfo['status'] eq 4"/>
                                    <div class="keep-modular">
                                        <div>订单已完成待评价</div>
                                    </div>
                                <elseif condition="$publishInfo['status'] eq 7"/>
                                    <div class="keep-modular">
                                        <div style="height: 30px;">订单已评价</div>
                                        <div style="margin-top: 5px;">评价内容：{pigcms{$evaluate_info.content}</div>
                                    </div>
                                </if>
                                

                            </div>



                        </div>

<!--                         <div class="pop_action_s5 contact-phone js_bottomfixed">
                            <div class="contact-phone-con">
                                <a href="tel:{pigcms{$publishInfo.phone}" class="btn btn-blue btn-phone js_contact_phone">
                                    <i class="ico ico-tel-6"></i>
                                    拨打电话
                                </a>
                                <div class="txt-s1" style="width: 180px;">
                                    <p>联系时请告知是{pigcms{$config.site_name}平台看到的</p>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div> -->

                        <div id="message_content">
                            <div id="js_add_message" matchid="213016793">
                                <div class="content-box add-message-box">
                                    <div class="l-reference-offer custom-msg js_custom_msg">
                                        <div class="l-offer-hd">
                                            <span class="hd-title">您给TA的留言：</span>
                                        </div>
                                        <div class="custom-msg-con">
                                            <div class="msg-box">
                                                <i class="tips-arrow" style="left: 52px;"></i>
                                                <div class="msg-box-con" style="height: 80px;">
                                                    <textarea style="resize: none;" class="msg-control" name="content" id="service_first_content" maxlength="200"></textarea>
                                                </div>
                                            </div>
                                            <div class="btn-custom-msg-wrap">
                                                <input type="button" class="btn btn-blue2 send-custom-msg" id="send_custom_msg" value="发 送" style="font-size:0.86rem;"/></div>
                                        </div>
                                    </div>

                                    <script>
                                        $("#send_custom_msg").click(function(){
                                            var message = $("#service_first_content").val();
                                            if(!message){
                                                layer.open({
                                                    content: '留言内容不可以为空'
                                                    ,btn: ['确定']
                                                });
                                                return false;
                                            }
                                            var send_msg_url = "{pigcms{:U('Service/send_msg')}";
                                            var offer_id = "{pigcms{$publishInfo['offer_id']}";
                                            var publish_id = "{pigcms{$publishInfo['publish_id']}";
                                            var uid = "{pigcms{$publishInfo['uid']}";
                                            var p_uid = "{pigcms{$user_session['uid']}";
                                            var type = 2;
                                            $.post(send_msg_url,{'offer_id':offer_id,'publish_id':publish_id,'uid':uid,'p_uid':p_uid,'type':type,'message':message},function(data){
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

                                        })
                                    </script>
                                    <div class="no_record hidden">
                                        <div class="no_record_con">暂无留言记录……</div>
                                    </div>
                                    <ul class="ul-list5">
                                        <volist name="offerMsgList" id="vo">
                                            <li class="li cell_li " matchid="213016793">
                                                <if condition="$vo['type'] eq 1">
                                                    <div class="ulli-3">
                                                        <div class="user_head_box">
                                                            <a href="javascript:;" class="user_head">
                                                                <img src="{pigcms{$publishInfo.avatar}" class="preview_head_img">                                    
                                                                <span class="radiushaed"></span>
                                                            </a>
                                                        </div>
                                                        <div class="cell-s1">
                                                            <div class="time">（{pigcms{$vo.add_time|date="Y-m-d H:i:s",###}）</div>
                                                            <span class="fc-orange">{pigcms{$publishInfo.nickname}</span> 给您留言:
                                                        </div>
                                                    </div>
                                                <else/>
                                                    <div class="ulli-3">
                                                        <div class="user_head_box">
                                                            <a href="javascript:;" class="user_head">
                                                                <img src="{pigcms{$user_session['avatar']}" class="preview_head_img">                                    
                                                                <span class="radiushaed"></span>
                                                            </a>
                                                        </div>
                                                        <div class="cell-s1">
                                                            <div class="time">（{pigcms{$vo.add_time|date="Y-m-d H:i:s",###}）</div>
                                                            <span class="fc-orange">您</span> 给{pigcms{$publishInfo.nickname}留言:
                                                        </div>
                                                    </div>

                                                </if>
                                                <div class="ulli-5">{pigcms{$vo.message}</div>
                                            </li>
                                        </volist>
                                    </ul>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="js_quickQuoteBarSit"></div>
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
            </div>
        </div>
        <div class="bottomfixed_h" style="height:56px;"></div>
    </div>


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

</body>
</html>