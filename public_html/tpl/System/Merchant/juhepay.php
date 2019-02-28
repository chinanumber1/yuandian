<include file="Public:header"/>
	<h2>聚合支付配置</h2>
	<form id="myform"  frame="true" refresh="true">
		
		  <table cellpadding="0" cellspacing="0" class="table_form" width="100%" id="tab_mer_pr" style="display: table;">
		   <tbody>
			
			<!--tr>
				<th width="160">聚合支付地址</th>
				<td >
					 <input type="text" class="input-text" name="juhe_pay_url" id="userid" value="{pigcms{$juhe.juhe_pay_url}" size="20"   />
					
				</td>
			</tr-->
		 
			<tr>
				<th width="160">聚合支付UserId</th>
				<td >
					 <input type="text" class="input-text" name="userid" id="userid" value="{pigcms{$juhe.userid}" size="20"   />
					 <input type="hidden" name="mer_id" value="{pigcms{$_GET['mer_id']}"  />
				</td>
			</tr>
			
			<!--tr>
				<th width="160">聚合支付小程序UserId</th>
				<td >
					 <input type="text" class="input-text" name="wx_userid" id="wx_userid" value="{pigcms{$juhe.wx_userid}" size="20"   />
					
				</td>
			</tr>
			
			<tr>
				<th width="160">聚合支付MercId</th>
				<td >
					 <input type="text" class="input-text" name="mercid" id="mercid" value="{pigcms{$juhe.mercid}" size="20"   />
				</td>
			</tr>
			
			<tr>
				<th width="160">聚合支付私钥</th>
				<td >
					<textarea name="private_key" id="private_key" rows="4" cols="75" class="valid">{pigcms{$juhe.private_key|html_entity_decode}</textarea>
					 
				</td>
			</tr>
			
			<tr>
				<th width="160">聚合支付验签秘钥</th>
				<td >
					 <input type="text" class="input-text" name="key_secret" id="key_secret" value="{pigcms{$juhe.key_secret}" size="40"   />
				</td>
			</tr-->
		  
		  
			
		   </tbody>
		  </table>
		 
		<input type="hidden" name="mer_id" value="{pigcms{$_GET['mer_id']}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			
		</table>
		<div class="btn ">
			<input  type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			
		</div>
	</form>
<script>
$(function(){
			$('#dosubmit').click(function(){
				$.post('{pigcms{:U('Merchant/juhepay')}', $('#myform').serialize(), function(data, textStatus, xhr) {
					//console.log(data);
					if(data.status==1){
						window.top.msg(2,data.info,true,2);
					}else{
						window.top.msg(0,data.info,true,2);
					}
					window.top.art.dialog({id:'edit_juhe'}).close();
				});
				
			});
});
			
</script>
	<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
	<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
	<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
	<script type="text/javascript">
		KindEditor.ready(function(K){
			var site_url = "{pigcms{$config.site_url}";
			var editor = K.editor({
				allowFileManager : true
			});
			$('.config_upload_image_btn').click(function(){
				var upload_file_btn = $(this);
				editor.uploadJson = "{pigcms{:U('Config/ajax_upload_pic')}";
				editor.loadPlugin('image', function(){
					editor.plugin.imageDialog({
						showRemote : false,
						clickFn : function(url, title, width, height, border, align) {
							upload_file_btn.siblings('.input-image').val(site_url+url);
							editor.hideDialog();
						}
					});
				});
			});
			$('.config_upload_file_btn').click(function(){
				var upload_file_btn = $(this);
				editor.uploadJson = "{pigcms{:U('Config/ajax_upload_file')}&name="+upload_file_btn.siblings('.input-file').attr('name');
				editor.loadPlugin('insertfile', function(){
					editor.plugin.fileDialog({
						showRemote : false,
						clickFn : function(url, title, width, height, border, align) {
							upload_file_btn.siblings('.input-file').val(url);
							editor.hideDialog();
						}
					});
				});
			});
		});
	</script>
	
	<style>
		.table_form{border:1px solid #ddd;}
		.tab_ul{margin-top:10px;border-color:#C5D0DC;margin-bottom:0!important;margin-left:0;position:relative;top:1px;border-bottom:1px solid #ddd;padding-left:0;list-style:none;}
		.tab_ul>li{position:relative;display:block;float:left;margin-bottom:-1px;}
		.tab_ul>li>a {
			position: relative;
			display: block;
			padding: 10px 15px;
			margin-right: 2px;
			line-height: 1.42857143;
			border: 1px solid transparent;
			border-radius: 4px 4px 0 0;
			padding: 7px 12px 8px;
			min-width: 50px;
			text-align: center;
		}
		.tab_ul>li>a, .tab_ul>li>a:focus {
			border-radius: 0!important;
			border-color: #c5d0dc;
			background-color: #F9F9F9;
			color: #999;
			margin-right: -1px;
			line-height: 18px;
			position: relative;
		}
		.tab_ul>li>a:focus, .tab_ul>li>a:hover {
			text-decoration: none;
			background-color: #eee;
		}
		.tab_ul>li>a:hover {
			border-color: #eee #eee #ddd;
		}
		.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
			color: #555;
			background-color: #fff;
			border: 1px solid #ddd;
			border-bottom-color: transparent;
			cursor: default;
		}
		.tab_ul>li>a:hover {
			background-color: #FFF;
			color: #4c8fbd;
			border-color: #c5d0dc;
		}
		.tab_ul>li:first-child>a {
			margin-left: 0;
		}
		.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
			color: #576373;
			border-color: #c5d0dc #c5d0dc transparent;
			border-top: 2px solid #4c8fbd;
			background-color: #FFF;
			z-index: 1;
			line-height: 18px;
			margin-top: -1px;
			box-shadow: 0 -2px 3px 0 rgba(0,0,0,.15);
		}
		.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
			color: #555;
			background-color: #fff;
			border: 1px solid #ddd;
			border-bottom-color: transparent;
			cursor: default;
		}
		.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
			color: #576373;
			border-color: #c5d0dc #c5d0dc transparent;
			border-top: 2px solid #4c8fbd;
			background-color: #FFF;
			z-index: 1;
			line-height: 18px;
			margin-top: -1px;
			box-shadow: 0 -2px 3px 0 rgba(0,0,0,.15);
		}
		.tab_ul:before,.tab_ul:after{
			content: " ";
			display: table;
		}
		.tab_ul:after{
			clear: both;
		}
	</style>
<include file="Public:footer"/>