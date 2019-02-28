<!DOCTYPE html>
<html lang="zh-CN" >
    <head>
        <meta charset="utf-8"/>
        <title>我的需求</title>
        <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no,width=device-width"/>
        <meta http-equiv="pragma" content="no-cache"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name='apple-touch-fullscreen' content='yes'/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
        <meta name="format-detection" content="telephone=no"/>
        <meta name="format-detection" content="address=no"/>
        <link href="{pigcms{$static_path}service/css/pigcms.css" rel="stylesheet"/>
        <link href="{pigcms{$static_path}service/css/mobiscroll.2.13.2.css" rel="stylesheet"/>
        <script src="{pigcms{$static_path}service/js/jquery-2.1.4.js"></script>
    </head>

    <body>
        <section class="public pageSliderHide">
      <!--   <a href="{pigcms{:U('Service/index')}" ><div class="return link-url" data-url-type="openLeftWindow" data-url="back"></div></a> -->
            <div class="content">我的需求（{pigcms{$publishCount}）</div>
        </section>

        <div class="h44"></div>
        <!-- <div class="remind" style="display:block; margin-top: 5px; background: #90c3be;">您已发布了{pigcms{$publishCount}条需求</div> -->

        <section class="store" style="margin-top: 5px;">
            <div class="entry kd_entry">
                <ul id="publish_list">
                    <volist name="publishList" id="vo">
                        <a href="{pigcms{:U('Service/price_list',array('publish_id'=>$vo['publish_id']))}">

                            <if condition="$vo.catgory_type eq 1">
                                

                                <li class="link-url" data-url="shop_detail.html?order_id=8867" data-webview="true">
                                    <div class="top clr">
                                        <if condition="$vo.endtime">
                                            <div class="fl">截至日期：{pigcms{$vo.endtime}</div>
                                        </if>
                                    </div>
                                    <div class="con">
                                        <div class="con_top">
                                            <p>
                                                所属分类：
                                                <span class="red">{pigcms{$vo.cat_name}</span>
                                            </p>
                                            <p>
                                                报价人数：{pigcms{$vo.offer_count}个
                                            </p>
                                            <p>
                                                发布时间：{pigcms{$vo.add_time|date="Y-m-d H:i:s",###}
                                            </p>
                                        </div>
                                        <div class="remark">
                                            <div>
                                                <volist name="vo['offer_list']" id="offer_vo" offset="0" length='5'> 
                                                    <img style="border-radius:35px; width:35px; height:35px; border:#cdefeb 1px solid;" src="{pigcms{$offer_vo.avatar}" alt=""> 
                                                    
                                                </volist>
                                                <if condition="$vo.offer_count gt 5">...</if>
                                            </div>
                                            <div class="overtime">
                                                <if condition="$vo['status'] eq 1">已发布
                                                <elseif condition="$vo['status'] eq 2"/>已付款待服务
                                                <elseif condition="$vo['status'] eq 3"/>已服务待确认
                                                <elseif condition="$vo['status'] eq 4"/>订单完成
                                                <elseif condition="$vo['status'] eq 5"/>申请退款
                                                <elseif condition="$vo['status'] eq 6"/>退款成功
                                                <elseif condition="$vo['status'] eq 7"/>评价成功
                                                <elseif condition="$vo['status'] eq 8"/>等待取货
                                                <elseif condition="$vo['status'] eq 9"/>配送中
                                                <elseif condition="$vo['status'] eq 10"/>已暂停报价
                                                <elseif condition="$vo['status'] eq 11"/>已经过期
                                                <elseif condition="$vo['status'] eq 12"/>过期退款
                                                <elseif condition="$vo['status'] eq 13"/>退款失败
                                                </if>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                            <elseif condition="$vo.catgory_type eq 2"/>
                                <li class="link-url" data-url="shop_detail.html?order_id=8867" data-webview="true">
                                    <div class="top clr">
                                        <div class="fl">截至日期：{pigcms{$vo.endtime}</div>
                                    </div>
                                    <div class="con">
                                        <div class="con_top">
                                            <p>
                                                所属分类：
                                                <span class="red">{pigcms{$vo.cat_name}</span>
                                            </p>
                                            <p>
                                                发布时间：{pigcms{$vo.add_time|date="Y-m-d H:i:s",###}
                                            </p>
                                        </div>
                                        <div class="remark">
                                            <div>
                                                <volist name="vo['offer_list']" id="offer_vo"> 
                                                    <img style="border-radius:35px; width:35px; height:35px; border:#3d9900 1px solid; padding-left: 1px;" src="{pigcms{$offer_vo.avatar}" alt=""> 
                                                </volist>
                                            </div>
                                            <div class="overtime">
                                                <if condition="$vo['status'] eq 1"><a style="border: 1px solid #06C1AE; padding: 3px 10px;border-radius: 3px;color: #06C1AE;" data-id="{pigcms{$vo['publish_id']}"  onclick="payMoney(this)" href="javascript:void(0);">去支付</a>
                                                <elseif condition="$vo['status'] eq 2"/>待接单
                                                <elseif condition="$vo['status'] eq 3"/>已服务
                                                <elseif condition="$vo['status'] eq 4"/>订单完成
                                                <elseif condition="$vo['status'] eq 5"/>申请退款
                                                <elseif condition="$vo['status'] eq 6"/>退款成功
                                                <elseif condition="$vo['status'] eq 7"/>评价成功
                                                <elseif condition="$vo['status'] eq 8"/>等待取货
                                                <elseif condition="$vo['status'] eq 9"/>配送中
                                                <elseif condition="$vo['status'] eq 11"/>已经过期
                                                </if>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <elseif condition="$vo.catgory_type eq 3"/>
                                <li class="link-url" data-url="shop_detail.html?order_id=8867" data-webview="true">
                                    <div class="top clr">
                                        <div class="fl">截至日期：{pigcms{$vo.endtime}</div>
                                    </div>
                                    <div class="con">
                                        <div class="con_top">
                                            <p>
                                                所属分类：
                                                <span class="red">{pigcms{$vo.cat_name}</span>
                                            </p>
                                            <p>
                                                发布时间：{pigcms{$vo.add_time|date="Y-m-d H:i:s",###}
                                            </p>
                                        </div>
                                        <div class="remark">
                                            <div>
                                                <volist name="vo['offer_list']" id="offer_vo"> 
                                                    <img style="border-radius:35px; width:35px; height:35px; border:#3d9900 1px solid; padding-left: 1px;" src="{pigcms{$offer_vo.avatar}" alt=""> 
                                                </volist>
                                            </div>
                                            <div class="overtime">
                                                <if condition="$vo['status'] eq 1"><a style="border: 1px solid #06C1AE; padding: 3px 10px;border-radius: 3px;color: #06C1AE;" data-id="{pigcms{$vo['publish_id']}"  onclick="payMoney(this)" href="javascript:void(0);">去支付</a>
                                                <elseif condition="$vo['status'] eq 2"/>待接单
                                                <elseif condition="$vo['status'] eq 3"/>已服务
                                                <elseif condition="$vo['status'] eq 4"/>订单完成
                                                <elseif condition="$vo['status'] eq 5"/>申请退款
                                                <elseif condition="$vo['status'] eq 6"/>退款成功
                                                <elseif condition="$vo['status'] eq 7"/>评价成功
                                                <elseif condition="$vo['status'] eq 8"/>等待取货
                                                <elseif condition="$vo['status'] eq 9"/>配送中
                                                <elseif condition="$vo['status'] eq 11"/>已经过期
                                                </if>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </if>
                        </a>
                    </volist>
                </ul>
            </div>
            <!-- 上拉刷新 -->
        <div class="pullup" style="display:none;">上拉加载更多...</div>
        <div class="loading" style="display:none;"><img src="{pigcms{$static_path}service/images/xubox_loading2.gif" alt="">正在加载</div>
            <div style="padding-bottom: 80px;"></div>
        </section>
        
        <style>
            .pullup,.loading{
                text-align: center;
                padding:0 0 10px 0;
                font-size: 14px;
                color: #999;
            }
            .loading img{
                height: 14px;
                vertical-align: middle;
                margin-right: 2px;
            }
            body{line-height: 1.5;}
           .fl { float: left; display: inline; }
            .bottom{ background: #fff; position: fixed; left:0px; bottom:0px; width: 100%; box-shadow: 0px 0px 25px 3px #d8dce0; z-index: 100000;}
            .bottom .bottom_n li { width: 20%; text-align: center; }
            .bottom .bottom_n li a{ width: 100%; display: block;  text-align: center; font-size: 12px; color: #757575; padding-top: 35px;
                margin-bottom: 5px;}


            .bottom .bottom_n li.xq a{ background: url({pigcms{$static_path}service/images/home/xq.png) center 6px no-repeat;  background-size: 24px 23px; }
             .bottom .bottom_n li.xq.active_img a{ background: url({pigcms{$static_path}service/images/home/2-1.png) center 6px no-repeat;  background-size: 24px 23px; color:#06C1AE;}
            .bottom .bottom_n li.xqon a{ background: url({pigcms{$static_path}service/images/home/xqon.png) center 6px no-repeat;  background-size: 24px 23px; color:#06c1ae; }

            .bottom .bottom_n li.gr a{ background: url({pigcms{$static_path}service/images/home/gr.png) center 6px no-repeat;  background-size: 24px 23px; }
            .bottom .bottom_n li.gron a{ background: url({pigcms{$static_path}service/images/home/gron.png) center 6px no-repeat;  background-size: 24px 23px; color:#06c1ae; }


            .bottom .bottom_n li.home i{ display: inline-block; width: 47px; height: 47px; border-radius: 100%;  background: url({pigcms{$static_path}service/images/home/home.png) center no-repeat #e0e0e0; background-size: 21px 19px; top: -20px; left: 50%; margin-left: -27px;  border: #fff 4px solid; position: absolute; box-shadow: 0px -10px 20px -5px #d8dce0}
            .bottom .bottom_n li.home a{  display:block; position: relative; }
            .bottom .bottom_n li.homeon i{ background: url({pigcms{$static_path}service/images/home/home.png) center no-repeat #e0e0e0; background-size: 21px 19px;  display: inline-block;width: 39px;height: 39px;}
            .bottom .bottom_n li.homeon a{ color: #757575;}


            
            .bottom .bottom_n li.bj a{ background: url({pigcms{$static_path}service/images/home/bj.png) center 6px no-repeat;  background-size: 20px 23px; }
            .bottom .bottom_n li.bjon a{ background: url({pigcms{$static_path}service/images/home/bjon.png) center 6px no-repeat;  background-size: 20px 23px; color:#06c1ae; }

            .bottom .bottom_n li.sh a{ background: url({pigcms{$static_path}service/images/home/sh.png) center 6px no-repeat;  background-size: 20px 23px; }
            .bottom .bottom_n li.shon a{ background: url({pigcms{$static_path}service/images/home/shon.png) center 6px no-repeat;  background-size: 20px 23px; color:#06c1ae; }

        </style>

        <section class="bottom">
            <div class="bottom_n">
                <ul>
                    <li class="xq fl active_img">
                    <a href="{pigcms{:U('Service/need_list')}">需求</a>
                </li>
                <li class="gr fl">
                    <a href="{pigcms{:U('My/index')}">我的</a>
                </li>
                <li class="home homeon fl">
                    <a href="{pigcms{:U('Service/index')}"><i></i>首页</a>
                </li>
                <li class="bj fl">
                    <a href="{pigcms{:U('Service/trade')}">报价</a>
                </li>
                <li class="sh fl">
                    <a href="{pigcms{:U('Service/provider_home')}">服务商</a>
                </li>
                </ul>
            </div>
        </section>
        <script>
            var flag = false;
            var page = 0;
            // 上拉加载和下拉刷新
            $(window).scroll(function(e) {
                e.stopPropagation();
                //上拉加载
                if(flag){
                    //数据加载中
                    return false;
                }
                if ($(document).scrollTop() >= $(document).height() - $(window).height()) {
                    $('.pullup').hide();
                    $('.loading').show();
                    flag = true;
                    page++;
                    changeAllshou();
                    $('.pullup').show();
                    $('.loading').hide();
                }
            });

            function changeAllshou(){
                var need_list_ajax_url = "{pigcms{:U('need_list_ajax')}";
                $.post(need_list_ajax_url,{page:page},function(data){
                    if(data.error == 1){
                        flag = false;
                        $("#publish_list").append(data.publishListHtml);
                    }else{
                        flag = true;
                        $('.pullup').html(data.msg);
                    }
                },'json');
            }
            var pay = false;
            // 支付
            function payMoney(obj) {
                if (pay) return false;
                pay = true;
                var id = $(obj).data('id');
                var order_url = "{pigcms{:U('Service/service_plat_order')}";
                console.log('支付的服务id:  ', id);
                $.post(order_url,{publish_id:id},function(data){
                    pay = false;
                    console.log('订单消息', data)
                    if (data && data.order_id) {
                        var order_id = data.order_id;
                        var pay_url = "{pigcms{:U('Pay/check',array('type'=>'plat'))}" + '&order_id=' + order_id;
                        console.log(pay_url);
                        window.location.href = pay_url;
                    }
                },'json');
            }
        </script>
    </body>
</html>

