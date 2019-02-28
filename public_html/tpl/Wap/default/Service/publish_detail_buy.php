<!-- <!DOCTYPE html> -->
<html>
	<head>
		<title>帮我买</title>
		<meta charset="utf-8">
		<meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name='apple-touch-fullscreen' content='yes'>
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/component.css">
	    <link rel="stylesheet" href="{pigcms{$static_path}service/css/buy_adress.css">
		<link href="{pigcms{$static_path}layer/need/layer.css" type="text/css" rel="styleSheet" id="layermcss">
		<link rel="stylesheet" href="{pigcms{$static_path}service/css/buy_index.css">
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
		<script src="{pigcms{$static_path}service/js/jquery-2.1.4.js"></script>
		<link href='{pigcms{$static_path}service/css/basic.css?t=58da05f1' rel='stylesheet' type='text/css' />
		<script type="text/javascript" src="{pigcms{$static_public}js/ajaxfileupload.js"></script>
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
                background: #06c1ae;
            }
 
        </style>
		<style>
			.form-list1 .li {
			    padding: 0 0 1.01rem;
			}
			.add-imglist1{
				margin-top: 1.01rem
			}
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
           /* #result:after{
            	content: " ";
            	display: block;
            	clear: both;
            }*/
            .add-imglist1 .btn-ftp1 {
			    /*background: #e6e6e6;*/
			    height: 5.5rem;
			    width: 94%;
			}
			#upimgFileBtn{
				cursor: pointer;
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
		<form id="publish_demand_form" method="post" formdataarr="demandForm_Arr">
		<section class="buy_shop">
			<p><s></s><span>商品要求</span></p>
			<textarea rows="3" cols="3" maxlength="100" name="goods_remarks" id="goods_remarks" placeholder="点击输入你的商品要求,例如麦当劳巨无霸汉堡一个">{pigcms{$temp_buy_data.goods_remarks}</textarea >
		</section>

		<div class="service-edit-box1 demand-form-list form-list-show" style="background-color: #ffffff; border-top: 1px solid #f0f0f0; overflow-x:auto;     padding-bottom: 0rem; height: 160px;padding: 0.5rem 0.6rem 0 0.6rem;">
            <div class="form-list1">
                <div class="li js_img_show_wrap ele-wrap add-imglist-col4">
                    <label class="lab-title"><span class="validate-title">商品图片：</span><span class="lab-blue">（最多上传4张图片）</span></label>
                    <div class="add-imglist1">
                        <div id="result">
                        	<if condition="$temp_buy_data['img']">
                        		<volist name="temp_buy_data['img']" id="imgvo">
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
                            <input type="file" id="imgUploadFile" onchange="imgUpload()" style="position:absolute;opacity:0;left:0;top:0;z-index:9999;width:100%;height:100%;" name="imgFile" accept="image/*" value="选择文件上传"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

		<input type="hidden" name="cid" id="cid" value="{pigcms{$_GET['cid']}">
		<div class="buy_time">
			<ul>
				<li class="lt">去哪买</li>
				<li class="lt pay_near"><if condition="$temp_buy_data['buy_type'] neq 2"><i class="active_click"></i><else/><i class="active_out"></i></if>就近购买</li>
				<li class="lt pay_appoint" onclick="tmp_adress_click('start')"><if condition="$temp_buy_data['buy_type'] eq 2"><i class="active_click"></i><else/><i class="active_out"></i></if>指定地址</li>
				<input type="hidden" name="buy_type" id="buy_type" value="1">
			</ul>
			
			<if condition="$temp_buy_data['address']">
				<div class="detailed" onclick="tmp_adress_click('start')" style="display:block;">
					<p>
						<span id="address" >{pigcms{$temp_buy_data.address}</span>
	                    <i></i>
					</p>
					<i class="icon_right rg"></i>
				</div>
			<else/>
				<div class="detailed" onclick="tmp_adress_click('start')" style="display:none;">
					<p>
						<span id="address" >请填写具体地址</span>
					</p>
					<i class="icon_right rg"></i>
				</div>
			</if>
			
            <input type="hidden" id="start_adress_lng" value="{pigcms{$temp_buy_data.start_adress_lng}"/>
            <input type="hidden" id="start_adress_lat" value="{pigcms{$temp_buy_data.start_adress_lat}"/>
            <input type="hidden" id="start_adress_detail" value="{pigcms{$temp_buy_data.start_adress_detail}"/>
            <input type="hidden" id="start_adress_area_id" value="{pigcms{$temp_buy_data.start_adress_area_id}"/>
            <input type="hidden" id="start_adress_area_name" value="{pigcms{$temp_buy_data.start_adress_area_name}"/>

            <input type="hidden" id="start_province_id" value="{pigcms{$temp_buy_data.start_province_id}"/>
            <input type="hidden" id="start_city_id" value="{pigcms{$temp_buy_data.start_city_id}"/>
            <input type="hidden" id="start_area_id" value="{pigcms{$temp_buy_data.start_area_id}"/>

			<div class="give_re" style="height: 75px;background: #fff;">
				<label class="buy_give">送到:</label>
                <!-- <p>点此快速添加地址</p> -->
				<a id="adress_html" onclick="tmp_adress_click('end')" style="border-right: 1px solid #eae9e9;"> 
					<if condition="$addressInfo">
							<p style="width:100%;"><span style="display:block; width:100%; overflow:hidden; text-overflow:ellipsis; white-space:nowrap">{pigcms{$addressInfo.adress}&nbsp;{pigcms{$addressInfo.detail}</span></p>
							<p><span>{pigcms{$addressInfo.name}</span> <span>{pigcms{$addressInfo.phone}</span></p>
					<else/>
						<span class="" >点此快速添加地址</span> 
					</if>
				</a>
				
				<input type="hidden" id="end_adress_name" value="<if condition="$temp_buy_data">{pigcms{$temp_buy_data.end_adress_name}<else/>{pigcms{$addressInfo.name}</if>" >
                <input type="hidden" id="end_adress_phone" value="<if condition="$temp_buy_data">{pigcms{$temp_buy_data.end_adress_phone}<else/>{pigcms{$addressInfo.phone}</if>" >
                <input type="hidden" id="end_adress_lng" value="<if condition="$temp_buy_data">{pigcms{$temp_buy_data.end_adress_lng}<else/>{pigcms{$addressInfo.longitude}</if>" >
                <input type="hidden" id="end_adress_lat" value="<if condition="$temp_buy_data">{pigcms{$temp_buy_data.end_adress_lat}<else/>{pigcms{$addressInfo.latitude}</if>" >
                <input type="hidden" id="end_adress_detail" value="<if condition="$temp_buy_data">{pigcms{$temp_buy_data.end_adress_detail}<else/>{pigcms{$addressInfo.adress}{pigcms{$addressInfo.detail}</if>"/>
                <input type="hidden" id="end_adress_area_id" value="<if condition="$temp_buy_data">{pigcms{$temp_buy_data.end_adress_area_id}<else/>{pigcms{$addressInfo.city}</if>" >
                <input type="hidden" id="end_adress_area_name" value="<if condition="$temp_buy_data">{pigcms{$temp_buy_data.end_adress_area_name}<else/>{pigcms{$addressInfo.city_txt}</if>" >

                <input type="hidden" id="end_province_id" value="<if condition="$temp_buy_data">{pigcms{$temp_buy_data.end_province_id}<else/>{pigcms{$addressInfo.province}</if>" >
                <input type="hidden" id="end_city_id" value="<if condition="$temp_buy_data">{pigcms{$temp_buy_data.end_city_id}<else/>{pigcms{$addressInfo.city}</if>" >
                <input type="hidden" id="end_area_id" value="<if condition="$temp_buy_data">{pigcms{$temp_buy_data.end_area_id}<else/>{pigcms{$addressInfo.area}</if>" >

				<!-- <i class="icon_right rg" onclick="adress_list_click('end')"></i> -->
                <span class="rg" onclick="adress_list_click('end')" style="margin-top: 23px;background: #06C1AE;padding: 4px 10px;margin:18px 7px;border-radius: 5px;color: #fff;">常用</span>
			</div>

			<input type="hidden" name="adress_type" id="adress_type" value="{pigcms{$temp_buy_data.adress_type}">
		</div>

		<script>
			/*去哪买点击效果*/
			$('.pay_near').click(function(){
				$(this).find('i').removeClass('active_out').addClass('active_click').parent('li').next('li').find('i').removeClass('active_click').addClass('active_out');
				$('.detailed').hide();
				$('.buy_time').css('height','120px');
				$("#buy_type").val(1);
                $("#distance_price").val(0);

				var basic_distance_price = parseFloat($("#basic_distance_price").val());
				var tip_price = parseInt($("#tip_price").val());
				var total_price = basic_distance_price+tip_price;
				total_price = total_price.toFixed(2);
                $("#total_price").val(total_price);
                $("#total_price_html").html(total_price);
                $(".total_price_html").html(total_price);
                $("#total_price_desc_html").html(total_price);
                var service_basic_km_time = parseInt($("#service_basic_km_time").val());
				$("#service_time").val(parseInt(service_basic_km_time))
            	$("#service_time_html").html(parseInt(service_basic_km_time))
			                    	

			});

			$('.pay_appoint').click(function(){
				$(this).find('i').removeClass('active_out').addClass('active_click').parent('li').prev('li').find('i').removeClass('active_click').addClass('active_out');
					$('.detailed').hide();
				$('.detailed').show();
				$('.buy_time').css('height','182px');
				$("#buy_type").val(2);
			});
		</script>

		<div class="buy_save">
			<div class="order-timefield">
                <label class="order-timefield-label">送达时间</label>
                <span id="user_receive_time" class="order-timefield-span">
                <span class="order-timefield-txt" >立即配送(预计<span id="service_time_html"><if condition="$temp_buy_data.arrival_time gt 0">{pigcms{$temp_buy_data.arrival_time}<else/>{pigcms{$config.service_basic_km_time}</if></span>分钟内送达)</span>
                </span>
            </div>
            <div class="shop_money">
            	<dl class="lt">
            		<dt>商品费用</dt>
					<dd>与骑手当面结算</dd>
            	</dl>
            	<input class="rg" type="text" name="estimate_goods_price" id="estimate_goods_price" value="{pigcms{$temp_buy_data.estimate_goods_price}" placeholder="输入预估价供骑手参考">
            </div>
            <div class="paysong">
            	<label>配送费</label>
            	<span class="rg"><i class="nofee_money "></i>￥<b class="total_price_html"><if condition="$temp_buy_data.total_price gt 0">{pigcms{$temp_buy_data.total_price}<else/>{pigcms{$config_time.service_delivery_fee}</if></b></span>
            	<input type="hidden" name="delivery_fee" id="delivery_fee" value="{pigcms{$config_time.service_delivery_fee}">
            </div>
            <div class="buy_tip">
            	<div>
            		<label>我要加小费</label>
	            	<span>加小费可以更快抢单哦<i></i></span>
	            	<a href="javascript:void(0);" class="rg add_money <if condition='$temp_buy_data.tip_price elt 0 '>remove_money</if> "><i class="rg"></i></a>
            	</div>
            	
            	<p style="display:<if condition='$temp_buy_data.tip_price elt 0 '>none<else/>block</if>;">
            		<button class="<if condition='$temp_buy_data.tip_price eq 5 '>buy_active</if>" data-price="5" type="button" >5元</button>
            		<button class="<if condition='$temp_buy_data.tip_price eq 10 '>buy_active</if>" data-price="10" type="button">10元</button>
            		<button class="<if condition='$temp_buy_data.tip_price eq 15 '>buy_active</if>" data-price="15" type="button">15元</button>
            		<button class="<if condition='$temp_buy_data.tip_price eq 20 '>buy_active</if>" data-price="20" type="button">20元</button>
            		<button data-price="" type="button" class="other buy_active">其他</button>
            	</p>
            	<p class="money_tip" style="display:<if condition='$temp_buy_data.tip_price gt 0 AND $temp_buy_data.tip_price neq 5 AND $temp_buy_data.tip_price neq 10 AND $temp_buy_data.tip_price neq 15 AND $temp_buy_data.tip_price neq 20 '>block<else/>none</if>;"><span>￥</span>
            	<input type="number" onkeyup="value=value.replace(/[^\d]/g,''),tip_price_keyup(this.value)" value="<if condition='$temp_buy_data.tip_price gt 0'>{pigcms{$temp_buy_data.tip_price}<else/>0</if>" id="tip_price_val" />
            	</p>
				<input type="hidden" name="tip_price" value="<if condition='$temp_buy_data.tip_price gt 0'>{pigcms{$temp_buy_data.tip_price}<else/>0</if>" id="tip_price">
            </div>

            <script>
            	$('.buy_tip a').click(function(){
					if($(this).is('.remove_money')){
						$(this).removeClass('remove_money');
						$('.other').addClass('buy_active');
						$(this).parent().next('p').show();
						$('.money_tip').show();
					}else{
						$("#tip_price_html").html(0);
						$(this).addClass('remove_money');
						$(this).parent().next('p').hide();
						$('.money_tip').hide();

						$('.buy_tip button').removeClass('buy_active');
						$('#tip_price_val').val(0);


						//基础配送距离
						var basic_distance = parseFloat($("#basic_distance").val());
						// 超出基础配送距离每公里加价
                        var per_km_price = parseFloat($("#per_km_price").val());
                        // 基础配送价格
                        var basic_distance_price = parseFloat($("#basic_distance_price").val());
                        // 小费价格
                        //超出的配送范围距离
                        var destance_sum = $("#destance_sum").val();

                        if(parseFloat(destance_sum) < basic_distance){
                            var distance_price = 0;
                        }else{
                            var distance_price = (parseFloat(destance_sum) - basic_distance) * per_km_price;
                        }

                        $("#distance_price").val(distance_price);
                        $("#distance_price_html").html(distance_price.toFixed(2));
                        var total_price = distance_price+basic_distance_price;
                        total_price = total_price.toFixed(2);
                        $("#total_price").val(total_price);
                        $("#total_price_html").html(total_price);
                        $(".total_price_html").html(total_price);
                        $("#total_price_desc_html").html(total_price);

					}
				});

				$('.buy_tip button').click(function(){
					if($(this).is('.other')){
						$('.money_tip').show();
					}else{
						$('.money_tip').hide();
					}
					$(this).addClass('buy_active').siblings('button').removeClass('buy_active');
					if($(this).data('price') > 0){
						$("#tip_price").val($(this).data('price'));
						$("#tip_price_html").html($(this).data('price'));
					}else{
						$("#tip_price").val(0);
						$("#tip_price_html").html(0);
						$("#tip_price_val").val($(this).data('price'));
					}
	
					
					var delivery_fee = $("#delivery_fee").val();//配送费
					if($(this).data('price')){
						// 基础配送价格
	                    var basic_distance_price = parseFloat($("#basic_distance_price").val());
	                    // 超出配送范围距离价格
	                    var distance_price = parseFloat($("#distance_price").val());
	                    var total_price = distance_price+basic_distance_price+parseFloat($(this).data('price'));
	                    total_price = total_price.toFixed(2);
	                    $("#total_price").val(total_price);
	                    $("#total_price_html").html(total_price);
	                    $(".total_price_html").html(total_price);
	                    $("#total_price_desc_html").html(total_price);

					}else{
						// 基础配送价格
	                    var basic_distance_price = parseFloat($("#basic_distance_price").val());
	                    var distance_price = parseFloat($("#distance_price").val());
	                    var total_price = distance_price+basic_distance_price;
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

				function tip_price_keyup(value){
					if(value){
						if(isNaN(value)){
	                        layer.open({
	                            content: '请输入数字'
	                            ,skin: 'msg'
	                            ,time: 2 
	                        });
	                        return false;
	                    }
						$('#tip_price').val(value);
						$("#tip_price_html").html(value);
						// 基础配送价格
	                    var basic_distance_price = parseFloat($("#basic_distance_price").val());
	                    // 超出配送范围距离价格
	                    var distance_price = parseFloat($("#distance_price").val());
	                    var total_price = distance_price+basic_distance_price+parseFloat(value);
	                    total_price = total_price.toFixed(2);
	                    $("#total_price").val(total_price);
	                    $("#total_price_html").html(total_price);
	                    $(".total_price_html").html(total_price);
	                    $("#total_price_desc_html").html(total_price);
					}else{
						$("#tip_price").val(0);
                        $("#tip_price_html").html(0);
						// 基础配送价格
	                    var basic_distance_price = parseFloat($("#basic_distance_price").val());
	                    var distance_price = parseFloat($("#distance_price").val());
	                    var total_price = distance_price+basic_distance_price;
	                    total_price = total_price.toFixed(2);
	                    $("#total_price").val(total_price);
	                    $("#total_price_html").html(total_price);
	                    $(".total_price_html").html(total_price);
	                    $("#total_price_desc_html").html(total_price);
					}
				}

            </script>
		</div>
		<div style="padding-bottom:110px; "></div>
		<footer>
			<span style="margin-left: 35px;">待支付 <b>￥</b><b id="total_price_html"><if condition='$temp_buy_data.total_price gt 0'>{pigcms{$temp_buy_data.total_price}<else/>{pigcms{$config_time.service_delivery_fee}</if></b></span>
			<input type="hidden" name="total_price" id="total_price" value="<if condition='$temp_buy_data.total_price gt 0'>{pigcms{$temp_buy_data.total_price}<else/>{pigcms{$config_time.service_delivery_fee}</if>">
			<button type="submit" style="background: #06c1ae;  float: right;">发布并支付</button>
		</footer>

		</form>

		<div id="rcv-mask" class="mask quxiao fee_show" style="display:none;"></div>


		<!-- 收获地址弹层 -->
		<div id="receive_time_dialog" style="display:none;" class="receive_time_dialog">
			<div class="receive_time_top">
	
				<div id="receive_time_wrap" class="receive_time_wrap">
					<div style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
						<dl class="list"  style="display:none;">
							<volist name="adress_list" id="vo">
								<dd class="address-wrapper dd-padding" style="position:relative;" onclick='address_choose("{pigcms{$vo.name}","{pigcms{$vo.phone}","{pigcms{$vo.adress}{pigcms{$vo.detail}","{pigcms{$vo.longitude}","{pigcms{$vo.latitude}","{pigcms{$vo.city}","{pigcms{$vo.city_txt}","{pigcms{$vo.province}","{pigcms{$vo.city}","{pigcms{$vo.area}")'>
						        	<div class="address-container" >
						                <div class="kv-line">
						                    <p>{pigcms{$vo.name}&nbsp;&nbsp;&nbsp;&nbsp;{pigcms{$vo.phone}</p>
						                    <if condition="$vo.default eq 1">
						                    	<span style="color:#06c1bb">【默认】</span>
						                    </if>
										</div>
						                <div class="kv-line">
						                    <p>{pigcms{$vo.province_txt}&nbsp;{pigcms{$vo.city_txt}&nbsp;{pigcms{$vo.area_txt}&nbsp;{pigcms{$vo.adress}&nbsp;{pigcms{$vo.detail}</p>
						                </div>
									</div>
									<!-- <a class="edit_bg" href="{pigcms{:U('My/edit_adress',array('adress_id'=>$vo['adress_id']))}"></a> -->
									<!-- <i class="edit"></i>  -->
							    </dd>
							</volist>
					    </dl>
					</div>
				</div>
			</div>

			<a href="javascript:void(0);" onclick="address_url()">
				<div class="receive_time_bottom bg">管理地址</div>
			</a>

			<script>
				function address_url(){
					var url = "{pigcms{:U('My/adress',array('cid'=>$_GET['cid']))}";
					// alert(url);

					var img = '';
	                $("input[name='img[]']").each(function(index,item){
	                    img += $(this).val()+";";
	                });
					var	goods_remarks =  $("#goods_remarks").val();
					var	cid =  $("#cid").val();
					var	buy_type =  $("#buy_type").val();


					var	start_adress_lng = $("#start_adress_lng").val();
					var	start_adress_lat = $("#start_adress_lat").val();
					var	start_adress_detail = $("#start_adress_detail").val();
                    var start_adress_area_id = $("#start_adress_area_id").val();
                    var start_adress_area_name = $("#start_adress_area_name").val();
                    var start_province_id = $("#start_province_id").val();
                    var start_city_id = $("#start_city_id").val();
                    var start_area_id = $("#start_area_id").val();


					var	estimate_goods_price = $("#estimate_goods_price").val();
					var	tip_price = $("#tip_price").val(); 
					var distance_price = $("#distance_price").val();
					var basic_distance_price = $("#basic_distance_price").val();
					var arrival_time = parseFloat($("#service_time").val());
					var	total_price = $("#total_price").val(); 
					var tempBuyDataUrl = "{pigcms{:U('Service/temp_buy_data')}";

					$.post(tempBuyDataUrl,{goods_remarks:goods_remarks,cid:cid,buy_type:buy_type,start_adress_lng:start_adress_lng,start_adress_lat:start_adress_lat,start_adress_detail:start_adress_detail,start_adress_area_id:start_adress_area_id,start_adress_area_name:start_adress_area_name,start_province_id:start_province_id,start_city_id:start_city_id,start_area_id:start_area_id,estimate_goods_price:estimate_goods_price,arrival_time:arrival_time,tip_price:tip_price,distance_price:distance_price,basic_distance_price:basic_distance_price,total_price:total_price,'img':img},function(data){
						
						location.href = "{pigcms{:U('My/adress',array('cid'=>$_GET['cid']))}";

					},'json');
				}
			</script>
			
		</div>


 


			<div class="delivery_fee_details" style="display:none;">
				<h4>费用说明</h4>
				<p class="fee_money"><span>￥</span><b id="total_price_desc_html">{pigcms{$config_time.service_delivery_fee}</b></p>
                <p class="fee_details"><b>基础配送费</b>￥<span id="basic_distance_price_html">{pigcms{$config_time.service_delivery_fee}</span></p>
                <p class="fee_details"><b>超出距离费用</b>￥<span id="distance_price_html"><if condition='$temp_buy_data.distance_price gt 0'>{pigcms{$temp_buy_data.distance_price}<else/>0</if></span></p>
                <p class="fee_details"><b>小费</b>￥<span id="tip_price_html"><if condition='$temp_buy_data.tip_price gt 0'>{pigcms{$temp_buy_data.tip_price}<else/>0</if></span></p>

				<div class="fee_error fee_show"></div>

                <!-- 基础配送价格 -->
                <input type="hidden" name="basic_distance_price" id="basic_distance_price" value="<if condition='$temp_buy_data.basic_distance_price gt 0'>{pigcms{$temp_buy_data.basic_distance_price}<else/>{pigcms{$config_time.service_delivery_fee}</if>">
                <!-- 基础配送距离  -->
                <input type="hidden" name="basic_distance" id="basic_distance" value="{pigcms{$config_time.service_basic_distance}">
                <!-- 超出配送范围距离价格 -->
                <input type="hidden" name="distance_price" id="distance_price" value="<if condition='$temp_buy_data.distance_price gt 0'>{pigcms{$temp_buy_data.distance_price}<else/>0</if>">
                <!-- 超出基础配送距离每公里加价 -->
                <input type="hidden" name="per_km_price" id="per_km_price" value="{pigcms{$config_time.service_per_km_price}">
                <!-- 出发跟到达地点的距离 -->
                <input type="hidden" name="destance_sum" id="destance_sum" value="0">
				
				<!-- 基础配送到达时间 -->
                <input type="hidden" name="service_time" id="service_time" value="{pigcms{$config.service_basic_km_time}">
                <input type="hidden" name="service_basic_km_time" id="service_basic_km_time" value="{pigcms{$config.service_basic_km_time}">
				
				<!-- 基础配送公里数 -->
                <input type="hidden" name="service_basic_km" id="service_basic_km" value="{pigcms{$config.service_basic_km}">
                

			</div>
			
			
			
			
			<div class="addressHtml" id="addressStartHtml">
	            <ul>
	                <li><i></i><p onclick="adress_click('end')" id="tmp_adress_start_html">请选择小区/大厦/标志建筑</p></li> 
	                <li><input type="text" value="" id="tmp_start_detail" placeholder="请补充详细地址"/></li>
	                <input type="hidden" id="tmp_start_adress" value=""/>
		            <input type="hidden" id="tmp_start_adress_lng" value=""/>
		            <input type="hidden" id="tmp_start_adress_lat" value=""/>
		            <input type="hidden" id="tmp_start_adress_area_id" value=""/>
		            <input type="hidden" id="tmp_start_adress_area_name" value=""/>
	            </ul>
	            <div class="btns">
	                <button onclick="cancel_adress()" style="margin-right: 2%; background: #fff; color: #999;">取消</button>
	                <button onclick="start_confirm_adress()" style="margin-left: 2%;color: #fff;background: #06C1AE;">确认</button>
	            </div>
	        </div>
				
			<script>
				function start_confirm_adress(){
	                var tmp_detail = $("#tmp_start_detail").val();
	                var tmp_adress = $("#tmp_start_adress").val();
	                var tmp_adress_lng = $("#tmp_start_adress_lng").val();
	                var tmp_adress_lat = $("#tmp_start_adress_lat").val();
	                var tmp_adress_area_id = $("#tmp_start_adress_area_id").val();
	                var tmp_adress_area_name = $("#tmp_start_adress_area_name").val();
                    
                    var tmp_province_id = $("#province_id").val();
                    var tmp_city_id = $("#city_id").val();
                    var tmp_area_id = $("#area_id").val();
	                if(!tmp_detail){
	                    alert('请输入详细地址');
	                    return false;
	                }
	                if(!tmp_adress){
	                    alert('请选择地址坐标');
	                    return false;
	                }
	                address_choose('','',tmp_adress+tmp_detail,tmp_adress_lng,tmp_adress_lat,tmp_adress_area_id,tmp_adress_area_name,tmp_province_id,tmp_city_id,tmp_area_id);

	                $('.addressHtml').hide();
				}
			</script>
			
			<div class="addressHtml" id="addressEndHtml">
	            <ul>
	                <li><i></i><p onclick="adress_click('end')" id="tmp_adress_end_html">请选择小区/大厦/标志建筑</p></li> 
	                <li><input type="text" value="" id="tmp_end_name" placeholder="联系人"/></li>
	                <li><input type="text" value="" id="tmp_end_phone" placeholder="手机号"/></li>
	                <li><input type="text" value="" id="tmp_end_detail" placeholder="请补充详细地址"/></li>
	                <input type="hidden" id="tmp_end_adress" value=""/>
		            <input type="hidden" id="tmp_end_adress_lng" value=""/>
		            <input type="hidden" id="tmp_end_adress_lat" value=""/>
		            <input type="hidden" id="tmp_end_adress_area_id" value=""/>
		            <input type="hidden" id="tmp_end_adress_area_name" value=""/>
	            </ul>
	            <div class="btns">
	                <button onclick="cancel_adress()" style="margin-right: 2%; background: #fff; color: #999;">取消</button>
	                <button onclick="confirm_adress()" style="margin-left: 2%;color: #fff;">确认</button>
	            </div>
	        </div>
			
			


	        <script>
	            

	            function cancel_adress(){
	                $('.addressHtml').hide();
	            }

	            function confirm_adress(){
	                var tmp_name = $("#tmp_end_name").val();
	                var tmp_phone = $("#tmp_end_phone").val();
	                var tmp_detail = $("#tmp_end_detail").val();
	                var tmp_adress = $("#tmp_end_adress").val();
	                var tmp_adress_lng = $("#tmp_end_adress_lng").val();
	                var tmp_adress_lat = $("#tmp_end_adress_lat").val();
	                var tmp_adress_area_id = $("#tmp_end_adress_area_id").val();
	                var tmp_adress_area_name = $("#tmp_end_adress_area_name").val();

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
						<!-- <div class="left-slogan" onclick="history.go(-1);">
							<a class="left-arrow icon-arrow-left2"></a>
						</div> -->
						<div class="city_box" style="padding-left: 15px;">
							<span>读取中</span>
						</div>

						<div class="center-title" style="padding-left: 15px;">
							<div class="ui-suggestion-mask">
								<input type="text" placeholder="请输入你的收货地址" id="se-input-wd" autocomplete="off"/>
								<!-- <button class="btn show_btn" >关闭</button> -->
								<div class="ui-suggestion-quickdel"></div>

							</div>
						</div>
						<div style="padding-left: 15px;">
							<span onclick="$('#searchAddress').hide();$('#searchAddressTxt').val('');$('#rcv-mask').hide();">关闭</span>
						</div>
						<!-- <div class="his-postion" data-node="historypos" style="">
							<div class="ui-suggestion" id="ui-suggestion-0" style="top: 0px; left: 0px; position: relative;">
								<div class="ui-suggestion-content" style="-webkit-tap-highlight-color: rgba(255, 255, 255, 0);"></div>
								<div class="ui-suggestion-button"><span class="ui-suggestion-clear" style="-webkit-tap-highlight-color: rgba(255, 255, 255, 0);">清除历史记录</span><span class="ui-suggestion-close" style="-webkit-tap-highlight-color: rgba(255, 255, 255, 0);"></span></div>
							</div>
						</div> -->
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
					<div id="fis_elm__4" style="display:none;">
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
                    var adress_type = $("#adress_type").val();

                    var cityMatchingUrl = "{pigcms{:U('Service/cityMatching')}";
                    $.get(cityMatchingUrl, {'lng':lng,'lat':lat}, function(data){
                        if(data.error == 1){
                            $("#tmp_"+adress_type+"_adress").val(sname);
                            $("#tmp_"+adress_type+"_adress_lng").val(lng);
                            $("#tmp_"+adress_type+"_adress_lat").val(lat);
                            $("#tmp_"+adress_type+"_adress_area_id").val(data.area_id);
                            $("#tmp_"+adress_type+"_adress_area_name").val(data.area_name);
                            $("#tmp_adress_"+adress_type+"_html").html(sname);
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
                        // console.log(result);
                        // console.log(res);
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
                var adress_type = $("#adress_type").val();

                var cityMatchingUrl = "{pigcms{:U('Service/cityMatching')}";
                $.get(cityMatchingUrl, {'lng':lng,'lat':lat}, function(data){
                    if(data.error == 1){
                        $("#tmp_"+adress_type+"_adress").val(sname);
                        $("#tmp_"+adress_type+"_adress_lng").val(lng);
                        $("#tmp_"+adress_type+"_adress_lat").val(lat);
                        $("#tmp_"+adress_type+"_adress_area_id").val(data.area_id);
                        $("#tmp_"+adress_type+"_adress_area_name").val(data.area_name);
                        $("#tmp_adress_"+adress_type+"_html").html(sname);
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
                // 	if(this.getStatus() == BMAP_STATUS_SUCCESS){
                // 		map.centerAndZoom(new BMap.Point(r.point.lng,r.point.lat), 16);
                // 		getPositionInfo(r.point.lat,r.point.lng);
                // 	}else{
                // 		alert('failed：'+this.getStatus());
                // 	}
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
					// console.log(result);
					// console.log(res);
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



			<script type="text/javascript">
				// 选择地址
				function address_choose(name,phone,address,lng,lat,city,city_txt,province_id,city_id,area_id){
					var adress_type = $("#adress_type").val();
					if(adress_type == 'end'){
                    	$("#adress_html").html("<p><span style='display:block; width: 220px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap'>"+address+"</span></p><p><span>"+name+"</span> <span>"+phone+"</span></p>");
                    	$("#"+adress_type+"_adress_name").val(name);
                    	$("#"+adress_type+"_adress_phone").val(phone);
					}else{
                    	$("#address").html(address);
					}
					
                    $("#"+adress_type+"_adress_lng").val(lng);
                    $("#"+adress_type+"_adress_lat").val(lat);
                    $("#"+adress_type+"_adress_detail").val(address);
                    $("#"+adress_type+"_adress_area_id").val(city);
                    $("#"+adress_type+"_adress_area_name").val(city_txt);
                    
                    $("#"+adress_type+"_province_id").val(province_id);
                    $("#"+adress_type+"_city_id").val(city_id);
                    $("#"+adress_type+"_area_id").val(area_id);

					var ajax_distance_url = "{pigcms{:U('Service/ajax_distance')}";
					var buy_type = $("#buy_type").val();

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

					if(buy_type != 1){

						if(start_lng && start_lat && end_lng && end_lat){

							$.post(ajax_distance_url,{start_lat:start_lat,start_lng:start_lng,end_lat:end_lat,end_lng:end_lng},function(data){
								console.log(data);
								if(data.errorCode && data.errorCode != '0'){
									alert('距离计算失败，无法使用');
								}else{
									var basic_distance = parseFloat($("#basic_distance").val());
									var per_km_price = parseFloat($("#per_km_price").val());
									var basic_distance_price = parseFloat($("#basic_distance_price").val());
									var tip_price = parseFloat($("#tip_price").val());

									var service_basic_km_time = parseInt($("#service_basic_km_time").val());
									var service_basic_km = parseFloat($("#service_basic_km").val());

									if(service_basic_km < parseFloat(data.destance_sum)){
										service_time = parseFloat(data.destance_sum)/service_basic_km*service_basic_km_time;
										$("#service_time").val(parseInt(service_time))
										$("#service_time_html").html(parseInt(service_time))
									}else{
										$("#service_time").val(parseInt(service_basic_km_time))
										$("#service_time_html").html(parseInt(service_basic_km_time))
									}

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
									
									var total_price = distance_price+basic_distance_price+tip_price;
									total_price = total_price.toFixed(2);
									$("#total_price").val(total_price);
									$("#total_price_html").html(total_price);
									$(".total_price_html").html(total_price);
									$("#total_price_desc_html").html(total_price);

		                        // 基础配送价格 + 超出配送范围距离价格 + 重量费用 = 总价 
		                        // basic_distance_price + distance_price + weight_price = total_price 
		                        // （ 出发跟到达地点的距离 - 基础配送距离 ）* 超出基础配送距离每公里加价 = 超出配送范围距离价格 
		                        // (destance_sum - basic_distance) * per_km_price = distance_price 
								}
		                    },'json');
	                    }
					}else{
						var service_basic_km_time = parseInt($("#service_basic_km_time").val());
						$("#service_time").val(parseInt(service_basic_km_time))
                    	$("#service_time_html").html(parseInt(service_basic_km_time))
					}

					$('.mask').hide();
                    $('.receive_time_dialog').hide();
				}
				//取消按钮点击和蒙层点击效果
				$('.quxiao').click(function(){
					$('.mask').hide();
					$('.receive_time_dialog').hide();
				});

				/*选择地址点击效果*/
				function adress_list_click(){
					$("#adress_type").val('end');
					$('.mask').show();
            		$('#receive_time_dialog').show();
            		$('#receive_date_wrap').hide();
            		$('#receive_time_wrap dl').show().siblings('ul').hide();
            		$('.bg').show().prev('.quxiao').hide();
				}

				/*配送费详情*/
				$('.nofee_money').click(function(){
					$('.mask').show();
					$('.delivery_fee_details').show();
				});
				$('.fee_show').click(function(){
					$('.mask').hide();
					$('.delivery_fee_details').hide();
				});
			</script>




			<script>
				$("#publish_demand_form").submit(function(){
			    	var img = '';
	                $("input[name='img[]']").each(function(index,item){
	                    img += $(this).val()+";";
	                });
					var	goods_remarks =  $("#goods_remarks").val();
					if(!goods_remarks){
						layer.open({
							content: '请输入商品要求'
							,btn: ['确定']
						});
						return false;
					}
					var	cid =  $("#cid").val();
					var	buy_type =  $("#buy_type").val();

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






					var destance_sum = $("#destance_sum").val();
					if(!end_adress_lat){
						layer.open({
							content: '请选择收货地址'
							,btn: ['确定']
						});
						return false;
					}
					if(buy_type == 2 && start_adress_lat == ''){
						layer.open({
							content: '请选择指定地址'
							,btn: ['确定']
						});
						return false;
					}
					var	estimate_goods_price = $("#estimate_goods_price").val();
					if(!estimate_goods_price){
						layer.open({
							content: '请输入商品预估价格'
							,btn: ['确定']
						});
						return false;
					}
					var	tip_price = $("#tip_price").val(); 
					var distance_price = $("#distance_price").val();
					var basic_distance_price = $("#basic_distance_price").val();
					var arrival_time = parseFloat($("#service_time").val());
					var	total_price = $("#total_price").val(); 

					


                    layer.closeAll();
                    layer.open({type: 2 ,content: '提交中...'});
                    var publishBuyDataUrl = "{pigcms{:U('Service/publish_buy_data')}";

                    $.post(publishBuyDataUrl,{goods_remarks:goods_remarks,cid:cid,buy_type:buy_type,start_adress_lng:start_adress_lng,start_adress_lat:start_adress_lat,start_adress_detail:start_adress_detail,start_adress_area_id:start_adress_area_id,start_adress_area_name:start_adress_area_name,start_province_id:start_province_id,start_city_id:start_city_id,start_area_id:start_area_id,end_adress_name:end_adress_name,end_adress_phone:end_adress_phone,end_adress_lng:end_adress_lng,end_adress_lat:end_adress_lat,end_adress_detail:end_adress_detail,end_adress_area_id:end_adress_area_id,end_adress_area_name:end_adress_area_name,end_province_id:end_province_id,end_city_id:end_city_id,end_area_id:end_area_id,estimate_goods_price:estimate_goods_price,arrival_time:arrival_time,tip_price:tip_price,distance_price:distance_price,basic_distance_price:basic_distance_price,total_price:total_price,'img':img,destance_sum:destance_sum},function(data){
                        if(data.error == 1){
                            location.href = data.url;
                        }else if(data.error == 3){
                            layer.open({
                                content: data.msg
                                ,btn: ['确定']
                                ,yes: function(index){
                                    location.href = "{pigcms{:U('My/recharge',array('label'=>'wap_servicebuy_'))}{pigcms{$_GET['cid']}";
                                }
                            });
                        }else{
							layer.closeAll();
                            layer.open({
                                content: data.msg
                                ,btn: ['确定']
                            });
                        }

                    },'json');



			    	return false;
			    })
			</script>
			<script>
                $('.load-div').hide();
                $('.load-div').css('padding-top', 0.48 * $(window).height());
				//上传图片
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
                                 var sum = $("#result .cell").length;
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
                    var sum = $("#result .cell").length;
                    var svm=$('#container').length;
                    if(sum < 4&&svm<=0){
						$('.add-imglist1').append('<div class="cell" id="container"><div class="btn-ftp1" id="upimgFileBtn"> <i class="ico ico-add"></i></div><input type="file" id="imgUploadFile" onchange="imgUpload()" style="position:absolute;opacity:0;left:0;top:0;z-index:9999;width:100%;height:100%;" name="imgFile" value="选择文件上传"></div>');
                    }
                });

                function tmp_adress_click(type){
			        // $('.mask').show();
			        $("#adress_type").val(type);
			        if(type == 'start'){

			        	$("#start_adress_lng").val('');
			        	$("#start_adress_lat").val('');
			        	$("#start_adress_detail").val('');
			        	$("#start_adress_area_id").val('');
			        	$("#start_adress_area_name").val('');
            			
            			$("#address").html('请填写具体地址');
            			
            			
            			
			        	$('#addressStartHtml').show();
			        	// $("#searchAddress").css('display','block');
			        }else{
			        	$('#addressEndHtml').show();
			        	// $("#searchAddress").css('display','block');
			        }
			        
			    }

			    function adress_click(){
			    	$("#searchAddress").css('display','block');
			    }

			    var get_adress_id = "{pigcms{$_GET['adress_id']}";
                if(get_adress_id){
                	$("#adress_type").val('end');
                    address_choose("{pigcms{$addressInfo.name}","{pigcms{$addressInfo.phone}","{pigcms{$addressInfo.adress}{pigcms{$addressInfo.detail}","{pigcms{$addressInfo.longitude}","{pigcms{$addressInfo.latitude}","{pigcms{$addressInfo.city}","{pigcms{$addressInfo.city_txt}","{pigcms{$addressInfo.province}","{pigcms{$addressInfo.city}","{pigcms{$addressInfo.area}")

                }
            </script>
			<script type="text/javascript">
			window.shareData = {
				"moduleName":"Home",
				"moduleID":"0",
				"imgUrl": "{pigcms{$config.site_logo}",
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Service/publish_detail',$_GET)}",
				"tTitle": "帮我买 - {pigcms{$config.site_name}",
				"tContent": "{pigcms{$config.site_name}"
			};
			</script>
			{pigcms{$shareScript}
	</body>
</html>