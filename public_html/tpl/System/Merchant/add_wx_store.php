<include file="Public:header"/>
	<style>
		.sub_mch{
			display:none
		}
	</style>
	<form id="myform" method="post" action="{pigcms{:U('Merchant/add_wx_store')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		
		
			<tr>
				<th width="160">一级类目</th>
				<td>
				<select id="first_catid"  name="first_catid" class="valid category_list"></select>
				<input type="hidden" id="second_catid_name" name="second_catid_name">
				<input type="hidden" id="first_catid_name" name="first_catid_name">
				</td>
			</tr>
			<tr>
				<th width="160">二级类目</th>
				<td>
				<select id="second_catid"   name="second_catid" class="valid category_list"></select>
		
				</td>
			</tr>
	
			<tr id="qualification_list" class="hide">
				<th width="160">类目相关证件</th>
				<td><input type="text"  style="width:200px;" name="qualification_list" class="input input-image" value=""  validate="required:true" readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage"  style="background: #87b87f!important;border-color: #87b87f;color:#fff;">上传图片</a>
				<input type="hidden" class="real_pic" name="qul_pic">
				</td>
				
				
			
						
			</tr>
			<tr>
				<th width="160">头像</th>
				<td><input type="text"  style="width:200px;" name="headimg_mediaid" class="input input-image" value=""  validate="required:true" readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage"  style="background: #87b87f!important;border-color: #87b87f;color:#fff;">上传图片</a>
				<input type="hidden" class="real_pic" name="head_pic">
				</td>
						
			</tr>
			
			
			<tr>
				<th width="80">昵称</th>
				<td><input type="text" class="input fl" name="nickname" size="20" /></td>
			</tr>
			
			<tr>
				<th width="80">介绍</th>
				<td><input type="text" class="input fl" name="intro" size="20" ></td>
			</tr>
			
			<tr>
				<th width="80">组织机构代码</th>
				<td><input type="text" class="input fl" name="org_code" size="20" /></td>
			</tr>
		
			
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
	<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
	<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>

	<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
	<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
	<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
	<script type="text/javascript">
		KindEditor.ready(function(K){
				var site_url = "{pigcms{$config.site_url}";
				var editor = K.editor({
					allowFileManager : true
				});
				$('.J_selectImages').click(function(){
					var upload_file_btn = $(this);
					editor.uploadJson = "{pigcms{:U('Config/ajax_upload_wx_media')}";
					editor.loadPlugin('image', function(){
						editor.plugin.imageDialog({
							showRemote : false,
							clickFn : function(url, title, width, height, border, align) {
								
								url = url.substring(1,url.length)
								var orignal_url = upload_file_btn.siblings('.input-image').val();
								count_url = orignal_url.split(';');
								if(count_url.length>5){
									alert('最多只能穿5个材料')
								}else{
									orignal_url+=url+';';
									upload_file_btn.siblings('.input-image').val(orignal_url)
								}
								editor.hideDialog();
							}
						});
					});
				});
				
				$('.J_selectImage').click(function(){
					var upload_file_btn = $(this);
					editor.uploadJson = "{pigcms{:U('Config/ajax_upload_wx_media')}";
					editor.loadPlugin('image', function(){
						editor.plugin.imageDialog({
							showRemote : false,
							clickFn : function(url, title, width, height, border, align) {
								alert(title)
								url = url.substring(1,url.length)
								var orignal_url = upload_file_btn.siblings('.input-image').val(url);
								 upload_file_btn.siblings('.real_pic').val(title);
								editor.hideDialog();
							}
						});
					});
				});

			});
	</script>
	<script>
		var category = $.parseJSON('{pigcms{$category_list}')
		
		add_option_html(get_domid(3),category)
		add_option_html(get_domid(4),category[0]['childrens'])
		function add_option_html(domid,area_list){
			html ='';
			for( x in area_list){
				if(domid=='second_catid'){
					html += '<option value="'+area_list[x]['id']+'" data-id='+area_list[x]['key']+' data-name="'+area_list[x]['fullname']+'" data-sensitive_type="'+area_list[x]['sensitive_type']+'">'+area_list[x]['fullname']+'</option>'
				}else{
					html += '<option value="'+area_list[x]['id']+'" data-id='+area_list[x]['key']+' data-name="'+area_list[x]['fullname']+'">'+area_list[x]['fullname']+'</option>'
				}
			}
			if(area_list[x]['sensitive_type']==1){
				$('#qualification_list').show();
			}else{
				$('#qualification_list').hide();
			}
			$('#'+domid+'_name').val(area_list[0].fullname)
			$("#"+domid).html(html)
		}



		function get_domid(type){
			if(type==0){
				domid = 'province'
			}else if(type==1){
				domid = 'city'
			}else if(type==2){
				domid = 'district'
			}else if(type==3){
				domid = 'first_catid'
			}else if(type==4){
				domid = 'second_catid'
			}
			return domid;
		}
		$(function(){
			$('.category_list').change(function(){
				var name = $(this).attr('name');
				var index = $(this).find('option:selected').data('id')
				var cate_name = $(this).find('option:selected').data('name')
				var sensitive_type = $(this).find('option:selected').data('sensitive_type')
				console.log(cate_name);
				if(name=='first_catid'){
					$('#first_catid_name').val(cate_name)
					add_option_html(get_domid(4),category[index]['childrens'])
				}else if(name="second_catid"){
					if(sensitive_type==1){
						$('#qualification_list').show();
					}else{
						$('#qualification_list').hide();
					}
					$('#first_catid_name').val(cate_name)
				}
			})
		});
	</script>
	
<include file="Public:footer"/>