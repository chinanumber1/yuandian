<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="//apps.bdimg.com/libs/bootstrap/3.3.4/css/bootstrap.min.css">
	<script type="text/javascript" src="//apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
	<script type="text/javascript" src="/static/layer/layer.js"></script>
	<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
	<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
	<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
</head>
<body>
<div class="container">
	<input type="hidden" id="yellow_id" value="{pigcms{$yellow_id}" />
	<input type="hidden" id="info_id" value="{pigcms{$custom_info.id}" />

	<div class="form-horizontal" style="margin-top:10px;">
	  <div class="form-group">
	    <label class="col-xs-3 control-label">自定义标题一：</label>
	    <div class="col-xs-9" style="margin-left: -60px;">
	        <input type="text" class="form-control" id="title1" value="{pigcms{$custom_info.title1}">
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-xs-3 control-label">自定义内容一：</label>
	    <div class="col-xs-9" style="margin-left: -60px;">
	    	<textarea id="msg1" style="width: 480px;">{pigcms{$custom_info.msg1}</textarea>
	    </div>
	  </div>

	  <div class="form-group">
	    <label class="col-xs-3 control-label">自定义标题二：</label>
	    <div class="col-xs-9" style="margin-left: -60px;">
	        <input type="text" class="form-control" id="title2" value="{pigcms{$custom_info.title2}">
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-xs-3 control-label">自定义内容二：</label>
	    <div class="col-xs-9" style="margin-left: -60px;">
	    	<textarea id="msg2" style="width: 480px;">{pigcms{$custom_info.msg2}</textarea>
	    </div>
	  </div>

	  <div class="form-group">
	    <label class="col-xs-3 control-label">自定义标题三：</label>
	    <div class="col-xs-9" style="margin-left: -60px;">
	        <input type="text" class="form-control" id="title3" value="{pigcms{$custom_info.title3}">
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-xs-3 control-label">自定义内容三：</label>
	    <div class="col-xs-9" style="margin-left: -60px;">
	    	<textarea id="msg3" style="width: 480px;">{pigcms{$custom_info.msg3}</textarea>
	    </div>
	  </div>

	  <div class="form-group">
	    <label class="col-xs-3 control-label">自定义标题四：</label>
	    <div class="col-xs-9" style="margin-left: -60px;">
	        <input type="text" class="form-control" id="title4" value="{pigcms{$custom_info.title4}">
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-xs-3 control-label">自定义内容四：</label>
	    <div class="col-xs-9" style="margin-left: -60px;">
	    	<textarea id="msg4" style="width: 480px;">{pigcms{$custom_info.msg4}</textarea>
	    </div>
	  </div>

	  <div class="form-group">
	    <label class="col-xs-3 control-label">自定义标题五：</label>
	    <div class="col-xs-9" style="margin-left: -60px;">
	        <input type="text" class="form-control" id="title5" value="{pigcms{$custom_info.title5}">
	    </div>
	  </div>
	  <div class="form-group">
	    <label class="col-xs-3 control-label">自定义内容五：</label>
	    <div class="col-xs-9" style="margin-left: -60px;">
	    	<textarea id="msg5" style="width: 480px;">{pigcms{$custom_info.msg5}</textarea>
	    </div>
	  </div>

	  <div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10">
	      <button class="btn btn-primary" onclick="save()">提交</button>
	    </div>
	  </div>

	</div>
</div>
</body>
</html>
<script type="text/javascript">

KindEditor.ready(function(K){
	var editor = K.editor({
		allowFileManager : true
	});

	// 初始化信息编辑器
	kind_editor_msg1 = K.create("#msg1",{
		uploadJson: "{pigcms{:U('Yellow/ajax_upload_pic')}",
		width:'480px',
		height:'200px',
		resizeType : 1,
		allowPreviewEmoticons:false,
		allowImageUpload : true,
		filterMode: true,
		items : [
			'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist', '|', 'emoticons', 'image', 'link'
		]
	});
	
	kind_editor_msg2 = K.create("#msg2",{
		uploadJson: "{pigcms{:U('Yellow/ajax_upload_pic')}",
		width:'480px',
		height:'200px',
		resizeType : 1,
		allowPreviewEmoticons:false,
		allowImageUpload : true,
		filterMode: true,
		items : [
			'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist', '|', 'emoticons', 'image', 'link'
		]
	});
	kind_editor_msg3 = K.create("#msg3",{
		uploadJson: "{pigcms{:U('Yellow/ajax_upload_pic')}",
		width:'480px',
		height:'200px',
		resizeType : 1,
		allowPreviewEmoticons:false,
		allowImageUpload : true,
		filterMode: true,
		items : [
			'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist', '|', 'emoticons', 'image', 'link'
		]
	});
	kind_editor_msg4 = K.create("#msg4",{
		uploadJson: "{pigcms{:U('Yellow/ajax_upload_pic')}",
		width:'480px',
		height:'200px',
		resizeType : 1,
		allowPreviewEmoticons:false,
		allowImageUpload : true,
		filterMode: true,
		items : [
			'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist', '|', 'emoticons', 'image', 'link'
		]
	});
	kind_editor_msg5 = K.create("#msg5",{
		uploadJson: "{pigcms{:U('Yellow/ajax_upload_pic')}",
		width:'480px',
		height:'200px',
		resizeType : 1,
		allowPreviewEmoticons:false,
		allowImageUpload : true,
		filterMode: true,
		items : [
			'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist', '|', 'emoticons', 'image', 'link'
		]
	});


});

// 保存
function save(){
	kind_editor_msg1.sync();
	kind_editor_msg2.sync();
	kind_editor_msg3.sync();
	kind_editor_msg4.sync();
	kind_editor_msg5.sync();

	var data = new Object();
	data.yellow_id = $('#yellow_id').val();
	data.info_id = $('#info_id').val();
	data.title1 = $.trim($('#title1').val());
	data.msg1 = $.trim($('#msg1').val());
	data.title2 = $.trim($('#title2').val());
	data.msg2 = $.trim($('#msg2').val());
	data.title3 = $.trim($('#title3').val());
	data.msg3 = $.trim($('#msg3').val());
	data.title4 = $.trim($('#title4').val());
	data.msg4 = $.trim($('#msg4').val());
	data.title5 = $.trim($('#title5').val());
	data.msg5 = $.trim($('#msg5').val());

	if(data.title1 == '' && data.msg1 == '' && data.title2 == '' && data.msg2 == '' && data.title3 == '' && data.msg3 == '' && data.title4 == '' && data.msg4 == '' && data.title5 == '' && data.msg5 == ''){
		layer.alert('请先填写自定义内容');
		return;
	}

	$.post("{pigcms{:U('Yellow/save_custom_info')}",data,function(response){
		if(response.code>0){
			layer.alert(response.msg);
		}else{
			layer.msg(response.msg);
			setTimeout(function(){window.parent.close_iframe();},1000);
		}
	},'json');
}
</script>