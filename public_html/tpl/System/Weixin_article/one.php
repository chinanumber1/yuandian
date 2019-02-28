<include file="Public:header"/>
	<div class="mainbox">
		<div id="nav" class="mainnav_title">
			<a href="{pigcms{:U('index')}">图文资料</a>
			<a href="{pigcms{:U('one')}" class="on">添加图文资料</a>
		</div>
		<form method="post" id="myform" action="{pigcms{:U('one')}" refresh="true" >
			<input type="hidden" class="input-text" name="system_menu" value=""/>
			<table cellpadding="0" cellspacing="0" class="table_form" width="100%">
				<tr>
					<th  width="120">标题：</td>
					<input type="hidden" value="{pigcms{$pigcms_id}" name="pigcms_id" />
					<input type="hidden" value="{pigcms{$image_text['pigcms_id']}" name="thisid" />
					<td><input type="text" class="input-text" id="title" name="title" value="{pigcms{$image_text['title']}" /></td>
				</tr>
				<tr>
					<th  width="120">作者：</th>
					<td><input type="text" class="input-text"  name="author" value="{pigcms{$image_text['author']}" validate="required:true" /></td>
				</tr>
				
				<tr>
					<th  width="120">封面图：</th>
					<td><a href="javascript:void(0)" class="btn btn-sm btn-success" id="J_selectImage">上传图片</a></td>
				</tr>
				
				<tr>
					<th  width="120">图片预览：</th>
					<input type="hidden" name="cover_pic" id="cover_pic" value="{pigcms{$image_text['cover_pic']}"/>
					<td>
					
					<div id="upload_pic_box">
						<ul id="upload_pic_ul">
							<if condition="$image_text['cover_pic']">
							<li class="upload_pic_li">
								<img src="{pigcms{$image_text['cover_pic']}"/><br/>
								<a href="#" onclick="deleteImage('{pigcms{$image_text['cover_pic']}',this);return false;">[ 删除 ]</a>
							</li>
							</if>
						</ul>
						<if condition="$image_text['cover_pic']">
						<label>
							<input name="is_show" value="1" type="checkbox" class="ace" <if condition="$image_text['is_show']">checked</if>>
							<span class="lbl" style="z-index: 1">封面图片显示在正文中</span>
						</label>
						</if>
					</div>				
					</td>		
				</tr>
				<tr>
					<th  width="120">摘要：</th>
					<td><textarea  class="input-text" id="digest" name="digest" >{pigcms{$image_text['digest']}</textarea></td>
				</tr>
				<tr>
					<th>正文：</th>
					<td><textarea class="input-text" id="content" name="content" style="width: 300px; height: 150px; display: none;">{pigcms{$image_text['content']|htmlspecialchars}</textarea></td>
				</tr>
				<tr>
					<th>外链：</th>
					<td><input class="input-text" name="url" id="url" type="text" value="{pigcms{$image_text['url']}"/>　
					<a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url',0)" data-toggle="modal">从功能库选择</a></td>
				</tr>
				<!--tr>
					<th>所属类别：</th>
					<td><input class="input-text" name="classname" id="classname" type="text" value="{pigcms{$image_text['classname']}"/>　
					<input class="input-text" name="classid" id="classid" type="hidden" value="{pigcms{$image_text['classid']}"/>
					<a href="javascript:void(0);" onclick="editClass('classid','classname',0)" class="btn btn-sm btn-success">选择所属分类</a></td>
				</tr-->
				<tr>
					<th>直接跳转外链网址：</th>
					<td>
						<label><input type="radio" name="location" value="1" <if condition="$image_text['location'] eq 1">checked="checked"</if>/>&nbsp;&nbsp;是</label>
						&nbsp;&nbsp;&nbsp;
						<label><input type="radio" name="location" value="0" <if condition="$image_text['location'] eq 0">checked="checked"</if>/>&nbsp;&nbsp;否</label>
						&nbsp;&nbsp;&nbsp;<span class="form_tips">打开页面时直接跳转外链地址，不会显示文章正文，建议在公众号图文回复的文章中开启</span>
					</td>		
				</tr>
			</table>
			<div class="btn">
				<input TYPE="submit" id="submit" name="dosubmit" value="提交" class="button" />
				<input type="reset" value="取消" class="button" />
			</div>
		</form>
	</div>

<style>
input.ke-input-text {
background-color: #FFFFFF;
background-color: #FFFFFF!important;
font-family: "sans serif",tahoma,verdana,helvetica;
font-size: 12px;
line-height: 24px;
height: 24px;
padding: 2px 4px;
border-color: #848484 #E0E0E0 #E0E0E0 #848484;
border-style: solid;
border-width: 1px;
display: -moz-inline-stack;
display: inline-block;
vertical-align: middle;
zoom: 1;
}
.form-group>label{font-size:12px;line-height:24px;}
#upload_pic_box{margin-top:20px;height:60px;}
#upload_pic_box .upload_pic_li{width:130px;float:left;list-style:none;}
#upload_pic_box img{width:100px;height:70px;}

.small_btn{
margin-left: 10px;
padding: 6px 8px;
cursor: pointer;
display: inline-block;
text-align: center;
line-height: 1;
letter-spacing: 2px;
font-family: Tahoma, Arial/9!important;
width: auto;
overflow: visible;
color: #333;
border: solid 1px #999;
-moz-border-radius: 5px;
-webkit-border-radius: 5px;
border-radius: 5px;
background: #DDD;
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFFFFF', endColorstr='#DDDDDD');
background: linear-gradient(top, #FFF, #DDD);
background: -moz-linear-gradient(top, #FFF, #DDD);
background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#FFF), to(#DDD));
text-shadow: 0px 1px 1px rgba(255, 255, 255, 1);
box-shadow: 0 1px 0 rgba(255, 255, 255, .7), 0 -1px 0 rgba(0, 0, 0, .09);
-moz-transition: -moz-box-shadow linear .2s;
-webkit-transition: -webkit-box-shadow linear .2s;
transition: box-shadow linear .2s;
outline: 0;
}
.small_btn:active{
border-color: #1c6a9e;
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#33bbee', endColorstr='#2288cc');
background: linear-gradient(top, #33bbee, #2288cc);
background: -moz-linear-gradient(top, #33bbee, #2288cc);
background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#33bbee), to(#2288cc));
}
</style>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="./static/js/upyun_system.js"></script>
<script type="text/javascript">
var diyVideo = "{pigcms{:U('Weixin_article/diyVideo')}";
var diyTool = "{pigcms{:U('Weixin_article/diytool')}";
var editor;
KindEditor.ready(function(K) {
	editor = K.create('#content', {
		filterMode: false,
		resizeType : 1,
		allowPreviewEmoticons : false,
		allowImageUpload : true,
		uploadJson : '/admin.php?g=System&c=Upyun&a=kindedtiropic',
		items : ['source', 'fontname', 'fontsize','subscript','superscript','indent','outdent','|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline','hr',
		 '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
		'insertunorderedlist','link', 'unlink','image','media','diyTool','diyVideo']
	});
	
	K('#J_selectImage').click(function(){
		if($('.upload_pic_li').size() >= 10){
			alert('最多上传10个图片！');
			return false;
		}
	
		editor.uploadJson = "{pigcms{:U('Config/ajax_upload_pic')}";

		editor.loadPlugin('image', function(){
		
			editor.plugin.imageDialog({
				showRemote : false,
				imageUrl : K('#course_pic').val(),
				clickFn : function(url, title, width, height, border, align) {
					$('#upload_pic_ul').html('<li class="upload_pic_li"><img src="'+url+'"/><br/><a href="#" onclick="deleteImage(\''+title+'\',this);return false;">[ 删除 ]</a></li>');
// 					$('#show_cover_pic').attr('src', url);
					$('#upload_pic_box').find('label').remove();
					$('#upload_pic_box').append('<label><input name="is_show" value="1" type="checkbox" class="ace"><span class="lbl" style="z-index: 1">封面图片显示在正文中</span></label>');
					$('#cover_pic').val(url);
					editor.hideDialog();
				}
			});
			$('.ke-dialog-default.ke-dialog').css('top','200px')
		});

	
	});
	
	$('.ke-dialog-default.ke-dialog').css('top','200px')
});
function deleteImage(path,obj){
	$.post("{pigcms{:U('Config/ajax_del_pic')}",{path:path});
	$(obj).closest('.upload_pic_li').remove();
	$('#upload_pic_box').find('label').remove();
}
</script>
<include file="Public:footer"/>