<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 免单编辑</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
		<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
		
	</head>
	<body>
		<form id="myform" method="post" action="{pigcms{:U('Sub_card/join_card')}" frame="true" refresh="true" autocomplete="off" onsubmit="return false;" >
		
		<textarea id="editor_id" name="content" style="width:99%;height:390px;">
			
		</textarea>	
		</form>
		<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
		<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
		<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
		<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
		<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>

		<script type="text/javascript">
		$(function(){


			$('#editor_id').val(window.top.desc_txt)
			artDialog.data("html", $('#editor_id').val());  
			art.dialog.data("html_empty", 3); 
			
			$('#editor_id').change(function(){
				console.log($(this).val())
			})
		});
		KindEditor.ready(function(K) {
			
			window.editor = K.create('#editor_id',{
				items:[
						'source', '|', 'undo', 'redo', '|',  'cut', 'copy', 'paste',
						'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
						'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
						'superscript', 'clearhtml', 'quickformat', 'selectall', '/', '|', 
						'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
						'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat'
				],
				 afterBlur: function(){ 
					var html='';
					html = editor.html(); 
					console.log('-------------------:'+editor.html())
					artDialog.data("html", html);  
					if(html==''){
						art.dialog.data("html_empty", 1);  
					}else{
						art.dialog.data("html_empty", 2);  
					}
					console.log(html)
						
					 //this.sync('#editor_id');
				 }
			});
        });
		//KindEditor.sync();
		</script>
		<script>
			
			
			
		</script>
	</body>
</html>