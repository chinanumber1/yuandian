<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>发布帖子</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name='apple-touch-fullscreen' content='yes' />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="format-detection" content="address=no" />
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css" />
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css" />
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/bbs.css" />
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/LCalendar.css" />
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<style type="text/css">
			#container {
				bottom: -80px;
				top:0
			}
		</style>
	</head>

	<body>
		<form action="__SELF__" method="post" onsubmit="return chk_submit()">
		<div id="container">
				<div id="scroller" class="village_my">
					<nav style="display:none">
						<section class="link-url">
							<p>帖子类型：</p>
						</section>
						<section class="fabu-type">
							<ul>
								<li class="active" data-type="0">普通帖子</li>
								<li data-type="1">报名帖子</li>
							</ul>
						</section>
					</nav>

					<div class="fabu-type-info">
						<nav>
							<section>
								<textarea name="aricle_title" id="content" onKeyDown="textdown(event)" onKeyUp="textup()" placeholder="快来晒晒你的新鲜事吧。"></textarea>
							</section>
						</nav>
					</div>

					<div class="fabu-type-info" style="display: none;">
						<nav>
							<section>
								<input type="text" name="aricle_title_activity" id="activity_title" value="" placeholder="报名标题（必填2-20字）" />
								<textarea id="content" name="aricle_content" onKeyDown="textdown(event)" onKeyUp="textup()" placeholder="填写报名描述，地点，规则等信息。"></textarea>
							</section>
						</nav>

						<nav>
							<section>
								<p>报名截止时间：<input id="activity_date" name="activity_date" type="text" readonly="" placeholder="选择日期" /></p>

							</section>
						</nav>

						<nav>
							<section class="activity-info">
								<p>活动人数上限：<input type="text" placeholder="最多200人" id="activity_num" name="num" /></p>
							</section>
						</nav>
					</div>
					
					<nav>
							<section>
								<dd class="item">
									<div class="upload_box">
										<ul class="upload_list clearfix" id="upload_list">
											<li class="upload_action"> <img src="{pigcms{$static_path}images/upimg.png"> <input type="file" accept="image/jpg,image/jpeg,image/png,image/gif" id="fileImage"> </li>
										</ul>
									</div>
								</dd>
						</nav>
						
						<nav>
							<section class="activity-info">
								<p>位置：<i id="location"></i></p>
							</section>
						</nav>
				</div>
			
			<div id="pullUp" style="bottom:-60px;">
				<img src="/static/logo.png" style="width:130px;height:60px;margin-top:10px" />
			</div>
		</div>
		<footer class="footerMenu wap house">
			<input type="hidden" value="{pigcms{$_GET['cat_id']}" name="cat_id" />
			<input type="hidden" value="0" name="type" />
			<input type="hidden" name="address" value="" />
			<input type="submit" value="发布" class="fabu-btn" />
		</footer>
		<div id="allmap"></div>
		</form>
		<script type="text/javascript" src="{pigcms{$static_path}js/LCalendar.js"></script>
		<script src="{pigcms{$static_path}js/exif.js"></script>
		<script src="{pigcms{$static_path}js/imgUpload.js"></script>
        <if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
            <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key={pigcms{$config.google_map_ak}"></script>
            <script type="text/javascript">var is_google_map = "{pigcms{$config.google_map_ak}"</script>
        <else />
		<script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
        </if>
		<script type="text/javascript" language="JavaScript">
			$('.fabu-type ul li').each(function(i) {
				$(this).click(function() {
					$('.fabu-type ul li').removeClass('active');
					$('.fabu-type-info').hide().eq(i).show();
					$(this).addClass('active');
					$('input[name="type"]').val($(this).data('type'));
				});
			});

			var calendar = new LCalendar();
			calendar.init({
				'trigger': '#activity_date', //标签id
				'type': 'date', //date 调出日期选择 datetime 调出日期时间选择 time 调出时间选择 ym 调出年月选择,
				'minDate': '1900-1-1', //最小日期
				'maxDate': '2016-12-31' //最大日期
			});

			function textdown(e) {
				textevent = e;
				if(textevent.keyCode == 8) {
					return;
				}
				if(document.getElementById('content').value.length >= 100) {
					alert("大侠，手下留情，此处限字100")
					if(!document.all) {
						textevent.preventDefault();
					} else {
						textevent.returnValue = false;
					}
				}
			}

			function textup() {
				var s = document.getElementById('content').value;
				//判断ID为text的文本区域字数是否超过100个 
				if(s.length > 100) {
					document.getElementById('content').value = s.substring(0, 100);
				}
			}
			
			var ajaxImgUpload_url = "{pigcms{:U('ajaxImgUpload')}&ml=luntan&village_id={pigcms{$_GET['village_id']}";
			
			if ($(".upload_list").length) {
				var imgUpload = new ImgUpload({
					fileInput: "#fileImage",
					container: "#upload_list",
					countNum: "#uploadNum",
					url:ajaxImgUpload_url,
				})
			}
		</script>
		
	<script type="text/javascript">
        if(typeof(is_google_map) != "undefined"){
            var map;
            var service;


            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    }
                    var request = {
                        location: pos,
                        radius: '200'
                    };
                    map = new google.maps.Map(document.getElementById('allmap'), {

                        center:{lat:pos.lat,lng:pos.lng},
                        zoom: 15
                    });
                    service = new google.maps.places.PlacesService(map);
                    service.nearbySearch(request, callback);
                });
            } else {
                console.log("浏览器不支持!");
            }
            //实验用
            // var request = {
            //     location: {lng:parseFloat(117.228692),lat:parseFloat(31.822943)},
            //     radius: '200'
            // };
            // map = new google.maps.Map(document.getElementById('allmap'), {
            //
            //     // center:{lat:pos.lat,lng:pos.lng},
            //     center:{lng:parseFloat(117.228692),lat:parseFloat(31.822943)},
            //     zoom: 15
            // });
            // service = new google.maps.places.PlacesService(map);
            // service.nearbySearch(request, callback);

            function callback(results, status) {
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    $('#location').html(results[1].name);
                    $('input[name="address"]').val(results[1].vicinity);

                }
            }
        }else{
            // 百度地图API功能
            var map = new BMap.Map("allmap");
            var point = new BMap.Point(116.331398,39.897445);
            map.centerAndZoom(point,12);

            var geolocation = new BMap.Geolocation();
            geolocation.getCurrentPosition(function(r){
                if(this.getStatus() == BMAP_STATUS_SUCCESS){
                    var mk = new BMap.Marker(r.point);
                    map.addOverlay(mk);
                    map.panTo(r.point);
                    var url = '{pigcms{:U("ajax_get_address_list")}&village_id={pigcms{$_GET["village_id"]}';

                    $.post(url,{'lat':r.point.lat,'long':r.point.lng},function(data){

                        $('#location').html(data['formatted_address']);

                        $('input[name="address"]').val(data['addressComponent']);
                    },'json')
                }
                else {
                    alert('failed'+this.getStatus());
                }
            },{enableHighAccuracy: true})

        }
        // var a = new FormData($('#formList')[0]);
        // $.ajax({
        //   'type':'POST',
        //   'data':a,
        //   'url': $('#formList').attr('action'),
        //   'dataType':'json',
        //   processData:false,
        //   success:function(data){
        //     console.log(data);
        //   }
        // })
	</script>
	</body>

</html>