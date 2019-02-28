<!DOCTYPE html>
<html>
    <head>
        <title>帮我送</title>
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name='apple-touch-fullscreen' content='yes'>
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="format-detection" content="telephone=no">
        <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="http://hf.pigcms.com/tpl/Wap/pure/static/css/component.css">
        <link rel="stylesheet" href="{pigcms{$static_path}service/css/buy_adress.css">
        <link href="http://hf.pigcms.com/tpl/Wap/pure/static/layer/need/layer.css" type="text/css" rel="styleSheet" id="layermcss">
        <link rel="stylesheet" href="{pigcms{$static_path}service/css/give_index.css">
        <script src="{pigcms{$static_path}service/js/jquery-2.1.4.js"></script>
        <script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>

        <link href='{pigcms{$static_path}service/css/basic.css?t=58da05f1' rel='stylesheet' type='text/css' /><!-- Public js-->
        <script src='{pigcms{$static_path}service/js/json2.js?t=58a16a34'></script>
        <script src='{pigcms{$static_path}service/js/basic.js?t=58d24290'></script>
        <script type="text/javascript" src="{pigcms{$static_public}js/ajaxfileupload.js"></script>
        <script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
        <link href='{pigcms{$static_path}service/css/demand.css?t=58d8bf20' rel='stylesheet' type='text/css' />
        
        <style>
            .addressHtml{
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 10000;
                background: #F1F1F1;
                display: none;
            }
            .addressHtml ul{
                 background: #fff;
                 margin-top: 20px;
            }
            .addressHtml ul li{
                width: 100%;
                padding: 0 5%;
                border-bottom: 1px solid #f1f1f1;
                background: #fff;

            }
            .addressHtml ul li input{
                width: 100%;
                font-size: 14px;
                height: 14px;
                padding: 22px 0;
                border: none;
                   padding-left: 20px;
            }
            .addressHtml ul li:first-child{
                position: relative;
            }
             .addressHtml ul li p{
                font-size: 14px;
                padding: 15px 0 15px 20px;
             }
            .addressHtml ul li:first-child i{
                display: inline-block;
                width: 16px;
                height: 16px;
                background: url("{pigcms{$static_path}service/images/basic/ico_distance.png") center no-repeat;
                background-size:contain;
                vertical-align: middle;
                position: absolute;
                top: 17px;
            }
            .btns{
                margin-top: 20px;
                width: 100%;
                text-align: center;
            }
            .btns button{
                width: 44%;
                height: 40px;
                text-align: center;
                line-height: 40px;
                border:none;
                border-radius: 5px;
                background: #0ED7B3;
            }
 
        </style>
        

        <style>
            .add-imglist-col4 .add-imglist1 .cell-del a {
                display: inline-block;
                position: absolute;
                z-index: 10000;
                left: -19px;
                top: -4px;
            }
            .add-imglist1 .cell-img img {
                /* width: 70.5px!important; */
                /* height: 70.5px!important; */
                display: block;
                width: 94%!important;
                height: 5.5rem!important;
            }
            .add-imglist1 .btn-ftp1 {
                /*background: #e6e6e6;*/
                height: 5.5rem!important;
                width: 94%!important;
            }
            .load-div {
                text-align: center;
                position: fixed;
                z-index: 10000;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                background: rgba(0,0,0,0.5);
                color: white;
            }
        </style>
    </head>
    <body>
    <div id="hidden_map" style="display: none;"></div>
    <input type="hidden" id="now_center" value="" />
        <form action="{pigcms{:U('Service/publish_give_data')}" id="publish_demand_form" method="post" formdataarr="demandForm_Arr">
            <div class="give_class">
                <div class="give_goods" onclick="catgory_list_click()">
                    <label class="order-timefield-label">物品分类</label>
                    <span  class="order-timefield-span">
                        <span id="catgory_html">{pigcms{$temp_give_data.goods_catgory}</span>
                        <input type="hidden" name="catgory_value" id="catgory_value" value="{pigcms{$temp_give_data.goods_catgory}">
                        <i class="rg"></i>
                    </span>
                </div>

                <div class="give_weight" onclick="weight_list_click()">
                    <label class="order-timefield-label">物品重量</label>
                    <span  class="order-timefield-span">
                    <span id="weight_html"><if condition="$temp_give_data.weight gt 0 ">{pigcms{$temp_give_data.weight}kg</if></span>
                        <input type="hidden" name="weight_value" id="weight_value" value="{pigcms{$temp_give_data.weight}">
                        <i class="rg"></i>
                    </span>
                </div>

                <div class="give_value" onclick="price_list_click()">
                    <label class="order-timefield-label">物品价值</label>
                    <span  class="order-timefield-span">
                        <span id="price_html">{pigcms{$temp_give_data.price}</span>
                        <input type="hidden" name="price_value" id="price_value" value="{pigcms{$temp_give_data.price}">
                        <i class="rg"></i>
                    </span>
                </div>

                <div class="service-edit-box1 demand-form-list form-list-show" style="background-color: #ffffff; border-top: 1px solid #f0f0f0; overflow-x:auto;     padding: 0.5rem 0.6rem 0 0.6rem; height: 160px;">
                    <div class="form-list1">
                        <div class="li js_img_show_wrap ele-wrap add-imglist-col4">
                            <label class="lab-title"><span class="validate-title">商品图片：</span><span class="lab-blue">（最多上传4张图片）</span></label>
                            <div class="add-imglist1">
                                <div id="result">
                                    <if condition="$temp_give_data['img']">
                                        <volist name="temp_give_data['img']" id="imgvo">
                                            <div class="cell">
                                                <div class="cell-img ">
                                                    <a href="javascript:void(0);" class="fancybox cell_li" rel="gallery" title=""><img src="{pigcms{$imgvo}"></a>
                                                </div>
                                                <input type="hidden" name="img[]" value="{pigcms{$imgvo}">
                                                <div class="cell-del">
                                                    <a href="javascript:;" class="del-btn"> <i class="ico ico-del"></i>
                                                        <span class="txt-del">删除</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </volist>
                                    </if>
                                </div>
                                <div class="cell" id="container">
                                    <div class="btn-ftp1" id="upimgFileBtn"> <i  class="ico ico-add"></i></div>
                                    <input type="file" id="imgUploadFile" onchange="imgUpload()" accept="image/*" style="position:absolute;opacity:0;left:0;top:0;z-index:9999;width:100%;height:100%;" name="imgFile" value="选择文件上传"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <!--发件地址-->
            <div class="give_sender" style="font-size:14px;" >
                <label class="order-timefield-label">发</label>
                <div id="start_adress_html" onclick="tmp_adress_click('start')" style="height:30px;width: 80%;    border-right: 1px solid #d5d5d5;margin-top: 7px;">
                    <if condition="$temp_give_data['start_adress_html'] neq ''">
                        {pigcms{$temp_give_data.start_adress_html|htmlspecialchars_decode}
                    <else/>
                        <span style="color: #a7a4a4;">点此快速添加地址</span>
                    </if>
                </div>
                <input type="hidden" id="start_adress_id" value="{pigcms{$temp_give_data.start_adress_id}">
                <input type="hidden" id="start_adress_name" value="{pigcms{$temp_give_data.start_adress_name}"/>
                <input type="hidden" id="start_adress_phone" value="{pigcms{$temp_give_data.start_adress_phone}" >
                <input type="hidden" id="start_adress_lng" value="{pigcms{$temp_give_data.start_adress_lng}"/>
                <input type="hidden" id="start_adress_lat" value="{pigcms{$temp_give_data.start_adress_lat}"/>
                <input type="hidden" id="start_adress_detail" value="{pigcms{$temp_give_data.start_adress_detail}"/>
                <input type="hidden" id="start_adress_area_id" value="{pigcms{$temp_give_data.start_adress_area_id}"/>
                <input type="hidden" id="start_adress_area_name" value="{pigcms{$temp_give_data.start_adress_area_name}"/>

                <input type="hidden" id="start_province_id" value="{pigcms{$temp_give_data.start_province_id}"/>
                <input type="hidden" id="start_city_id" value="{pigcms{$temp_give_data.start_city_id}"/>
                <input type="hidden" id="start_area_id" value="{pigcms{$temp_give_data.start_area_id}"/>

                <span class="rg" onclick="adress_list_click('start')" style="margin-top: 23px;background: #06C1AE;padding: 4px 10px;margin-right: 7px;border-radius: 5px;color: #fff;position: absolute;right: 0;top: -15px;z-index: 100;">常用</span>
                <!-- <i class="rg" ></i> -->
            </div>

            <!--收件地址-->
            <div class="give_addressee" style="font-size:14px;">
                <label class="order-timefield-label">收</label>
                <div id="end_adress_html" onclick="tmp_adress_click('end')"  style="height: 30px;width: 80%; margin-top: 7px;   border-right: 1px solid #d5d5d5;">
                    <if condition="$temp_give_data['end_adress_html'] neq ''">
                        {pigcms{$temp_give_data.end_adress_html|htmlspecialchars_decode}
                    <else/>
                        <span style="color: #a7a4a4;">点此快速添加地址</span>
                    </if>
                </div>

                <input type="hidden" id="end_adress_id" value="{pigcms{$temp_give_data.end_adress_id}">

                <input type="hidden" id="end_adress_name" value="{pigcms{$temp_give_data.end_adress_name}" >
                <input type="hidden" id="end_adress_phone" value="{pigcms{$temp_give_data.end_adress_phone}" >
                <input type="hidden" id="end_adress_lng" value="{pigcms{$temp_give_data.end_adress_lng}" >
                <input type="hidden" id="end_adress_lat" value="{pigcms{$temp_give_data.end_adress_lat}" >
                <input type="hidden" id="end_adress_detail" value="{pigcms{$temp_give_data.end_adress_detail}"/>
                <input type="hidden" id="end_adress_area_id" value="{pigcms{$temp_give_data.end_adress_area_id}" >
                <input type="hidden" id="end_adress_area_name" value="{pigcms{$temp_give_data.end_adress_area_name}" >

                <input type="hidden" id="end_province_id" value="{pigcms{$temp_give_data.end_province_id}"/>
                <input type="hidden" id="end_city_id" value="{pigcms{$temp_give_data.end_city_id}"/>
                <input type="hidden" id="end_area_id" value="{pigcms{$temp_give_data.end_area_id}"/>

                <!-- <i class="rg" onclick="adress_list_click('end')"></i> -->
                <span class="rg" onclick="adress_list_click('end')"style="margin-top: 23px;background: #06C1AE;padding: 4px 10px;margin-right: 7px;border-radius: 5px;color: #fff;    position: absolute;right: 0;top: -15px;z-index: 100;">常用</span>
            </div>
            <input type="hidden" name="adress_type" id="adress_type" value="{pigcms{$temp_give_data.adress_type}">
            

            <!-- 取件时间 配送费 -->
            <div class="order-timefield" onclick="time_list_click()">
                <label class="order-timefield-label">取件时间</label>
                <span class="order-timefield-span">
                <span id="time_html"><if condition="$temp_give_data.fetch_time">{pigcms{$temp_give_data.fetch_time}<else/>立即取件</if></span>
                    <input type="hidden" name="time_value" id="time_value" value="{pigcms{$temp_give_data.fetch_time}">
                    <input type="hidden" name="time_type" id="time_type" value="">
                    <i class="icon-arrow-right-thin"></i>
                </span>
            </div>

            <div class="order-region">
                <label class="order-timefield-label">配送费</label>
                <span class="order-timefield-span">
                    <i class="nofee_money"></i>
                    ￥<span class="total_price_html"><if condition='$temp_give_data.total_price gt 0'>{pigcms{$temp_give_data.total_price}<else/>{pigcms{$config_time['service_delivery_fee']+$config['service_basic_weight_price']}</if></span>
                </span>
            </div>

            <!-- 我要加小费 -->
            <div class="buy_tip">
                <div>
                    <label>我要加小费</label>
                    <span>加小费可以更快抢单哦<i></i></span>
                    <a href="javascript:void(0);" class="rg add_money <if condition='$temp_give_data.tip_price elt 0 '>remove_money</if>"><i class="rg"></i></a>
                </div>
                
                <p style="display:<if condition='$temp_give_data.tip_price elt 0 '>none<else/>block</if>;">
                    <button class="<if condition='$temp_give_data.tip_price eq 5 '>buy_active</if>" data-price="5" type="button" >5元</button>
                    <button class="<if condition='$temp_give_data.tip_price eq 10 '>buy_active</if>" data-price="10" type="button">10元</button>
                    <button class="<if condition='$temp_give_data.tip_price eq 15 '>buy_active</if>" data-price="15" type="button">15元</button>
                    <button class="<if condition='$temp_give_data.tip_price eq 20 '>buy_active</if>" data-price="20" type="button">20元</button>
                    <button data-price="" type="button" class="other <if condition='$temp_give_data.tip_price neq 5 AND $temp_give_data.tip_price neq 10 AND $temp_give_data.tip_price neq 15 AND $temp_give_data.tip_price neq 20 '>buy_active</if>">其他</button>
                </p>
                <p class="money_tip" style="display:<if condition='$temp_give_data.tip_price gt 0 AND $temp_give_data.tip_price neq 5 AND $temp_give_data.tip_price neq 10 AND $temp_give_data.tip_price neq 15 AND $temp_give_data.tip_price neq 20 '>block<else/>none</if>;"><span>￥</span><input style="width: 93%;" type="text" onkeyup="value=value.replace(/[^\d]/g,''),tip_price_keyup(this.value)" value="<if condition='$temp_give_data.tip_price gt 0'>{pigcms{$temp_give_data.tip_price}<else/>0</if>"></p>
                <input type="hidden" name="tip_price" value="<if condition='$temp_give_data.tip_price gt 0'>{pigcms{$temp_give_data.tip_price}<else/>0</if>" id="tip_price">
            </div>

            <!-- 备注 -->
            <div class="order-region_beizhu">
                <div class="order-field clearfix" style="height: 43px;line-height: 42px;">
                    <label class="order-label">备注：</label>
                    <input class="order-input" type="text" id="remarks" value="{pigcms{$temp_give_data.remarks}" placeholder="想对骑手说什么">
                </div>
            </div>
            <div style="padding-bottom: 70px;"></div>
            <div class="order-btn-field">
                <input id="order-submit" class="combtn order-btn" type="submit" style="background : #06c1ae;" value="发布并支付">
                <span style="margin-left: 35px; font-size: 16px;" class="order-total-field">待支付&nbsp;<b style="color: #06c1ae;">￥</b><span id="total_price_html" style="color: #06c1ae;"><if condition='$temp_give_data.total_price gt 0'>{pigcms{$temp_give_data.total_price}<else/>{pigcms{$config_time['service_delivery_fee']+$config['service_basic_weight_price']}</if></span></span>
                <span id="order-benefit" class="order-benefit"></span>
            </div>
            <input type="hidden" name="cid" id="cid" value="{pigcms{$_GET['cid']}">
        </form>
        




        <div class="addressHtml">
            <ul>
                <li><i></i><p onclick="address_click()" id="tmp_adress_name_html">请选择小区/大厦/标志建筑</p></li> 
                <li><input type="text" value="" id="tmp_name" placeholder="联系人"/></li>
                <li><input type="text" value="" id="tmp_phone" placeholder="手机号"/></li>
                <li><input type="text" value="" id="tmp_detail" placeholder="请补充详细地址"/></li>

                <input type="hidden" id="tmp_adress" value=""/>
                <input type="hidden" id="tmp_adress_lng" value=""/>
                <input type="hidden" id="tmp_adress_lat" value=""/>
                <input type="hidden" id="tmp_adress_area_id" value=""/>
                <input type="hidden" id="tmp_adress_area_name" value=""/>
            </ul>
            <div class="btns">
                <button onclick="cancel_adress()" style="margin-right: 2%; background: #fff; color: #999;">取消</button>
                <button onclick="confirm_adress()" style="margin-left: 2%;color: #fff;background: #06C1AE;">确认</button>
            </div>
        </div>

        <script>
            setTimeout(function(){
                var timeList = "{pigcms{$timeList}";
                console.log(timeList);
                if (!timeList) {
                    layer.open({
                        content:'抱歉！当前暂无符合范围的取件时间',
                        btn: ['返回'],
                        shadeClose:false,
                        yes:function(){
                            history.go(-1);
                        }
                    });
                }
            },500);
            function tmp_adress_click(type){
                $("#adress_type").val(type);
                $("#tmp_adress_name_html").html('请选择小区/大厦/标志建筑');
                $("#tmp_name").val('');
                $("#tmp_phone").val('');
                $("#tmp_detail").val('');
                $("#tmp_adress").val('');
                $("#tmp_adress_lng").val('');
                $("#tmp_adress_lat").val('');
                $("#tmp_adress_area_id").val('');
                $("#tmp_adress_area_name").val('');
                $('.addressHtml').show();
            }

            function cancel_adress(){
                $('.addressHtml').hide();
            }

            function confirm_adress(){
                var tmp_name = $("#tmp_name").val();
                var tmp_phone = $("#tmp_phone").val();
                var tmp_detail = $("#tmp_detail").val();
                var tmp_adress = $("#tmp_adress").val();
                var tmp_adress_lng = $("#tmp_adress_lng").val();
                var tmp_adress_lat = $("#tmp_adress_lat").val();
                var tmp_adress_area_id = $("#tmp_adress_area_id").val();
                var tmp_adress_area_name = $("#tmp_adress_area_name").val();
                // var adress_type = $("#adress_type").val();

                var tmp_province_id = $("#province_id").val();
                var tmp_city_id = $("#city_id").val();
                var tmp_area_id = $("#area_id").val();


                if(!tmp_name){
                    alert('请输入联系人');
                    return false;
                }
                if(!tmp_phone){
                    alert('请输入联系方式');
                    return false;
                }
                if(!tmp_detail){
                    alert('请输入详细地址');
                    return false;
                }
                if(!tmp_adress){
                    alert('请选择地址坐标');
                    return false;
                }

                address_choose(tmp_name,tmp_phone,tmp_adress+tmp_detail,tmp_adress_lng,tmp_adress_lat,tmp_adress_area_id,tmp_adress_area_name,tmp_province_id,tmp_city_id,tmp_area_id);


                $('.addressHtml').hide();

            }

            function address_click(){
                $('.mask').show();
                $("#searchAddress").css('display','block');
            }

        </script>




        <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}yuedan/css/style_dd39d16.css"/>
        <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style_dd39d16.css">
        <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}yuedan/css/address_9d295cd.css?t=432"/>
        <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/address_9d295cd.css?t=001">
        <script type="text/javascript" src="{pigcms{$static_path}js/jquery.cookie.js"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/common.js?002"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/SelectChar.js?210" charset="utf-8"></script>

        <div id="searchAddress" style="z-index:100001; background:#ffffff; display: none;  position:fixed; left:0; top:0%; width:100%; height:100%; margin-top: -0;">
            <input type="hidden" id="area_id"/>
            <input type="hidden" id="city_id"/>
            <input type="hidden" id="province_id"/>

            <div id="address-widget-map" class="address-widget-map">
                <div class="address-map-nav">
                    <div class="city_box" style="padding-left: 15px;">
                        <span>读取中</span>
                    </div>
                    <div class="center-title" style="padding-left: 15px;">
                        <div class="ui-suggestion-mask">
                            <input type="text" placeholder="请输入你的收货地址" id="se-input-wd" autocomplete="off"/>
                            <div class="ui-suggestion-quickdel"></div>
                        </div>
                    </div>
                    <div style="padding-left: 15px;">
                        <span onclick="$('#searchAddress').hide();$('#searchAddressTxt').val('');$('#rcv-mask').hide();">关闭</span>
                    </div>
                </div>
                <div id="fis_elm__3">
                    <div class="map">
                        <div class="MapHolder" id="cmmap"></div>
                        <div class="dot" style="display:block;"></div>
                    </div>
                    <div class="mapaddress" data-node="mapaddress">
                        <ul id="addressShow"> </ul>
                    </div>
                </div>
                <div id="fis_elm__4" style="display:none;width: 100%;">
                    <section class="citylistBox">
                        <dl>
                            <volist name="all_city" id="vo">
                                <dt id="city_{pigcms{$key}" class="cityKey" data-city_key="{pigcms{$key}">{pigcms{$key}</dt>
                                <volist name="vo" id="voo">
                                    <dd class="city_location" data-city_url="{pigcms{$voo.area_url}">{pigcms{$voo.area_name}</dd>
                                </volist>
                            </volist>
                        </dl>
                    </section>
                    <div id="selectCharBox"></div>
                </div>
            </div>
        </div>
    <if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places&key={pigcms{$config.google_map_ak}"></script>
        <script type="text/javascript">
            var address = '';
            var timeout = 0;
            var map = null;
            $(document).ready(function(){
                $('.map .MapHolder').height(($(window).height()-50)*0.4);
                $('.mapaddress').height(($(window).height()-52)*0.6);

                $('#fis_elm__4').height($(window).height()-52);

                var cityKey = [];
                $.each($('.cityKey'),function(i,item){
                    cityKey.push($(item).data('city_key'));
                });

                $("#selectCharBox").css({'float':'right',height:($(window).height()-50),width:50,'z-index':9998}).seleChar({
                    chars:cityKey,
                    callback:function(ret){
                        $('#fis_elm__4').scrollTop(($('#city_'+ret).position().top) + $('#fis_elm__4').scrollTop() - 50);
                    }
                });

                $('.city_location').click(function(){
                    $('.city_box span').html($(this).html());
                    $('.city_box').removeClass('arrow');
                    $('#fis_elm__3').show();
                    $('#fis_elm__4').hide();

                    var geocoder =  new google.maps.Geocoder();
                    geocoder.geocode( { 'address': $(this).html()}, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            map.setCenter({lat:results[0].geometry.location.lat(),lng:results[0].geometry.location.lng()},16);
                            $('#now_center').val(results[0].geometry.location.lat()+','+results[0].geometry.location.lng());
                        } else {
                            alert("Something got wrong " + status);
                        }
                    });
                    setTimeout(function(){
                        var centerMap = map.getCenter();
                        getPositionInfo(centerMap.lat(),centerMap.lng());
                    },700);
                });

                $('.city_box').click(function(){
                    if($(this).hasClass('arrow')){
                        $(this).removeClass('arrow');
                        $('#fis_elm__3').show();
                        $('#fis_elm__4').hide();
                    }else{
                        $(this).addClass('arrow');
                        $('#fis_elm__3').hide();
                        $('#fis_elm__4').show();
                    }
                });

                // 谷歌地图API功能

                var map = new google.maps.Map(document.getElementById('cmmap'), {
                    mapTypeControl:false,
                    zoom: 16,
                    center: {lng:parseFloat(117.228692),lat:parseFloat(31.822943)}
                });
                //初始化
                $('#now_center').val('31.822943,117.228692');
                $('.city_box span').html('合肥');
                getPositionInfo(31.822943,117.228692);

                map.addListener("dragend", function(e){
                    $('#addressShow').empty();
                    var centerMap = map.getCenter();
                    $('#now_center').val(centerMap.lat()+','+centerMap.lng());
                    getPositionInfo(centerMap.lat(),centerMap.lng());
                });

                $("#se-input-wd").bind('input', function(e){
                    var address = $.trim($('#se-input-wd').val());
                    if(address.length > 0){
                        $('#cmmap').hide();
                        $('.mapaddress').height(($(window).height()-52));

                        $('#addressShow').empty();
                        clearTimeout(timeout);
                        timeout = setTimeout("search('"+address+"')", 500);
                    }else{
                        $('#cmmap').show();
                        $('.mapaddress').height(($(window).height()-52)*0.6);
                    }
                });

                $('#addressShow').delegate("li","click",function(){
                    var sname = $(this).attr("sname");
                    var lng = $(this).attr("lng");
                    var lat = $(this).attr("lat");

                    var cityMatchingUrl = "{pigcms{:U('Service/cityMatching')}";
                    $.get(cityMatchingUrl, {'lng':lng,'lat':lat}, function(data){
                        if(data.error == 1){

                            $("#tmp_adress").val(sname);
                            $("#tmp_adress_lng").val(lng);
                            $("#tmp_adress_lat").val(lat);
                            $("#tmp_adress_area_id").val(data.area_id);
                            $("#tmp_adress_area_name").val(data.area_name);
                            $("#tmp_adress_name_html").html(sname);
                            $('#area_id').val(data.now_area_id);

                        }else{
                            layer.open({
                                content: data.msg
                                ,btn: ['确定']
                            });
                        }
                    },'json');
                    $('.mask').hide();
                    $("#searchAddress").css('display','none');
                });

                // var geolocation = new BMap.Geolocation();


                var user_address = $.cookie('user_address');
                if(user_address){
                    user_address = $.parseJSON(user_address);
                }
                if(user_address && user_address.longitude && user_address.latitude){
                    map.setCenter({lng:parseFloat(user_address.longitude),lat:parseFloat(user_address.latitude)}, 16);
                    $('#now_center').val(parseFloat(user_address.latitude)+','+parseFloat(user_address.longitude));
                    getPositionInfo(user_address.latitude,user_address.longitude);
                }else{
                    navigator.geolocation.getCurrentPosition(function(position){
                        map.setCenter({lng:parseFloat(position.coords.longitude),lat:parseFloat(position.coords.latitude)}, 16);
                        getPositionInfo(position.coords.latitude,position.coords.longitude);
                    })
                }
            });

            function getIframe(userLonglat,userLong,userLat){
                geoconv('realResult',userLong,userLat);
            }
            function realResult(result){
                var lng = result.result[0].x;
                var lat = result.result[0].y;
                var point = new BMap.Point(lng, lat);
                // alert(JSON.stringify(result));
                map.centerAndZoom(point, 16);
                getPositionInfo(lat, lng);
            }

            function search(address){

                var map;
                var service;
                var centerMap = $('#now_center').val();
                centerMap = centerMap.split(',');

                if(centerMap){
                    user_long = parseFloat(centerMap[1]);
                    user_lat = parseFloat(centerMap[0]);
                }else{
                    user_long = 117.228692;
                    user_lat = 31.822943;
                }

                map = new google.maps.Map(document.getElementById('hidden_map'), {
                    center:{lat:user_lat,lng:user_long},
                    zoom: 15
                });

                var request = {
                    bounds:map.getBounds(),
                    query: address
                };

                service = new google.maps.places.PlacesService(map);
                service.textSearch(request, callback);

                function callback(results, status) {
                    if (status == google.maps.places.PlacesServiceStatus.OK) {
                        getAdress(results,false,1);
                    }
                }
            }
            function getPositionInfo(lat,lng){
                var map;
                var service;
                var centerMap = $('#now_center').val();
                centerMap = centerMap.split(',');
                if(centerMap){
                    user_long = parseFloat(centerMap[1]);
                    user_lat = parseFloat(centerMap[0]);
                } else{
                    user_long = parseFloat(lng);
                    user_lat = parseFloat(lat);
                }

                map = new google.maps.Map(document.getElementById('hidden_map'), {
                    center:{lat:user_lat,lng:user_long},
                    zoom: 15
                });

                var request = {
                    location: {lat:user_lat,lng:user_long},
                    radius: '200'
                };

                service = new google.maps.places.PlacesService(map);
                service.nearbySearch(request, callback);

                function callback(results, status) {
                    if (status == google.maps.places.PlacesServiceStatus.OK) {
                        getAdress(results,false);
                    }
                }
            }
            function getPositionAdress(result){
                if(result.status == 0){
                    result = result.result;
                    $.post("{pigcms{:U('Home/cityMatching')}",{'city_name':result.addressComponent.city,'area_name':result.addressComponent.district,'get_province':'1','all_city':'1'},function(res){
                        if(res.status == 1){
                            $('.city_box span').html(res.info.area_name);
                            $('#province_id').val(res.info.province_id);
                            $('#city_id').val(res.info.area_id);
                            $('#area_id').val(res.info.now_area_id);
                            var re = [];
                            if(result.sematic_description.indexOf("附近0米") < 0){
                                re.push({'name':result.sematic_description,'address':result.formatted_address,'long':result.location.lng,'lat':result.location.lat});
                            }
                            for(var i in result.pois){
                                re.push({'name':result.pois[i].name,'address':result.pois[i].addr,'long':result.pois[i].point.x,'lat':result.pois[i].point.y});
                            }
                            getAdress(re,false);
                        }else{
                            alert('当前城市不可用');
                        }
                    });
                }else{
                    alert('获取位置失败！');
                }
            }
            function getAdress(re,isSearch,isText){
                $('#addressShow').html('');
                var addressHtml = '';
                if(isText==1){
                    for(var i=0;i<re.length;i++){
                        if (re[i].geometry.location.lng() == null || re[i].geometry.location.lat() == null) continue;
                        addressHtml += '<li lng="'+re[i].geometry.location.lng()+'" lat="'+re[i].geometry.location.lat()+'" sug_address="'+re[i].name+'" address="'+re[i].formatted_address+'" sname="'+re[i].name+'" class="addresslist" '+(isSearch ? 'data-search="true" data-city="'+re[0].name+'" data-district="'+re[re.length-1]['district']+'"' : '')+'>';
                        addressHtml += '<div class="mapaddress-title '+(i!=0 ? 'notself' : '')+'">';
                        addressHtml += '<span class="icon-location" data-node="icon"></span>';
                        addressHtml += '<span class="recommend"> '+(i == 0 ? '[建议位置]' : '')+'   '+re[i].name+' </span> </div>';
                        addressHtml += '<div class="mapaddress-body"> '+re[i].formatted_address+' </div>';
                        addressHtml += '</li>';
                    }
                }else{
                    for(var i=1;i<re.length-1;i++){
                        if (re[i].geometry.location.lng() == null || re[i].geometry.location.lat() == null) continue;
                        addressHtml += '<li lng="'+re[i].geometry.location.lng()+'" lat="'+re[i].geometry.location.lat()+'" sug_address="'+re[i].name+'" address="'+re[i].vicinity+'" sname="'+re[i].name+'" class="addresslist" '+(isSearch ? 'data-search="true" data-city="'+re[0].name+'" data-district="'+re[re.length-1]['district']+'"' : '')+'>';
                        addressHtml += '<div class="mapaddress-title '+(i!=1 ? 'notself' : '')+'">';
                        addressHtml += '<span class="icon-location" data-node="icon"></span>';
                        addressHtml += '<span class="recommend"> '+(i == 1 ? '[建议位置]' : '')+'   '+re[i].name+' </span> </div>';
                        addressHtml += '<div class="mapaddress-body"> '+re[i].vicinity+' </div>';
                        addressHtml += '</li>';
                    }
                }
                $('#addressShow').append(addressHtml);
                $('.mapaddress').css('width','100%');
            }
        </script>
        <else />
        <script type="text/javascript" src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1" charset="utf-8"></script>
        <script type="text/javascript">
            var address = '';
            var timeout = 0;
            var map = null;
            $(document).ready(function(){
                $('.map .MapHolder').height(($(window).height()-50)*0.4);
                $('.mapaddress').height(($(window).height()-52)*0.6);
                
                $('#fis_elm__4').height($(window).height()-52);
                
                var cityKey = [];
                $.each($('.cityKey'),function(i,item){
                    cityKey.push($(item).data('city_key'));
                });

                $("#selectCharBox").css({'float':'right',height:($(window).height()-50),width:50,'z-index':9998}).seleChar({
                    chars:cityKey,
                    callback:function(ret){
                        $('#fis_elm__4').scrollTop(($('#city_'+ret).position().top) + $('#fis_elm__4').scrollTop() - 50);
                    }
                });
                
                $('.city_location').click(function(){
                    $('.city_box span').html($(this).html());
                    $('.city_box').removeClass('arrow');
                    $('#fis_elm__3').show();
                    $('#fis_elm__4').hide();
                    
                    map.centerAndZoom($(this).html(), 16);
                    setTimeout(function(){
                        var centerMap = map.getCenter();
                        getPositionInfo(centerMap.lat,centerMap.lng);
                    },700);     
                });
                
                $('.city_box').click(function(){
                    if($(this).hasClass('arrow')){
                        $(this).removeClass('arrow');
                        $('#fis_elm__3').show();
                        $('#fis_elm__4').hide();
                    }else{
                        $(this).addClass('arrow');
                        $('#fis_elm__3').hide();
                        $('#fis_elm__4').show();
                    }
                });
                
                // 百度地图API功能
                map = new BMap.Map("cmmap",{enableMapClick:false});
                map.centerAndZoom(new BMap.Point(117.228692,31.822943), 16);
                
                
                map.addEventListener("dragend", function(e){
                    $('#addressShow').empty();
                    var centerMap = map.getCenter();
                    getPositionInfo(centerMap.lat,centerMap.lng);
                });
                
                $("#se-input-wd").bind('input', function(e){
                    var address = $.trim($('#se-input-wd').val());
                    if(address.length > 0){
                        $('#cmmap').hide();
                        $('.mapaddress').height(($(window).height()-52));
                        
                        $('#addressShow').empty();
                        clearTimeout(timeout);
                        timeout = setTimeout("search('"+address+"')", 500);
                    }else{
                        $('#cmmap').show();
                        $('.mapaddress').height(($(window).height()-52)*0.6);
                    }
                });

                $('#addressShow').delegate("li","click",function(){
                    var sname = $(this).attr("sname");
                    var lng = $(this).attr("lng");
                    var lat = $(this).attr("lat");

                    var cityMatchingUrl = "{pigcms{:U('Service/cityMatching')}";
                    $.get(cityMatchingUrl, {'lng':lng,'lat':lat}, function(data){
                        if(data.error == 1){

                            $("#tmp_adress").val(sname);
                            $("#tmp_adress_lng").val(lng);
                            $("#tmp_adress_lat").val(lat);
                            $("#tmp_adress_area_id").val(data.area_id);
                            $("#tmp_adress_area_name").val(data.area_name);
                            $("#tmp_adress_name_html").html(sname);
                            $('#area_id').val(data.now_area_id);

                        }else{
                            layer.open({
                                content: data.msg
                                ,btn: ['确定']
                            });
                        }
                    },'json');
                    $('.mask').hide();
                    $("#searchAddress").css('display','none');
                }); 

                // var geolocation = new BMap.Geolocation();
                
                
                var user_address = $.cookie('user_address');
                if(user_address){
                    user_address = $.parseJSON(user_address);
                }
                if(user_address && user_address.longitude && user_address.latitude){
                    map.centerAndZoom(new BMap.Point(user_address.longitude,user_address.latitude), 16);
                    getPositionInfo(user_address.latitude,user_address.longitude);
                }else{
                    // geolocation.getCurrentPosition(function(r){
                    //     if(this.getStatus() == BMAP_STATUS_SUCCESS){
                    //         map.centerAndZoom(new BMap.Point(r.point.lng,r.point.lat), 16);
                    //         getPositionInfo(r.point.lat,r.point.lng);
                    //     }else{
                    //         alert('failed：'+this.getStatus());
                    //     }
                    // },{enableHighAccuracy: true});
                    getUserLocation({useHistory:false,okFunction:'getIframe'});
                }
            });

            function getIframe(userLonglat,userLong,userLat){
                geoconv('realResult',userLong,userLat);
            }
            function realResult(result){
                var lng = result.result[0].x;
                var lat = result.result[0].y;
                var point = new BMap.Point(lng, lat);
                // alert(JSON.stringify(result));
                map.centerAndZoom(point, 16);
                getPositionInfo(lat, lng);
            }

            function search(address){
                $.get('index.php?g=Index&c=Map&a=suggestion', {city_id:$('#city_id').val(),query:$('.city_box span').html() + address}, function(data){
                    if(data.status == 1){
                        if(data.result[0] && data.result[0].city && data.result[0].district){
                            getAdress(data.result,true);
                        }
                    }
                });
            }
            function getPositionLocation(result){
                if(result.status == 0){
                    result = result.result;
                    getPositionInfo(result.location.lat,result.location.lng);
                }else{
                    alert('获取位置失败！');
                }
            }
            function getPositionInfo(lat,lng){
                $.getJSON('https://api.map.baidu.com/geocoder/v2/?ak=4c1bb2055e24296bbaef36574877b4e2&location='+lat+','+lng+'&output=json&pois=1&callback=getPositionAdress&json=?');
            }
            function getPositionAdress(result){
                if(result.status == 0){
                    result = result.result;
                    $.post("{pigcms{:U('Home/cityMatching')}",{'city_name':result.addressComponent.city,'area_name':result.addressComponent.district,'get_province':'1','all_city':'1'},function(res){
                        if(res.status == 1){
                            $('.city_box span').html(res.info.area_name);
                            $('#province_id').val(res.info.province_id);
                            $('#city_id').val(res.info.area_id);
                            $('#area_id').val(res.info.now_area_id);
                            var re = [];
                            if(result.sematic_description.indexOf("附近0米") < 0){
                                re.push({'name':result.sematic_description,'address':result.formatted_address,'long':result.location.lng,'lat':result.location.lat});
                            }
                            for(var i in result.pois){
                                re.push({'name':result.pois[i].name,'address':result.pois[i].addr,'long':result.pois[i].point.x,'lat':result.pois[i].point.y});
                            }
                            getAdress(re,false);
                        }else{
                            alert('当前城市不可用');
                        }
                    });
                }else{
                    alert('获取位置失败！');
                }
            }
            function getAdress(re,isSearch){
                $('#addressShow').html('');
                var addressHtml = '';
                for(var i=0;i<re.length;i++){
                    if (re[i]['long'] == null || re[i]['lat'] == null) continue;
                    addressHtml += '<li lng="'+re[i]['long']+'" lat="'+re[i]['lat']+'" sug_address="'+re[i]['name']+'" address="'+re[i]['address']+'" sname="'+re[i]['name']+'" class="addresslist" '+(isSearch ? 'data-search="true" data-city="'+re[i]['city']+'" data-district="'+re[i]['district']+'"' : '')+'>';
                    addressHtml += '<div class="mapaddress-title '+(i!=0 ? 'notself' : '')+'">';
                    addressHtml += '<span class="icon-location" data-node="icon"></span>';
                    addressHtml += '<span class="recommend"> '+(i == 0 ? '[建议位置]' : '')+'   '+re[i]['name']+' </span> </div>';
                    addressHtml += '<div class="mapaddress-body"> '+re[i]['address']+' </div>';
                    addressHtml += '</li>';
                }
                $('#addressShow').append(addressHtml);
				$('.mapaddress').css('width','100%');
            }
        </script>
    </if>






        <script>
            $("#publish_demand_form").submit(function(){
                var img = '';
                $("input[name='img[]']").each(function(index,item){
                    img += $(this).val()+";";
                });
                var catgory_value = $("#catgory_value").val();
                var weight_value = $("#weight_value").val();
                var price_value = $("#price_value").val();

                var start_adress_name = $("#start_adress_name").val();
                var start_adress_phone = $("#start_adress_phone").val();
                var start_adress_lng = $("#start_adress_lng").val();
                var start_adress_lat = $("#start_adress_lat").val();
                var start_adress_detail = $("#start_adress_detail").val();
                var start_adress_area_id = $("#start_adress_area_id").val();
                var start_adress_area_name = $("#start_adress_area_name").val();

                var start_province_id = $("#start_province_id").val();
                var start_city_id = $("#start_city_id").val();
                var start_area_id = $("#start_area_id").val();


                var end_adress_name = $("#end_adress_name").val();
                var end_adress_phone = $("#end_adress_phone").val();
                var end_adress_lng = $("#end_adress_lng").val();
                var end_adress_lat = $("#end_adress_lat").val();
                var end_adress_detail = $("#end_adress_detail").val();
                var end_adress_area_id = $("#end_adress_area_id").val();
                var end_adress_area_name = $("#end_adress_area_name").val();

                var end_province_id = $("#end_province_id").val();
                var end_city_id = $("#end_city_id").val();
                var end_area_id = $("#end_area_id").val();




                var time_value = $("#time_value").val();
                // alert(time_value);
                var tip_price = $("#tip_price").val();
                var remarks = $("#remarks").val();
                var destance_sum = $("#destance_sum").val();
                var weight_price = $("#weight_price").val();
                var basic_distance_price = $("#basic_distance_price").val();
                var distance_price = $("#distance_price").val();
                var total_price = $("#total_price").val();
                var cid = $("#cid").val();

                if(!catgory_value){
                    layer.open({
                        content: '请选择物品分类'
                        ,btn: ['确定']
                    });
                    return false;
                }

                if(!weight_value){
                    layer.open({
                        content: '请选择物品重量'
                        ,btn: ['确定']
                    });
                    return false;
                }

                if(!price_value){
                    layer.open({
                        content: '请选择物品的价值'
                        ,btn: ['确定']
                    });
                    return false;
                }

                if(!start_adress_lat){
                    layer.open({
                        content: '请填写发货地址'
                        ,btn: ['确定']
                    });
                    return false;
                }

                if(!end_adress_lat){
                    layer.open({
                        content: '请填写收货地址'
                        ,btn: ['确定']
                    });
                    return false;
                }

                if(!time_value){
                    layer.open({
                        content: '请选择取件时间'
                        ,btn: ['确定']
                    });
                    return false;
                }

                var lock = false;

                layer.closeAll();
                layer.open({type: 2 ,content: '提交中...'});
                if(!lock){
                    lock = true;//发送请求
                    var publishGiveDataUrl = "{pigcms{:U('Service/publish_give_data')}";
                    $.post(publishGiveDataUrl,{goods_catgory:catgory_value,weight:weight_value,price:price_value,start_adress_name:start_adress_name,start_adress_phone:start_adress_phone,start_adress_lng:start_adress_lng,start_adress_lat:start_adress_lat,start_adress_detail:start_adress_detail,start_adress_area_id:start_adress_area_id,start_adress_area_name:start_adress_area_name,start_province_id:start_province_id,start_city_id:start_city_id,start_area_id:start_area_id,end_adress_name:end_adress_name,end_adress_phone:end_adress_phone,end_adress_lng:end_adress_lng,end_adress_lat:end_adress_lat,end_adress_detail:end_adress_detail,end_adress_area_id:end_adress_area_id,end_adress_area_name:end_adress_area_name,end_province_id:end_province_id,end_city_id:end_city_id,end_area_id:end_area_id,fetch_time:time_value,tip_price:tip_price,remarks:remarks,destance_sum:destance_sum,weight_price:weight_price,basic_distance_price:basic_distance_price,distance_price:distance_price,total_price:total_price,cid:cid,'img':img},function(data){
                        if(data.error == 1){
                            location.href = data.url;
                        }else if(data.error == 3){
                            layer.open({
                                content: data.msg
                                ,btn: ['确定']
                                ,yes: function(index){
                                    location.href = "{pigcms{:U('My/recharge',array('label'=>'wap_servicegive_'))}{pigcms{$_GET['cid']}";
                                }
                            });
                        }else{
							layer.closeAll();
                            lock = false;
                            layer.open({
                                content: data.msg
                                ,btn: ['确定']
                            });
                        }

                    },'json');
                }


                return false;
            })
        </script>






            <!-- 下拉框弹层样式 -->
            <div id="rcv-mask" class="mask mask_show fee_show" style="display:none;"></div>
            <!-- 选择地址 -->
            <div id="adress_list" style="display:none;" class="receive_time_dialog">
                <div class="receive_time_wrap">
                    <div>
                        <dl class="list" >
                            <volist name="adress_list" id="vo">
                                <dd class="address-wrapper dd-padding" style="position:relative;" onclick='address_choose("{pigcms{$vo.name}","{pigcms{$vo.phone}","{pigcms{$vo.adress}{pigcms{$vo.detail}","{pigcms{$vo.longitude}","{pigcms{$vo.latitude}","{pigcms{$vo.city}","{pigcms{$vo.city_txt}","{pigcms{$vo.province}","{pigcms{$vo.city}","{pigcms{$vo.area}")'>
                                    <div class="address-container" >
                                        <div class="kv-line">
                                            <p>{pigcms{$vo.name}&nbsp;&nbsp;&nbsp;&nbsp;{pigcms{$vo.phone}</p>
                                            <if condition="$vo.default eq 1"><span style="color:#06c1bb">【默认】</span></if>
                                        </div>
                                        <div class="kv-line">
                                            <p>{pigcms{$vo.province_txt}&nbsp;{pigcms{$vo.city_txt}&nbsp;{pigcms{$vo.area_txt}&nbsp;{pigcms{$vo.adress}&nbsp;{pigcms{$vo.detail}</p>
                                        </div>
                                    </div>
                                    <!-- <a class="edit_bg" href="{pigcms{:U('My/edit_adress',array('adress_id'=>$vo['adress_id']))}"></a>
                                    <i class="edit"></i>  -->
                                </dd>
                            </volist>
                        </dl>
                    </div>
                </div>
                <a href="javascript:void(0);" onclick="address_url()">
                    <div class="receive_time_bottom bg">管理地址</div>
                </a>
            </div>
            
 

            <script>
                function address_url(){
                    var url = "{pigcms{:U('My/adress',array('cid'=>$_GET['cid']))}";
                    var img = '';
                    $("input[name='img[]']").each(function(index,item){
                        img += $(this).val()+";";
                    });
                    var catgory_value = $("#catgory_value").val();
                    var weight_value = $("#weight_value").val();
                    var price_value = $("#price_value").val();

                    // var start_adress_id = $("#start_adress_id").val();

                    var start_adress_name = $("#start_adress_name").val();
                    var start_adress_phone = $("#start_adress_phone").val();
                    var start_adress_lng = $("#start_adress_lng").val();
                    var start_adress_lat = $("#start_adress_lat").val();
                    var start_adress_detail = $("#start_adress_detail").val();
                    var start_adress_area_id = $("#start_adress_area_id").val();
                    var start_adress_area_name = $("#start_adress_area_name").val();



                    var start_province_id = $("#start_province_id").val();
                    var start_city_id = $("#start_city_id").val();
                    var start_area_id = $("#start_area_id").val();

                    // var end_adress_id = $("#end_adress_id").val();

                    var end_adress_name = $("#end_adress_name").val();
                    var end_adress_phone = $("#end_adress_phone").val();
                    var end_adress_lng = $("#end_adress_lng").val();
                    var end_adress_lat = $("#end_adress_lat").val();
                    var end_adress_detail = $("#end_adress_detail").val();
                    var end_adress_area_id = $("#end_adress_area_id").val();
                    var end_adress_area_name = $("#end_adress_area_name").val();

                    var end_province_id = $("#end_province_id").val();
                    var end_city_id = $("#end_city_id").val();
                    var end_area_id = $("#end_area_id").val();

              



                    var time_value = $("#time_value").val();
                    var tip_price = $("#tip_price").val();
                    var remarks = $("#remarks").val();
                    var destance_sum = $("#destance_sum").val();
                    var weight_price = $("#weight_price").val();
                    var basic_distance_price = $("#basic_distance_price").val();
                    var distance_price = $("#distance_price").val();
                    var total_price = $("#total_price").val();
                    var cid = $("#cid").val();
                    var adress_type = $("#adress_type").val();
                    var start_adress_html = $("#start_adress_html").html();
                    var end_adress_html = $("#end_adress_html").html();

                    var tempGiveDataUrl = "{pigcms{:U('Service/temp_give_data')}";
                    $.post(tempGiveDataUrl,{goods_catgory:catgory_value,weight:weight_value,price:price_value,start_adress_name:start_adress_name,start_adress_phone:start_adress_phone,start_adress_lng:start_adress_lng,start_adress_lat:start_adress_lat,start_adress_detail:start_adress_detail,start_adress_area_id:start_adress_area_id,start_adress_area_name:start_adress_area_name,end_adress_name:end_adress_name,end_adress_phone:end_adress_phone,end_adress_lng:end_adress_lng,end_adress_lat:end_adress_lat,end_adress_detail:end_adress_detail,end_adress_area_id:end_adress_area_id,end_adress_area_name:end_adress_area_name,fetch_time:time_value,tip_price:tip_price,remarks:remarks,destance_sum:destance_sum,weight_price:weight_price,basic_distance_price:basic_distance_price,distance_price:distance_price,total_price:total_price,cid:cid,'img':img,adress_type:adress_type,start_adress_html:start_adress_html,end_adress_html:end_adress_html},function(data){

                        location.href = "{pigcms{:U('My/adress',array('cid'=>$_GET['cid']))}";

                    },'json');
 
                }
            </script>


            <!-- 选择分类 -->
            <div id="catgory_list" style="display:none;" class="receive_time_dialog">
                <div class="receive_time_wrap">
                    <div style="text-align: center;">
                        <ul class="receive_time_list">
                            <li style="color: red; margin-top: 20px;"><span>物品分类</span></li>
                            <li onclick="choose_value('鲜花','catgory')"><span>鲜花</span></li>
                            <li onclick="choose_value('餐饮','catgory')"><span>餐饮</span></li>
                            <li onclick="choose_value('生鲜','catgory')"><span>生鲜</span></li>
                            <li onclick="choose_value('文件','catgory')"><span>文件</span></li>
                            <li onclick="choose_value('电子产品','catgory')"><span>电子产品</span></li>
                            <li onclick="choose_value('钥匙','catgory')"><span>钥匙</span></li>
                            <li onclick="choose_value('服饰','catgory')"><span>服饰</span></li>
                            <li onclick="choose_value('其他','catgory')"><span>其他</span></li>
                        </ul>
                    </div>
                </div>
                <div class="receive_time_bottom quxiao mask_show">取消</div>
            </div>

            <!-- 选择重量 -->
            <div id="weight_list" style="display:none;" class="receive_time_dialog">
                <div class="receive_time_wrap">
                    <div style="text-align: center;">
                        <ul class="receive_time_list">
                            <li style="color: red; margin-top: 20px;"><span>物品重量</span></li>
                            <li onclick="choose_weight_value('1kg','1')"><span>1kg</span></li>
                            <li onclick="choose_weight_value('2kg','2')"><span>2kg</span></li>
                            <li onclick="choose_weight_value('3kg','3')"><span>3kg</span></li>
                            <li onclick="choose_weight_value('4kg','4')"><span>4kg</span></li>
                            <li onclick="choose_weight_value('5kg','5')"><span>5kg</span></li>
                            <li onclick="choose_weight_value('6kg','6')"><span>6kg</span></li>
                            <li onclick="choose_weight_value('7kg','7')"><span>7kg</span></li>
                            <li onclick="choose_weight_value('8kg','8')"><span>8kg</span></li>
                            <li onclick="choose_weight_value('9kg','9')"><span>9kg</span></li>
                            <li onclick="choose_weight_value('10kg','10')"><span>10kg</span></li>
                            <li onclick="choose_weight_value('11kg','11')"><span>11kg</span></li>
                            <li onclick="choose_weight_value('12kg','12')"><span>12kg</span></li>
                            <li onclick="choose_weight_value('13kg','13')"><span>13kg</span></li>
                            <li onclick="choose_weight_value('14kg','14')"><span>14kg</span></li>
                            <li onclick="choose_weight_value('15kg','15')"><span>15kg</span></li>
                            <li onclick="choose_weight_value('16kg','16')"><span>16kg</span></li>
                            <li onclick="choose_weight_value('17kg','17')"><span>17kg</span></li>
                            <li onclick="choose_weight_value('18kg','18')"><span>18kg</span></li>
                            <li onclick="choose_weight_value('19kg','19')"><span>19kg</span></li>
                            <li onclick="choose_weight_value('20kg','20')"><span>20kg</span></li>
                        </ul>
                    </div>
                </div>
                <div class="receive_time_bottom quxiao mask_show">取消</div>
            </div>
            
            <!-- 物品价格 -->
            <div id="price_list" style="display:none;" class="receive_time_dialog">
                <div class="receive_time_wrap">
                    <div style="text-align: center;">
                        <ul class="receive_time_list">
                            <li style="color: red; margin-top: 20px;"><span>物品价值</span></li>
                            <li onclick="choose_value('100元以下','price')"><span>100元以下</span></li>
                            <li onclick="choose_value('100-200','price')"><span>100-200</span></li>
                            <li onclick="choose_value('200-300','price')"><span>200-300</span></li>
                            <li onclick="choose_value('300-400','price')"><span>300-400</span></li>
                            <li onclick="choose_value('400-500','price')"><span>400-500</span></li>
                            <li onclick="choose_value('500-600','price')"><span>500-600</span></li>
                            <li onclick="choose_value('600-700','price')"><span>600-700</span></li>
                            <li onclick="choose_value('700-800','price')"><span>700-800</span></li>
                            <li onclick="choose_value('800-900','price')"><span>800-900</span></li>
                            <li onclick="choose_value('900-1000','price')"><span>900-1000</span></li>
                        </ul>
                    </div>
                </div>
                <div class="receive_time_bottom quxiao mask_show">取消</div>
            </div>

            <!-- 取货时间 -->
            <div id="time_list" style="display:none;" class="receive_time_dialog">
                <div class="receive_time_top">
                    <div id="receive_date_wrap" class="receive_date_wrap">
                        <div>
                            <ul class="receive_date_list" style="overflow:auto; height: 220px;">
                                <volist name="daysList" id="vo">
                                    <li data-list="{pigcms{$key}" class='<if condition="$key eq 0">hvr</if>'>{pigcms{$vo}</li>
                                </volist>
                            </ul>
                        </div>
                    </div>
                    <div id="receive_time_wrap" class="receive_time_wrap">
                        <div>
                            <volist name="timeList" id="vo">
                                <if condition="$key eq 0">
                                    <ul class="receive_time_list time_list" id="{pigcms{$key}" style="<if condition='$key eq 0'>display: block;<else/>display: none;</if>">
                                        <volist name="vo" id="vovo" key="kk">
                                            <if condition="$kk eq 1">
                                                <li  data-id="{pigcms{$kk}" class="hvr" style="color: #020202;" onclick='time_choose_value("立即取件（{pigcms{$vovo.week} {pigcms{$vovo.time}）","{pigcms{$vovo.time}","{pigcms{$vovo.per_km_price}","{pigcms{$vovo.delivery_fee}","{pigcms{$vovo.basic_distance}")'>立即取件 （{pigcms{$vovo.time}）</li>
                                                <else/>
                                                <li class="hvr" style="color: #020202;" onclick='time_choose_value("{pigcms{$vovo.date} {pigcms{$vovo.week}（{pigcms{$vovo.time}）","{pigcms{$vovo.time}","{pigcms{$vovo.per_km_price}","{pigcms{$vovo.delivery_fee}","{pigcms{$vovo.basic_distance}")'>{pigcms{$vovo.time}</li>
                                            </if>
                                        </volist>
                                    </ul>
                                    <else/>
                                    <ul class="receive_time_list time_list" id="{pigcms{$key}" style="<if condition='$key eq 0'>display: block;<else/>display: none;</if>">
                                        <volist name="vo" id="vovo" key="kk">
                                            <li class="hvr" style="color: #020202;" onclick='time_choose_value("{pigcms{$vovo.date} {pigcms{$vovo.week}（{pigcms{$vovo.time}）","{pigcms{$vovo.time}","{pigcms{$vovo.per_km_price}","{pigcms{$vovo.delivery_fee}","{pigcms{$vovo.basic_distance}")'>{pigcms{$vovo.time}</li>
                                        </volist>
                                    </ul>
                                </if>
                            </volist>
                        </div>
                    </div>
                </div>
                <div class="receive_time_bottom quxiao mask_show">取消</div>
            </div>
            

            <!-- 配送费详情 -->
            <div class="delivery_fee_details" style="display:none;">
                <h4>费用说明</h4>
                <p class="fee_money"><span>￥</span><b id="total_price_desc_html"><if condition='$temp_give_data.total_price gt 0'>{pigcms{$temp_give_data.total_price}<else/>{pigcms{$config_time.service_delivery_fee}</if></b></p>
                <p class="fee_details"><b>基础配送费</b><span style="margin-left: 5%">￥</span><span id="basic_distance_price_html">{pigcms{$config_time.service_delivery_fee}</span></p>
                <p class="fee_details"><b>物品重量费用</b>￥<span id="weight_price_html"><if condition='$temp_give_data.weight_price gt 0'>{pigcms{$temp_give_data.weight_price}<else/>{pigcms{$config.service_basic_weight_price}</if> </span></p>
                <p class="fee_details"><b>超出距离费用</b>￥<span id="distance_price_html"><if condition='$temp_give_data.distance_price gt 0'>{pigcms{$temp_give_data.distance_price}<else/>0</if></span></p>
                <p class="fee_details"><b>小费</b>￥<span id="tip_price_html"><if condition='$temp_give_data.tip_price gt 0'>{pigcms{$temp_give_data.tip_price}<else/>0</if></span></p>

                <!-- 重量费用 -->
                <input type="hidden" name="weight_price" id="weight_price" value="<if condition='$temp_give_data.weight_price gt 0'>{pigcms{$temp_give_data.weight_price}<else/>{pigcms{$config.service_basic_weight_price}</if> ">
                <!-- 基础配送价格 -->
                <input type="hidden" name="basic_distance_price" id="basic_distance_price" value="{pigcms{$config_time.service_delivery_fee}">
                <!-- 基础配送距离  -->
                <input type="hidden" name="basic_distance" id="basic_distance" value="{pigcms{$config_time.service_basic_distance}">
                <!-- 超出配送范围距离价格 -->
                <input type="hidden" name="distance_price" id="distance_price" value="<if condition='$temp_give_data.distance_price gt 0'>{pigcms{$temp_give_data.distance_price}<else/>0</if>">
                <!-- 超出基础配送距离每公里加价 -->
                <input type="hidden" name="per_km_price" id="per_km_price" value="{pigcms{$config_time.service_per_km_price}">
                <!-- 出发跟到达地点的距离 -->
                <input type="hidden" name="destance_sum" id="destance_sum" value="0">
                <!-- 基础配送价格 + 超出配送范围距离价格 + 重量费用 = 总价 -->
                <!-- basic_distance_price + distance_price + weight_price = total_price -->
                <!-- （ 出发跟到达地点的距离 - 基础配送距离 ）* 超出基础配送距离每公里加价 = 超出配送范围距离价格 -->
                <!-- (destance_sum - basic_distance) * per_km_price = distance_price -->
                <!-- 总价 -->
                <input type="hidden" name="total_price" id="total_price" value="<if condition='$temp_give_data.total_price gt 0'>{pigcms{$temp_give_data.total_price}<else/>{pigcms{$config_time.service_delivery_fee}</if>">

                <div class="fee_error fee_show"></div>
            </div>


            

            <script>
                // 选择加小费
                $('.buy_tip a').click(function(){
                    if($(this).is('.remove_money')){
                        $(this).removeClass('remove_money');
                        $(this).parent().next('p').show();
                    }else{
                        $(this).addClass('remove_money');
                        $(this).parent().next('p').hide();
                        $('.money_tip').hide();
                        $("#tip_price_html").html(0);
                        // total_price
                        var distance_price = parseFloat($("#distance_price").val());
                        var weight_price = parseFloat($("#weight_price").val());
                        var basic_distance_price = parseFloat($("#basic_distance_price").val());

                        var total_price = distance_price+weight_price+basic_distance_price;
                        total_price = total_price.toFixed(2);
                        $("#total_price").val(total_price);
                        $("#total_price_html").html(total_price);
                        $(".total_price_html").html(total_price);
                        $("#total_price_desc_html").html(total_price);
                        $("#tip_price").val(0);

                        $('.buy_tip button').removeClass('buy_active');

                    }
                });

                // 选择加小费数量
                $('.buy_tip button').click(function(){
                    if($(this).is('.other')){
                        $('.money_tip').show();
                    }else{
                        $('.money_tip').hide();
                    }
                    $(this).addClass('buy_active').siblings('button').removeClass('buy_active');
                    


                    var distance_price = parseFloat($("#distance_price").val());
                    var weight_price = parseFloat($("#weight_price").val());
                    var basic_distance_price = parseFloat($("#basic_distance_price").val());

                    if($(this).data('price')){
                        $("#tip_price").val($(this).data('price'));
                        $("#tip_price_html").html($(this).data('price'));
                        var total_price = distance_price+weight_price+basic_distance_price+parseFloat($(this).data('price'));
                        total_price = total_price.toFixed(2);
                        $("#total_price").val(total_price);
                        $("#total_price_html").html(total_price);
                        $(".total_price_html").html(total_price);
                        $("#total_price_desc_html").html(total_price);
                    }else{
                        $("#tip_price").val(0);
                        var total_price = distance_price+weight_price+basic_distance_price;
                        total_price = total_price.toFixed(2);
                        $("#total_price").val(total_price);
                        $("#total_price_html").html(total_price);
                        $(".total_price_html").html(total_price);
                        $("#total_price_desc_html").html(total_price);
                    }


                });

                $('.other').click(function(){
                    $('.money_tip').show();
                });

                // 输入小费金额
                function tip_price_keyup(value){
                    if(isNaN(value)){
                        layer.open({
                            content: '请输入数字'
                            ,skin: 'msg'
                            ,time: 2 
                        });
                        return false;
                    }

                    var distance_price = parseFloat($("#distance_price").val());
                    var weight_price = parseFloat($("#weight_price").val());
                    var basic_distance_price = parseFloat($("#basic_distance_price").val());

                    if(value){
                        $('#tip_price').val(value);
                        $("#tip_price_html").html(value);
                        var total_price = distance_price+weight_price+basic_distance_price+parseFloat(value);
                        total_price = total_price.toFixed(2);
                        $("#total_price").val(total_price);
                        $("#total_price_html").html(total_price);
                        $(".total_price_html").html(total_price);
                        $("#total_price_desc_html").html(total_price);

                    }else{
                        $("#tip_price").val(0);
                        $("#tip_price_html").html(0);
                        var total_price = distance_price+weight_price+basic_distance_price;
                        total_price = total_price.toFixed(2);
                        $("#total_price").val(total_price);
                        $("#total_price_html").html(total_price);
                        $(".total_price_html").html(total_price);
                        $("#total_price_desc_html").html(total_price);
                    }
                }



                // 选择地址
                function address_choose(name,phone,address,lng,lat,city,city_txt,province_id,city_id,area_id){
                    var adress_type = $("#adress_type").val();
                    $("#"+adress_type+"_adress_html").html("<p><span style='display:block; width: 240px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;'>"+address+"</span></p><p><span>"+name+"</span> <span>"+phone+"</span></p>");
                    $("#"+adress_type+"_adress_name").val(name);
                    $("#"+adress_type+"_adress_phone").val(phone);
                    $("#"+adress_type+"_adress_lng").val(lng);
                    $("#"+adress_type+"_adress_lat").val(lat);
                    $("#"+adress_type+"_adress_detail").val(address);
                    $("#"+adress_type+"_adress_area_id").val(city);
                    $("#"+adress_type+"_adress_area_name").val(city_txt);

                    $("#"+adress_type+"_province_id").val(province_id);
                    $("#"+adress_type+"_city_id").val(city_id);
                    $("#"+adress_type+"_area_id").val(area_id);
                    
                    var ajax_distance_url = "{pigcms{:U('Service/ajax_distance')}"
                    if(adress_type == 'start'){

                        var start_lng = lng;
                        var start_lat = lat;
                        var end_lng = $("#end_adress_lng").val();
                        var end_lat = $("#end_adress_lat").val();
                        
                    }else if(adress_type == 'end'){
                        var start_lng = $("#start_adress_lng").val();
                        var start_lat = $("#start_adress_lat").val();
                        var end_lng = lng;
                        var end_lat = lat;
                    }

                    if(start_lng && start_lat && end_lng && end_lat){
						
                        $.post(ajax_distance_url,{start_lat:start_lat,start_lng:start_lng,end_lat:end_lat,end_lng:end_lng},function(data){
							if(data.errorCode && data.errorCode != '0'){
								alert('距离计算失败，无法使用');
							}else{
								var basic_distance = parseFloat($("#basic_distance").val());
								var per_km_price = parseFloat($("#per_km_price").val());
								var weight_price = parseFloat($("#weight_price").val());
								var basic_distance_price = parseFloat($("#basic_distance_price").val());
								var tip_price = parseFloat($("#tip_price").val());
								$("#destance_sum").val(data.destance_sum);
								
								if(parseFloat(data.destance_sum) < basic_distance){
									var distance_price = 0;
								}else{
									var distance_price = (parseFloat(data.destance_sum) - basic_distance) * per_km_price;
								}
								
								// 计算配送费如何计算
								console.log('distance_price_old',distance_price);
								var count_freight_charge_method = {pigcms{$config.count_freight_charge_method};
								if(count_freight_charge_method == 0){
									distance_price = distance_price.toFixed(2);
								}else if(count_freight_charge_method == 1){
									distance_price = Math.ceil(distance_price*10) / 10;
								}else if(count_freight_charge_method == 2){
									distance_price = Math.floor(distance_price*10) / 10;
								}else if(count_freight_charge_method == 3){
									distance_price = distance_price.toFixed(1);
								}else if(count_freight_charge_method == 4){
									distance_price = Math.ceil(distance_price);
								}else if(count_freight_charge_method == 5){
									distance_price = Math.floor(distance_price);
								}else if(count_freight_charge_method == 6){
									distance_price = distance_price.toFixed(0);
								}
								distance_price = parseFloat(distance_price);
								$("#distance_price").val(distance_price);
								$("#distance_price_html").html(distance_price);
								console.log('distance_price_new',distance_price);
								
								var total_price = distance_price+weight_price+basic_distance_price+tip_price;
								total_price = total_price.toFixed(2);
								$("#total_price").val(total_price);
								$("#total_price_html").html(total_price);
								$(".total_price_html").html(total_price);
								$("#total_price_desc_html").html(total_price);
							}
                        },'json');
                    }

                    $('.mask').hide();
                    $('.receive_time_dialog').hide();
                }

                // 时间选择
                function time_choose_value(html,value,per_km_price,delivery_fee,basic_distance){
                    
                    $("#basic_distance_price").val(delivery_fee);
                    $("#basic_distance_price_html").html(delivery_fee);
                    $("#per_km_price").val(per_km_price);
                    $("#basic_distance").val(basic_distance);

                    var weight_price = parseFloat($("#weight_price").val());

                    var basic_distance = parseFloat($("#basic_distance").val());
                    var per_km_price = parseFloat($("#per_km_price").val());
                    var basic_distance_price = parseFloat($("#basic_distance_price").val());
                    var destance_sum = parseFloat($("#destance_sum").val());
                    var tip_price = parseFloat($("#tip_price").val());

                    if(destance_sum < basic_distance){
                        var distance_price = 0;
                    }else{
                        var distance_price = (destance_sum - basic_distance) * per_km_price;
                    }

                    // 计算配送费如何计算
					console.log('distance_price_old',distance_price);
					var count_freight_charge_method = {pigcms{$config.count_freight_charge_method};
					if(count_freight_charge_method == 0){
						distance_price = distance_price.toFixed(2);
					}else if(count_freight_charge_method == 1){
						distance_price = Math.ceil(distance_price*10) / 10;
					}else if(count_freight_charge_method == 2){
						distance_price = Math.floor(distance_price*10) / 10;
					}else if(count_freight_charge_method == 3){
						distance_price = distance_price.toFixed(1);
					}else if(count_freight_charge_method == 4){
						distance_price = Math.ceil(distance_price);
					}else if(count_freight_charge_method == 5){
						distance_price = Math.floor(distance_price);
					}else if(count_freight_charge_method == 6){
						distance_price = distance_price.toFixed(0);
					}
					distance_price = parseFloat(distance_price);
					$("#distance_price").val(distance_price);
					$("#distance_price_html").html(distance_price);
					console.log('distance_price_new',distance_price);
                    
                    var total_price = distance_price+weight_price+basic_distance_price+tip_price;
                    total_price = total_price.toFixed(2);
                    $("#total_price").val(total_price);
                    $("#total_price_html").html(total_price);
                    $(".total_price_html").html(total_price);
                    $("#total_price_desc_html").html(total_price);

                    $("#time_html").html(html);
                    $("#time_value").val(html);
                    // $("#time_type").val(type);
                    $('.mask').hide();
                    $('.receive_time_dialog').hide();

                }

                // 重量选择  value
                function choose_weight_value(value,weight){
                    
                    var basic_weight = parseFloat("{pigcms{$config.service_basic_weight}");//基础重量
                    var basic_weight_price = parseFloat("{pigcms{$config.service_basic_weight_price}");// 基础价格
                    var bounds_weight = parseFloat("{pigcms{$config.service_bounds_weight}");// 超出配送费
                    var tip_price = parseFloat($("#tip_price").val());
                    var total_price = '';

                    if(weight <= basic_weight){
                        var weight_price = basic_weight_price;
                    }else{
                        var weight_price =  ((weight - basic_weight)*bounds_weight)+basic_weight_price;
                    }

                    $("#weight_price").val(weight_price);
                    $("#weight_price_html").html(weight_price);

                    var distance_price = parseFloat($("#distance_price").val());
                    var basic_distance_price = parseFloat($("#basic_distance_price").val());
                    total_price = distance_price+weight_price+basic_distance_price+tip_price;
                    total_price = total_price.toFixed(2);
                    $("#total_price").val(total_price);
                    $("#total_price_html").html(total_price);
                    $(".total_price_html").html(total_price);
                    $("#total_price_desc_html").html(total_price);

                    $("#weight_html").html(value);
                    $("#weight_value").val(weight);

                    $('.mask').hide();
                    $('.receive_time_dialog').hide();
                    
                }

                $(".receive_date_list li").click(function(){
                    $(".receive_date_list li").removeClass('hvr');
                    $(this).addClass('hvr');
                    $(".time_list").css('display','none');
                    $("#"+$(this).data ( "list" )).css('display','block');
                })

                

                // 分类列表显示
                function catgory_list_click(){
                    $('.mask').show();
                    $('#catgory_list').show();
                    $('.quxiao').show();
                }
                
                // 物品重量列表
                function weight_list_click(){
                    $('.mask').show();
                    $('#weight_list').show();
                    $('.quxiao').show();
                }
                // 价格列表显示
                function price_list_click(){
                    $('.mask').show();
                    $('#price_list').show();
                    $('.quxiao').show();
                }
                // 地址列表显示
                function adress_list_click(type){
                    $("#adress_type").val(type);
                    $('.mask').show();
                    $('#adress_list').show();
                    $('.quxiao').show();
                }
                // 时间列表
                function time_list_click(){
                    $('.mask').show();
                    $('#time_list').show();
                    $('#receive_date_wrap').show();
                    $('.quxiao').show();
                }

                // 选择筛选属性
                function choose_value(value,type){
                    $("#"+type+"_html").html(value);
                    $("#"+type+"_value").val(value);
                    $('.mask').hide();
                    $('.receive_time_dialog').hide();
                }


                /*取消按钮点击效果*/
                $('.mask_show').click(function(){
                    $('.mask').hide();
                    $('.receive_time_dialog').hide();

                });

                /*配送费详情*/
                $('.nofee_money').click(function(){
                    $('.mask_show').show();
                    $('.delivery_fee_details').show();
                });

                $('.fee_show').click(function(){
                    $('.mask_show').hide();
                    $('.delivery_fee_details').hide();
                });

            </script>

            <script>
                $('.load-div').hide();
                $('.load-div').css('padding-top', 0.48 * $(window).height());
                var length=$("#result .cell").length;

                if(length>=4){
                    $('#container').remove();
                }
                function imgUpload(){
                    $('.load-div').show();
                    $.ajaxFileUpload({
                        url:"{pigcms{:U('Service/ajax_upload_file')}",
                        secureuri:false,
                        fileElementId:'imgUploadFile',
                        dataType: 'json',
                        success: function (data) {
                            $('.load-div').hide();
                            if(data.error == 2){

                                $("#result").append('<div class="cell"> <div class="cell-img " > <a href="javascript:void(0);" class="fancybox cell_li" rel="gallery" title=""> <img src="'+data.url+'"> </a> </div> <input type="hidden" name="img[]" value="'+data.url+'" /> <div class="cell-del"><a href="javascript:;" class="del-btn"> <i class="ico ico-del"></i> <span class="txt-del">删除</span> </a> </div> </div>');
                                 let sum = $("#result .cell").length;
                                 if(sum >= 4){
                                    $('#container').remove();
                                    return false;
                                }
                            }else{
                                layer.open({
                                    content: data.msg
                                    ,btn: ['确定']
                                });
                            }
                        }
                    }); 
                }

                $(document).on('click', '.del-btn', function() {
                    var is_cover = $(this).parent().parent().find('.cell_li img').attr('is_cover');
                    if (is_cover==1) {
                        $('.multiple_cover_key').val('');
                    }
                    $(this).parent().parent().remove();
                    let sum = $("#result .cell").length;
                    let svm=$('#container').length;
                    if(sum < 4&&svm<=0){
						$('.add-imglist1').append('<div class="cell" id="container"><div class="btn-ftp1" id="upimgFileBtn"> <i class="ico ico-add"></i></div><input type="file" id="imgUploadFile" onchange="imgUpload()" style="position:absolute;opacity:0;left:0;top:0;z-index:9999;width:100%;height:100%;" name="imgFile" value="选择文件上传"></div>');
                    }
                });


                var get_adress_id = "{pigcms{$_GET['adress_id']}";
                if(get_adress_id){
                    address_choose("{pigcms{$addressInfo.name}","{pigcms{$addressInfo.phone}","{pigcms{$addressInfo.adress}{pigcms{$addressInfo.detail}","{pigcms{$addressInfo.longitude}","{pigcms{$addressInfo.latitude}","{pigcms{$addressInfo.city}","{pigcms{$addressInfo.city_txt}","{pigcms{$addressInfo.province}","{pigcms{$addressInfo.city}","{pigcms{$addressInfo.area}")

                }

            </script>
			<script type="text/javascript">
			window.shareData = {
				"moduleName":"Home",
				"moduleID":"0",
				"imgUrl": "{pigcms{$config.site_logo}",
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Service/publish_detail',$_GET)}",
				"tTitle": "帮我送 - {pigcms{$config.site_name}",
				"tContent": "{pigcms{$config.site_name}"
			};
			</script>
			{pigcms{$shareScript}
    </body>
</html>