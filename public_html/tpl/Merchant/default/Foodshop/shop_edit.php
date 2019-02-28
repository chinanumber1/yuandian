<include file="Public:header"/>
<div class="main-content">
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Foodshop/index')}">{pigcms{$config.meal_alias_name}管理</a>
			</li>
			<li class="active">编辑店铺信息</li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">
							<li class="active">
								<a data-toggle="tab" href="#basicinfo">基本信息</a>
							</li>
							<li>
								<a data-toggle="tab" href="#category">选择分类</a>
							</li>
							<!--li>
								<a data-toggle="tab" href="#promotion">店铺折扣</a>
							</li>
							<li>
								<a data-toggle="tab" href="#stock">库存类型选择</a>
							</li-->
						  	<if condition="!empty($levelarr) AND false">
							<li>
								<a data-toggle="tab" href="#levelcoupon">会员优惠</a>
							</li>
							</if>
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
                                <div class="form-group">
                                    <label class="col-sm-1">餐厅环境图片</label>
                                    <div style="display:inline-block;" id="J_selectImage">
                                        <div class="btn btn-sm btn-success" style="position:relative;width:78px;height:34px;">上传图片</div>
                                    </div>
                                    <span class="form_tips">第一张将作为主图片！最多上传5个图片！图片宽度建议为900px，高度建议为450px。</span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1">图片预览</label>
                                    <div id="upload_pic_box">
                                        <ul id="upload_pic_ul">
                                            <volist name="store_shop['pic_arr']" id="vo">
                                                <li class="upload_pic_li"><img src="{pigcms{$vo.url}"/><input type="hidden" name="pic[]" value="{pigcms{$vo.title}"/><br/><a href="#" onclick="deleteImage('{pigcms{$vo.title}',this);return false;">[ 删除 ]</a></li>
                                            </volist>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1">点餐页背景图</label>
                                    <div style="display:inline-block;" id="background">
                                        <div class="btn btn-sm btn-success" style="position:relative;width:78px;height:34px;">上传图片</div>
                                    </div>
                                    <span class="form_tips">系统默认给一张默认图！图片建议尺寸为1920*1080，大小小于1M。</span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1">图片预览</label>
                                    <div id="upload_pic_box">
                                        <ul id="upload_backgroud_li">
                                            <volist name="store_shop['background_arr']" id="vo">
                                                <li class="upload_backgroud_li"><img src="{pigcms{$vo.url}"/><input type="hidden" name="background[]" value="{pigcms{$vo.title}"/><br/><a href="#" onclick="deleteImage('{pigcms{$vo.title}',this);return false;">[ 删除 ]</a></li>
                                            </volist>
                                        </ul>
                                    </div>
                                </div>
								<if condition="empty($store_shop)">
								<div class="alert alert-info" style="margin:10px;">
								<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>同步数据只能在完善店铺信息的时候同步，以后修改店铺时不允许同步
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>同步原餐饮的商品</label></label>
									<label><input name="sysnc" value="0" type="radio">&nbsp;&nbsp;不同步</label>&nbsp;&nbsp;&nbsp;
									<label><input name="sysnc" checked="checked" value="1" type="radio" >&nbsp;&nbsp;同步</label>&nbsp;&nbsp;&nbsp;
								</div>
								</if>
								
								<!--div class="form-group">
									<label class="col-sm-1"><label for="Config_notice">店铺公告</label></label>
									<textarea class="col-sm-3" rows="4" name="store_notice" id="Config_notice">{pigcms{$store_shop.store_notice}</textarea>
								</div-->
								
								<!--div class="form-group">
									<label class="col-sm-1"><label>开发票</label></label>
									<label><input name="is_invoice" <if condition="$store_shop['is_invoice'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;&nbsp;不支持</label>&nbsp;&nbsp;&nbsp;
									<label><input name="is_invoice" <if condition="$store_shop['is_invoice'] eq 1 ">checked="checked"</if> value="1" type="radio">&nbsp;&nbsp;支持</label>&nbsp;&nbsp;&nbsp;
								</div>
								
								<div class="form-group invoice" <if condition="$store_shop['is_invoice'] eq 0 ">style="display:none"</if>>
									<label class="col-sm-1">满足</label>
									<input class="col-sm-1" size="10" maxlength="10" name="invoice_price" id="Config_invoice_price" type="text" value="{pigcms{$store_shop.invoice_price|floatval}" />
									<label class="col-sm-1">元，可开发票</label>
								</div-->
								
								<div class="form-group">
									<label class="col-sm-1"><label>预订</label></label>
									<label><input name="is_book" <if condition="$store_shop['is_book'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;&nbsp;不支持</label>&nbsp;&nbsp;&nbsp;
									<label><input name="is_book" <if condition="$store_shop['is_book'] eq 1 ">checked="checked"</if> value="1" type="radio" >&nbsp;&nbsp;支持</label>
								</div>
								<div class="form-group book" <if condition="$store_shop['is_book'] eq 0 ">style="display:none"</if>>
									<label class="col-sm-1"><label>预订时长</label></label>
									<div>
										<input id="book_day" type="text" value="{pigcms{$store_shop.book_day|default=1}" name="book_day"/>
										<span class="form_tips red">可提前预订多少天后的桌台</span>
									</div>
								</div>
								<div class="form-group book" <if condition="$store_shop['is_book'] eq 0 ">style="display:none"</if>>
									<label class="col-sm-1"><label>预订时间</label></label>
									<div>
										<input id="book_start" type="text" value="{pigcms{$store_shop.book_start|default='00:00'}" name="book_start" readonly/>	至
										<input id="book_stop" type="text" value="{pigcms{$store_shop.book_stop|default='23:59'}" name="book_stop" readonly/>
										<span class="form_tips red">如果两个都不填写的话，表示从零点开始，按预订间隔时长进行全天预订</span>
									</div>
								</div>
								<div class="form-group book" <if condition="$store_shop['is_book'] eq 0 ">style="display:none"</if>>
									<label class="col-sm-1">预订间隔时长</label>
									<input class="col-sm-1" name="book_time" type="text" value="{pigcms{$store_shop.book_time|default=60}" />
									<span class="form_tips red">两个可预订时间之间相隔的时长，单位（分钟）</span>
								</div>
								<div class="form-group book" <if condition="$store_shop['is_book'] eq 0 ">style="display:none"</if>>
									<label class="col-sm-1">提前取消时长</label>
									<input class="col-sm-1" name="cancel_time" type="text" value="{pigcms{$store_shop.cancel_time|default=60}" />
									<span class="form_tips red">至少要提前多久才能取消，否则不能取消，单位（分钟）</span>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label>排号</label></label>
									<label><input name="is_queue" <if condition="$store_shop['is_queue'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;&nbsp;不支持</label>&nbsp;&nbsp;&nbsp;
									<label><input name="is_queue" <if condition="$store_shop['is_queue'] eq 1 ">checked="checked"</if> value="1" type="radio" >&nbsp;&nbsp;支持</label>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label>外送</label></label>
									<label><input name="is_takeout" <if condition="$store_shop['is_takeout'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;&nbsp;不支持</label>&nbsp;&nbsp;&nbsp;
									<label><input name="is_takeout" <if condition="$store_shop['is_takeout'] eq 1 ">checked="checked"</if> value="1" type="radio" >&nbsp;&nbsp;支持</label>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label>停车位</label></label>
									<label><input name="is_park" <if condition="$store_shop['is_park'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;&nbsp;没有</label>&nbsp;&nbsp;&nbsp;
									<label><input name="is_park" <if condition="$store_shop['is_park'] eq 1 ">checked="checked"</if> value="1" type="radio" >&nbsp;&nbsp;有</label>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1">店员确认上菜</label>
									<label><input name="is_auto_order" <if condition="$store_shop['is_auto_order'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;&nbsp;不要确认</label>&nbsp;&nbsp;&nbsp;
									<label><input name="is_auto_order" <if condition="$store_shop['is_auto_order'] eq 1 ">checked="checked"</if> value="1" type="radio" >&nbsp;&nbsp;要确认</label>
									<span class="form_tips red">客户在通知上菜的时候是否要店员确认后再上菜</span>
								</div>
                                
                                <div class="form-group">
                                    <label class="col-sm-1">点餐页模板</label>
                                    <label><input name="template" <if condition="$store_shop['template'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;&nbsp;列表</label>&nbsp;&nbsp;&nbsp;
                                    <label><input name="template" <if condition="$store_shop['template'] eq 1 ">checked="checked"</if> value="1" type="radio" >&nbsp;&nbsp;图片</label>
                                    <span class="form_tips red">点餐页模板的界面显示形式</span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1">推荐别名</label>
                                    <div>
                                        <input id="hot_alias_name" type="text" value="{pigcms{$store_shop.hot_alias_name|default='推荐榜'}" name="hot_alias_name"/>
                                        <span class="form_tips red">建议四个字以内</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1">热销榜显示个数</label>
                                    <div>
                                        <input id="ranking_num" type="text" value="{pigcms{$store_shop.ranking_num|default=10}" name="ranking_num"/>
                                        <span class="form_tips red">热销榜显示前几位</span>
                                    </div>
                                </div>
								
								<!--div class="form-group">
									<label class="col-sm-1">人均消费</label>
									<input class="col-sm-1" size="10" maxlength="10" name="mean_money" id="Config_mean_money" type="text" value="{pigcms{$store_shop.mean_money|floatval}" />
									<span class="form_tips">元</span>
								</div-->
							</div>
							<div id="category" class="tab-pane">
								<volist name="category_list" id="vo">
									<div class="form-group">
										<div class="radio">
											<label>
												<span class="lbl"><label style="color: red">{pigcms{$vo.cat_name}：</label></span>
											</label>
											<volist name="vo['son_list']" id="child">
												<label>
													<input class="cat_class" type="checkbox" name="store_category[]" value="{pigcms{$vo.cat_id}-{pigcms{$child.cat_id}" id="Config_store_category_{pigcms{$child.cat_id}" <if condition="in_array($child['cat_id'],$relation_array)">checked="checked"</if>/>
													<span class="lbl"><label for="Config_store_category_{pigcms{$child.cat_id}">{pigcms{$child.cat_name}</label></span>
												</label>
											</volist>
										</div>
									</div>
								</volist>
							</div>
							<div id="label" class="tab-pane">
								<volist name="label_list" id="vo">
									<div class="form-group">
										<div class="radio">
											<label>
												<input class="cat_class" type="checkbox" name="store_labels[]" value="{pigcms{$vo.id}" id="Config_store_label_{pigcms{$vo.id}" <if condition="in_array($vo['id'], $store_shop['store_labels'])">checked="checked"</if>/>
												<span class="lbl"><label for="Config_store_label_{pigcms{$vo.id}">{pigcms{$vo.name}</label></span>
											</label>
										</div>
									</div>
								</volist>
							</div>
							<div id="promotion" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1">店铺折扣</label>
									<input class="col-sm-1" size="10" maxlength="10" name="store_discount" id="Config_mean_full_money" type="text" value="{pigcms{$store_shop.store_discount}" /><strong style="color:red">请填写0~100之间的整数，0和100都是表示无折扣，98表示9.8折</strong>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>优惠方式</label></label>
									<span><label><input id='discount_type0' name="discount_type" <if condition="$store_shop['discount_type'] eq 0 ">checked="checked"</if> value="0" type="radio"></label>&nbsp;<span>折上折</span>&nbsp;</span>
									<span><label><input id='discount_type1' name="discount_type" <if condition="$store_shop['discount_type'] eq 1 ">checked="checked"</if> value="1" type="radio" ></label>&nbsp;<span>折扣最优</span></span>
									<strong style="color:red">折上折的意思是如果这个用户是有平台VIP等级，平台VIP等级有折扣优惠。那么这个用户的优惠计算方式是先用店铺的优惠进行打折后，再用VIP折扣进去打折；<br/>
									折扣最优是指：购买产品的总价用店铺优惠打折后的价格与总价跟VIP优惠打折后的价格进行比较，取最小值的优惠方式。
									</strong>
								</div>
								<div style="clear:both;"></div>
							</div>
							
							<div id="stock" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1">库存类型：</label>
									<label><input type="radio" name="stock_type" value="0" <if condition="$store_shop['stock_type'] eq 0">checked="checked"</if>>&nbsp;&nbsp;每天自动更新固定量的库存</label>&nbsp;&nbsp;&nbsp;
									<label><input type="radio" name="stock_type" value="1" <if condition="$store_shop['stock_type'] eq 1">checked="checked"</if>>&nbsp;&nbsp;固定的库存，不会每天自动更新</label>&nbsp;&nbsp;&nbsp;
								</div>
								<div style="clear:both;"></div>
							</div>

							<if condition="!empty($levelarr)">
							<div id="levelcoupon" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1" style="color:red;width:95%;">说明：必须设置一个会员等级优惠类型和优惠类型对应的数值，我们将结合优惠类型和所填的数值来计算该商品会员等级的优惠的幅度！</label>
								</div>
							    <volist name="levelarr" id="vv">
								  <div class="form-group">
								    <input  name="leveloff[{pigcms{$vv['level']}][lid]" type="hidden" value="{pigcms{$vv['id']}"/>
								    <input  name="leveloff[{pigcms{$vv['level']}][lname]" type="hidden" value="{pigcms{$vv['lname']}"/>
									<label class="col-sm-1">{pigcms{$vv['lname']}：</label>
									优惠类型：&nbsp;
									<select name="leveloff[{pigcms{$vv['level']}][type]">
										<option value="0">无优惠</option>
										<option value="1" <if condition="$vv['type'] eq 1">selected="selected"</if>>百分比（%）</option>
										<!--<option value="2">立减</option>-->
									</select>
									<input name="leveloff[{pigcms{$vv['level']}][vv]" type="text" value="{pigcms{$vv['vv']}" placeholder="请填写一个优惠值数字" onkeyup="value=value.replace(/[^1234567890]+/g,'')"/>
								</div>
								</volist>
							</div>
							</if>
							
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
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
#upload_pic_box .upload_pic_li{width:130px;float:left;list-style:none;}
#upload_pic_box .upload_image_li{width:130px;float:left;list-style:none;}
#upload_pic_box img{width:100px;height:70px;}
.webuploader-element-invisible {
    position: absolute !important;
    clip: rect(1px 1px 1px 1px);
    clip: rect(1px,1px,1px,1px);
}
.webuploader-pick-hover .btn{
	background-color: #629b58!important;
    border-color: #87b87f;
}
</style>
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script>
$(document).ready(function(){
    var uploader = WebUploader.create({
    	auto: true,
    	swf: '{pigcms{$static_public}js/Uploader.swf',
    	server: "{pigcms{:U('Foodshop/ajax_store_pic')}",
    	pick: {
    		id:'#J_selectImage',
    		multiple:false
    	},
    	accept: {
    		title: 'Images',
    		extensions: 'gif,jpg,jpeg,png',
    		mimeTypes: 'image/gif,image/jpeg,image/jpg,image/png'
    	}
    });
    uploader.on('fileQueued',function(file){
    	if($('.upload_pic_li').size() >= 5){
    		uploader.cancelFile(file);
    		alert('最多上传5个图片！');
    		return false;
    	}
    });
    uploader.on('uploadSuccess',function(file,response){
    	if(response.error == 0){
    		$('#upload_pic_ul').append('<li class="upload_pic_li"><img src="'+response.url+'"/><input type="hidden" name="pic[]" value="'+response.title+'"/><br/><a href="#" onclick="deleteImage(\''+response.title+'\',this);return false;">[ 删除 ]</a></li>');
    	}else{
    		alert(response.info);
    	}
    });
    uploader.on('uploadError', function(file,reason){
    	$('.loading'+file.id).remove();
    	alert('上传失败！请重试。');
    });

    
    var uploaderBackGround = WebUploader.create({
        auto: true,
        swf: '{pigcms{$static_public}js/Uploader.swf',
        server: "{pigcms{:U('Foodshop/ajax_store_pic')}",
        pick: {
            id:'#background',
            multiple:false
        },
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,png',
            mimeTypes: 'image/gif,image/jpeg,image/jpg,image/png'
        }
    });
    uploaderBackGround.on('fileQueued',function(file){
        if($('.upload_backgroud_li').size() >= 1){
            uploader.cancelFile(file);
            alert('最多上传1个图片！');
            return false;
        }
    });
    uploaderBackGround.on('uploadSuccess',function(file,response){
        if(response.error == 0){
            $('#upload_backgroud_li').append('<li class="upload_backgroud_li"><img src="'+response.url+'"/><input type="hidden" name="background[]" value="'+response.title+'"/><br/><a href="#" onclick="deleteImage(\''+response.title+'\',this);return false;">[ 删除 ]</a></li>');
        }else{
            alert(response.info);
        }
    });
    uploaderBackGround.on('uploadError', function(file,reason){
        $('.loading'+file.id).remove();
        alert('上传失败！请重试。');
    });
});
function deleteImage(path,obj){
	$.post("{pigcms{:U('Foodshop/ajax_delstore_pic')}",{path:path});
	$(obj).closest('li').remove();
}
function check(obj){
	var length = $('.paycheck:checked').length;
	if(length == 0){
		$(obj).attr('checked','checked');
		bootbox.alert('最少要选择一种支付方式');
	}			
}
$(function($){
	$('input[name=is_book]').click(function(){
		if ($(this).val() == 1) {
			$('.book').css('display', 'block');
		} else {
			$('.book').css('display', 'none');
		}
	});
	$('input[name=is_invoice]').click(function(){
		if ($(this).val() == 1) {
			$('.invoice').css('display', 'block');
		} else {
			$('.invoice').css('display', 'none');
		}
	});
	
	$('#book_start').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm:ss','hour':'00','minute':'00'}));
	$('#book_stop').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm:ss','hour':'23','minute':'59'}));

	var is_submit = false;
	$('#edit_form').submit(function(){
		if (is_submit) return false;
		is_submit = true;
		$.post("{pigcms{:U('Foodshop/shop_edit',array('store_id'=>$_GET['store_id']))}",$('#edit_form').serialize(),function(result){
			if(result.status == 1){
				alert(result.info);
				window.location.href = "{pigcms{:U('Foodshop/index')}";
			}else{
				is_submit = false;
				alert(result.info);
			}
		})
		return false;
	});
});
</script>
<include file="Public:footer"/>
