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

    <link href='{pigcms{$static_path}service/css/basic.css?t=58da05f1' rel='stylesheet' type='text/css' />
    <script src='{pigcms{$static_path}service/js/jquery-2.1.4.js?t=58a16a34'></script>
    <script src='{pigcms{$static_path}service/js/json2.js?t=58a16a34'></script>
    <script src='{pigcms{$static_path}service/js/basic.js?t=58d24290'></script>
    <script src='{pigcms{$static_path}service/js/md5.min.js?t=58a16a34'></script>
    <script src='{pigcms{$static_path}service/js/newcode-src.js?t=58a16a34'></script>
    <title>已完成需求</title>
    <link href='{pigcms{$static_path}service/css/quote.css?t=58d24290' rel='stylesheet' type='text/css' />
    <link href='{pigcms{$static_path}service/css/quote_1.css?t=58ddfc0c' rel='stylesheet' type='text/css' />
    <link href='{pigcms{$static_path}service/css/animate.css?t=58a16a34' rel='stylesheet' type='text/css' />
</head>
<body>
<div class="pagewrap" id="mainpage">
    <div class="main padd-wrap1">
        <!-- 措施机会不显示 -->
        <div class="status-list-wrap "  >
            <form id="service-publish-form">
                <ul class="status-list">
                    <li class="status_li" style="width: 25%;"> <a href="{pigcms{:U('Service/trade_special')}">跑腿需求</a> </li>
                    <li class="status_li" style="width: 25%;"> <a href="{pigcms{:U('Service/trade')}" >待报价需求</a> </li>
                    <li class="status_li" style="width: 25%;"> <a href="{pigcms{:U('Service/trade_contact')}">联系中需求</a> </li>
                    <li class="status_li" style="width: 25%;"> <a href="{pigcms{:U('Service/trade_over')}" class="current" >已完成需求</a> </li>
                </ul>
            </form>
        </div>
        <div id="wrapper_top"></div>
        <div >
            <if condition="is_array($publishList)">
                <ul class="ul-list-9" style="margin-top: 15px;">
                    <volist name="publishList" id="vo">
                        <li>
                            <if condition="$vo.catgory_type eq 1">
                                <a href="{pigcms{:U('Service/offer_detail',array('offer_id'=>$vo['offer_id']))}">
                            <else/>
                                <a href="{pigcms{:U('Service/detail_special',array('publish_id'=>$vo['publish_id']))}">
                            </if>
                            
                            <dl class="sm-cell">
                                <dt class="sm-li1" style="z-index: 0;">
                                    <div class="fl-right"></div>
                                    <span class="user-name">{pigcms{$vo.nickname}</span>
                                    <span class="phone">({pigcms{$vo.phone})</span>
                                    <span class="quote-state blue">
                                        订单已完成 
                                    </span>
                                </dt>


                                <dd>
                                    <div class="sm-li2">
                                        <span class="con-txt1">
                                            正在寻找
                                            <span class="fc-orange">{pigcms{$vo.cat_name}</span>
                                            <span class="small">需求概要:</span>
                                        </span>

                                        <if condition="$vo.catgory_type eq 1">
                                            <volist name="vo.cat_field" id="vovo">
                                                <span class="txt-cell"> 【{pigcms{$vovo.alias_name}】
                                                    <span class="txt-cell-con">
                                                        <if condition="$vovo['type'] eq 6">
                                                           {pigcms{$vovo.value.address}
                                                        <elseif condition="$vovo['type'] eq 3"/>
                                                            <volist name="vo.value" id="vvo">
                                                                {pigcms{$vvo}
                                                            </volist>
                                                        <elseif condition="$vovo['type'] eq 2"/>
                                                            <if condition="$vovo['value'] eq 'inputdesc'">
                                                                {pigcms{$vovo.desc}
                                                            <elseif condition="$vovo['value'] eq 'time'"/>
                                                                {pigcms{$vovo.date}{pigcms{$vovo.minute}
                                                            <else/>
                                                                {pigcms{$vovo.value}
                                                            </if>
                                                        <elseif condition="$vovo['type'] eq 4"/>
                                                            {pigcms{$vovo.value.time_start} {pigcms{$vovo.value.time_end}
                                                        <elseif condition="$vovo['type'] eq 7"/>
                                                                起点：{pigcms{$vovo['value']['address_start']} 到达点：{pigcms{$vovo['value']['address_end']}
                                                        <else/>
                                                        {pigcms{$vovo.value}
                                                        </if>
                                                    </span> 
                                                </span>
                                            </volist>
                                        <elseif condition="$vo.catgory_type eq 2"/>
                                            【商品要求】{pigcms{$vo.cat_field.goods_remarks}
                                            【预估价格】{pigcms{$vo.cat_field.estimate_goods_price}
                                            【配送费】{pigcms{$vo.cat_field.total_price}
                                            【送达时间】{pigcms{$vo.cat_field.arrival_time}（分钟内）
                                        <elseif condition="$vo.catgory_type eq 3"/>
                                            【商品分类】{pigcms{$vo.cat_field.goods_catgory}
                                            【商品重量】{pigcms{$vo.cat_field.weight}（KG）
                                            【物品价值】{pigcms{$vo.cat_field.price}
                                            【取件时间】{pigcms{$vo.cat_field.fetch_time}
                                            【支付小费】{pigcms{$vo.cat_field.tip_price}
                                        </if>
                                        
                                    </div>
                                    <div class="sm-li3">
                                        
                                        <div class="time"><i class="ico ico-service-time"></i>{pigcms{$vo.add_time|date="Y-m-d H:i:s",###}</div>
                                    </div>
                                </dd>
                            </dl>
                            </a>
                        </li>
                    </volist>
                </ul>
            <else/>
                <div class="no_record service_no_record">
                    <div class="no_record_con">此项无结果……</div>
                </div>
            </if>

        </div>
    </div>
</div>

<style>
    body{line-height: 1.5;}
   .fl { float: left; display: inline; }
    .bottom{ background: #fff; position: fixed; left:0px; bottom:0px; width: 100%; box-shadow: 0px 0px 25px 3px #d8dce0; }
    .bottom .bottom_n li { width: 20%; text-align: center; }
    .bottom .bottom_n li a{ width: 100%; display: block;  text-align: center; font-size: 12px; color: #757575; padding-top: 35px;margin-bottom: 5px;}


    .bottom .bottom_n li.xq a{ background: url({pigcms{$static_path}service/images/home/xq.png) center 6px no-repeat;  background-size: 24px 23px; }
     .bottom .bottom_n li.bj.active_img a{ background: url({pigcms{$static_path}service/images/home/4-1.png) center 6px no-repeat;  background-size: 24px 23px; color:#06C1AE;}
    .bottom .bottom_n li.xqon a{ background: url({pigcms{$static_path}service/images/home/xqon.png) center 6px no-repeat;  background-size: 24px 23px; color:#06c1ae; }

    .bottom .bottom_n li.gr a{ background: url({pigcms{$static_path}service/images/home/gr.png) center 6px no-repeat;  background-size: 24px 23px; }
    .bottom .bottom_n li.gron a{ background: url({pigcms{$static_path}service/images/home/gron.png) center 6px no-repeat;  background-size: 24px 23px; color:#06c1ae; }


    .bottom .bottom_n li.home i{ display: inline-block; width: 47px; height: 47px; border-radius: 100%;  background: url({pigcms{$static_path}service/images/home/home.png) center no-repeat #e0e0e0; background-size: 21px 19px; top: -20px; left: 50%; margin-left: -27px;  border: #fff 4px solid; position: absolute; box-shadow: 0px -10px 20px -5px #d8dce0}
    .bottom .bottom_n li.home a{  display:block; position: relative; }
    .bottom .bottom_n li.homeon i{ background: url({pigcms{$static_path}service/images/home/1-1.png) center no-repeat #e0e0e0; background-size: 21px 19px;  }
    .bottom .bottom_n li.homeon a{ color: #757575;}


    
    .bottom .bottom_n li.bj a{ background: url({pigcms{$static_path}service/images/home/bj.png) center 6px no-repeat;  background-size: 20px 23px; }
    .bottom .bottom_n li.bjon a{ background: url({pigcms{$static_path}service/images/home/bjon.png) center 6px no-repeat;  background-size: 20px 23px; color:#06c1ae; }

    .bottom .bottom_n li.sh a{ background: url({pigcms{$static_path}service/images/home/sh.png) center 6px no-repeat;  background-size: 20px 23px; }
    .bottom .bottom_n li.shon a{ background: url({pigcms{$static_path}service/images/home/shon.png) center 6px no-repeat;  background-size: 20px 23px; color:#06c1ae; }

</style>
<section class="bottom">
    <div class="bottom_n">
        <ul>
            <li class="xq fl ">
                <a href="{pigcms{:U('Service/need_list')}">需求</a>
            </li>
            <li class="gr fl">
                <a href="{pigcms{:U('My/index')}">我的</a>
            </li>
            <li class="home homeon fl">
                <a href="{pigcms{:U('Service/index')}"><i></i>首页</a>
            </li>
            <li class="bj fl active_img">
                <a href="{pigcms{:U('Service/trade')}">报价</a>
            </li>
            <li class="sh fl">
                <a href="{pigcms{:U('Service/provider_home')}">服务商</a>
            </li>
        </ul>
    </div>
</section>

<script type="text/javascript">
    $(function(){
        $('#tab-s6-screen .screen-list').on('click',function(){
            $('#tab-s6-screen').removeClass('tab-s6-first');
            $(this).addClass('cur').siblings().removeClass('cur');
            var screenIndex=$(this).index();//获取tab-s6下tab的索引
            $('#popnormal .screen-tpl-pop .screen-list').eq(screenIndex).addClass('cur');//在要弹层相对应的ul显示
            popnormal({
                'popTplId':'#filter_tpl_pop',//内容模板id
                'popId':'popnormal',//弹出层id 默认为popnormal,（可自定义）
                "popSize":"filter-tpl-pop",//控制大小样式 //可选项
                'eventEle':'#tab-s6-screen .screen-list',//点击事件元素（不定义：立即弹出）
                'popStyle':'slideInUp',
                'popAction':[{'actionTxt':'取消','actionId':'js_delOption'}],//按钮区 actionColse=false 取消自带点击关闭层
                'popCallbackFun':function(args,TfThis){//popCallbackFun 可选项
                    $('#popnormal .screen-tpl-pop .screen-list').eq(screenIndex).addClass('cur');
                }
            });
        })
    });
</script>

</body>
</html>
