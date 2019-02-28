<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-credit-card"></i>
				<a href="{pigcms{:U('Sub_card/index')}">免单套餐</a>
			</li>
			<li class="active">参加套餐</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
				
				<form class="form" method="post" action="" target="_top" enctype="multipart/form-data">
					<table class="table table-striped table-bordered table-hover" style="table-layout:fixed">
					
						<tr>
							<th style="width:11%">参加套餐</th>
							<th style="width:13%">店铺名称</th>
							<th style="width:7%">库存</th>
							<th style="width:8%">是否预约</th>
							<th style="width:20%">描述</th>
							<th style="width:20%">详细描述</th>
							<th style="width:30%">图片（建议尺寸：500*412,最多5张）</th>
							<th style="width:25%">使用有效期</th>
					
						</tr>
						<input type="hidden" name="sub_card_id" value="{pigcms{$_GET.id}"	>
						<volist name="store_list" id="vo">
							<tr>
								<th tyle="width:11%">
									<input type="checkbox" name="store_id[]" <if condition="is_array($store_join_list[$vo['store_id']])">checked="checked"</if> value="{pigcms{$vo.store_id}" <if condition="$store_join_list[$vo['store_id']]['status'] eq 1">onclick="return false;" </if>><if condition="$store_join_list[$vo['store_id']]['status'] eq 1">通过审核不能取消</if>
									<input type="hidden" name="store_name[{pigcms{$vo.store_id}]" value="{pigcms{$vo.name}"	>
								</th>
								<th style="width:13%">
									<b>{pigcms{$vo.name}</b>
								</th>
								<th style="width:6%">
									<input type="text" style="width:95%" class="NumText" name="sku[{pigcms{$vo.store_id}]" value="{pigcms{$store_join_list[$vo['store_id']]['sku']}" >
								</th>
								<th style="width:6%">
									<span class="cb-enable"><label class="cb-enable <if condition="$store_join_list[$vo['store_id']]['appoint'] eq 1">selected</if>"><span>是</span><input type="radio" name="appoint[{pigcms{$vo.store_id}]" value="1" <if condition="$store_join_list[$vo['store_id']]['appoint'] eq 1">checked="checked"</if>/></label></span>
									<span class="cb-disable"><label class="cb-disable <if condition="$store_join_list[$vo['store_id']]['appoint'] eq 0">selected</if>"><span>否</span><input type="radio" name="appoint[{pigcms{$vo.store_id}]" value="0" <if condition="$store_join_list[$vo['store_id']]['appoint'] eq 0">checked="checked"</if>/></label></span>
								</th>
								<th style="width:20%;" >
								
									<input type="text" name="desc[{pigcms{$vo.store_id}]" value="{pigcms{$store_join_list[$vo['store_id']]['desc']}" style="width:95%"	>
									
								</th>
								<th style="width:20%;" class="handle_btn" href="{pigcms{:U('edit_desc')}" id="desc_{pigcms{$i}">
									<content class="desc_txt_detail">
									<if condition="empty($store_join_list[$vo['store_id']]['desc_txt'])">
									编辑描述
									<else />
									{pigcms{$store_join_list[$vo['store_id']]['desc_txt']|html_entity_decode}
									</if>
									</content>
									<input type="hidden" name="desc_txt[{pigcms{$vo.store_id}]" value="{pigcms{$store_join_list[$vo['store_id']]['desc_txt']}"	>
									
								</th>
								<th style="width:30%">
									<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage " style="float:left;margin-right:5px;">上传图片</a>
									<input type="hidden" class="input-image" name="pic_list[{pigcms{$vo.store_id}]" value="{pigcms{$store_join_list[$vo['store_id']]['pic_list']}">
									<ul class="image_list" style="margin: 0 0 10px 28px;padding: 0;display: inline;">
										<if condition="is_array($store_join_list[$vo['store_id']]['pic_lists']) ">
											<volist name="store_join_list[$vo['store_id']]['pic_lists']" id="vv">
												<li  class="upload_pic_li" style="float:left;list-style:none;"><img src="{pigcms{$vv}" style="width:30px;height:30px;margin-right:5px;"><br><a href="javascript:void(0)" onclick="del(this,{pigcms{$i})">[删除]</a></li>
											</volist>
										</if>
									</ul>
								</th>
								<th style="width:25%">
									<input type="text" class="input-text" name="start_time[{pigcms{$vo.store_id}]" style="width:120px;" id="d4311"  value="<if condition="$store_join_list[$vo['store_id']]['start_time'] gt 0">{pigcms{$store_join_list[$vo['store_id']]['start_time']|date="Y-m-d",###}</if>" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>&nbsp;-&nbsp;			   
									<input type="text" class="input-text" name="end_time[{pigcms{$vo.store_id}]" style="width:120px;" id="d4311" value="<if condition="$store_join_list[$vo['store_id']]['end_time'] gt 0">{pigcms{$store_join_list[$vo['store_id']]['end_time']|date="Y-m-d",###}</if>" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
								</th>
							</tr>
						</volist>	
					</table>
					
				
					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-info" type="submit">
								<i class="ace-icon fa fa-check bigger-110"></i>
								保存
							</button>
						</div>
					</div>
					</form>
			</div>
		</div>
	</div>
	

</div>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
		<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
		<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
		<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
		<style>
			a:hover,a:visited{color:#666;}
			.cb-enable, .cb-disable, .cb-enable span, .cb-disable span {
background: url(tpl/System/Static/css/img/form_onoff.png) repeat-x;
display: block;
float: left;
cursor: pointer;
}
.cb-enable .selected {
background-position: 0 -48px;
}
.cb-enable span, .cb-disable span {
font-weight: bold;
line-height: 24px;
background-repeat: no-repeat;
display: block;
}
.cb-enable span {
background-position: left -72px;
padding: 0 10px;
}
.cb-enable .selected span {
background-position: left -120px;
color: #fff;
}
.cb-enable input, .cb-disable input {
display: none;
}
.cb-disable span {
background-position: right -144px;
padding: 0 10px;
}
.cb-disable .selected {
background-position: 0 -24px;
}
.cb-disable .selected span {
background-position: right -168px;
color: #fff;
}
		</style>
		<script type="text/javascript">
		
		var desc_txt ='';
		var desc_before = '';

			KindEditor.ready(function(K){
					var site_url = "{pigcms{$config.site_url}";
					var editor = K.editor({
						allowFileManager : true
					});
					$('.J_selectImage').click(function(){
						
						var upload_file_btn = $(this);
						
						editor.uploadJson = "{pigcms{:U('Config/ajax_upload_pic')}";
						editor.loadPlugin('image', function(){
							editor.plugin.imageDialog({
								showRemote : false,
								clickFn : function(url, title, width, height, border, align) {
									var li_length = upload_file_btn.siblings('.image_list').find('li').length
									
									if(li_length>4){
										alert('最多上传5个图片！');
									}else{
										
										var image_url = upload_file_btn.siblings('.input-image').val();
										if(li_length==0){
											upload_file_btn.siblings('.input-image').val(url);
										}else{	
											upload_file_btn.siblings('.input-image').val(image_url+';'+url);
										}
										var html='<li class="upload_pic_li" style="float:left;list-style:none;"><img src="'+url+'" style="width:30px;height:30px;margin-right:5px;"><br><a href="javascript:void(0)" onclick="del(this,'+(li_length+1)+')">[删除]</a></li>';
										upload_file_btn.siblings('.image_list').append(html)
										editor.hideDialog();
									}
									
								}
							});
						});
					});

				});
				function del(obj,id){
					var image_list = $(obj).parents('.image_list').siblings('.input-image').val();
					console.log(image_list)
					console.log(id)
					image_list =  image_list.split(';')
				
					var tmp_img_list= '';
					for (var i=1;i<=image_list.length;i++){
						if(i!=id){
							tmp_img_list += (i==1?'':';')+image_list[i];
						}
					}
					
					$(obj).parents('.image_list').siblings('.input-image').val(tmp_img_list)
					
					console.log(tmp_img_list);
					$(obj).closest('.upload_pic_li').remove();
				}
		</script>
		<script>
		function trimStr(str){return str.replace(/(^\s*)|(\s*$)/g,"");}
			$(function(){
				$('.handle_btn').live('click',function(){
					var me = $(this);
					
					desc_txt = $(me).find('input').val();
					art.dialog.open($(this).attr('href'),{
						init: function(){
							var iframe = this.iframe.contentWindow;
							window.top.art.dialog.data('iframe_handle',iframe);
							
						},
						id: 'desc',
						title:'编辑描述',
						padding: 0,
						width: 600,
						height: 400,
						lock: true,
						resize: false,
						background:'black',
						button: null,
						fixed: false,
						close: null,
						left: '50%',
						top: '38.2%',
						opacity:'0.4',
						close:function(){
							  var html_txt = art.dialog.data('html'); 
							  var html_empty = art.dialog.data('html_empty'); 
						
							  if(html_empty!=3){
								  
								  if(html_empty=='1'){
									  html_txt = '编辑描述';
									  $(me).find('input').val('')
								  }else{
									  $(me).find('input').val(html_txt)
								  }
									  
								  $(me).find('.desc_txt_detail').html(html_txt)
							  }
							  
						}
						
					});

					return false;
				});
				
				$(".NumText").keyup(function(){
					if($(this).val().length==1)	{						
						$(this).val($(this).val().replace(/[^0-9.]/g,'')); 
					}else{
						$(this).val($(this).val().replace(/\D/g,''));
					}				
					$(this).val($(this).val().replace(/^[0]*/g,''));
				}).bind("paste",function(){ 
					if($(this).val().length==1)	{						
						$(this).val($(this).val().replace(/[^0-9.]/g,'')); 
					}else{
						$(this).val($(this).val().replace(/\D/g,''));
					}	
					$(this).val($(this).val().replace(/^[0]*/g,''));
				})
				
				
				$('#group_id').change(function(){
					$('#frmselect').submit();
				});
				$('#submit').click(function(){
					$.ajax({
						url: '{pigcms{:U('Sub_card/join_card')}',
						type: 'POST',
						dataType: 'json',
						data: $('#myform').serialize(),
						success:function(date){
							if(date.status){
								alert(date.info);
								parent.location.reload();   
							}else{
								alert(date.info);
							}
						}
					});
				});
				
				$('.cb-enable').click(function(){
					$(this).find('label').addClass('selected');
					$(this).find('label').find('input').prop('checked',true);
					$(this).next('.cb-disable').find('label').find('input').prop('checked',false);
					$(this).next('.cb-disable').find('label').removeClass('selected');
				});
				$('.cb-disable').click(function(){
					$(this).find('label').addClass('selected');
					$(this).find('label').find('input').prop('checked',true);
					$(this).prev('.cb-enable').find('label').find('input').prop('checked',false);
					$(this).prev('.cb-enable').find('label').removeClass('selected');
				});
				
				$('#reset').click(function(){
				 parent.location.reload();   

				});
			});
			
			
		</script>
<include file="Public:footer"/>