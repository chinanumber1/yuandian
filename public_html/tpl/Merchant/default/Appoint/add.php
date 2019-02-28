<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Appoint/index')}">预约管理</a>
			</li>
			<li class="active">添加预约</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
				#levelcoupon select {width:150px;margin-right: 20px;}
				#add_form *, *::after, *::before{ box-sizing:content-box}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">
							<li class="active">
								<a data-toggle="tab" href="#basicinfo">基本信息</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtstore">选择店铺</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtimage">介绍图片</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtattr">规格属性</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtorder">状态设置</a>
							</li>
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="add_form">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1">服务名称：</label>
									<input class="col-sm-3" maxlength="30" name="appoint_name" type="text" value="" /><span class="form_tips red">&nbsp;*&nbsp;必填。在预约页显示此名称！</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">服务简介：</label>
									<textarea class="col-sm-3" rows="5" name="appoint_content"></textarea><span class="form_tips red">&nbsp;*&nbsp;预约的简短介绍，建议为100字以下。</span>
								</div>
								<div class="form-group"></div>
								<div class="form-group">
									<label class="col-sm-1">收取定金</label>
									<label style="padding-left:0px;padding-right:20px;"><input onclick="paymentShow();" type="radio" class="ace" value="1" name="payment_status"><span style="z-index: 1" class="lbl">开启</span></label>
									<label style="padding-left:0px;"><input onclick="paymentHide();" type="radio" class="ace" value="0" checked="checked" name="payment_status"><span style="z-index: 1" class="lbl">关闭</span></label>
								</div>

								<div class="form-group" id="payment_money" style="display:none;">
									<label class="col-sm-1">定金：</label>
									<input class="col-sm-1" maxlength="100" name="payment_money" type="text" value="" /><span class="form_tips red">&nbsp;*&nbsp;最多支持2位小数（超过后，系统自动截取）</span>
								</div>

								<div class="form-group">
									<label class="col-sm-1">全价：</label>
									<select style=" width:120px" class="col-sm-1" name="is_appoint_price">
										<option value="0">面议</option>
										<option value="1">自定义</option>
									</select>

									<div style="display:none;" id="appoint_price">
										<input class="col-sm-1" maxlength="30" name="appoint_price" type="text" value="" style="margin-left:20px" />
										<if condition="$config.open_extra_price eq 1">
											元 + <input class="col-sm-1" maxlength="30" name="extra_pay_price" type="text" value="" style="float:none"/>{pigcms{$config.extra_price_alias_name}
											<span class="form_tips">如果填写{pigcms{$config.extra_price_alias_name}字段，商品价格将变为：金额+{pigcms{$config.extra_price_alias_name}数</span>
										</if>
										<span class="form_tips red">&nbsp;*&nbsp;必填。最多支持2位小数（超过后，系统自动截取）</span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-1">耗时：</label>
									<input class="col-sm-1" maxlength="30" name="expend_time" type="text" value="" /><span class="form_tips red">&nbsp;*&nbsp;必填。单位：分钟</span>
								</div>


								<div class="form-group">
									<label class="col-sm-1">排序：</label>
									<input class="col-sm-1" maxlength="30" name="sort" type="text" value="0" /><span class="form_tips red">&nbsp;*&nbsp;值越大，越往前显示。</span>
								</div>


								<div class="form-group"></div>
								<div class="form-group">
									<label class="col-sm-1">开始时间：</label>
									<input class="col-sm-2 Wdate" type="text" readonly style="height:30px;" onfocus="WdatePicker({minDate:'{pigcms{:date('Y年m月d日',$_SERVER['REQUEST_TIME'])}',isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日',startDate:'{pigcms{:date('Y-m-d',$_SERVER['REQUEST_TIME'])}',vel:'start_time'})" value="{pigcms{:date('Y年m月d日',$_SERVER['REQUEST_TIME'])}"/>
									<input name="start_time" id="start_time" type="hidden" value="{pigcms{:date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME'])}"/>
									<span class="form_tips red">&nbsp;*&nbsp;到了开始时间，会自动显示！</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">结束时间：</label>
									<input class="col-sm-2 Wdate" type="text" readonly style="height:30px;" onfocus="WdatePicker({minDate:'{pigcms{:date('Y年m月d日',$_SERVER['REQUEST_TIME'])}',isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日',startDate:'{pigcms{:date('Y-m-d',strtotime('+30 day'))}',vel:'end_time'})" value="{pigcms{:date('Y年m月d日 ',strtotime('+30 day'))}"/>
									<input name="end_time" id="end_time" type="hidden" value="{pigcms{:date('Y-m-d H:i:s',strtotime('+30 day'))}"/>
									<span class="form_tips red">&nbsp;*&nbsp;超过结束时间，会自动结束！</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">服务类别：</label>
									<select name="appoint_type" class="col-sm-2">
										<option value="0">到店</option>
										<option value="1">上门</option>
									</select>
								</div>

								<div class="form-group">
									<label class="col-sm-1">选择分类：</label>
									<select id="choose_catfid" name="cat_fid" class="col-sm-1" style="margin-right:10px;">
										<option value="">请选择</option>
										<volist name="f_category_list" id="vo">
											<option value="{pigcms{$vo.cat_id}">{pigcms{$vo.cat_name}</option>
										</volist>
									</select>
									<select id="choose_catid" name="cat_id" class="col-sm-1" style="margin-right:10px;">
										<option value="">请选择</option>
										<volist name="s_category_list" id="vo">
											<option value="{pigcms{$vo.cat_id}">{pigcms{$vo.cat_name}</option>
										</volist>
									</select>
									<input type="hidden" name="cat_id" id="cat_id" value=""/>
									<span class="form_tips red" style="display:none" id="autotrophic_warn">*此分类为平台自营，须等待平台派单，用户无法直接浏览</span>
								</div>
								<div class="form-group" >
									<label class="col-sm-1">服务详情：<br/></label>
									<textarea name="appoint_pic_content" id="content" style="width:702px;"></textarea>
								</div>
								<div class="tabbable">
									<ul class="nav nav-tabs" id="myTab">
										<li class="active">
											<a data-toggle="tab" href="#shop_time_1">
												营业时间段
											</a>
										</li>
									</ul>
									<div class="tab-content" id="office_time">
										<div id="shop_time_1" class="tab-pane in active">
											<div>
												<input class="Config_shop_start_time" type="text" value="08:00" name="office_start_time[]" />&nbsp;&nbsp;至&nbsp;&nbsp;
												<input class="Config_shop_stop_time" type="text" value="20:00" name="office_stop_time[]" />
											<!-- 	<div class="errorMessage" id="Config_shop_start_time_em_" style="display:none"></div>
												<div class="errorMessage" id="Config_shop_stop_time_em_" style="display:none"></div> -->
												<span class="form_tips red">&nbsp;*&nbsp;如果营业时间段设置为00:00-00:00，则表示24小时营业</span>
											</div>
										</div>
									</div>
									<a class="btn btn-success" id="add_shop_time" style="margin-top: 5px">添加时间段</a>
								</div>
									
								<div class="form-group"></div>
										<!--div class="form-group">
											<label class="col-sm-1">限定人数：</label>
											<input class="col-sm-1" maxlength="100" name="appoint_people" type="text" value="0" /><span class="form_tips red">&nbsp;*&nbsp;限制每个时间点的预约人数，0为不限制</span>
										</div-->
								<div class="form-group" style="margin-top: 5px;;">
									<label class="col-sm-1">时间间隔：</label>
									<input class="col-sm-1" maxlength="100" name="time_gap" type="text" value="30" /><span class="form_tips red">&nbsp;*&nbsp;预约时间间隔，单位分钟，必须是5的倍数，填写-1则显示为天数预约。</span>
								</div>
							</div>


							<div id="txtstore" class="tab-pane">
                            <div class="form-group">
									<label style="margin-left:20px"><label for="is_store">是否开启选择店铺：</label></label>
										<label style="margin-left:10px"><input type="radio" checked="checked" class="ace" value="1" name="is_store"><span style="z-index: 1" class="lbl">开启</span></label>
										<label style="margin-left:10px"><input type="radio" class="ace" value="0" name="is_store"><span style="z-index: 1" class="lbl">关闭</span></label>
										<span class="form_tips red">关闭后，订单将由商家指定门店及技师进行服务。</span>
								</div>

								<div class="form-group store_list">
									<volist name="store_list" id="vo">
										<div class="radio">
											<label>
												<input class="paycheck ace store-list" type="checkbox" name="store_id[]" value="{pigcms{$vo.store_id}" id="store{pigcms{$vo.store_id}" />
												<span class="lbl"><label for="store{pigcms{$vo.store_id}">{pigcms{$vo.name} - {pigcms{$vo.area_name}-{pigcms{$vo.adress}</label></span>
											</label>
											<label><input class="paycheck ace " name="store_sort[]" value="" type="text" style="width:350px" placeholder="排序值(此排序控制店铺内预约商品的前后顺序。)" /></label>
										</div>

                                        <if condition="$vo['worker_list']">
                                        <div style=" margin:5px 0 0 60px;display:none" class="worker-list" data-store-id="{pigcms{$vo.store_id}">
                                        	<volist name="vo['worker_list']" id="val">
                                                <label style="margin-right:10px">
                                                    <input class="paycheck ace" type="checkbox" name="worker_memus[]" value="{pigcms{$vo.store_id},{pigcms{$val.merchant_worker_id}" id="worker{pigcms{$val.merchant_worker_id}"/>
                                                    <span class="lbl"><label for="worker{pigcms{$val.merchant_worker_id}">&nbsp;&nbsp;{pigcms{$val.name}</label></span>
                                                </label>
                                            </volist>
										</div>
                                        </if>
									</volist>
								</div>
							</div>

							<div id="txtimage" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1">上传图片</label>
									<a href="javascript:void(0)" class="btn btn-sm btn-success" id="J_selectImage">上传图片</a>
									<span class="form_tips red">&nbsp;*&nbsp;第一张将作为列表页图片展示！最多上传5个图片！<php>if(!empty($config['group_pic_width'])){$group_pic_width=explode(',',$config['group_pic_width']);echo '图片宽度建议为：'.$group_pic_width[0].'px，';}</php><php>if(!empty($config['group_pic_height'])){$group_pic_height=explode(',',$config['group_pic_height']);echo '高度建议为：'.$group_pic_height[0].'px';}</php></span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">图片预览</label>
									<div id="upload_pic_box">
										<ul id="upload_pic_ul"></ul>
									</div>
								</div>
							</div>

							<div id="txtattr" class="tab-pane">
								<label style="margin-left:20px"><label for="is_store">规格类型：</label></label>
								<select name="product_type" style="margin-left:10px">
									<option value="0">开启时间段</option>
									<option value="1">关闭时间段</option>
								</select>
								<span class="form_tips red">开启，表示预约商品与店员的时间段照常显示在前台，反之，则用户前台隐藏选择时间段功能	</span>
								<div style="border:1px solid #c5d0dc;padding-left:22px;margin-bottom:10px;" id="cue_html_tips">
									<table class="table table-striped">
										<tbody>
											<tr>
												<th scope="col">规格序号</th>
												<th scope="col">规格名称</th>
												<th scope="col">规格定金</th>
												<th scope="col">规格全价</th>
												<th scope="col">规格描述</th>
                                                <th scope="col">平均用时（分钟）</th>
											</tr>
											<for start="1" end="10">
												<tr class="parent" data-index="{pigcms{$i}">
													<td><i class="ace-icon fa"></i>序号{pigcms{$i}</td>
													<td><input name="custom_name[]" type="text" class="span2 title" value=""></td>
													<td>￥<input name="custom_payment_price[]" type="text" class="span2 keyword" value=""></td>
													<td>￥<input name="custom_price[]" type="text" class="span2 keyword" value=""></td>
													<td><input name="custom_content[]" type="text" class="span3 url" value=""></td>
                                                    <td><input name="use_time[]" type="text" class="span3 url" value=""></td>
												</tr>
											</for>
										</tbody>
									</table>
								</div>
							</div>



							<div id="txtorder" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1">预约状态：</label>
									<select name="appoint_status" class="col-sm-1">
										<option value="0">开启</option>
										<option value="1">关闭</option>
									</select>
									<span class="form_tips red">为了方便用户能查找到以前的订单，预约无法删除！</span>
								</div>
							</div>

							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit" id="save_btn">
										<i class="ace-icon fa fa-check bigger-110"></i>
										保存
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<link rel="stylesheet" href="{pigcms{$static_path}css/appoint_add.css">
<style>
    .reduce_recharge {
        background: url("{pigcms{$static_path}css/img/reduce_nc.png") no-repeat;
        width: 32px;
        height: 32px;
        border: none;
        position: absolute;
        margin-left: 5px;
    }
    .add_tips_info {
        margin-left: 40px;
    }
</style>
<script type="text/javascript">
<!--

var ajax_worker_list_url = "{pigcms{:U('Appoint/ajax_worker_list')}";

//-->
</script>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_path}js/appoint_add.js"></script>
<script>
$(".tab-content").on("focus",".Config_shop_start_time",function(){
　　　$(this).timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'10','minute':'52','second':'25'}));
});
$(".tab-content").on("focus",".Config_shop_stop_time",function(){
　　　$(this).timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'10','minute':'52','second':'25'}));
});

// 添加时间段
var index = 1;
$('#add_shop_time').click(function(){
	index = index + 1;
	var html_div = '';
	var end_time = $("input[name='office_stop_time[]']:last").val();
	var arr = end_time.split(':');
	console.log(arr);

	if (parseInt(arr[0]) >= 23 && parseInt(arr[1]) >= 59 ) {
		alert('不能添加时间段');
		return false;
	}

	html_div = '<div id="shop_time_1" style="margin-top: 5px;" class="tab-pane in active"><div><input class="Config_shop_start_time" type="text" value="'+end_time+'" name="office_start_time[]"/>&nbsp;&nbsp;至&nbsp;&nbsp;	<input class="Config_shop_stop_time" type="text" value="" name="office_stop_time[]" /><button type="button" class="reduce_recharge"></button><span class="form_tips red add_tips_info">&nbsp;*&nbsp;如果营业时间段设置为00:00-00:00，则表示24小时营业</span></div></div>';
	$('#office_time').append(html_div);
    // 删除
    $(".reduce_recharge").click(function() {
        if($('#office_time').children().length<=1){
            $(this).val('');
        }else{
            $(this).css('visibility','hidden');
            $(this).parents('#shop_time_1').remove();
        }
    });

});


var diyVideo = "{pigcms{:U('Article/diyVideo')}";
KindEditor.ready(function(K) {
	var content_editor = K.create("#content",{
		width:'702px',
		height:'260px',
		resizeType : 1,
		allowPreviewEmoticons:false,
		allowImageUpload : true,
		filterMode: true,
		autoHeightMode : true,
		afterCreate : function() {
			this.loadPlugin('autoheight');
		},
		items : [
			'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist', '|', 'emoticons', 'image', 'link', 'table','diyVideo'
		],
		emoticonsPath : './static/emoticons/',
		uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=appoint/content",
		cssPath : "{pigcms{$static_path}css/group_editor.css"
	});

	$('input[name="is_store"]').each(function(i){
		$(this).click(function(){
			if(i == 0){
				$('.store_list').show();
			}else{
				$('.store_list').hide();
			}
		});
	});

	var editor = K.editor({
		allowFileManager : true
	});
	K('#J_selectImage').click(function(){
		if($('.upload_pic_li').size() >= 5){
			alert('最多上传5个图片！');
			return false;
		}
		editor.uploadJson = "{pigcms{:U('Appoint/ajax_upload_pic')}";
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				showRemote : false,
				imageUrl : K('#course_pic').val(),
				clickFn : function(url, title, width, height, border, align) {
					$('#upload_pic_ul').append('<li class="upload_pic_li"><img src="'+url+'"/><input type="hidden" name="pic[]" value="'+title+'"/><br/><a href="#" onclick="deleteImg(\''+title+'\',this);return false;">[ 删除 ]</a></li>');
					editor.hideDialog();
				}
			});
		});
	});

	$('#choose_catfid').change(function(){
		$.getJSON("{pigcms{:U('Appoint/ajax_get_category')}",{cat_fid:$(this).val()},function(result){
			var html = '';
			html += '<option value="">请选择</option>';
			if(result.error == 0){
				for ( var i=0; i<result.cat_list.length; i++){
                    html += '<option value="'+ result.cat_list[i].cat_id +'" data-is-autotrophic="'+result.cat_list[i].is_autotrophic+'">' + result.cat_list[i].cat_name + '</option>';
                }
                $('#choose_catid').html(html);
            } else {
                $("#choose_catid").html(html);
            }
		});
	});

	$('#choose_catid').change(function(){
		var cat_id = $(this).val();
		var is_autotrophic = $(this).find(':selected').data('is-autotrophic');
		$('#cat_id').attr('value', cat_id);
		if(is_autotrophic==1){
			$('#autotrophic_warn').show();
		}else{
			$('#autotrophic_warn').hide();
		}

	});

	$('#add_form').submit(function(){
		content_editor.sync();
		$('#save_btn').prop('disabled',true);
		$.post("{pigcms{:U('Appoint/add')}",$('#add_form').serialize(),function(result){
			if(result.status == 1){
				alert(result.info);
				window.location.href = "{pigcms{:U('Appoint/index')}";
			}else{
				alert(result.info);
			}
			$('#save_btn').prop('disabled',false);
		})
		return false;
	});

	$('#editor_plan_btn').click(function(){
		var dialog = K.dialog({
				width : 200,
				title : '输入欲插入表格行数',
				body : '<div style="margin:10px;"><input id="edit_plan_input" style="width:100%;"/></div>',
				closeBtn : {
						name : '关闭',
						click : function(e) {
							dialog.remove();
						}
				},
				yesBtn : {
						name : '确定',
						click : function(e){
							var value = $('#edit_plan_input').val();
							if(!/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(value)){
								alert('请输入数字！');
								return false;
							}
							value = parseInt(value);
							var html = '<table class="deal-menu">';
							html += '<tr><th class="name" colspan="2">套餐内容</th><th class="price">单价</th><th class="amount">数量/规格</th><th class="subtotal">小计</th></tr>';
							for(var i=0;i<value;i++){
								html += '<tr><td class="name" colspan="2">内容'+(i+1)+'</td><td class="price">¥</td><td class="amount">1份</td><td class="subtotal">¥</td></tr>';
							}
							html += '</table>';
							html += '<p class="deal-menu-summary">价值: <span class="inline-block worth">¥</span>{pigcms{$config.group_alias_name}价： <span class="inline-block worth price">¥</span></p><br/><br/>介绍...';
							content_editor.appendHtml(html);

							dialog.remove();
						}
				},
				noBtn : {
						name : '取消',
						click : function(e) {
							dialog.remove();
						}
				}
		});
	});
});
</script>
<include file="Public:footer"/>