<include file="Public:header"/>
	<style>.frame_form td{vertical-align:middle;}
		select{width:80px;}
		textarea{width:300px;height:80px;}
		.textIamge{
			background-image:none!important;
		}
		.wx_coupon{
			<if condition="$coupon['sync_wx'] eq 0 OR  $coupon['wx_cardid'] eq ''">display:none;</if>
		}
		.rand_send{
			<if condition="$coupon['rand_send'] eq 0">display:none;</if>
		}
		.mini_img{
			width:60px;
			height:30px;
		}
	</style>
	<form id="myform" method="post" action="{pigcms{:U('Coupon/edit')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<input type="hidden" name="coupon_id" value="{pigcms{$coupon.coupon_id}">
			<tr>
				<td width="100">优惠券名称：</td>
				<td>
				{pigcms{$coupon.name}
				</td>
			</tr>
			<tr>
				<td width="100">优惠券图标：</td>
				<td><input type="text"  style="width:200px;" name="img" class="input input-image" value="{pigcms{$coupon.img}"  validate="required:true" readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage"  style="background: #87b87f!important;border-color: #87b87f;color:#fff;">上传图片</a></td>
				<td>图片尺寸建议 正方形 200 X 200</td>			
			</tr>
			
			<tr>
				<td width="100">微信分享图片：</td>
				<td><input type="text"  style="width:200px;" name="wx_share_img" class="input input-image" value="{pigcms{$coupon.wx_share_img}"  validate="required:true" readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage"  style="background: #87b87f!important;border-color: #87b87f;color:#fff;">上传图片</a></td>
				<td><font color="red">不上传图片则不能生产二维码</font></td>			
			</tr>
			<tr>
				<td width="100">同步到微信卡券：</td>
				<td colspan="2">
					<if condition="$coupon['sync_wx'] eq 1"><span>是</span><else /><span>否</span></if>
					&nbsp;&nbsp;
					<a href="javascript:void(0)" onclick="window.top.artiframe('{pigcms{:U('Coupon/show')}','微信卡券示例图',300,500,true,false,false,'','show',true);" style="color:blue">微信卡券示例图</a>
				</td>
				
			</tr>
				
			<tr class="wx_coupon">
				<td width="100" style="color:red">创建朋友的券：</td>
				<td >
					<if condition="$coupon['share_friends'] eq 1"><span>是</span><else /><span>否</span></if>
				</td>
				<td>
					选择创建朋友的券后该优惠券不能分享和赠送
				</td>
					
			</tr>
			
			<tr class="wx_coupon">
				<td width="100" style="color:red">卡券颜色</td>
				<td colspan="2">
					<div id="wx_color" style="width:30px;height:30px;background-color:{pigcms{$coupon.color}; float:left;margin-left:10px"></div>
				</td>
					
			</tr>
			<tr class="wx_coupon">
				<td width="100" style="color:red">商家名称</td>
				<td colspan="2">
				{pigcms{$coupon.brand_name}
				</td>
					
			</tr>
			
			<tr class="wx_coupon">
				<td width="100" style="color:red">卡券提示</td>
				<td colspan="2">
					{pigcms{$coupon.notice}
				</td>
					
			</tr>
			<tr class="wx_coupon">
				<td width="100" style="color:red">卡券副标题</td>
				<td colspan="2">
					{pigcms{$coupon.center_sub_title}
				</td>
			</tr>
			<tr class="wx_coupon">
				<td width="100" style="color:red">立即使用链接</td>
				<td colspan="2">
				<a href="{pigcms{$coupon.center_url|html_entity_decode}" target="_blank">点击查看</a>
				</td>
					
			</tr>
		
			
			<tr class="wx_coupon">
				<td width="100" style="color:red">更多优惠链接</td>
				<td colspan="2">
					<a href="{pigcms{$coupon['promotion_url']}" target="_blank">点击查看</a>
				</td>
			</tr>
			<tr class="wx_coupon">
				<td width="100" style="color:red">自定义链接</td>
				<td colspan="2">
					标题：{pigcms{$coupon.custom_url_name}<br><br>
					链接：{pigcms{$coupon.custom_url}<br><br>
					副标题：{pigcms{$coupon.custom_url_sub_title}
				</td>
			</tr>
			<tr class="wx_coupon">
				<td width="100" style="color:red">封面图片</td>
				<td colspan="2"><img class="mini_img" src="{pigcms{$coupon.icon_url_list}">&nbsp;&nbsp; 描述 :{pigcms{$coupon.abstract}</td>
			</tr>
			<tr class="wx_coupon">
				<td width="100" style="color:red">商家服务类型</td>
				<td colspan="2">
					<volist name="coupon.business_service" id="vo">
						<if condition="$vo eq 'BIZ_SERVICE_DELIVER'">
						外卖服务&nbsp;&nbsp;
						<elseif condition="$vo eq 'BIZ_SERVICE_FREE_PARK'" />
						停车位&nbsp;&nbsp;
						<elseif condition="$vo eq 'BIZ_SERVICE_WITH_PET'" />
						可带宠物&nbsp;&nbsp;
						<elseif condition="$vo eq 'BIZ_SERVICE_FREE_WIFI'" />
						免费wifi&nbsp;&nbsp;
						</if>
					</volist>
					
				</td>
			</tr>
			<tr class="wx_coupon">
				<td colspan="3">
				<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
					<volist name="coupon.text_image_list" id="vo">
					<tr class="plus textIamge" >
						<td width="60" style="color:red">卡券图文<label>{pigcms{$i}</label></td>
						<td>
							<table style="width:100%;border:#d5dfe8 1px solid;padding:2px;">
								<tr class="textIamge">
									<td width="36" style="color:red">图片：</td>
									<td><img class="mini_img" src="{pigcms{$vo.image_url}"></td>
									<td width="36" style="color:red">描述：</td>
									<td>
									{pigcms{$vo.text}
									</td>
									<td rowspan="2" class="delete">
									
									</td>
								</tr>
								
							</table>
						</td>
					</tr>
					</volist>
					<tr class="textIamge">
						
					</tr>
					
					
				</table>
				</td>
			</tr>
		
			<tr>
				<td width="100">是否只允许新用户领取：</td>
				<td colspan="2">
					<if condition="$coupon['allow_new'] eq 1">是<elseif condition="$coupon['allow_new'] eq 0"/>否</if>
				</td>
				
			</tr>
			
			<tr>
				<td width="100">是否随机派发：</td>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$coupon['rand_send'] eq 1"> selected</if>"><span>是</span><input type="radio" name="rand_send" value="1" <if condition="$coupon['rand_send'] eq 1"> checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$coupon['rand_send'] eq 0"> selected</if>"><span>否</span><input type="radio" name="rand_send" value="0" <if condition="$coupon['rand_send'] eq 0"> checked="checked"</if>/></label></span>
				</td>
				<td>随机派发的优惠券不能被用户主动领取，只能弹窗领取</td>
			</tr>
			
			<tr class="rand_send">
				<td width="100">随机派发数量</td>
				<td >
					<input type="text" class="input fl" name="rand_send_num" value="{pigcms{$coupon.rand_send_num}"  autocomplete="off" validate="digits:true">
				</td>
				<td>随机派发数量不受优惠券总数量限制</td>
			</tr>
			<tr class="rand_send">
				<td width="100" >随机派发开始结束时间</td>
				<td >
					<input type="text" class="input-text" name="rand_send_start_time" style="width:120px;" id="d4311"  value="<if condition="$coupon.rand_send_start_time gt 0">{pigcms{$coupon.rand_send_start_time|date='Y:m:d H:i:s',###}</if>" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:00:00'})"/>-
					<input type="text" class="input-text" name="rand_send_end_time" style="width:120px;" id="d4311" value="<if condition="$coupon.rand_send_end_time gt 0">{pigcms{$coupon.rand_send_end_time|date='Y:m:d H:i:s',###}</if>" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:00:00'})" />
				</td>
				<td>随机派发的优惠券不能被用户主动领取，只能弹窗领取</td>
			</tr>
			<tr>
				<td width="100">使用平台：</td>
				<td>
				{pigcms{$coupon.platform}
				</td>
			</tr>
			<tr>
				<td width="100">使用类别：</td>
				<td>
				{pigcms{$coupon.cate_name}
				</td>
				
			</tr>
			<tr>
				<td width="100">使用分类：</td>
				<td id="cate_id">
				{pigcms{$coupon.cate_id}
				</td>
			</tr>
			<tr>
				<td width="100">微信展示简短描述(微信卡包优惠说明)：</td>
				<td>
				<textarea name="des" value=""  autocomplete="off" validate="required:true">{pigcms{$coupon.des}</textarea>
				</td>
			</tr>
			<tr>
				<td width="100">领取页面详细描述(微信卡包使用须知)：</td>
				<td>
				<textarea name="des_detial" value=""  autocomplete="off" validate="required:true">{pigcms{$coupon.des_detial}</textarea>
				</td>
				<td>每条描述请换行</td>
			</tr>
			<tr>
				<td width="100">数量：{pigcms{$coupon.now_num}</td>
				<td width="85%" colspan="3">
				
				<input type="hidden" name="status" value="{pigcms{$coupon.status}"/>
				<input type="hidden" name="had_pull" value="{pigcms{$coupon.had_pull}"/>
				<input type="hidden" name="num" value="{pigcms{$coupon.now_num}"/>
				<select name="add" class="fl">
					<option value="0">增加</option>
					<option value="1">减少</option>
				</select>
				<input type="text" class="input fl" style="margin-left:4px;" name="num_add" value=""  autocomplete="off" validate="digits:true,min:1">已经被领了{pigcms{$coupon.had_pull}张
				</td>
			</tr>
			
			<tr>
				<td width="100">领取数量限制：</td>
				<td>
				{pigcms{$coupon.limit}
				</td>
			</tr>
			<tr>
				<td width="100">使用数量限制：</td>
				<td>
				{pigcms{$coupon.use_limit}
				</td>
			</tr>
			<tr>
				<td width="100">是否是折扣券：</td>
				<td>
				<if condition="$coupon.is_discount eq 1">是<else />否</if>
				</td>
			</tr>
			<if condition="$coupon.is_discount eq 1">
			<tr>
				<td width="100">折扣：</td>
				<td>
				{pigcms{$coupon.discount_value|floatval}
				</td>
			</tr>
			<else />
			<tr>
				<td width="100">优惠金额：</td>
				<td>
				{pigcms{$coupon.discount}
				</td>
			</tr>
			</if>
			<tr>
				<td width="100">最小订单金额：</td>
				<td>
				{pigcms{$coupon.order_money}
				</td>
			</tr>
			<tr>
				<td width="100">起始时间：</td>
				<td>
					{pigcms{$coupon.start_time|date='Y年m月d日',###}——{pigcms{$coupon.end_time|date='Y年m月d日',###}
				</td>
			</tr>
			
			<if condition="($coupon.status eq 0) OR ($coupon.status eq 1)">
			<tr>
				<td width="100">状态</td>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$coupon['status'] eq 1">selected</if>"><span>启用</span><input type="radio" name="status" value="1" <if condition="$coupon['status'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$coupon['status'] eq 0">selected</if>"><span>禁止</span><input type="radio" name="status" value="0" <if condition="$coupon['status'] eq 0">checked="checked"</if>/></label></span>
				</td>
			</tr>
			</if>
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
				$('.J_selectImage').click(function(){
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

			});
			
			$(document).ready(function() {
			
			
			$('select[name="color"]').css('background-color','#63b359');	
			$('select[name="color"]').change(function(event) {
				$('#wx_color').css('background-color',$('select[name="color"]').find('option:selected').html());
				$(this).css('background-color',$('select[name="color"]').find('option:selected').html());
			});		

			$('input:radio[name="sync_wx"]').click(function(i,val){
				if($(this).val()==1){
					$('.wx_coupon').show();
				}else{
					$('.wx_coupon').hide();
				}
			});
			$('input:radio[name="rand_send"]').click(function(i,val){
				if($(this).val()==1){
					$('.rand_send').show();
				}else{
					$('.rand_send').hide();
				}
			});
			
		});
		
		function plus(){
			var item = $('.plus:last');
			var newitem = $(item).clone(true);
			var No = parseInt(item.find("label").html())+1;
			$('.delete').children().show();
			if(No>4){
				alert('不能超过4条信息');
			}else{
				$(item).after(newitem);
				newitem.find('input').attr('value','');
				newitem.find('textarea').attr('value','');
				newitem.find("#addLink").attr('onclick',"addLink('url"+No+"',0)");
				newitem.find("label").html(No);
				newitem.find('input[name="url[]"]').attr('id','url'+No);
				newitem.find('.delete').children().show();
			}
		}
		function del(obj){
			if($('.plus').length<=1){
				$('.delete').children().hide();
			}else{
				if($('.plus').length==2){
					$('.delete').children().hide();
				}
				$(obj).parents('.plus').remove();
				$.each($('.plus'), function(index, val) {
					var No =index+1;
					$(val).find('label').html(No);
					$(val).find('input[name="url[]"]').attr('id','url'+No);
					$(val).find("#addLink").attr('onclick',"addLink('url"+No+"',0)");
				});
			}
		}
	</script>

	
<include file="Public:footer"/>

