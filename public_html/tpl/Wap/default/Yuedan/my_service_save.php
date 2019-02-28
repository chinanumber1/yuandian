<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>编辑技能</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-touch-fullscreen" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="address=no">
		<link href='{pigcms{$static_path}service/css/basic.css?t=58da05f1' rel='stylesheet' type='text/css' />
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}yuedan/css/release.css"/>
		<script src="{pigcms{$static_path}yuedan/js/jquery-1.9.1.min.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/ajaxfileupload.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
	</head>
	<style>
		.receive_time_bottom {
		    font-size: 16px;
		    text-align: center;
		    border-top: 1px solid #e4e4e4;
		    padding: 17px 0;
		}
	</style>

	<body>
		<header>
			<a href="JavaScript:history.back(-1)"><i></i></a>
			<span>编辑</span>
		</header>
		<div class="" style="height: 120px; margin-top: 15px;">
            <div class="form-list1">
                <div class="li js_img_show_wrap ele-wrap add-imglist-col4">
                    <label class="lab-title" style="padding-left: 15px;"><span class="validate-title">物品图片：</span><span class="lab-blue">（最多上传4张图片）</span></label>
                    <div class="add-imglist1" style="margin-top: 15px; padding-left: 20px;">
                        <div id="result">
                        	<volist name="myServiceInfo.img" id="vo">
								<div class="cell">
	                        		<div class="cell-img " style="height:79.43px;width:79.43px">
	                        			<a href="javascript:void(0);" class="fancybox cell_li" rel="gallery" title=""><img src="{pigcms{$vo}"></a>
	                        		</div>
	                        		<input type="hidden" name="img[]" value="{pigcms{$vo}">                        
	                        		<div class="cell-del">
	                        			<a href="javascript:;" class="del-btn"> <i class="ico ico-del"></i>
	                        				<span class="txt-del">删除</span>
	                        			</a>
	                        		</div>
	                        	</div>
							</volist>
                        </div>
                        <if condition="$myServiceInfo['imgCount'] lt 4">
                        	<div class="cell" id="container" style="display: block;">
	                            <div class="btn-ftp1 upimgFileBtn" id="upimgFileBtn" style="width: 72.2px; height: 72.2px;"> <i  class="ico ico-add" ></i></div>
                                <input type="file" id="imgUploadFile" onchange="imgUpload()" style="display: none;" name="imgFile" accept="image/*" value="选择文件上传"/>
	                        </div>
                        <else/>
                            <div class="cell" id="container" style="display: none;">
                                <div class="btn-ftp1 upimgFileBtn" id="upimgFileBtn" style="width: 72.2px; height: 72.2px;"> <i  class="ico ico-add" ></i></div>
                                <input type="file" id="imgUploadFile" onchange="imgUpload()" style="display: none;" name="imgFile" accept="image/*" value="选择文件上传"/>
                            </div>
                        </if>
                        
                    </div>
                </div>
            </div>
        </div>
		<!--筛选条件-->
		<div class="screen">
			<ul>
				<li>
					<span class="left_style">服务标题</span>
					<input type="tel" name="title" id="title" value="{pigcms{$myServiceInfo['title']}" placeholder="输入服务标题"/>
				</li>
				<li class="after" onclick="address_click()">
					<span class="left_style">我在</span>
					<span class="adress" id="adress_name_html">{pigcms{$myServiceInfo['address_name']}</span>
					<input type="hidden" name="address_name" id="address_name" value="{pigcms{$myServiceInfo['address_name']}"/>
					<input type="hidden" name="address_lng" id="address_lng" value="{pigcms{$myServiceInfo['address_lng']}"/>
					<input type="hidden" name="address_lat" id="address_lat" value="{pigcms{$myServiceInfo['address_lat']}"/>
					<input type="hidden" name="address_area_id" id="address_area_id" value="{pigcms{$myServiceInfo['address_area_id']}"/>
					<input type="hidden" name="address_area_name" id="address_area_name" value="{pigcms{$myServiceInfo['address_area_name']}"/>
					<i class="rg"></i>
				</li>
				<li>
					<span class="left_style">价格</span>
					<input type="tel" name="price" id="price" value="{pigcms{$myServiceInfo['price']}" onkeyup="value=value.replace(/[^\d.]/g,'')" placeholder="输入服务价格 ( 元 )"/>
				</li>
				<li class="after unit">
					<span class="left_style">单位</span>
					<span id="unit_html">{pigcms{$myServiceInfo['unit']}</span>
					<input type="hidden" name="unit" id="unit" value="{pigcms{$myServiceInfo['unit']}">
					<i class="rg"></i>
				</li>
				<li class="after sevice_type">
					<span class="left_style">服务类型</span>
					<span id="cat_html">{pigcms{$myServiceInfo['cat_name']}</span>
					<input type="hidden" name="cid" id="cid" value="{pigcms{$myServiceInfo['cid']}">
					<input type="hidden" name="cat_name" id="cat_name" value="{pigcms{$myServiceInfo['cat_name']}">
					<i class="rg"></i>
				</li>
				<!-- <li class="after range">
					<span class="left_style">服务范围</span>
					<span>请选择</span>
					<i class="rg"></i>
				</li> -->
				<li>
                    <span class="left_style">开始结束时</span>
                    <input type="tel" name="startH" id="startH" readonly="readonly" data-val="-2" style="width: 100px;" value="{pigcms{$myServiceInfo['startH']}" onkeyup="value=value.replace(/[^\d.]/g,'')" placeholder="开始点"/> —
                    <input type="tel" name="endH" id="endH" readonly="readonly" data-val="-1" style="width: 100px;" value="{pigcms{$myServiceInfo['endH']}" onkeyup="value=value.replace(/[^\d.]/g,'')" placeholder="结束点"/>
                </li>

                <li>
                    <span class="left_style">预约天数</span>
                    <input type="tel" name="bespeak_day" id="bespeak_day" value="{pigcms{$myServiceInfo['bespeak_day']}" onkeyup="bespeak_day_check(this,this.value,{pigcms{$config.yuedan_max_day})" placeholder="输入预约天数 ( 天 )"/>
                </li>
                <script>
                    function bespeak_day_check(obj,val,sum){
                        val=val.replace(/[^\d.]/g,'');
                        if(val > sum){
                            val = sum;
                        }
                        $(obj).val(val);
                    }
                </script>
                <li>
                    <span class="left_style">预约间隔</span>
                    <input type="tel" name="interval" id="interval" value="{pigcms{$myServiceInfo['interval']}" onkeyup="value=value.replace(/[^\d.]/g,'')" placeholder="输入预约间隔 ( 分钟 ) "/>
                </li>


				<li class="">
					<span class="left_style" style="float: left;">描述</span>
					<div class="content after"><textarea name="describe" id="describe" rows="6" style="float: left; width: 70%; resize: none; padding: 10px ; border: none; -webkit-appearance: none; font-family: '微软雅黑',Arial;" cols="" maxlength="1000" placeholder="清晰准确的描述有助于了解你的服务，例如服务流程、服务方式、为了更好的展示你的优点">{pigcms{$myServiceInfo['describe']}</textarea>
					<p class="rg length" style="margin-right: 2%; color: #C1C1C1; padding: 5px 3%;"><span style="color: #f00;">0</span>/500</p></div>
				</li>
				<script type="text/javascript" charset="utf-8">
						$('.length span').text("{pigcms{$myServiceInfo['describe']}".length);

					$('.content textarea').keyup(function(e){
						var text=$(this).val();
						var len=text.length;
						$('.length span').text(len);
					});
				</script>
			</ul>
		</div>
		<h4><span style="color: #060606;">同意</span>【平台发布服务协议】</h4>
		<input type="hidden" name="rid" id="rid" value="{pigcms{$myServiceInfo['rid']}"/>
		<div style="padding-bottom: 70px;"></div>
		<div class="bottom" onclick="submitRelease()"> 保存 </div>

		<script>
			function submitRelease(){
				var uid = "{pigcms{$user_session['uid']}";
				if(!uid){
		            layer.open({
		                content: '请先登录再进行操作'
		                ,btn: ['登录']
		                ,yes: function(index){
		                    location.href = "{pigcms{:U('Login/index')}";
		                }
		            });
		            return false;
		        }
				var img = '';
                $("input[name='img[]']").each(function(index,item){
                    img += $(this).val()+";";
                });
                if(!img){
                	layer.open({
						content: '请先上传图片！'
						,skin: 'msg'
						,time: 2 
				  	});
                	return false;
                }

                var title = $("#title").val();
                if(!title){
                	layer.open({
						content: '请舒服服务标题！'
						,skin: 'msg'
						,time: 2 
				  	});
                	return false;
                }

                var address_name = $("#address_name").val();
                var address_lng = $("#address_lng").val();
                var address_lat = $("#address_lat").val();
                var address_area_id = $("#address_area_id").val();
                var address_area_name = $("#address_area_name").val();
                if(!address_name){
                	layer.open({
						content: '请选择服务位置！'
						,skin: 'msg'
						,time: 2 
				  	});
                	return false;
                }
                var price = parseFloat($("#price").val()).toFixed(2);
                var unit = $("#unit").val();
                if(!unit){
                	layer.open({
						content: '请选择单位！'
						,skin: 'msg'
						,time: 2 
				  	});
                	return false;
                }
                var cid = $("#cid").val();
                var cat_name = $("#cat_name").val();
                if(!cid){
                	layer.open({
						content: '请选择服务分类！'
						,skin: 'msg'
						,time: 2 
				  	});
                	return false;
                }

                var startH = parseInt($("#startH").val());
                var endH = parseInt($("#endH").val());
                if(startH >= endH){
                    layer.open({
                        content: '开始时间不能大于结束时间'
                        ,skin: 'msg'
                        ,time: 2 
                    });
                    return false;
                }

                if(endH > 24 ){
                    layer.open({
                        content: '结束时间不可以大于24点'
                        ,skin: 'msg'
                        ,time: 2 
                    });
                    return false;
                }

                var interval = parseInt($("#interval").val());
                var yuedan_interval = parseInt("{pigcms{$config.yuedan_interval}");

                if(interval > 60 ){
                    layer.open({
                        content: '不可以大于60分钟'
                        ,skin: 'msg'
                        ,time: 2 
                    });
                    return false;
                }

                if(interval < yuedan_interval ){
                    layer.open({
                        content: '预约间隔不可以小于'+yuedan_interval+'分钟'
                        ,skin: 'msg'
                        ,time: 2 
                    });
                    return false;
                }
                
                var bespeak_day = parseInt($("#bespeak_day").val());
                if(!bespeak_day){
                    layer.open({
                        content: '请填写预约天数！'
                        ,skin: 'msg'
                        ,time: 2 
                    });
                    return false;
                }
                
                var rid = $("#rid").val();
                if(!rid){
                	layer.open({
						content: '数据异常请重试！'
						,skin: 'msg'
						,time: 2 
				  	});
                	return false;
                }
                var describe = $("#describe").val();
                var my_service_save_data_url = "{pigcms{:U('my_service_save_data')}";
                $.post(my_service_save_data_url,{'rid':rid,'img':img,'title':title,'address_name':address_name,'address_lng':address_lng,'address_lat':address_lat,'address_area_id':address_area_id,'address_area_name':address_area_name,'price':price,'unit':unit,'cid':cid,'cat_name':cat_name,'describe':describe,'startH':startH,'endH':endH,'interval':interval,'bespeak_day':bespeak_day},function(data){
                	if(data.error == 1){
                		layer.open({
			                content: data.msg
			                ,btn: ['确定']
			                ,yes: function(index){
			                    location.href = "{pigcms{:U('Yuedan/my_service_list')}";
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




			var mo1=1;
            $('#startH').click(function(e){
                $('.mask').show();
                $('#unit_list1').show();
                var val2=$('#endH').attr('data-val'); 
                var val=$   (this).attr('data-val');
                $('#unit_list1 ul li').click(function(e){
                    var text=$(this).text();
                    var  val6=$(this).attr('data-val');
                   // console.log(val,val2);
                   if(mo1==1){
                        if(Number(val)>=Number(val2)){
                            layer.open({
                                content: '开始时间不可大于结束时间'
                                ,skin: 'msg'
                                ,time: 2 
                            });
                         
                        }else{
                            $('.mask').hide();
                            $('#unit_list1').hide();
                            $('#startH').val(text);
                            $('#startH').attr('data-val',val6);
                        }
                        mo1++;
                        console.log(mo1);
                   }else{
                    console.log(val6,val2);
                        if(Number(val6)>=Number(val2)){
                            layer.open({
                                content: '开始时间不可大于结束时间'
                                ,skin: 'msg'
                                ,time: 2 
                            });
                            
                        }else{
                            $('.mask').hide();
                            $('#unit_list1').hide();
                            $('#startH').val(text);
                            $('#startH').attr('data-val',val6);
                        }
                   }
                    
                });

            });

          	$('#endH').click(function(e){
                $('.mask').show();
                $('#unit_list2').show();
                var startTime=$('#startH').attr('data-val');
                $('#unit_list2 ul li').click(function(e){

                    var text=$(this).text();
                    //console.log(typeof(text));
                    var val2=$(this).attr('data-val');
                    console.log(startTime,val2);
                    if(parseFloat(val2)<=parseFloat(startTime)){
                          layer.open({
                            content: '结束时间不可小于开始时间'
                            ,skin: 'msg'
                            ,time: 2 
                        });
                        return false;
                    }else{
                        $('.mask').hide();
                        $('#unit_list2').hide();
                        $('#endH').val(text);  
                        $('#endH').attr('data-val',val2);
                    }
                    
                });

          	});
		</script>

		<div class="mask" style="display: none;"></div>
        <!-- 单位选择 -->
        <div id="unit_list"  class="receive_time_dialog " style="display:none;">
            <div class="receive_time_wrap">
                <div style="text-align: center;">
                    <ul class="receive_time_list">
                        <li onclick="choose_value('次')"><span>次</span></li>
                        <li onclick="choose_value('小时')"><span>小时</span></li>
                        <li onclick="choose_value('分钟')"><span>分钟</span></li>
                        <li onclick="choose_value('天（24h）')"><span>天（24h）</span></li>
                        <li onclick="choose_value('周（7 天）')"><span>周（7 天）</span></li>
                        <li onclick="choose_value('月（30 天）')"><span>月（30 天）</span></li>
                    </ul>
                </div>
            </div>
            <div class="receive_time_bottom quxiao mask_show">取消</div>
        </div>

        <div id="unit_list1"  class="receive_time_dialog " style="display:none;">
            <div class="receive_time_wrap">
                <div style="text-align: center;">
                    <ul class="receive_time_list">
                        <li data-val='1'><span>01:00</span></li>
                        <li data-val='2'><span>02:00</span></li>
                        <li data-val='3'><span>03:00</span></li>
                        <li data-val='4'><span>04:00</span></li>
                        <li data-val='5'><span>05:00</span></li>
                        <li data-val='6'><span>06:00</span></li>
                        <li data-val='7'><span>07:00:</span></li>
                        <li data-val='8'><span>08:00:</span></li>
                        <li data-val='9'><span>09:00:</span></li>
                        <li data-val='10'><span>10:00</span></li>
                        <li data-val='11'><span>11:00</span></li>
                        <li data-val='12'><span>12:00</span></li>
                        <li data-val='13'><span>13:00</span></li>
                        <li data-val='14'><span>14:00</span></li>
                        <li data-val='15'><span>15:00</span></li>
                        <li data-val='16'><span>16:00</span></li>
                        <li data-val='17'><span>17:00</span></li>
                        <li data-val='18'><span>18:00</span></li>
                        <li data-val='19'><span>19:00</span></li>
                        <li data-val='20'><span>20:00</span></li>
                        <li data-val='21'><span>21:00</span></li>
                        <li data-val='22'><span>22:00</span></li>
                        <li data-val='23'><span>23:00</span></li>
                        <li data-val='24'><span>24:00</span></li>
                    </ul>
                </div>
            </div>
            <div class="receive_time_bottom quxiao mask_show">取消</div>
        </div>

        <div id="unit_list2"  class="receive_time_dialog " style="display:none;">
            <div class="receive_time_wrap">
                <div style="text-align: center;">
                    <ul class="receive_time_list">
                          <li data-val='1'><span>01:00</span></li>
                        <li data-val='2'><span>02:00</span></li>
                        <li data-val='3'><span>03:00</span></li>
                        <li data-val='4'><span>04:00</span></li>
                        <li data-val='5'><span>05:00</span></li>
                        <li data-val='6'><span>06:00</span></li>
                        <li data-val='7'><span>07:00:</span></li>
                        <li data-val='8'><span>08:00:</span></li>
                        <li data-val='9'><span>09:00:</span></li>
                        <li data-val='10'><span>10:00</span></li>
                        <li data-val='11'><span>11:00</span></li>
                        <li data-val='12'><span>12:00</span></li>
                        <li data-val='13'><span>13:00</span></li>
                        <li data-val='14'><span>14:00</span></li>
                        <li data-val='15'><span>15:00</span></li>
                        <li data-val='16'><span>16:00</span></li>
                        <li data-val='17'><span>17:00</span></li>
                        <li data-val='18'><span>18:00</span></li>
                        <li data-val='19'><span>19:00</span></li>
                        <li data-val='20'><span>20:00</span></li>
                        <li data-val='21'><span>21:00</span></li>
                        <li data-val='22'><span>22:00</span></li>
                        <li data-val='23'><span>23:00</span></li>
                        <li data-val='24'><span>24:00</span></li>
                    </ul>
                </div>
            </div>
            <div class="receive_time_bottom quxiao mask_show">取消</div>
        </div>

        <!-- 分类列表 -->
        <div id="cat_list" class="receive_time_dialog" style="display:none;">
            <div class="receive_time_top">
                <div id="receive_date_wrap" class="receive_date_wrap">
                    <div>
                        <ul class="receive_date_list" style="overflow:auto; height: 220px;">
                            <volist name="catList" key="k" id="vo">
                                <li data-list="{pigcms{$k}" class='<if condition="$k eq 1">hvr</if>'>{pigcms{$vo.cat_name}</li>
                            </volist>
                        </ul>
                    </div>
                </div>
                <div id="receive_time_wrap" class="receive_time_wrap">
                    <div>
                        <volist name="catList" key="kk" id="vovo">
                            <ul class="receive_time_list cat_list" id="{pigcms{$kk}" style="<if condition='$kk eq 1'>display: block;<else/>display: none;</if>">
                                <volist name="vovo['catList']" id="vv">
                                    <li style="text-align: center; color: #020202; height: 48px;" class="hvr" onclick='cat_choose_value("{pigcms{$vv.cat_name}","{pigcms{$vv.cid}")'>{pigcms{$vv.cat_name}</li>
                                </volist>
                            </ul>
                        </volist>
                    </div>
                </div>
            </div>
            <div class="receive_time_bottom quxiao mask_show">取消</div>
        </div>


        <!-- 分类列表 -->
        <div id="range_list" class="receive_time_dialog" style="display:none;">
            <div class="receive_time_top">
                <div id="receive_date_wrap" class="receive_date_wrap">
                    <div>
                        <ul class="receive_date_list" style="overflow:auto; height: 220px;">
                            <volist name="catList" key="k" id="vo">
                                <li data-list="{pigcms{$k}" class='<if condition="$k eq 1">hvr</if>'>{pigcms{$vo.cat_name}</li>
                            </volist>
                        </ul>
                    </div>
                </div>
                <div id="receive_time_wrap" class="receive_time_wrap">
                    <div>
                        <volist name="catList" key="kk" id="vovo">
                            <ul class="receive_time_list cat_list" id="{pigcms{$kk}" style="<if condition='$kk eq 1'>display: block;<else/>display: none;</if>">
                                <volist name="vovo['catList']" id="vv">
                                    <li style="text-align: center; color: #020202; height: 48px;" class="hvr" onclick='cat_choose_value("{pigcms{$vovo.cat_name}","{pigcms{$vovo.cid}")'>{pigcms{$vovo.cat_name},{pigcms{$vovo.cid}</li>
                                </volist>
                            </ul>
                        </volist>
                    </div>
                </div>
            </div>
            <div class="receive_time_bottom quxiao mask_show">取消</div>
        </div>




		<textarea id="demo5a" style="display:none;">
			<div style="padding:20px; ">

				<div style="height:500px;overflow:auto;overflow-x:hidden;">
					<span>
						{pigcms{$agreementInfo.content}
					</span>
				</div>
				<div class="bottoss" style="text-align: center;height: 50px;background: #06C1AE;line-height: 50px;color: #fff;margin-top: 15px;">
					<a href="javascript:;"  class="" onclick="layer.closeAll();">我要关闭！！</a>
				</div>
			</div>
		</textarea>




		<script type="text/javascript">
			$('h4').click(function(e){
				var html = demo5a.value;
				var pageii = layer.open({
					type: 1
					,content: html
					,anim: 'up'
					,style: 'position:fixed; left:0; top:0; width:100%; height:100%;'
				});
			});

			//单位点击
			$('.unit').click(function(e){
				$('.mask').show();
				$('#unit_list').show();
			});

			//服务类型点击
			$('.sevice_type').click(function(e){
				$('.mask').show();
				$('#cat_list').show();
			});

			//范围点击
			$('.range').click(function(e){
				$('.mask').show();
				$('#range_list').show();
			});

			// 取消按钮点击效果
            $('.mask_show').click(function(){
                $('.mask').hide();
                $('.receive_time_dialog').hide();
            });
            //点击遮罩层
            $('.mask').click(function(e){
				$('.mask').hide();
                $('.receive_time_dialog').hide();
			});

			//弹层左边li点击
			$(".receive_date_list li").click(function(){
                $(".receive_date_list li").removeClass('hvr');
                $(this).addClass('hvr');
                $(".cat_list").css('display','none');
                $("#"+$(this).data ( "list" )).css('display','block');
            })


            function choose_value(val){
            	$("#unit_html").html(val);
            	$("#unit").val(val);
            	$('.mask').hide();
                $('.receive_time_dialog').hide();
            }

            function cat_choose_value(name,cid){
            	$("#cat_html").html(name);
            	$("#cid").val(cid);
            	$("#cat_name").val(name);
            	$('.mask').hide();
                $('.receive_time_dialog').hide();
            }

		</script>
		



		

		<!-- 上传图片 -->
		<script>
            var length=$("#result .cell").length;

            if(length>=4){
                // $('#container').remove();
                $('#container').css('display','none'); 
            }
            
            $(".upimgFileBtn").click(function(){
                $("#imgUploadFile").click();
            })

            function imgUpload(){
                $.ajaxFileUpload({
                    url:"{pigcms{:U('Yuedan/ajax_upload_file')}",
                    secureuri:false,
                    fileElementId:'imgUploadFile',
                    dataType: 'json',
                    success: function (data) {
                        if(data.error == 2){

                            $("#result").append('<div class="cell"> <div class="cell-img " style="height:79.43px;width:79.43px"> <a href="javascript:void(0);" class="fancybox cell_li" rel="gallery" title=""> <img src="'+data.url+'"> </a> </div> <input type="hidden" name="img[]" value="'+data.url+'" /> <div class="cell-del"><a href="javascript:;" class="del-btn"> <i class="ico ico-del"></i> <span class="txt-del">删除</span> </a> </div> </div>');
                             let sum = $("#result .cell").length;
                             if(sum >= 4){
                                // $('#container').remove();
                                $('#container').css('display','none'); 
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
                if(sum < 4){
                    $('#container').css('display','block'); 
                }
            });
        </script>

		<!-- 弹出地图层 -->
		<script type="text/javascript" src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1" charset="utf-8"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/address_9d295cd.css">
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style_dd39d16.css">
		<style>
			.mapaddress {height: 91%; overflow-y: scroll; z-index: 99999; }
		</style>			
		<div id="searchAddress" style="z-index:100001; background:#ffffff; display: none;  position:fixed; left:0; top:0%; width:100%; height:100%; margin-top: -0;">
		    <div class="" style=" width: 100%; background color: red; height: 50px; text-align: center;">
		        <input type="text" placeholder="请输入小区、大厦或学校" onkeyup="bb(this.value)" value="" style="width:75%;height:14px;font-size:14px;margin-top:12px;border:1px solid #ccc;padding:17px 6px; border-radius: 3px;" id="searchAddressTxt"/>
		    </div>
		    <div class="mapaddress">
		        <ul id="addressShow" class="addressShow"></ul>
		    </div>
		</div>

		<!-- 弹出地图 -->
		<script>
		    function address_click(){
		    	$('.mask').show();
		        $("#searchAddress").css('display','block');
		    }

		    var timeout = 0;
		    function bb(a){
		        var address = a;
		        if(address.length>0 && address !== '请输入小区、大厦或学校'){
		            $('.addressShow').empty();
		            clearTimeout(timeout);
		            timeout = setTimeout("search('"+address+"')", 500);
		        }
		    }

		    $('.addressShow').delegate("li","click",function(){ 
		        var sname = $(this).attr("sname");
		        var lng = $(this).attr("lng");
		        var lat = $(this).attr("lat");


		        var cityMatchingUrl = "{pigcms{:U('Yuedan/cityMatching')}";
		        $.get(cityMatchingUrl, {'lng':lng,'lat':lat}, function(data){
		            if(data.error == 1){
		                $("#adress_name_html").html(sname);
		                $("#address_name").val(sname);
		                $("#address_lng").val(lng);
		                $("#address_lat").val(lat);
		                $("#address_area_id").val(data.area_id);
            			$("#address_area_name").val(data.area_name);
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



		    $(document).ready(function(){
				$('#searchAddressTxt').width($(window).width()-20-80);
				var geolocation = new BMap.Geolocation();
				geolocation.getCurrentPosition(function(r){
					if(this.getStatus() == BMAP_STATUS_SUCCESS){
						getPositionInfo(r.point.lat,r.point.lng);
					} else {
						alert('failed'+this.getStatus());
					}
				},{enableHighAccuracy: true});
		    });

		    function search(address){
		        $.get('index.php?g=Index&c=Map&a=suggestion', {query:address}, function(data){
		            if(data.status == 1){
		                getAdress(data.result);
		            }
		        });
		    }
		    function getPositionLocation(result)
		    {
		        if(result.status == 0){
		            result = result.result;
		            getPositionInfo(result.location.lat,result.location.lng);
		        }else{
		            layer.open({
						content: '获取位置失败！'
						,btn: ['确定']
					});
		        }
		    }
		    function getPositionInfo(lat,lng){
		        $.getJSON('https://api.map.baidu.com/geocoder/v2/?ak=4c1bb2055e24296bbaef36574877b4e2&callback=renderReverse&location='+lat+','+lng+'&output=json&pois=1&callback=getPositionAdress&json=?');
		    }
		    function getPositionAdress(result){
		        if(result.status == 0){
		            result = result.result;
		            var re = [];
		            re.push({'name':result.sematic_description,'address':result.formatted_address,'long':result.location.lng,'lat':result.location.lat});
		            for(var i in result.pois){
		                re.push({'name':result.pois[i].name,'address':result.pois[i].addr,'long':result.pois[i].point.x,'lat':result.pois[i].point.y});
		            }
		            getAdress(re);
		        }else{
		            layer.open({
						content: '获取位置失败！'
						,btn: ['确定']
					});
		        }
		    }

		    function getAdress(re){
		        $(".addressShow").html('');
		        var addressHtml = '';
		        for(var i=0;i<re.length;i++){
		            addressHtml += '<li lng="'+re[i]['long']+'" lat="'+re[i]['lat']+'" sug_address="'+re[i]['name']+'" address="'+re[i]['address']+'" sname="'+re[i]['name']+'" class="addresslist">';
		            addressHtml += '<div class="mapaddress-title"> <span class="icon-location" data-node="icon"></span> <span class="recommend"> '+(i == 0 ? '[推荐位置]' : '')+'   '+re[i]['name']+' </span> </div>';
		            addressHtml += '<div class="mapaddress-body"> '+re[i]['address']+' </div>';
		            addressHtml += '</li>';
		        }
		        $('.addressShow').html(addressHtml);
		    }

		</script>
	</body>
</html>
