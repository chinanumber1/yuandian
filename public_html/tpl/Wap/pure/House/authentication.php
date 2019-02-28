<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<meta http-equiv="Expires" content="-1">
		<meta http-equiv="Cache-Control" content="no-cache">
		<meta http-equiv="Pragma" content="no-cache">
		<meta charset="utf-8">
		<title>实名认证</title>
		<link href="{pigcms{$static_path}scenic/css/css_whir.css" rel="stylesheet"/>
		<link href="{pigcms{$static_path}scenic/css/wap_uploadimg.css" rel="stylesheet"/>
		<script src="{pigcms{$static_path}scenic/js/jquery-1.8.3.min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
		<!--[if lte IE 9]>
		<script src="scripts/html5shiv.min.js"></script>
		<![endif]-->
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
		.increaseid_top,.increaseid_top img,.increaseid_top input,.increaseid_top .upload_action, .increaseid_top .upload_item,.increaseid_top .webuploader-pick{ height: 185px; }
		</style>
	</head>
	<body>
		<div class="Secondary">
			<ul>
				<li>
					<span>真实姓名</span>
					<input id="name" name="name" type="text" placeholder="请输入姓名">
				</li>
				<li>
					<span>身份证号</span>
					<input id="uid" name="uid" type="text" placeholder="请输入身份证号">
				</li>
				<li>
					<span>上传文件</span>
					<input type="text" readonly="true" placeholder="请手持证件拍照">
				</li>
				<dl class="scwj clr">
					<dd>
						<ul class="upload_list1 clearfix" id="upload_list1">
							<li class="upload_action">
								<img src="http://www.group.com/tpl/Wap/pure/static/scenic/images/grjj_11.png" />
								<input type="hidden" name="image1" id="image1" />
							</li>
						</ul>
						<span>身份证正面</span>
					</dd>
					<dd>
						<ul class="upload_list1 upload_list2 clearfix" id="upload_list2">
							<li class="upload_action">
								<img src="http://www.group.com/tpl/Wap/pure/static/scenic/images/grjj_13.png" />
								<input type="hidden" name="image2" id="image2" />
							</li>
						</ul>
						<span>身份证背面</span>
					</dd>
				</dl>
			</ul>
			<!-- 新增 -->
			<div class="increaseid">
				<ul class="upload_list3 clearfix increaseid_top" id="upload_list3">
					<li class="upload_action">
						<img src="{pigcms{$static_path}scenic/images/xbq_06.png" />
						<input type="hidden" name="image3" id="image3" />
					</li>
				</ul>
			    <div class="increaseid_end">
		    		<h2>本人手持身份证照片</h2>
					<p>(请确保姓名、身份证号码清晰)</p>
			    </div>

			</div>
		</div>
		<div class="dysb_d">
			<input id="submit" type="submit" value="提交" class="dysub">
		</div>
	    <script type="text/javascript">
    		//	上传图片
			var  uploader = WebUploader.create({
				auto: true,
				swf: '{pigcms{$static_public}js/Uploader.swf',
				server: "{pigcms{:U('Scenic_aguide/ajaxWebUpload')}",
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
				server: "{pigcms{:U('Scenic_aguide/ajaxWebUpload')}",
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
				server: "{pigcms{:U('Scenic_aguide/ajaxWebUpload')}",
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
				server: "{pigcms{:U('Scenic_aguide/ajaxWebUpload')}",
				pick: {
					id:'#uploadPicBtn',
					multiple:false
				},
				accept: {
					title: 'Images',
					extensions: 'gif,jpg,jpeg,png',
					mimeTypes: 'image/*'
				}
			});
	    </script>
		<script>
			$("#submit").click(function(){
				var name	=	$('#name').val();
				var uid		=	$('#uid').val();
				var image1	=	$('#image1').val();
				var image2	=	$('#image2').val();
				var image3	=	$('#image3').val();
				if(name==''){
					alert('姓名不能为空');
					return false;
				}
				var check = checkCardId(uid);
				if(check == false){
					return false;
				}
				if(image1 == ''){
					alert('身份证正面不能为空');
					return false;
				}
				if(image2 == ''){
					alert('身份证反面不能为空');
					return false;
				}
				if(image3 == ''){
					alert('手持身份证不能为空');
					return false;
				}
				var request = true;
				if(request == true){
					request = false;
					$.ajax({
						type : "post",
						url : "{pigcms{:U('authentication_json')}",
						dataType : "json",
						data:{
							user_truename	:	name,
							user_id_number	:	uid,
							authentication_img	:	image1,
							authentication_back_img	:	image2,
							hand_authentication	:	image3,
							village_id:"{pigcms{$_GET['village_id']}"
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
		</script>
	</body>
</html>