<!doctype html>
<html>
<head>
	<include file="header"/>
</head>
<body>
	<div class="weui-pull-to-refresh__layer">
		<div class='weui-pull-to-refresh__arrow'></div>
		<div class='weui-pull-to-refresh__preloader'></div>
		<div class="down">下拉刷新</div>
		<div class="up">释放刷新</div>
		<div class="refresh">正在刷新</div>
	</div>
	<link rel="stylesheet" href="{pigcms{$static_path}classifynew/css/cropper.css"/>
	<style>.x_header{z-index:10}.weui-cells_form .weui-cell__ft{font-size:17px}</style>
	<header class="x_header bgcolor_11 cl f15">
		<a class="z f14" href="javascript:window.history.go(-1);"><i class="iconfont icon-fanhuijiantou w15"></i>返回</a>
		<a class="y sidectrl " href="{pigcms{:U('fabu')}">切换</a>  
		<div class="navtitle">{pigcms{$page_title}</div>
	</header>
	<div class="x_header_fix" ></div>
	<div class="page__bd">
		<form action="{pigcms{:U('Classify/fabuTosave',array('cid'=>$cid))}" method="post" id="form" enctype="multipart/form-data">
			<input type="hidden" name="cid" value="{pigcms{$cid}" />
			<input type="hidden" name="fcid" value="{pigcms{$fcid}" />
			<input type="hidden" name="pfcid" value="{pigcms{$_GET.pfcid}" />
			<div class="weui-cells__title">
				【特别提醒】请认真全面填写信息，以便大家查阅。<br/>
				【免责声明】发布的所有信息，平台只负责发布、展示，与平台本身无关，不承担任何责任。
			</div>
			<div class="weui-cells weui-cells_form">
                <div class="weui-cell">
					<div class="weui-cell__bd">
						<textarea name="description" class="weui-textarea" placeholder="请填写信息的内容，越详细越好" rows="4"></textarea>
					</div>
				</div>
				<div class="weui-cell">
					<div class="weui-cell__bd">
						<div class="weui-uploader">
							<div class="weui-uploader__hd">
								<p class="weui-uploader__title">上传照片</p>
								<div class="weui-uploader__info">最多上传8张照片</div>
							</div>
							<div class="weui-uploader__bd">
								<ul class="weui-uploader__files" id="uploaderFiles">
								</ul>
								<div class="weui-uploader__input-box">
									<input id="uploaderInput" class="weui-uploader__input" type="file"/>
								</div>
							</div>
						</div>
					</div>
				</div>
            </div>
			<div class="weui-cells weui-cells_form">
				<div class="weui-cell">
					<div class="weui-cell__hd">
						<i class="iconfont icon-wode1 main_color"></i>
					</div>
					<div class="weui-cell__hd"><label class="weui-label">联系人</label></div>
					<div class="weui-cell__bd">
						<input name="lxname" class="weui-input" type="text" placeholder="请填写联系人姓名" value=""/>
					</div>
				</div>
				<div class="weui-cell">
					<div class="weui-cell__hd">
						<i class="iconfont icon-dianhua2 main_color"></i>
					</div>
					<div class="weui-cell__hd"><label class="weui-label">电话号码</label></div>
					<div class="weui-cell__bd">
						<input name="lxtel" class="weui-input" type="tel" placeholder="请填写联系人电话号码" value="{pigcms{$user_session.phone}"/>
					</div>
				</div>
			</div>
			<if condition="!empty($catfield)">
				<div class="weui-cells weui-cells_form">
					<volist name="catfield" id="vv" key="kk">
						<if condition="$vv['type'] eq 1">
							<div class="weui-cell">
								<div class="weui-cell__hd">
									<php>if($vv['iswrite']>0){echo '<strong style="color:red;">*</strong>';}else{echo '&nbsp;';}</php>
								</div>
								<div class="weui-cell__hd"><label class="weui-label">{pigcms{$vv['name']}</label></div>
								<div class="weui-cell__bd">
									<input name="input[{pigcms{$kk}][vv]" class="weui-input" type="<php>if($vv['inarr']==1){echo 'number';}else{echo 'text';}</php>" value="" <php>if($vv['inarr']==1)echo 'onkeyup="value=clearNoNum(this.value)"';</php>/>
									<input name="input[{pigcms{$kk}][tn]"  value="{pigcms{$vv['name']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][unit]"  value="{pigcms{$vv['inunit']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][inarr]"  value="{pigcms{$vv['inarr']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][input]"  value="{pigcms{$vv['input']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][iswrite]"  value="{pigcms{$vv['iswrite']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][isfilter]"  value="{pigcms{$vv['isfilter']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][type]"  value="1"  type="hidden" />
								</div>
								<php>if(($vv['inarr']==1) && !empty($vv['inunit'])){</php>
									<div class="weui-cell__ft">
										{pigcms{$vv['inunit']}
									</div>
								<php>}</php>
							</div>
						<elseif condition="$vv['type'] eq 2"/>
							<div class="weui-cell weui-cell_select weui-cell_select-after">
								<div class="weui-cell__hd">
									<php>if($vv['iswrite']>0){echo '<strong style="color:red;">*</strong>';}else{echo '&nbsp;';}</php>
								</div>
								<div class="weui-cell__hd"><label class="weui-label">{pigcms{$vv['name']}</label></div>
								<div class="weui-cell__bd">
									<select class="weui-select" name="input[{pigcms{$kk}][vv]">
										<volist name="vv['opt']" id="opt">
											<option value="{pigcms{$opt}">{pigcms{$opt}</option>
										</volist>
									</select>
									<input name="input[{pigcms{$kk}][tn]"  value="{pigcms{$vv['name']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][input]"  value="{pigcms{$vv['input']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][iswrite]"  value="{pigcms{$vv['iswrite']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][isfilter]"  value="{pigcms{$vv['isfilter']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][type]"  value="2"  type="hidden" />
								</div>
							</div>
						<elseif condition="$vv['type'] eq 3"/>
							<div class="weui-cell">
								<div class="weui-cell__hd">
									<php>if($vv['iswrite']>0){echo '<strong style="color:red;">*</strong>';}else{echo '&nbsp;';}</php>
								</div>
								<div class="weui-cell__hd"><label class="weui-label">{pigcms{$vv['name']}</label></div>
								<div class="weui-cell__bd">
									<div class="post-tags cl" id="post-typeid" style="padding:5px 0px;">
										<volist name="vv['opt']" id="opt">
											<a class="weui-btn weui-btn_mini weui-btn_default " href="javascript:;" onclick="return setTypeidNew(this);">{pigcms{$opt}</a>
											<input class="none" name="input[{pigcms{$kk}][vv][]" type="checkbox" value="{pigcms{$opt}"/>
										</volist>
                                    </div>
									<input name="input[{pigcms{$kk}][tn]"  value="{pigcms{$vv['name']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][input]"  value="{pigcms{$vv['input']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][iswrite]"  value="{pigcms{$vv['iswrite']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][isfilter]"  value="{pigcms{$vv['isfilter']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][type]"  value="3"  type="hidden" />
								</div>
							</div>
						<elseif condition="$vv['type'] eq 4"/>
							<div class="weui-cell weui-cell_select weui-cell_select-after">
								<div class="weui-cell__hd">
									<php>if($vv['iswrite']>0){echo '<strong style="color:red;">*</strong>';}else{echo '&nbsp;';}</php>
								</div>
								<div class="weui-cell__hd"><label class="weui-label">{pigcms{$vv['name']}</label></div>
								<div class="weui-cell__bd">
									<select class="weui-select" name="input[{pigcms{$kk}][vv]">
										<volist name="vv['opt']" id="opt">
											<option value="{pigcms{$opt}">{pigcms{$opt}</option>
										</volist>
									</select>
									<input name="input[{pigcms{$kk}][tn]"  value="{pigcms{$vv['name']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][input]"  value="{pigcms{$vv['input']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][iswrite]"  value="{pigcms{$vv['iswrite']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][isfilter]"  value="{pigcms{$vv['isfilter']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][type]"  value="4"  type="hidden" />
								</div>
							</div>
						<elseif condition="$vv['type'] eq 5"/>
							<div class="weui-cell">
								<div class="weui-cell__hd">
									<php>if($vv['iswrite']>0){echo '<strong style="color:red;">*</strong>';}else{echo '&nbsp;';}</php>
								</div>
								<div class="weui-cell__hd"><label class="weui-label">{pigcms{$vv['name']}</label></div>
								<div class="weui-cell__bd">
									<textarea name="input[{pigcms{$kk}][vv]" class="weui-textarea" rows="3"></textarea>
									<input name="input[{pigcms{$kk}][tn]"  value="{pigcms{$vv['name']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][input]"  value="{pigcms{$vv['input']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][iswrite]"  value="{pigcms{$vv['iswrite']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][isfilter]"  value="{pigcms{$vv['isfilter']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][type]"  value="5"  type="hidden" />
								</div>
							</div>
						</if>
					</volist>
				</div>
			</if>
			<label class="weui-agree mt10" onclick="$(&quot;#agree__text&quot;).popup();">
				<input id="weuiAgree" type="checkbox" checked="checked" disabled="" readonly="" class="weui-agree__checkbox"/>
				<span class="weui-agree__text">
					阅读并同意<a href="javascript:void(0);">《用户协议条款》</a>
				</span>
			</label>
			<div id="agree__text" class="weui-popup__container">
				<div class="weui-popup__overlay"></div>
				<div class="weui-popup__modal">
					<div class="fixpopuper">
					<article class="weui-article">
						<h1>用户协议条款</h1>
						<section>
							<section>
								<h3>一、服务使用规范：</h3>
								<p>
								</p><p>通过本平台服务平台，您可以按照本平台的规则发布各种生活信息。但所发布的信息不得含有以下内容：</p>
								<p>1) 反对宪法所确定的基本原则，煽动抗拒、破坏宪法和法律、行政法规实施的；<br>
									2) 煽动危害国家安全、泄露国家秘密、颠覆国家政权，推翻社会主义制度的；<br>
									3) 煽动分裂国家、破坏国家统一、损害国家荣誉和民族利益的；<br>
									4) 煽动民族仇恨、民族歧视，破坏民族团结的；<br>
									5) 捏造或者歪曲事实，散布谣言，扰乱社会秩序的；<br>
									6) 进行政治宣传或破坏国家宗教政策、宣扬封建迷信、淫秽、色情、赌博、暴力、凶杀、恐怖、教唆犯罪的；<br>
									7) 公然侮辱他人或者捏造事实诽谤他人的，或者进行其他恶意攻击的；<br>
									8) 损害国家机关信誉的；<br>
									9) 其他违反宪法和法律法规的；</p>
								<p></p>                    </section>
						</section>
					</article>
						<div class="footer_fix"></div>
						<div class="bottom_fix"></div>
					</div>
					<div class="fix-bottom">
						<a class="weui-btn weui-btn_primary close-popup" href="javascript:;">我已阅读并同意此协议</a>
					</div>
				</div>
			</div>
			<div class="fix-bottom" style="position: relative">
				<input type="submit" class="weui-btn weui-btn_primary" name="dosubmit" id="dosubmit" value="确认发布">
			</div>
		</form>
    </div>
	<div id="popctrl" class="weui-popup__container">
		<div class="weui-popup__overlay"></div>
		<div class="weui-popup__modal">
			<div style="height:100vh"><img id="photo"></div>
			<div class="pub_funcbar">
				<a class="weui-btn close-popup weui-btn_primary" data-method="confirm">确定</a>
				<a class="weui-btn close-popup weui-btn_default" data-method="destroy">取消</a>
			</div>
		</div>
	</div>
	
	<div class="masker" style="position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.5);display:none;z-index:1000" onclick='$("#choose_sh").select("close")'></div>
	<div class="cl footer_fix"></div>
	<include file="footer"/>
	<script>
		function clearNoNum(value){
			//清除"数字"和"."以外的字符

			value = value.replace(/[^\d.]/g,"");

			//验证第一个字符是数字而不是
			value = value.replace(/^\./g,"");

			//只保留第一个. 清除多余的
			value = value.replace(/\.{2,}/g,".");
			value = value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");

			//只能输入两个小数
			value = value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');
			return value;
		}
		function setTypeidNew(dom){
			if($(dom).hasClass("tag-on")){
				$(dom).removeClass("tag-on");
				$(dom).next('input').attr('checked',false);
			}else{
				$(dom).addClass("tag-on");
				$(dom).next('input').attr('checked',true);
			}
		}
	</script>
	<script src="{pigcms{$static_path}classifynew/js/cropper.min.js" type="text/javascript"></script>
	<script>
		var photoct = $('#photo');
		var imgpop = $('#popctrl');
		var uploadinput = $('#uploaderInput');
		var max_upload_num = 6;
		var boxer = $('#uploaderFiles');
		$(function () {
			var URL = window.URL || window.webkitURL;
			var blobURL;
			var file;
			uploadinput.on("change", function () {
				photoct.cropper('destroy').cropper({
					minContainerHeight: 320,
					autoCropArea:1
				});
				var files = this.files;
				if (!photoct.data('cropper')) {
					return;
				}
				if (files && files.length) {
					file = files[0];
					if (/^image\/\w+$/.test(file.type)) {
						blobURL = URL.createObjectURL(file);
						photoct.one('built.cropper', function () {
							URL.revokeObjectURL(blobURL);
						}).cropper('reset').cropper('replace', blobURL);
						uploadinput.val('');
						imgpop.popup();
					} else {
						$.toptip('Please choose an image file.', 'error');
					}
				}
			});
			
			$('.pub_funcbar a').each(function () {
				var btn = $(this);
				var mtd = btn.attr('data-method');
				btn.on('click', function () {
					if (mtd == 'destroy') {
					} else if (mtd == 'confirm') {
						result = photoct.cropper('getCroppedCanvas');
						photo = result.toDataURL('image/jpeg');
						var img=photo.split(',')[1];
						img=window.atob(img);
						var ia = new Uint8Array(img.length);
						for (var i = 0; i < img.length; i++) {
							ia[i] = img.charCodeAt(i);
						}
						var bfile = new Blob([ia], {type:"image/jpeg"});


						compress(bfile, function(cmp_photo){
							var img = cmp_photo.split(',')[1];
							img = window.atob(img);
							var ia = new Uint8Array(img.length);
							for (var i = 0; i < img.length; i++) {
								ia[i] = img.charCodeAt(i);
							}
							var blob = new Blob([ia], {type:"image/jpeg"});
							var formdata=new FormData();
							formdata.append('filename','file');
							formdata.append('file',blob);

							$.ajax({
								type: 'post',
								url: "{pigcms{:U('ajaxImgUpload')}",
								data :  formdata,
								processData : false,
								contentType : false,
								success: function (data) {
									if(null==data){ tip_common('error|'+ERROR_TIP); return false;}
									if(data.indexOf('success')!==-1){
										var html = '<li class="weui-uploader__file weui-uploader__file_status" style="background-image:url(' + cmp_photo + ')"><input type="hidden" name="inputimg[]" value="' + data.split('|')[1] + '"/><div class="weui-uploader__file-content"><i class="weui-icon-warn iconfont icon-shanchu"></i></div></li>';
										$('#loadingimg').remove();
										boxer.append(html);
									} else {
										$('#loadingimg').remove();
										tip_common(data);
									}
								}
							});
						});

						boxer.append('<li id="loadingimg" class="weui-uploader__file weui-uploader__file_status"><div class="weui-uploader__file-content"><img src="{pigcms{$static_path}classifynew/images/loading.gif"/></div></li>');


					} else {
						var opt = btn.attr('data-option');
						photoct.cropper(mtd, opt);
					}
				});
			});
		});
		function compress(file, callback){
			var reader = new FileReader();

			reader.onload = function (e) {

				var image = $('<img/>');
				image.on('load', function () {
					var square = 640;
					var canvas = document.createElement('canvas');


					if (this.width > this.height) {
						canvas.width = Math.round(square * this.width / this.height);
						canvas.height = square;
					} else {
						canvas.height = Math.round(square * this.height / this.width);
						canvas.width = square;
					}

					var context = canvas.getContext('2d');
					context.clearRect(0, 0, square, square);
					var imageWidth = canvas.width;
					var imageHeight = canvas.height;
					var offsetX = 0;
					var offsetY = 0;
					context.drawImage(this, offsetX, offsetY, imageWidth, imageHeight);
					var data = canvas.toDataURL('image/jpeg', 0.8);
					console.log([imageWidth,imageHeight]);
					callback(data);
				});

				image.attr('src', e.target.result);
			};

			reader.readAsDataURL(file);
		}
		function sms_time() {
			var o = $('#vcodebtn');
			if (SMS_WAIT_TIME <= 0) {
				o.removeAttr("disabled");
				o.html("获取验证码");
				SMS_WAIT_TIME = 120;
			} else {
				o.attr("disabled", true);
				o.html("重发(" + SMS_WAIT_TIME + ")");
				SMS_WAIT_TIME--;
				setTimeout(function() {
					sms_time();
				}, 1000);
			}
		}
			$('#vcode_tel').on('keyup', function () {
			var vcode_btn = $('#vcode_btn'), vcode_area = $('#vcode_area'), that =$(this);
			var ombi = that.data('old'), omval = that.val();
			if(omval == ombi){
				vcode_btn.addClass('w0');
				vcode_area.addClass('none');
			}else{
				vcode_btn.removeClass('w0');
				vcode_area.removeClass('none');
			}
		});

		if($('.openlocation').length>0){
		PUB_VARID = $('.openlocation').data('id');
		setTimeout(function () {
			IGNORETIP = 1;
			he_getlocation(setPoint);
			if(typeof wx!=='undefined'){
				wx.ready(function () {
					he_getlocation(setPoint);
				});
			}
		}, 300);
	}
	function dosubmit_s() {
		if(!$('#multip').val() || $('#multip').val() == ''){
			$.alert('请选择发布规格');
			return false;
		}
		return true;
	}
	</script>
</body>
</html>