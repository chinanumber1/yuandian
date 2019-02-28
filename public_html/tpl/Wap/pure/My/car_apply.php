<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta http-equiv="Expires" content="-1">
	<meta http-equiv="Cache-Control" content="no-cache">
	<meta http-equiv="Pragma" content="no-cache">
	<meta charset="utf-8">
	<title>申请车主认证</title>
	<link href="{pigcms{$static_path}car_owner/css/css_whir.css" rel="stylesheet"/>
	<link href="{pigcms{$static_path}car_owner/css/wap_uploadimg.css" rel="stylesheet"/>
	<script src="{pigcms{$static_path}car_owner/js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}car_owner/js/webuploader.min.js"></script>
	<script src="{pigcms{$static_path}js/iscroll.js"></script>
	<style>
		.webuploader-element-invisible {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px);
			clip: rect(1px,1px,1px,1px);
		}
		.webuploader-pick-hover .btn{
			background-color: #629b58!important;
			border-color: #87b87f;
		}
		.webuploader-pick,dl.scwj dd input,.upload_list1,.upload_list1 .upload_action, .upload_list1 .upload_item,dl.scwj dd img{height:85px;}
		.increaseid_top,.increaseid_top img,.increaseid_top input,.increaseid_top .upload_action, .increaseid_top .upload_item,.increaseid_top .webuploader-pick{ height: 125px; }
		.mask {
			position: fixed;
			left: 0;
		    top: 0;
		    width: 100%;
		    height: 110%;
		    background-color: rgba(0, 0, 0, .2);
		    /*z-index: 100;*/
		}
		.car_show{
			width: 80%;
			height: 400px;
			margin:0 10%;
			position: fixed;
			top:50%;
			margin-top: -200px;
			background: #fff;
			border-radius: 5px;
			/*z-index: 10000;*/
			/*overflow: hidden;*/
			overflow:auto; 
			 -webkit-overflow-scrolling : touch; 
		}
		.car_show p{
			padding: 10px 0;
			font-size: 16px;
		}
		.car_show div{
			width: 90%;
			margin:0 5%;
			padding-bottom: 15px;

		}
		.add{
			position: fixed;
		}
		.hidden{
			display: none;
		}
		.bg{
		    width:100%;
		    height:100%;
		    background-color: rgba(0,0,0,0.5);
		    position:absolute;
		    top:0;
		    left:0;
		}
	</style>
	<!--[if lte IE 9]>
	<script src="scripts/html5shiv.min.js"></script>
	<![endif]-->
</head>
<body>
	<div class="Secondary">
		<ul>
			<li>
				<span class="on">姓名</span>
				<input id="name" type="text" placeholder="请输入姓名">
			</li>
			<li>
				<span>身份证号</span>
				<input id="user_id_number" type="text" placeholder="请输入身份证号">
			</li>
			<li>
				<span class="on1">车牌号</span>
				<select id="front_number" name="fuwu" class="on" style="width: 45%">
					<volist name="plate_number" id="vo">
						<option value="{pigcms{$vo['front']}">{pigcms{$vo['front']}({pigcms{$vo['name']})</option>
					</volist>
				</select>
				<input id="front" type="text" placeholder="请输入车牌号" style="width: 52%;">
			</li>
			<li>
				<span>车辆车型</span>
				<input id="models" type="text" placeholder="请输入车辆车型">
			</li>
			<li>
				<span class="on">驾龄</span>
				<input id="driving" type="text" placeholder="请输入驾龄">
			</li>
			<li>
				<span class="on">车龄</span>
				<input id="age" type="text" placeholder="请输入车龄">
			</li>
			<li>
				<span style="font-size: 14px;">车辆裸车价</span>
				<input id="car_price" type="text" placeholder="请输入车辆裸车价" onkeypress="return myNumberic(event)">
			</li>
		</ul>
	</div>
	<div class="Secondary">
		<ul>
			<li>
				<span>上传文件</span>
				<input style="border-bottom:0px;" type="text" readonly="true" placeholder="">
			</li>
			<dl class="scwj clr" style="display:none">
				<dd>
					<ul class="upload_list1 clearfix" id="upload_list1">
						<li class="upload_action">
							<img src="{pigcms{$static_path}images/grjj_11.png" />
							<input type="hidden" name="image1" id="image1" />
						</li>
					</ul>
					<span>身份证正面</span>
				</dd>
				<dd>
					<ul class="upload_list1 upload_list2  clearfix" id="upload_list2">
						<li class="upload_action">
							<img src="{pigcms{$static_path}images/grjj_13.png" />
							<input type="hidden" name="image2" id="image2" />
						</li>
					</ul>
					<span>身份证背面</span>
				</dd>
			</dl>
		</ul>
	<!-- 新增 -->
	<div class="increaseid">
		<dl class="clr">
			<dd class="fl">
				<div class="increaseid_end">
					<h2>{pigcms{$config.ride_upload_pic_name_1}上传</h2>
				</div>
			</dd>
			<dd class="p165">
				<ul class="upload_list3 clearfix increaseid_top" id="upload_list3">
					<li class="upload_action">
						<img src="{pigcms{$static_path}images/xbq_06.png" />
						<input type="hidden" name="image3" id="image3" />
					</li>
				</ul>
			</dd>
		</dl>
	</div>
	<div class="increaseid">
		<dl class="clr">
			<dd class="fl">
				<div class="increaseid_end">
					<h2>{pigcms{$config.ride_upload_pic_name_2}上传</h2>
				</div>
			</dd>
			<dd class="p165">
				<ul class="upload_list3 clearfix increaseid_top" id="upload_list4">
					<li class="upload_action">
						<img src="{pigcms{$static_path}images/xbq_06.png" />
						<input type="hidden" name="image4" id="image4" />
					</li>
				</ul>
			</dd>
		</dl>
	</div>
	
	<div class="increaseid">
		<dl class="clr">
			<dd class="fl">
				<div class="increaseid_end">
					<h2>{pigcms{$config.ride_upload_pic_name_3}上传</h2>
				</div>
			</dd>
			<dd class="p165">
				<ul class="upload_list3 clearfix increaseid_top" id="upload_list5">
					<li class="upload_action">
						<img src="{pigcms{$static_path}images/xbq_06.png" />
						<input type="hidden" name="image5" id="image5" />
					</li>
				</ul>
			</dd>
		</dl>
	</div>
	
	<div class="increaseid">
		<dl class="clr">
			<dd class="fl">
				<div class="increaseid_end">
					<h2>{pigcms{$config.ride_upload_pic_name_4}上传</h2>
				</div>
			</dd>
			<dd class="p165">
				<ul class="upload_list3 clearfix increaseid_top" id="upload_list6">
					<li class="upload_action">
						<img src="{pigcms{$static_path}images/xbq_06.png" />
						<input type="hidden" name="image6" id="image6" />
					</li>
				</ul>
			</dd>
		</dl>
	</div>
	<if condition="$config.car_apply_rule neq ''">
	<div class="increaseid">
		<dl class="clr">
			<input type="checkbox" id="car_apply_rule_agreement" name="car_apply_rule_agreement" value="1" style="-webkit-appearance:checkbox;vertical-align: middle;margin-right: 5px;"><span style="vertical-align: middle;">同意《认证协议》</span><a href="javascript:;" id="read_register_agreement" style="color: #ff7a92;vertical-align: bottom;">查看协议</a>
		</dl>
	</div>
	</if>
    <div class="dysb_d">
      <input id="submit" type="submit" value="提交" class="dysub">
    </div>
	
	<div class="mask hidden">

			<div class="car_show" id="show_car"> 
				<p class="tips" id="tips_content" style=" text-align:center;">车主认证协议</p>
				<div>
					{pigcms{$config.car_apply_rule|nl2br}
				</div>
			</div>	

	</div>
</body>
	<!-- 上传图片 -->
	<script type="text/javascript">
		//提交按钮
		var find = '{pigcms{$find}';
		if(find != 0){
			window.location.href	=	"{pigcms{:U('car_owner')}";
		}
		var request = true;
		$("#submit").click(function(){
			var name	=	$('#name').val();
			var front	=	$('#front').val();
			var front_number	=	$('#front_number').val();
			var models	=	$('#models').val();
			var driving	=	$('#driving').val();
			var age		=	$('#age').val();
			var car_price=	$('#car_price').val();
			var user_id_number=	$('#user_id_number').val();
			var image1	=	$('#image1').val();
			var image2	=	$('#image2').val();
			var image3	=	$('#image3').val();
			var image4	=	$('#image4').val();
			var image5	=	$('#image5').val();
			var image6	=	$('#image6').val();
			var car_apply_rule_agreement	=	0;
			if(name==''){
				alert('姓名不能为空');
				return false;
			}
			var check = checkCardId(user_id_number);
			if(check == false){
				return false;
			}
			if(front==''){
				alert('车牌号不能为空');
				return false;
			}
			if(models==''){
				alert('车辆车型不能为空');
				return false;
			}
			if(driving==''){
				alert('驾龄不能为空');
				return false;
			}
			if(age==''){
				alert('车龄不能为空');
				return false;
			}
			if(car_price==''){
				alert('车价不能为空');
				return false;
			}
			// if(image1 == ''){
				// alert('身份证正面不能为空');
				// return false;
			// }
			// if(image2 == ''){
				// alert('身份证反面不能为空');
				// return false;
			// }
			<if condition="$config.ride_upload_pic_name_1 neq ''" >
				if(image3 == ''){
					alert('{pigcms{$config.ride_upload_pic_name_1}不能为空');
					return false;
				}
			</if>
			<if condition="$config.ride_upload_pic_name_2 neq ''" >
				if(image4 == ''){
					alert('{pigcms{$config.ride_upload_pic_name_2}不能为空');
					return false;
				}
			</if>
			<if condition="$config.ride_upload_pic_name_3 neq ''" >
				if(image5 == ''){
					alert('{pigcms{$config.ride_upload_pic_name_3}不能为空');
					return false;
				}
			</if>
			<if condition="$config.ride_upload_pic_name_4 neq ''" >
				if(image6 == ''){
					alert('{pigcms{$config.ride_upload_pic_name_4}不能为空');
					return false;
				}
			</if>
			<if condition="$config.car_apply_rule neq ''">
			if(!$('input[name="car_apply_rule_agreement"]').is(':checked')){
				alert('必须同意认证协议');
				return false;
			}
			if($('input[name="car_apply_rule_agreement"]').is(':checked')){
				car_apply_rule_agreement = 1;
			}
			</if>
			if(request == true){
				request = false;
				$.ajax({
					type : "post",
					url : "{pigcms{:U('car_apply_json')}",
					dataType : "json",
					data:{
						name	:	name,
						user_id_number	:	user_id_number,
						front	:	front_number+front,
						models	:	models,
						driving	:	driving,
						age	:	age,
						car_price	:	car_price,
						authentication_img	:	image5,
						authentication_back_img	:	image6,
						drivers_license	:	image3,
						driving_license	:	image4,
						car_apply_rule_agreement	:	car_apply_rule_agreement,
					},
					success : function(result){
						var rideList	=	result;
						if(result.errorCode == 0){
							window.location.href	=	rideList.result;
						}else{
							request = true;
							alert(result.errorMsg);
						}
					},
					error:function(){
						request = true;
						alert('接口出错');
					}
				})
			}
		})
		//  上传图片
		var uploader = WebUploader.create({
			auto: true,
			swf: '{pigcms{$static_public}js/Uploader.swf',
			server: "{pigcms{:U('My/ajaxWebUpload')}",
			pick: {
				id:'#upload_list1',
				multiple:false
			},
			accept: {
				title: 'Images',
				extensions: 'gif,jpg,jpeg,png',
				mimeTypes: 'image/*'
			}
		});
		uploader.on('uploadSuccess',function(file,response){
			if(response.error == 0){
				$('#upload_list1 li img').attr('src',response.url);
				$('#image1').val(response.title);
			}else{
				alert(response.info);
			}
		});
		uploader.on('uploadError', function(file,reason){
			alert('上传失败！请重试。');
		});

		var uploader1 = WebUploader.create({
			auto: true,
			swf: '{pigcms{$static_public}js/Uploader.swf',
			server: "{pigcms{:U('My/ajaxWebUpload')}",
			pick: {
				id:'#upload_list2',
				multiple:false
			},
			accept: {
				title: 'Images',
				extensions: 'gif,jpg,jpeg,png',
				mimeTypes: 'image/*'
			}
		});
		uploader1.on('uploadSuccess',function(file,response){
			if(response.error == 0){
				$('#upload_list2 li img').attr('src',response.url);
				$('#image2').val(response.title);
			}else{
				alert(response.info);
			}
		});
		uploader1.on('uploadError', function(file,reason){
			alert('上传失败！请重试。');
		});

		var uploader2 = WebUploader.create({
			auto: true,
			swf: '{pigcms{$static_public}js/Uploader.swf',
			server: "{pigcms{:U('My/ajaxWebUpload')}",
			pick: {
				id:'#upload_list3',
				multiple:false
			},
			accept: {
				title: 'Images',
				extensions: 'gif,jpg,jpeg,png',
				mimeTypes: 'image/*'
			}
		});
		uploader2.on('uploadSuccess',function(file,response){
			if(response.error == 0){
				$('#upload_list3 li img').attr('src',response.url);
				$('#image3').val(response.title);
			}else{
				alert(response.info);
			}
		});
		uploader2.on('uploadError', function(file,reason){
			alert('上传失败！请重试。');
		});

		var uploader3 = WebUploader.create({
			auto: true,
			swf: '{pigcms{$static_public}js/Uploader.swf',
			server: "{pigcms{:U('My/ajaxWebUpload')}",
			pick: {
				id:'#upload_list4',
				multiple:false
			},
			accept: {
				title: 'Images',
				extensions: 'gif,jpg,jpeg,png',
				mimeTypes: 'image/*'
			}
		});
		uploader3.on('uploadSuccess',function(file,response){
			if(response.error == 0){
				$('#upload_list4 li img').attr('src',response.url);
				$('#image4').val(response.title);
			}else{
				alert(response.info);
			}
		});
		uploader3.on('uploadError', function(file,reason){
			alert('上传失败！请重试。');
		});
		
		var uploader4 = WebUploader.create({
			auto: true,
			swf: '{pigcms{$static_public}js/Uploader.swf',
			server: "{pigcms{:U('My/ajaxWebUpload')}",
			pick: {
				id:'#upload_list5',
				multiple:false
			},
			accept: {
				title: 'Images',
				extensions: 'gif,jpg,jpeg,png',
				mimeTypes: 'image/*'
			}
		});
		uploader4.on('uploadSuccess',function(file,response){
			if(response.error == 0){
				$('#upload_list5 li img').attr('src',response.url);
				$('#image5').val(response.title);
			}else{
				alert(response.info);
			}
		});
		uploader4.on('uploadError', function(file,reason){
			alert('上传失败！请重试。');
		});
		
		var uploader5 = WebUploader.create({
			auto: true,
			swf: '{pigcms{$static_public}js/Uploader.swf',
			server: "{pigcms{:U('My/ajaxWebUpload')}",
			pick: {
				id:'#upload_list6',
				multiple:false
			},
			accept: {
				title: 'Images',
				extensions: 'gif,jpg,jpeg,png',
				mimeTypes: 'image/*'
			}
		});
		uploader5.on('uploadSuccess',function(file,response){
			if(response.error == 0){
				$('#upload_list6 li img').attr('src',response.url);
				$('#image6').val(response.title);
			}else{
				alert(response.info);
			}
		});
		uploader5.on('uploadError', function(file,reason){
			alert('上传失败！请重试。');
		});
		
		function checkCardId(socialNo){
			if(socialNo == ""){
				alert("输入身份证号码不能为空!");
				return (false);
			}
			if (socialNo.length != 15 && socialNo.length != 18){
				alert("输入身份证号码格式不正确!");
				return (false);
			}
			var area={11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",21:"辽宁",22:"吉林",23:"黑龙江",31:"上海",32:"江苏",33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",42:"湖北",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",51:"四川",52:"贵州",53:"云南",54:"西藏",61:"陕西",62:"甘肃",63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外"};
			if(area[parseInt(socialNo.substr(0,2))]==null){
				alert("身份证号码不正确(地区非法)!");
				return (false);
			}
			if (socialNo.length == 15){
				pattern= /^\d{15}$/;
				if (pattern.exec(socialNo)==null){
					alert("15位身份证号码必须为数字！");
					return (false);
				}
				var birth = parseInt("19" + socialNo.substr(6,2));
				var month = socialNo.substr(8,2);
				var day = parseInt(socialNo.substr(10,2));
				switch(month) {
					case '01':
					case '03':
					case '05':
					case '07':
					case '08':
					case '10':
					case '12':
						if(day>31) {
							alert('输入身份证号码不格式正确!');
							return false;
						}
						break;
					case '04':
					case '06':
					case '09':
					case '11':
						if(day>30) {
							alert('输入身份证号码不格式正确!');
							return false;
							}
						break;
					case '02':
						if((birth % 4 == 0 && birth % 100 != 0) || birth % 400 == 0){
							if(day>29) {
								alert('输入身份证号码不格式正确!');
								return false;
							}
						}else{
							if(day>28){
								alert('输入身份证号码不格式正确!');
								return false;
							}
						}
						break;
					default:
						alert('输入身份证号码不格式正确!');
						return false;
				}
				var nowYear = new Date().getYear();
				if(nowYear - parseInt(birth)<15 || nowYear - parseInt(birth)>100) {
					alert('输入身份证号码不格式正确!');
					return false;
				}
				return (true);
			}

			var Wi = new Array(
				7,9,10,5,8,4,2,1,6,
				3,7,9,10,5,8,4,2,1
			);
			var   lSum        = 0;
			var   nNum        = 0;
			var   nCheckSum   = 0;
			for (i = 0; i < 17; ++i){
				if ( socialNo.charAt(i) < '0' || socialNo.charAt(i) > '9' )
				{
				alert("输入身份证号码格式不正确!");
				return (false);
				}
				else
				{
				nNum = socialNo.charAt(i) - '0';
				}
				lSum += nNum * Wi[i];
			}
			if( socialNo.charAt(17) == 'X' || socialNo.charAt(17) == 'x'){
				lSum += 10*Wi[17];
			}else if( socialNo.charAt(17) < '0' || socialNo.charAt(17) > '9' ){
				alert("输入身份证号码格式不正确!");
				return (false);
			}else{
				lSum += ( socialNo.charAt(17) - '0' ) * Wi[17];
			}
			if((lSum % 11) == 1 ){
				return true;
			}else{
				alert("输入身份证号码格式不正确!");
				return (false);
			}
		}
		if(/(pigcmso2oreallifeapp)/.test(navigator.userAgent.toLowerCase()) || (/(pigcmso2olifeapp)/.test(navigator.userAgent.toLowerCase()) && /(life_app)/.test(navigator.userAgent.toLowerCase()))){
			var reg = /versioncode=(\d+),/;
			var arr = reg.exec(navigator.userAgent.toLowerCase());
			if(arr == null){
				
			}else{
				var version = parseInt(arr[1]);
				if(version >= 50){
					if(/(iphone|ipad|ipod)/.test(navigator.userAgent.toLowerCase())){
						$('body').append('<iframe src="pigcmso2o://hideWebViewHeader/true" style="display:none;"></iframe>');
						$('#li_delivery a').click(function(){
							var address_id = $('#address_id').val() == '' ? '0' : $('#address_id').val();
							$('body').append('<iframe src="pigcmso2o://getUserAddress/'+address_id+'" style="display:none;"></iframe>');
							return false;
						});
					}else{
						$('#li_delivery a').click(function(){
							var address_id = $('#address_id').val() == '' ? '0' : $('#address_id').val();
							window.lifepasslogin.getUserAddress(address_id);
							return false;
						});
					}
				}
			}
		}

		
		// $('#read_register_agreement').click(function(event){
		// 	$('.mask').removeClass('hidden');
		// 	$('.bg').on('touchmove', function (e) { e.preventDefault(); }, false);//弹出层的背景层阻止默认滑动事件
  //           new IScroll('#show_car');//
		// 	//$('body').addClass('body');
		// 	//$('html').addClass('body');
		// 	$('.mask').click(function(e){
		// 		//$('body').removeClass('body');
		// 		//$('html').removeClass('body');
		// 		$('.mask').addClass('hidden');
		// 	});
		// });
		 var top = 0;//给top变量一个初始值，以便下方赋值并引用。
	       $('#read_register_agreement').click(function(){//点击事件弹出层显示，
	          $('.mask').removeClass('hidden'); //弹出层出现     
	            top = $(window).scrollTop();//获取页面的scrollTop；
	            $('body').css("top",-top+"px");//给body一个负的top值；
	            $('body').addClass('add');//给body增加一个类，position:fixed; 
	        });
	        $('.mask').click(function(){//弹出层消失点击事件
	            $('.mask').addClass('hidden'); //弹出层消失
	            $('body').removeClass('add');//去掉给body的类
	            $(window).scrollTop(top);//设置页面滚动的高度，如果不设置，关闭弹出层时页面会回到顶部。
	        });
		
			function myNumberic(e,len) {
				var obj=e.srcElement || e.target;
				var dot=obj.value.indexOf(".");//alert(e.which);
				len =(typeof(len)=="undefined")?2:len;
				var  key=e.keyCode|| e.which;
				if(key==8 || key==9 || key==46 || (key>=37  && key<=40))//这里为了兼容Firefox的backspace,tab,del,方向键
					return true;
				if (key<=57 && key>=48) { //数字
					if(dot==-1)//没有小数点
						return true;
					else if(obj.value.length<=dot+len)//小数位数
						return true;
					} else if((key==46) && dot==-1){//小数点
						return true;
				}       
				return false;
			}

	</script>
	
</html>