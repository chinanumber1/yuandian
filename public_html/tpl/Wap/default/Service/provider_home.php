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
    <meta name="keywords" content="服务，服务快派，到位"/>
    <meta name="description" content="服务快派，一个快速解决多行业需求与跑腿服务的平台。"/>
    <link href='{pigcms{$static_path}service/css/basic.css?t=58da05f1' rel='stylesheet' type='text/css' />

    <!-- Public js-->
    <script src='{pigcms{$static_path}service/js/jquery-2.1.4.js?t=58a16a34'></script>
    <script src='{pigcms{$static_path}service/js/json2.js?t=58a16a34'></script>
    <script src='{pigcms{$static_path}service/js/basic.js?t=58d24290'></script>

    <title>服务商主页</title>
    <link href='{pigcms{$static_path}service/css/home.css?t=58da05f1' rel='stylesheet' type='text/css' />
</head>
<body>

    <!-- <include file="Service:right_nav"/> -->
    <div class="pagewrap" id="mainpage">
        <!-- <include file="Service:header_top"/> -->

        <div class="main home-page main-minh" pagebg="bg-gray">
            <div id="homebar_wrap">
                <div class="home-topbar banner-8">
                    <div class="info-basic-wrap v-center">
                        <a href="javascript:;" class="user_head_box">
                            <div class="user_head"><img src="{pigcms{$providerInfo.avatar}" id="company_logo" class="js_ftpview preview_head_img"></div>
                        </a>
                        <div class="info-basic">
                            <div class="user-name">{pigcms{$providerInfo.name}</div>
                            <div class="l-phone">
                                商户电话：
                                <span class="has-tel">{pigcms{$providerInfo.phone}</span>
                            </div>
                            <div class="l-phone">
                                赚取费用：
                                <span class="has-tel">{pigcms{$providerInfo.total_amount}</span>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <a href="{pigcms{:U('Service/editheader')}" class="ico ico-edit-topbar"></a>
                </div>
                <div id="homebar_sit"></div>
                <div class="home-nav-wrap padd-wrap1">
                    <ul class="home-nav">
                        <li class="nav-li"> <a href="javascript:;" show_id="#home_index" class="cur">商户信息</a> </li>
                        <li class="nav-li"> <a href="javascript:;" show_id="#home_userEvaluate">服务评价</a> </li>
                    </ul>
                </div>
            </div>
            
            <!-- 服务商信息 -->
            <div class="info-option-wrap padd-wrap1" id="home_index">
                <div class="info-option option-frist address_option ">
                    <div class="home-tab">
                        <div class="fl-right">
                            <if condition="$providerInfo['sname']">
                                <a href="{pigcms{:U('Service/editaddress')}" class="ico ico-edit"></a>
                            <else/>
                                <a href="{pigcms{:U('Service/editaddress')}" class="add-link"> <i class="ico ico-add"></i> 添加 </a>
                            </if>
                            
                        </div>
                        <div class="info-address">{pigcms{$providerInfo.sname}</div>
                    </div>
                </div>






                <div class="info-option img_option  js_img_show_wrap">
                    <div class="home-tab">
                        <div class="fl-right">
                            <if condition="is_array($imgList)">
                                <a href="{pigcms{:U('Service/authentication')}" class="ico ico-edit"></a>
                                <else/>
                                <a href="{pigcms{:U('Service/authentication')}" class="add-link">
                                    认证
                                </a>
                            </if>
                        </div>
                        <span class="title-s2">商户认证</span>
                    </div>

                </div>




                <div class="info-option img_option  js_img_show_wrap">
                    <div class="home-tab">
                        <div class="fl-right">
                            <if condition="is_array($imgList)">
                                <a href="{pigcms{:U('Service/editphoto')}" class="ico ico-edit"></a>
                                <else/>
                                <a href="{pigcms{:U('Service/editphoto')}" class="add-link"> <i class="ico ico-add"></i>添加 </a>
                            </if>
                        </div>
                        <span class="title-s2">商户相册:</span>
                    </div>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
                    <div class="con-block" style="white-space:normal;">
                        <div class="img-show swiper-container swiper-container-horizontal swiper-container-android">
                            <div class="img-group" style="">
                                <volist name="imgList" id="vo">
                                        <a href="javascript:void(0);" onclick='showImg("{pigcms{$vo.img_url}")'><img src="{pigcms{$vo.img_url}" style="float:left;margin-right:10px;margin-top:10px; border-radius:0px; width: 70.5px; height: 70.5px;"></a>
                                </volist>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>

                </div>

       

                <div class="info-option introduce_cate_option">
                    <div class="home-tab">
                        <div class="fl-right">
                            <if condition="$providerInfo['describe']">
                                <a href="{pigcms{:U('Service/editdesc')}" class="ico ico-edit"></a>
                            <else/>
                                <a href="{pigcms{:U('Service/editdesc')}" class="add-link"> <i class="ico ico-add"></i> 添加 </a>
                            </if>
                            
                        </div>
                        <span class="title-s2">商户介绍:</span>
                    </div>
                    <div class="con-block introduce"><if condition="$providerInfo['describe']">{pigcms{$providerInfo.describe}<else/>暂未填写商户介绍……</if></div>


                    <div class="con-block cate_option">
                        <div class="cate-tag">
                            <ul class="ullist-tag ">
                                <volist name="addresList" id="vo">
                                    <li class="city-li">
                                        <div class="city-title select-city">{pigcms{$vo.sname}</div>
                                        <div class="tag-list1-wrap">
                                            <div class="tag-list1 clearfix">
                                                <volist name="vo.catList" id="vovo">
                                                    <p rel_id="{pigcms{$vovo.cid}" class="btn-desc">{pigcms{$vovo.cat_name}</p>
                                                </volist>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                    </li>
                                </volist>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <div class="info-option-wrap padd-wrap1 hidden" id="home_userEvaluate">
                <div class="info-option diary_option">
                    <div class="home-tab">
                        <span class="title-s2">用户评价：</span>
                    </div>
                    <if condition="is_array($offer_evaluate_list)">
                        <volist name="offer_evaluate_list" id="vo">
                            <div class="diary-list js_agent">
                                <div class="diary-li">
                                    <div class="diary-con">
                                        <div style="height: 25px;">
                                            <span style="float:left;">{pigcms{$vo.nickname}</span> 
                                            <span style="float: right;color: #07cdad;">{pigcms{$vo.whole}分</span>
                                        </div>

                                        <div class="con">
                                            <span class="con-text">{pigcms{$vo.content}</span>
                                        </div>
                                        <div class="time">
                                            发布日期：{pigcms{$vo.add_time|date="Y-m-d H:i:s",###}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </volist>
                    <else/>
                        <div class="no_record">
                            <div class="no_record_con">暂无评价……</div>
                        </div>
                    </if>
                </div>
            </div>
        </div>
    </div>
     <style>
            body{line-height: 1.5;}
           .fl { float: left; display: inline; }
            .bottom{ background: #fff; position: fixed; left:0px; bottom:0px; width: 100%; box-shadow: 0px 0px 25px 3px #d8dce0; z-index: 100000;}
            .bottom .bottom_n li { width: 20%; text-align: center; }
            .bottom .bottom_n li a{ width: 100%; display: block;  text-align: center; font-size: 12px; color: #757575; padding-top: 35px;margin-bottom: 5px;}


            .bottom .bottom_n li.xq a{ background: url({pigcms{$static_path}service/images/home/xq.png) center 6px no-repeat;  background-size: 24px 23px; }
             .bottom .bottom_n li.sh.active_img a{ background: url({pigcms{$static_path}service/images/home/5-1.png) center 6px no-repeat;  background-size: 24px 23px; color:#06C1AE;}
            .bottom .bottom_n li.xqon a{ background: url({pigcms{$static_path}service/images/home/xqon.png) center 6px no-repeat;  background-size: 24px 23px; color:#06c1ae; }

            .bottom .bottom_n li.gr a{ background: url({pigcms{$static_path}service/images/home/gr.png) center 6px no-repeat;  background-size: 24px 23px; }
            .bottom .bottom_n li.gron a{ background: url({pigcms{$static_path}service/images/home/gron.png) center 6px no-repeat;  background-size: 24px 23px; color:#06c1ae; }


            .bottom .bottom_n li.home i{ display: inline-block; width: 47px; height: 47px; border-radius: 100%;  background: url({pigcms{$static_path}service/images/home/home.png) center no-repeat #e0e0e0; background-size: 21px 19px; top: -20px; left: 50%; margin-left: -27px;  border: #fff 4px solid; position: absolute; box-shadow: 0px -10px 20px -5px #d8dce0}
            .bottom .bottom_n li.home a{  display:block; position: relative; }
            .bottom .bottom_n li.homeon i{ background: url({pigcms{$static_path}service/images/home/1-1.png) center no-repeat #e0e0e0; background-size: 21px 19px;  }
            .bottom .bottom_n li.homeon a{ color: #757575;}


            
            .bottom .bottom_n li.bj a{ background: url({pigcms{$static_path}service/images/home/bj.png) center 6px no-repeat;  background-size: 23px 23px; }
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
                    <li class="bj fl">
                        <a href="{pigcms{:U('Service/trade')}">报价</a>
                    </li>
                    <li class="sh fl active_img">
                        <a href="{pigcms{:U('Service/provider_home')}">服务商</a>
                    </li>
                </ul>
            </div>
        </section>
         <!-- <div class="mask hide" ></div>
        <div class="content_img">
            <img src="" alt="" class="img_show">
        </div> -->
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
    <script type="text/javascript">
       $('.home-nav .nav-li a').on('click',function(){
            $('.home-nav .nav-li a').removeClass('cur');
            $(this).addClass('cur');
            $('.home-page .info-option-wrap').addClass("hidden");
            var show_id=$(this).attr('show_id');
            $(show_id).removeClass('hidden');
        });
    </script>
</body>
</html>