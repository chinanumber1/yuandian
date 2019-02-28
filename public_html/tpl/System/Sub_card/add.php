<include file="Public:header"/>
	<style>.frame_form td{vertical-align:middle;}
		select{width:80px;}
		textarea{width:300px;height:80px;}
		
		.textIamge{
			background-image:none!important;
		}
		.wx_sub_card{
			display:none;
		}
		.rand_send{
			display:none;
		}
		.ke-dialog{
			top:10px;
		}
	</style>
	<form id="myform" method="post" action="{pigcms{:U('Sub_card/add')}" frame="true" refresh="true">
	
		<input type="hidden" name="id" value="{pigcms{$_GET['id']}">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<td width="100">套餐名称：</td>
				<td colspan="2">
				<input type="text"  style="width:300px;" class="input fl" name="name" value="{pigcms{$sub_card.name}"  validate="required:true" autocomplete="off" />
				</td>
			</tr>
			<tr>
				<td width="100">套餐描述：</td>
				<td colspan="2">
				
				<textarea name="desc" cols="56" rows="8"  autocomplete="off" validate="required:true">{pigcms{$sub_card.desc}</textarea>
				</td>
			</tr>
			<tr>
				<td width="100">图片：</td>
				<td >
					<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage" style="background: #87b87f!important;border-color: #87b87f;color:#fff;" >上传图片</a>
					<input type="hidden" class="input-image" name="pic_list" value="{pigcms{$sub_card['pic_list']}">
					<ul class="image_list">
						<if condition="is_array($sub_card['pic_lists']) ">
							<volist name="sub_card['pic_lists']" id="vv">
								<li  class="upload_pic_li" style="float:left;list-style:none;"><img src="{pigcms{$vv}" style="width:30px;height:30px;margin-right:5px;"><br><a href="javascript:void(0)" onclick="del(this,{pigcms{$i})">[删除]</a></li>
							</volist>
						</if>
					</ul>
				</td>
				
				<td>
					建议尺寸：500*412,最多5张
				</td>
			</tr>
			<tr>
				<td width="100">价格：</td>
				<td colspan="2">
				<input type="text" class="input fl" name="price" value="{pigcms{$sub_card.price|floatval}"  autocomplete="off" validate="required:true,min:0">
				</td>
			</tr>
			<tr>
				<td width="100">免单总数量：</td>
				<td colspan="2">
				<input type="text" class="input fl" name="free_total_num" value="{pigcms{$sub_card.free_total_num}"  autocomplete="off" validate="required:true,digits:true,min:1">
				</td>
			</tr>
			<tr>
				<td width="100">一个商家免单次数最多为：</td>
				<td width="200">
				<input type="text" class="input fl" name="mer_free_num" value="{pigcms{$sub_card.mer_free_num}"  autocomplete="off" validate="required:true,digits:true,min:1">
				</td>
				<td>
					请填写大于等于1且小于等于免单总次数的正整数
				</td>
			</tr>
			<tr>
				<td width="100">用户选取商家的最大数量</td>
				<td width="200">
				<input type="text" class="input fl" name="user_mer_max_select" value="{pigcms{$sub_card.user_mer_max_select}"  autocomplete="off" validate="required:true,digits:true,min:0">
				</td>
				<td>
					请填写整数，0代表不限制，1即用户只能选取一个商家
				</td>
			</tr>
			<tr>
				<td width="100">店铺参加最大数量：</td>
				<td width="200">
				<input type="text" class="input fl" name="store_max_join_num" value="{pigcms{$sub_card.store_max_join_num}"  autocomplete="off" validate="required:true,digits:true,min:0">
				</td>
				<td>
					参与本套餐的店铺数量限制，0为不限制，如：设置为10，并且已经有10个店铺参与套餐，则第11个店铺不能再参与
				</td>
			</tr>
			<tr>
				<td width="100">套餐抽成：</td>
				<td colspan="2">
				<input type="text" class="input fl" name="percent" value="{pigcms{$sub_card.percent|floatval}"  autocomplete="off" validate="required:true,number:true,min:0">
				</td>
			</tr>
			
			<tr>
				<td width="100">购买有效期：</td>
				<td colspan="2">
					<span class="cb-enable"><label class="cb-enable <if condition="$sub_card['buy_time_type'] eq 1 OR !isset($sub_card)">selected</if>"><span>自定义</span><input type="radio" name="buy_time_type" value="1" <if condition="$sub_card['buy_time_type'] eq 1 OR !isset($sub_card)">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$sub_card['buy_time_type'] eq 0 AND isset($sub_card)">selected</if>"><span>无限时</span><input type="radio" name="buy_time_type" value="0" <if condition="$sub_card['buy_time_type'] eq 0 AND isset($sub_card)">checked="checked"</if>/></label></span>&nbsp;&nbsp;
					<input type="text" class="input-text time" name="start_time" style="width:120px; <if condition="$sub_card['buy_time_type'] eq 0 AND isset($sub_card)">display:none</if>" id="d4311"  value="<if condition="$sub_card.start_time gt 0">{pigcms{$sub_card.start_time|date='Y-m-d',###}</if>" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})"  />&nbsp;&nbsp;
					<input type="text" class="input-text time" name="end_time" style="width:120px;<if condition="$sub_card['buy_time_type'] eq 0 AND isset($sub_card)">display:none</if>" id="d4311" value="<if condition="$sub_card.start_time gt 0">{pigcms{$sub_card.end_time|date='Y-m-d',###}</if>" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" />
				</td>
			</tr>
			
			<tr>
				<td width="100">购买后有效天数：</td>
				<td colspan="2">
				<span class="cb-enable"><label class="cb-enable <if condition="$sub_card['use_time_type'] eq 1 OR !isset($sub_card)">selected</if>"><span>自定义</span><input type="radio" name="use_time_type" value="1" <if condition="$sub_card['use_time_type'] eq 1 OR !isset($sub_card)">checked="checked"</if>/></label></span>
				<span class="cb-disable"><label class="cb-disable <if condition="$sub_card['use_time_type'] eq 0 AND isset($sub_card)">selected</if>"><span>永久有效</span><input type="radio" name="use_time_type" value="0" <if condition="$sub_card['use_time_type'] eq 0 AND isset($sub_card)">checked="checked"</if>/></label></span>&nbsp;&nbsp;
				<input type="text" class="input fl" name="effective_days" value="{pigcms{$sub_card.effective_days}"  autocomplete="off"  <if condition="$sub_card['use_time_type'] eq 0 AND isset($sub_card)">style="display:none"</if> placeholder="请填写有效天数">
				<input type="text" class="input fl" id="forever_txt" style="<if condition="$sub_card.use_time_type eq 1 OR !isset($sub_card)">display:none;</if>" name="forever_txt"  placeholder="自定义描述,建议6个字符" value="{pigcms{$sub_card.forever_txt}">
				</td>
			</tr>
			<if condition="isset($sub_card)">
			<tr>
			   <th width="100">使用区域</th>
                <td colspan="2">
                    <span class="cb-enable"><label class="cb-enable <if condition="$sub_card['use_area'] eq 0 AND isset($sub_card) OR !isset($sub_card)">selected</if>"><span>全区域可用</span><input type="radio" name="use_area" value="0" <if condition="$sub_card['use_area'] eq 0 AND isset($sub_card) OR !isset($sub_card)">checked="checked"</if> /></label></span>
                    <span class="cb-disable"><label class="cb-disable <if condition="$sub_card['use_area'] eq 1 AND isset($sub_card)">selected</if>"><span>指定区域可用</span><input type="radio" name="use_area" value="1" <if condition="$sub_card['use_area'] eq 1 AND isset($sub_card)">checked="checked"</if>/></label></span>
					
					<a  href="javascript:void(0);" class="edit_area" onclick="window.top.artiframe('{pigcms{:U('Sub_card/edit_area',array('sub_cardid'=>$sub_card['id']))}','编辑区域',600,400,true,false,false,null,'edit_area',true);" style="color:blue;margin-left:5px;<if condition="$sub_card.use_area eq 0 OR !isset($sub_card)">display:none;</if>">编辑区域</a>
                </td>
			</tr>
			</if>
			<tr>
				<td width="100">状态</td>
				<td colspan="2">
					<span class="cb-enable"><label class="cb-enable <if condition="$sub_card['status'] eq 1 OR !isset($sub_card)">selected</if>"><span>启用</span><input type="radio" name="status" value="1" <if condition="$sub_card['status'] eq 1 OR !isset($sub_card)">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$sub_card['status'] eq 0 AND isset($sub_card)">selected</if>"><span>关闭</span><input type="radio" name="status" value="0" <if condition="$sub_card['status'] eq 0 AND isset($sub_card)">checked="checked"</if>/></label></span>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" is="reset" />
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
	<script type="text/javascript">
	 $(function(){
			$('#reset').click(function(){
				 window.top.art.dialog({id:'add_sub_card'}).close();
			});
			$('input[name="use_time_type"]').click(function(){
				if($(this).val()==1){
					$('input[name="effective_days"]').show();
					$('#forever_txt').hide();
				}else{
					$('input[name="effective_days"]').hide();
					$('#forever_txt').show();
				}
			})
			$('input[name="buy_time_type"]').click(function(){
				if($(this).val()==1){
					$('.time').show();
				}else{
					$('.time').hide();
				}
			})
			
			$('input[name="use_area"]').click(function(){
				if($(this).val()==1){
					$('.edit_area').show();
				}else{
					$('.edit_area').hide();
				}
			})
		})

	</script>
	
<include file="Public:footer"/>

