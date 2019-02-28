<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>{pigcms{$config.site_name} - 会员卡编辑</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
		<style>
			body{background:white;}
			body form{width:50%;background:#F3F3F3;}
			a:hover,a:visited{color:#666;}
			table th{padding:17px 10px 19px;}
			table td{padding:17px 10px 19px 15px;}
			#img_preview{	
				width: 40%;
				position: absolute;
				right: 5%;
				top: 30%;
				overflow: hidden;
				-webkit-user-select: none;
				-webkit-touch-callout: none;
				max-width: 686px;
			}
			#img_preview_img{
				width:100%;
			}
			.bgcolorTd label{
				margin-right:20px;
			}
			.bgcolorTd label.active img{
				border:2px solid blue;
			}
			.bgcolorTd img{
				border:2px solid #F3F3F3;
				padding:5px;
				width:120px;
				cursor:pointer;
			}
			#big_name_preview,#small_name_preview{
				position:absolute;
				z-index:1001;
			}
			#img_preview_good_box{
				position: absolute;
				z-index:1001;
				width: 60px;
				height: 80px;
				right: 80px;
				top: 0;
				overflow:hidden;
			}
			#img_preview_good{
				width:100%;
			}
			.btn-success, .btn-success:focus{
				position: relative;
				width: 78px;
				height: 34px;
				background-color: #87b87f!important;
				border-color: #87b87f;
				text-align: center;
				line-height: 34px;
				color: white;
				cursor: pointer;
			}
			.webuploader-element-invisible {
				position: absolute !important;
				clip: rect(1px 1px 1px 1px);
				clip: rect(1px,1px,1px,1px);
			}
			.webuploader-pick-hover .btn{
				background-color: #629b58!important;
				border-color: #87b87f;
			}
		</style>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
	</head>
	<body>
		<form id="myform" method="post" action="{pigcms{:U('shop_fitment_build_subject_pic_func')}" autocomplete="off">
			<input type="hidden" name="store_id" value="{pigcms{$_GET.store_id}"/>
			<table>
				<tr>
					<th width="20%">背景底图</th>
					<td width="80%" colspan="3" class="bgcolorTd">
						<label class="active" data-img="{pigcms{$static_public}images/shop_fitment/showcase_img1.png" data-type="1" data-big_font="40" data-big_color="#432D0A" data-big_left="50" data-big_top="35" data-small_font="24" data-small_left="50" data-small_top="116" data-small_color="#68502E" data-good_left="426" data-good_top="22" data-good_width="232" data-good_height="132"><img src="{pigcms{$static_public}images/shop_fitment/showcase_img1.png"/></label>
						<label data-type="2" data-img="{pigcms{$static_public}images/shop_fitment/showcase_img2.png" data-big_font="40" data-big_color="#FAD3C5" data-big_left="50" data-big_top="35" data-small_font="24" data-small_left="50" data-small_top="110" data-small_color="#AD5740" data-good_left="430" data-good_top="22" data-good_width="234" data-good_height="136"><img src="{pigcms{$static_public}images/shop_fitment/showcase_img2.png"/></label>
						<label data-type="3" data-img="{pigcms{$static_public}images/shop_fitment/showcase_img3.png" data-big_font="40" data-big_color="#3D474E" data-big_left="50" data-big_top="35" data-small_font="24" data-small_left="50" data-small_top="92" data-small_color="#AEB1B3" data-good_left="442" data-good_top="14" data-good_width="232" data-good_height="132"><img src="{pigcms{$static_public}images/shop_fitment/showcase_img3.png"/></label>
						<label data-type="4" data-img="{pigcms{$static_public}images/shop_fitment/showcase_img4.png" data-big_font="40" data-big_color="#FFFFFF" data-big_left="36" data-big_top="60" data-small_font="22" data-small_left="36" data-small_top="122" data-small_color="#FFA49E" data-good_left="433" data-good_top="23" data-good_width="232" data-good_height="132"><img src="{pigcms{$static_public}images/shop_fitment/showcase_img4.png"/></label>
						<label data-type="5" data-img="{pigcms{$static_public}images/shop_fitment/showcase_img5.png" data-big_font="40" data-big_color="#FFFFFF" data-big_left="36" data-big_top="30" data-small_font="24" data-small_left="36" data-small_top="104" data-small_color="#FFFFFF" data-good_left="433" data-good_top="23" data-good_width="232" data-good_height="132"><img src="{pigcms{$static_public}images/shop_fitment/showcase_img5.png"/></label>
						<label data-type="6" data-img="{pigcms{$static_public}images/shop_fitment/showcase_img6.png" data-big_font="36" data-big_color="#3D474E" data-big_left="354" data-big_top="40" data-small_font="24" data-small_left="354" data-small_top="96" data-small_color="#AEB1B3" data-good_left="22" data-good_top="22" data-good_width="232" data-good_height="132"><img src="{pigcms{$static_public}images/shop_fitment/showcase_img6.png"/></label>
						<label data-type="7" data-img="{pigcms{$static_public}images/shop_fitment/showcase_img7.png" data-big_font="40" data-big_color="#FFFFFF" data-big_left="56" data-big_top="90" data-small_font="24" data-small_left="108" data-small_top="40" data-small_color="#A6BDF3" data-good_left="432" data-good_top="22" data-good_width="232" data-good_height="132"><img src="{pigcms{$static_public}images/shop_fitment/showcase_img7.png"/></label>
						<label data-type="8" data-img="{pigcms{$static_public}images/shop_fitment/showcase_img8.png" data-big_font="36" data-big_color="#FFFFFF" data-big_left="32" data-big_top="90" data-small_font="24" data-small_left="32" data-small_top="36" data-small_color="#FFFFFF" data-good_left="392" data-good_top="22" data-good_width="232" data-good_height="132"><img src="{pigcms{$static_public}images/shop_fitment/showcase_img8.png"/></label>
						<label data-type="9" data-img="{pigcms{$static_public}images/shop_fitment/showcase_img9.png" data-big_font="40" data-big_color="#FFFFFF" data-big_left="48" data-big_top="38" data-small_font="24" data-small_left="48" data-small_top="100" data-small_color="#B2A5D2" data-good_left="432" data-good_top="22" data-good_width="232" data-good_height="134"><img src="{pigcms{$static_public}images/shop_fitment/showcase_img9.png"/></label>
					</td>
				</tr>
				<tr>
					<th width="20%">商品图片</th>
					<td width="80%" colspan="3">
						<div>
							<div style="display:inline-block;" id="J_selectImage">
								<div class="btn btn-sm btn-success" style="position:relative;width:78px;height:34px;    background-color:#87b87f!important;border-color:#87b87f;">上传图片</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<th width="20%">主标题</th>
					<td width="80%" colspan="3"><input type="text" class="input" id="big_name" value="优选食材精品推荐" style="width:200px;"/>&nbsp;&nbsp;&nbsp;<span>字数限制为4-8个字</span></td>
				</tr>
				<tr>
					<th width="20%">副标题</th>
					<td width="80%" colspan="3"><input type="text" class="input" id="small_name" value="满60减20 满20减10" style="width:200px;"/>&nbsp;&nbsp;&nbsp;<span>字数限制4-16个字</span></td>
				</tr>
			</table>
			<div class="btn">
				<button type="submit" id="submit">生成图片</button>
			</div>
		</form>
		<div id="img_preview">
			<img id="img_preview_img" src="{pigcms{$static_public}images/shop_fitment/showcase_img1.png"/>
			<div id="big_name_preview"></div>
			<div id="small_name_preview"></div>
			<div id="img_preview_good_box">
				<img id="img_preview_good" src="{pigcms{$static_public}images/shop_fitment/demo_good.png"/>
			</div>
		</div>
		<script type="text/javascript" src="{pigcms{$static_public}layer/layer.js"></script>
		<script>
			var preview_scare = 0;
			var upload_layer_tip = null;
			$(function(){
				$('#myform').height($(window).height());
				
				$('#big_name_preview').html($('#big_name').val());
				$('#small_name_preview').html($('#small_name').val());
				$('#big_name').keyup(function(){
					$('#big_name_preview').html($('#big_name').val());
				});
				$('#small_name').keyup(function(){
					$('#small_name_preview').html($('#small_name').val());
				});
				

				$('.bgcolorTd label').click(function(){
					// 686 设计原图宽
					preview_scare = $('#img_preview').width() / 686;
					
					$(this).addClass('active').siblings().removeClass('active');
					$('#img_preview_img').attr('src',$(this).data('img'));

					var big_left = parseInt($(this).data('big_left')*preview_scare);
					var big_top = parseInt($(this).data('big_top')*preview_scare);
					var big_font = parseInt($(this).data('big_font')*preview_scare);
					
					var small_left = parseInt($(this).data('small_left')*preview_scare);
					var small_top = parseInt($(this).data('small_top')*preview_scare);
					var small_font = parseInt($(this).data('small_font')*preview_scare);
					
					var good_left = parseInt($(this).data('good_left')*preview_scare);
					var good_top = parseInt($(this).data('good_top')*preview_scare);
					var good_width = parseInt($(this).data('good_width')*preview_scare);
					var good_height = parseInt($(this).data('good_height')*preview_scare);
					
					
					$('#big_name_preview').css({left:big_left,top:big_top,color:$(this).data('big_color'),'font-size':big_font,'font-weight':'bold'});
					$('#small_name_preview').css({left:small_left,top:small_top,color:$(this).data('small_color'),'font-size':small_font});
					$('#img_preview_good_box').css({left:good_left,top:good_top,width:good_width,height:good_height});
					
					if($('#img_preview_good').data('good_width')){
						$('#img_preview_good').css('margin-top','-'+parseInt((($('#img_preview_good').width()*$('#img_preview_good').data('good_height')/$('#img_preview_good').data('good_width'))-$('#img_preview_good_box').height())/2)+'px');
					}
				});
				$('.bgcolorTd label.active').trigger('click');
				$(window).resize(function(){
					$('.bgcolorTd label.active').trigger('click');
				});
				
				var  uploader = WebUploader.create({
					auto: true,
					swf: './static/js/Uploader.swf',
					server: "/merchant.php?g=Merchant&c=Shop&a=ajax_upload_pic&store_id={pigcms{$_GET.store_id}",
					pick: {
						id:'#J_selectImage',
						multiple:false
					},
					accept: {
						title: 'Images',
						extensions: 'gif,jpg,jpeg,png',
						mimeTypes: 'image/gif,image/jpeg,image/jpg,image/png'
					}
				});
				uploader.on('fileQueued',function(file){
					upload_layer_tip = top.layer.load(1, {
					  shade: [0.1,'#000'] //0.1透明度的白色背景
					});
				});
				uploader.on('uploadSuccess',function(file,response){
					top.layer.close(upload_layer_tip);
					if(response.error == 0){
						var img = new Image();
						img.src = response.url;
						// 如果图片被缓存，则直接返回缓存数据
						if(img.complete){
							console.log(img.width, img.height);
							$('#img_preview_good').css('margin-top','-'+parseInt((($('#img_preview_good').width()*img.height/img.width)-$('#img_preview_good_box').height())/2)+'px').data({good_width:img.width,good_height:img.height});
						}else{
							img.onload = function(){
								console.log(img.width,img.height);
								$('#img_preview_good').css('margin-top','-'+parseInt((($('#img_preview_good').width()*img.height/img.width)-$('#img_preview_good_box').height())/2)+'px').data({good_width:img.width,good_height:img.height});
							}
						}
						$('#img_preview_good').attr('src',response.url);
					}else{
						alert(response.message);
					}
				});

				uploader.on('uploadError', function(file,reason){
					$('.loading'+file.id).remove();
					alert('上传失败！请重试。');
				});
				
				
				$('#myform').submit(function(){
					if(!$('#img_preview_good').data('good_width')){
						top.layer.msg('请上传商品图片');
						return false;
					}
					var postData = $('.bgcolorTd label.active').data();
					postData.good_img_width = $('#img_preview_good').data('good_width');
					postData.good_img_height = $('#img_preview_good').data('good_height');
					postData.good_img_src = $('#img_preview_good').attr('src');
					postData.big_name = $.trim($('#big_name').val());
					postData.small_name = $.trim($('#small_name').val());
					postData.store_id = '{pigcms{$_GET.store_id}';
					
					if(postData.big_name.length < 4 || postData.big_name.length > 8){
						top.layer.msg('主标题限制4-8个字，当前字数：'+postData.big_name.length);
						return false;
					}
					if(postData.small_name.length < 4 || postData.small_name.length > 16){
						top.layer.msg('副标题限制4-16个字，当前字数：'+postData.small_name.length);
						return false;
					}
					
					//主标题颜色值数组
					var a = postData.big_color;
					if(a.substr(0,1)=="#") a=a.substring(1);
					a=a.toLowerCase();
					b=new Array();
					for(x=0;x<3;x++){
						b[0]=a.substr(x*2,2)
						b[3]="0123456789abcdef";
						b[1]=b[0].substr(0,1)
						b[2]=b[0].substr(1,1)
						b[20+x]=b[3].indexOf(b[1])*16+b[3].indexOf(b[2])
					}
					postData.big_color_arr = [b[20],b[21],b[22]];
					
					//副标题颜色值数组
					var a = postData.small_color;
					if(a.substr(0,1)=="#") a=a.substring(1);
					a=a.toLowerCase();
					b=new Array();
					for(x=0;x<3;x++){
						b[0]=a.substr(x*2,2)
						b[3]="0123456789abcdef";
						b[1]=b[0].substr(0,1)
						b[2]=b[0].substr(1,1)
						b[20+x]=b[3].indexOf(b[1])*16+b[3].indexOf(b[2])
					}
					postData.small_color_arr = [b[20],b[21],b[22]];
					
					console.log(postData);
					
					$('#submit').prop('disabled',true).html('生成图片中...');
					$.post($('#myform').attr('action'),postData,function(result){
						if(result.status == 1){
							parent.fitment_header_bgcolor = $('#shop_fitment_color').val();
							parent.layer.alert('生成成功',{
								end:function(index){
									console.log(result.info);
									parent.frames[parent.subject_win_name].build_image_save(result.info);
									parent.layer.close(index);
									var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
									parent.layer.close(index); //再执行关闭
								}
							});
						}else{
							parent.layer.alert(result.info);
							$('#submit').prop('disabled',false).html('生成图片');
						}
					});
					return false;
				});
			});
		</script>
	</body>
</html>