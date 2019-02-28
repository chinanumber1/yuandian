<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('index')}">礼品列表</a>|
					<a href="{pigcms{:U('gift_edit',array('gift_id'=>$detail['gift_id']))}" class="on">修改礼品</a>
				</ul>
			</div>
			<form method="post" action="__SELF__" refresh="true" enctype="multipart/form-data" >
				<table cellpadding="0" cellspacing="0" class="table_form" width="100%">
					<tr>
						<th width="80">礼品名称</th>
						<td><input type="text" class="input-text" name="gift_name" size="20" validate="maxlength:20,required:true" value="{pigcms{$detail.gift_name}"/></td>
					</tr>
					<tr>
						<th width="100">礼品兑换类别</th>
						<td>
						<select name="exchange_type" class="col-sm-2">
							<option value="0" <if condition='$detail["exchange_type"] eq 0'>selected="selected"</if>>纯{pigcms{$config['score_name']}</option>
							<option value="1" <if condition='$detail["exchange_type"] eq 1'>selected="selected"</if>>{pigcms{$config['score_name']}+余额</option>
							<option value="2" <if condition='$detail["exchange_type"] eq 2'>selected="selected"</if>>不限</option>
						</select>
						</td>
					</tr>


					<tr class="payment_type" <if condition='!in_array($detail["exchange_type"],array(0,2))'>style="display:none"</if>>
						<th width="100">纯{pigcms{$config['score_name']}类型</th>
						<td>&nbsp;&nbsp;所需{pigcms{$config['score_name']}：<input type="text" class="input-text" name="payment_pure_integral" size="10" validate="maxlength:20,required:true" value="{pigcms{$detail.payment_pure_integral}"/></td>
					</tr>

					<tr class="payment_type"  <if condition='!in_array($detail["exchange_type"],array(1,2))'>style="display:none"</if>>
						<th width="100">{pigcms{$config['score_name']}+余额类型</th>
						<td>&nbsp;&nbsp;所需{pigcms{$config['score_name']}：<input type="text" class="input-text" name="payment_integral" size="10" validate="maxlength:20,required:true" value="{pigcms{$detail.payment_integral}"/>&nbsp;+&nbsp;所需金额：<input type="text" class="input-text" name="payment_money" size="10" validate="maxlength:20,required:true" value="{pigcms{$detail.payment_money}"/></td>
					</tr>

					<tr>
						<th width="80">选择分类</th>
						<td>


							<select id="choose_catfid" name="cat_fid" class="col-sm-1" style="margin-right:10px;" onchange="get_son_category($(this).val())">

								<option value="0" >请选择</option>
								<volist name="f_category_list" id="vo">
									<option value="{pigcms{$key}" <if condition='$detail["cat_fid"] eq $key'>selected="selected"</if>>{pigcms{$vo}</option>
								</volist>

							</select>

							<select id="choose_catid" name="cat_id" class="col-sm-1" style="margin-right:10px;">
								<option value="0" >请选择</option>
								<volist name="s_category_list" id="vo">
									<option value="{pigcms{$key}" <if condition='$detail["cat_id"] eq $key'>selected="selected"</if>>{pigcms{$vo}</option>
								</volist>
							</select>
						</td>
					</tr>
					<tr>
						<th width="100">每人限制兑换数量</th>
						<td><input type="text" class="input-text" name="exchange_limit_num" size="10" validate="maxlength:20,required:true" value="{pigcms{$detail.exchange_limit_num}"/><span style="color:red">&nbsp;*&nbsp;0为不限制</span></td>
					</tr>


					<tr>
						<th width="100">库存数量</th>
						<td><input type="text" class="input-text" name="sku" size="10" validate="maxlength:20,required:true" value="{pigcms{$detail.sku}"/></td>
					</tr>

					<tr>
						<th width="100">已兑换人数</th>
						<td><input type="text" class="input-text" name="exchanged_num" size="10" validate="maxlength:20,required:true" value="{pigcms{$detail.exchanged_num}"/></td>
					</tr>
					<tr>
						<th width="100">电脑端图片</th>
						<td><a href="javascript:void(0)" class="btn btn-sm btn-success" id="J_selectImage">上传图片</a>
						<span class="form_tips">第一张将作为列表页图片展示！最多上传5个图片！建议尺寸：800*800</span></td>
					</tr>
					<tr>
						<th width="100">图片预览</th>
						<td id="upload_pic_box">
							<ul id="upload_pic_ul">
								<volist name="detail['pc_pic_list']" id="vo">
									<li class="upload_pic_li"><img src="{pigcms{$vo.url}"/><input type="hidden" name="pc_pic[]" value="{pigcms{$vo.title}"/><br/><a href="#" onclick="deleteImage('{pigcms{$vo.title}',this);return false;">[ 删除 ]</a></li>
								</volist>
							</ul>
						</td>
					</tr>


					<tr>
						<th width="100">手机端图片</th>
						<td><a href="javascript:void(0)" class="btn btn-sm btn-success" id="J_wap_selectImage">上传图片</a>
						<span class="form_tips">第一张将作为列表页图片展示！最多上传5个图片！建议尺寸：600*400</span></td>
					</tr>
					<tr>
						<th width="100">图片预览</th>
						<td id="upload_pic_box">
							<ul id="wap_upload_pic_ul">

							<volist name="detail['wap_pic_list']" id="vo">
									<li class="wap_upload_pic_li"><img src="{pigcms{$vo.url}"/><input type="hidden" name="wap_pic[]" value="{pigcms{$vo.title}"/><br/><a href="#" onclick="wap_deleteImage('{pigcms{$vo.title}',this);return false;">[ 删除 ]</a></li>
								</volist>

							</ul>
						</td>
					</tr>

					<tr>
						<th width="80">简述</th>
						<td><textarea rows="6" cols="104" name="intro">{pigcms{$detail.intro}</textarea></td>
					</tr>

					<tr>
						<th width="80">规格</th>
						<td>
						<textarea rows="6" cols="104" name="specification">{pigcms{$detail["specification"]}</textarea><span style="color:red">&nbsp;&nbsp;*&nbsp;&nbsp;以回车换行进行分割</span></td>
					</tr>

					<tr>
						<th width="80">发货清单</th>
						<td><textarea rows="6" cols="104" name="invoice_content">{pigcms{$detail.invoice_content}</textarea></td>
					</tr>
					<tr>
						<th width="80">礼品详情</th>
						<td><textarea rows="" cols="" name="gift_content" id="content">{pigcms{$detail.gift_content}</textarea></td>
					</tr>

					<tr>
						<th width="80">排序</th>
						<td><input type="text" class="input-text" name="sort" size="10" validate="maxlength:20,required:true" value="{pigcms{$detail.sort}"/><img title="排序值越大，越往前显示！" class="tips_img" src="./tpl/System/Static/images/help.gif"></td>
					</tr>

					<tr>
						<th width="100">今日新品</th>
						<td>
						<select name="is_new" class="col-sm-1">
							<option value="1" <if condition='$detail["is_new"] eq 1'>selected="selected"</if>>开启</option>
							<option value="0" <if condition='$detail["is_new"] eq 0'>selected="selected"</if>>关闭</option>
						</select>
						</td>
					</tr>

					<!--tr>
						<th width="100">是否热门</th>
						<td>
						<select name="is_hot" class="col-sm-1">
							<option value="1" <if condition='$detail["is_hot"] eq 1'>selected="selected"</if>>开启</option>
							<option value="0" <if condition='$detail["is_hot"] eq 0'>selected="selected"</if>>关闭</option>
						</select>
						</td>
					</tr-->

					<tr>
						<th width="80">礼品状态</th>
						<td>
						<select name="status" class="col-sm-1">
							<option value="1" <if condition='$detail["status"] eq 1'>selected="selected"</if>>开启</option>
							<option value="0" <if condition='$detail["status"] eq 0'>selected="selected"</if>>关闭</option>
						</select>
						</td>
					</tr>
				</table>
				<div class="btn">
					<input type="submit"  name="dosubmit" value="提交" class="button" />
					<input type="reset"  value="取消" class="button" />
				</div>
			</form>
		</div>

<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="./static/js/upyun.js"></script>

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
#upload_pic_box{margin-top:20px;height:150px;}
#upload_pic_box .upload_pic_li,.wap_upload_pic_li{width:130px;float:left;list-style:none;}
#upload_pic_box img{width:100px;height:70px;}
</style>
<script>
var diyTool = "{pigcms{:U('Home/diytool')}";
var editor;
var diyVideo = "{pigcms{:U('Article/diyVideo')}";
KindEditor.ready(function(K) {
	editor = K.create('#content', {
		filterMode: false,
		resizeType : 1,
		allowPreviewEmoticons : false,
		allowImageUpload : true,
		uploadJson : '/admin.php?g=System&c=Upyun&a=kindedtiropic',
		items : ['fontname', 'fontsize','subscript','superscript','indent','outdent','|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline','hr',
		 '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist','link', 'unlink','image','diyVideo','diyTool']
	});
});


var url = "{pigcms{:U('ajax_category')}";
function get_son_category(fid){
	if(fid==0){
		var shtml = '<option value="0" >请选择</option>';
		$('#choose_catid').empty('').append(shtml);
	}else{
		$.post(url,{'cat_fid':fid},function(data){
			if(data.status){
				var shtml = '<option value="0" >请选择</option>';
				for(var i in data['cat_list']){
					shtml += '<option value="'+i+'">'+data['cat_list'][i]+'</option>';
				}

				$('#choose_catid').empty('').append(shtml);
			}
		},'json');
	}
}

function deleteImage(path,obj){
	$.post("{pigcms{:U('ajax_del_pic')}",{path:path});
	$(obj).closest('.upload_pic_li').remove();
}
function wap_deleteImage(path,obj){
	$.post("{pigcms{:U('ajax_del_pic')}",{path:path});
	$(obj).closest('.wap_upload_pic_li').remove();
}

KindEditor.ready(function(K) {
	var content_editor = K.create("#content",{
		width:'100',
		height:'100',
		resizeType : 1,
		allowPreviewEmoticons:false,
		allowImageUpload : true,
		filterMode: true,
		autoHeightMode : true,
		afterCreate : function() {
			this.loadPlugin('autoheight');
		},
		items : [
			'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist', '|', 'emoticons', 'image', 'link', 'table'
		],
		emoticonsPath : './static/emoticons/',
		uploadJson : "/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=system/gift",
		cssPath : "./tpl/Merchant/default/static/css/group_editor.css"
	});

	var editor = K.editor({
		allowFileManager : true
	});
	K('#J_selectImage').click(function(){
		if($('.upload_pic_li').size() >= 5){
			alert('最多上传5个图片！');
			return false;
		}
		editor.uploadJson = "{pigcms{:U('ajax_upload_pic')}";
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				showRemote : false,
				imageUrl : K('#course_pic').val(),
				clickFn : function(url, title, width, height, border, align) {
					$('#upload_pic_ul').append('<li class="upload_pic_li"><img src="'+url+'"/><input type="hidden" name="pc_pic[]" value="'+title+'"/><br/><a href="#" onclick="deleteImage(\''+title+'\',this);return false;">[ 删除 ]</a></li>');
					editor.hideDialog();
				}
			});
		});
	});


	K('#J_wap_selectImage').click(function(){
		if($('.wap_upload_pic_li').size() >= 5){
			alert('最多上传5个图片！');
			return false;
		}
		editor.uploadJson = "{pigcms{:U('ajax_upload_pic')}";
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				showRemote : false,
				imageUrl : K('#course_pic').val(),
				clickFn : function(url, title, width, height, border, align) {
					$('#wap_upload_pic_ul').append('<li class="wap_upload_pic_li"><img src="'+url+'"/><input type="hidden" name="wap_pic[]" value="'+title+'"/><br/><a href="#" onclick="wap_deleteImage(\''+title+'\',this);return false;">[ 删除 ]</a></li>');
					editor.hideDialog();
				}
			});
		});
	});
});


$('select[name="exchange_type"]').change(function(){
	var obj = $('input[name="payment_money"]').parents('tr');
	var num = $(this).val();
	if(num == 0){
		$('.payment_type').hide().eq(num).show();
	}else if(num == 1){
		$('.payment_type').hide().eq(num).show();
	}else{
		$('.payment_type').show();
	}

});


</script>
<include file="Public:footer"/>