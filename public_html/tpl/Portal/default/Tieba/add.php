<include file="Public/header" />
<link href="{pigcms{$static_path}tieba/css/_fabu.css" type="text/css" rel="stylesheet" />
<link href="{pigcms{$static_path}tieba/css/calendar.css" rel="stylesheet" type="text/css">
<link href="{pigcms{$static_path}tieba/css/dragCode.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}layer/layer.js"></script>
<!-- <script src="{pigcms{$static_public}js/jquery-1.9.1.min.js"></script> -->
<script src="{pigcms{$static_public}js/ajaxfileupload.js"></script>

<div class="content w-1200 clearfix">
	<style type="text/css">
.edit_box li span.span_1 { line-height:32px;}
.edit_box li .textInt { font-size:14px; width:889px; border-radius:4px; padding:7px 5px; border:1px solid #d4d4d4; outline:0;}
.edit_box li select { border:1px solid #d4d4d4; border-radius:4px; padding:5px; font-size:14px; margin:0 5px 0 0; outline:0;}
</style>
	<div class="site_crumb clearfix">
		<span>当前位置：</span>
		<a href="{pigcms{:U('Portal/Index/index')}">首页</a>
		<a href="{pigcms{:U('Tieba/index')}">贴吧</a>
		<span class="cur_tit">发帖</span>
	</div>
	<!-- 主体 -->
	
		<div id="content">
			<div class="fbd">
				<div class="edit_box">
					<ul>
						<form method="post" action="{pigcms{:U('Tieba/add')}" id="myformFabu" enctype="multipart/form-data">

							<li>
								<span class="span_1">帖子类型：</span>
								<span class="span_2" style="font-size:12px; color:#888;">
									<input type="radio" name="type"  checked="checked" value="0"> 图文
									<input type="radio" name="type" value="1"> 视频
								</span>
							</li>

							<script>
								$('input:radio[name="type"]').click(function(){
									var val=$('input:radio[name="type"]:checked').val();
									if(val == 0){
										$("#imageText").css('display','block');
										$("#video").css('display','none');
									}else{
										$("#imageText").css('display','none');
										$("#video").css('display','block');
									}
								})
							</script>

							<li>
								<span class="span_1">版块和主题：</span>
								<span class="span_2">
									<select name='plate_id' id='plate_id' >
										<option value=''>::请选择贴吧板块::</option>
										<volist name="plateList" id="vo">
											<option value='{pigcms{$vo.plate_id}' >{pigcms{$vo.plate_name}</option>
										</volist>
									</select>
								</span>
							</li>
							<li>
								<span class="span_1">帖子标题：</span>
								<span class="span_2" style="font-size:12px; color:#888;">
									<input type="text" class="textInt" id="title" size="100" maxlength="50" name="title" />
								</span>
							</li>

							<li style="display: block;" id="imageText">
								<span class="span_1">帖子内容：</span>
								<textarea id="msg" name="content" style="width: 900px; height: 500px;">{pigcms{$article.msg|htmlspecialchars_decode}</textarea>
							</li>

							<li style="display: none;" id="video">
								<span class="span_1">上传视频：</span>
								<input style="width: 20%;height: 20px;" class="textInt" type="text" value="" id="videoUrl" name="videoUrl" >
                                <input type="file" id="imgUploadFile" onchange="imgUpload()" name="videoFile" value="选择文件上传"/>
							</li>
							

							<script>
								$("#upimgFileBtn").click(function(){
								    $("#imgUploadFile").click();
								})

								function imgUpload(){

									var index = layer.load(1, {
							          shade: [0.7,'#000'] //0.1透明度的白色背景
							        });

								    $.ajaxFileUpload({
								        url:"{pigcms{:U('Tieba/ajax_upload_file')}",
								        secureuri:false,
								        fileElementId:'imgUploadFile',
								        dataType: 'json',
								        success: function (data) {

								        	console.log(data);
                							setTimeout(function(){layer.closeAll();},1000);

								            if(data.error == 2){
								            	$("#videoUrl").val(data.url);
								            }else{
								                layer.open({
								                    content: data.msg
								                    ,btn: ['确定']
								                });
								            }
								        }
								    }); 
								}
							</script>
							<script>
							
							</script>

							<li>
								<span class="span_1">验证码：</span>
								<span style="width: 80px; float: left;"><input class="textInt" type="text" id="verify" style="width:60px;" maxlength="4" name="verify"/></span>
								<span style="float: left;">
									<img src="{pigcms{:U('Tieba/verify')}" id="verifyImg" onclick="fleshVerify('{pigcms{:U('Tieba/verify')}')" title="刷新验证码" alt="刷新验证码"/>
                                    <a href="javascript:fleshVerify('{pigcms{:U('Tieba/verify')}')" id="fleshVerify">刷新验证码</a>
								</span>
							</li>
						</form>
							<li class="last">
								<span class="span_1">&nbsp;</span>
								<span class="span_2">
									<button onclick="fabiao()" class="fabu_btn">发 布</button>
								</span>
							</li>
						
					</ul>
				</div>
			</div>
		</div>
		<!-- 主体 结束 -->
	
</div>

<include file="Public/footer" />
<script>
    function fleshVerify(url){
        var time = new Date().getTime();
        $('#verifyImg').attr('src',url+"&time="+time);
    }
</script>
<script type="text/javascript">
	KindEditor.ready(function(K){
		var editor = K.editor({
			allowFileManager : true
		});

		K('#J_selectImage').click(function(){
			editor.uploadJson = "{pigcms{:U('Building/ajax_upload_pic')}";
			editor.loadPlugin('image', function(){
				editor.plugin.imageDialog({
					showRemote : false,
					imageUrl : K('#course_pic').val(),
					clickFn : function(url, title, width, height, border, align) {
							var thumb_html = '<div class="col-sm-1 imgs"><img src="'+url+'" /><a href="javascript:;" onclick="del_img(this)">&times</a></div>';
							$('#thumb_img').html(thumb_html);
							$('#J_selectImage').hide();
						editor.hideDialog();
					}
				});
			});
		});

		// 初始化信息编辑器
		kind_editor_msg = K.create("#msg",{
			uploadJson: "{pigcms{:U('Tieba/ajax_upload_pic')}",
			width:'900px',
			height:'500px',
			resizeType : 0,
			allowPreviewEmoticons:false,
			allowImageUpload : true,
			filterMode: true,
			items : [
				'source','fullscreen','fontsize','bold','justifyleft', 'justifycenter', 'justifyright', '|', 'emoticons', 'image'
			]


// 			['source', '|', 'fullscreen', 'undo', 'redo', 'print', 'cut', 'copy', 'paste',
// 'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
// 'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
// 'superscript', '|', 'selectall', '-',
// 'title', 'fontname', 'fontsize', '|', 'textcolor', 'bgcolor', 'bold',
// 'italic', 'underline', 'strikethrough', 'removeformat', '|', 'image',
// 'flash', 'media', 'advtable', 'hr', 'emoticons', 'link', 'unlink', '|', 'about']


			

		});
	});

	function fabiao(){
	    kind_editor_msg.sync();
	    var title = $("#title").val();
	    var uid = "{pigcms{$user_session['uid']}";
	    if(!uid){ 
	        layer.msg('请先登录，然后再进行发帖',{time:1500},function(){
	        	window.location.href = "{pigcms{$config.site_url}"+'/index.php?g=Index&c=Login&a=index';
	        }); 
	        return false;
	    }
	    if(!title){
	        layer.msg('请填写标题');
	        return false;
	    }
	    var verify = $("#verify").val();
	    if(!verify){
	        layer.msg('请填验证码');
	        return false;
	    }
	    $("#myformFabu").submit();
	}
</script>