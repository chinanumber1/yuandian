<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Shop/index')}">{pigcms{$config.shop_alias_name}管理</a>
			</li>
			<li class="active">发布商品到批发市场</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
				#levelcoupon select {width:150px;margin-right: 20px;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">
							<li class="active">
								<a data-toggle="tab" href="#basicinfo">商品信息</a>
							</li>
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post">
						<input type="hidden" value="{pigcms{$now_goods.goods_id}" id="goods_id" />
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane  active">
								<if condition="$error_tips">
									<div class="alert alert-danger">
										<p>请更正下列输入错误:</p>
										<p>{pigcms{$error_tips}</p>
									</div>
								</if>
								<if condition="$ok_tips">
									<div class="alert alert-info">
										<p>{pigcms{$ok_tips}</p>
									</div>
								</if>
								<div class="form-group">
									<label class="col-sm-1"><label for="name">商品名称</label></label>
									<input class="col-sm-1" size="20" name="name" id="name" type="text" value="{pigcms{$now_goods.name}" disabled/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="name">商品条形码</label></label>
									<input class="col-sm-1" size="20" name="number" id="number" type="text" value="{pigcms{$now_goods.number}" disabled/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="unit">商品单位</label></label>
									<input class="col-sm-1" size="20" name="unit" id="unit" type="text" value="{pigcms{$now_goods.unit}" disabled/>
								</div>
                                <!--div class="form-group">
                                    <label class="col-sm-1" for="Food_status">商品状态</label>
                                    <select name="status" id="Food_status">
                                        <option value="1" <if condition="$now_goods['status'] eq 1">selected="selected"</if>>正常</option>
                                        <option value="0" <if condition="$now_goods['status'] eq 0">selected="selected"</if>>下架</option>
                                    </select>
                                </div-->
                                <div class="form-group">
                                    <label class="col-sm-1" for="Food_status">商品分类</label>
                                    <fieldset id="choose_category" cat_fid="{pigcms{$now_goods.cat_fid}" cat_id="{pigcms{$now_goods.cat_id}"></fieldset>
                                </div>
								<if condition="empty($now_goods['list'])">
								<div class="form-group">
									<label class="col-sm-1"><label for="wholesale_price">批发价</label></label>
									<input class="col-sm-1" size="20" name="wholesale_price" id="wholesale_price" type="text" value="{pigcms{$now_goods.wholesale_price}"/>
									<span class="form_tips">必填。</span>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="min_num">最低批发数</label></label>
									<input class="col-sm-1" size="20" name="min_num" id="min_num" type="text" value="{pigcms{$now_goods.min_num}"/>
									<span class="form_tips">必填。</span>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="stock">库存</label></label>
									<input class="col-sm-1" size="20" name="stock" id="stock" type="text" value="{pigcms{$now_goods.stock}"/>
									<span class="form_tips">必填。</span>
								</div>
								</if>
								<div class="form-group">
									<label class="col-sm-1">图片预览</label>
									<div id="upload_pic_box">
										<ul id="upload_pic_ul">
											<volist name="now_goods['pic_arr']" id="vo">
												<li class="upload_pic_li"><img src="{pigcms{$vo.url}"/></li>
											</volist>
										</ul>
									</div>
								</div>
								<div class="form-group topic_box">
								    <volist name="now_goods['discount_info']" id="drow">
								    <div class="question_box properties">
								    <p class="question_info">
								    <span>批发满：</span>
								    <input type="text" class="txt properties_name" value="{pigcms{$drow['num']}" name="nums[]" style="width:50px"/>
								    <span>件，享受：</span>
								    <input type="text" class="txt properties_num" value="{pigcms{$drow['discount']}" name="discounts[]" style="width:50px"/>折优惠　
                                    <a href="javascript:;" class="box_del">删除</a></p></div>
								    </volist>
								    <p class="add_properties" style="margin-left:12px;"><a href="javascript:;" title="添加" class="btn btn-sm btn-success">新增优惠</a></p>
								</div>
								<div class="topic_box">
									<table class="table table-striped table-bordered table-hover" id="table_list">
									<if condition="$now_goods['spec_list']">
									<tbody>
										<tr>
											<th>商品条形码</th>
											<volist name="now_goods['spec_list']" id="gs">
											<th>{pigcms{$gs['name']}</th>
											</volist>
											<th>批发价</th><th>库存</th><th>最低批发数</th>
										</tr>
										
										<volist name="now_goods['list']" id="gl" key="id_index" >
											<tr id="{pigcms{$gl['index']}">
												<td>{pigcms{$gl['number']}</td>
												<volist name="gl['spec']" id="g">
												<td>{pigcms{$g['spec_val_name']}</td>
												</volist>
												
												<td><input type="text" class="txt" name="wholesale_prices[{pigcms{$gl['index']}]" value="{pigcms{$gl['wholesale_prices']}" style="width:80px;"></td>
												<td><input type="text" class="txt" name="stocks[{pigcms{$gl['index']}]" value="{pigcms{$gl['stocks']}" style="width:80px;"></td>
												<td><input type="text" class="txt" name="min_nums[{pigcms{$gl['index']}]" value="{pigcms{$gl['min_nums']}" style="width:80px;"></td>
											</tr>
										</volist>
									</tbody>
									</if>
									</table>
								</div>
							</div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa fa-check bigger-110"></i>
										发布
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
<link rel="stylesheet" href="{pigcms{$static_path}css/activity.css">
<script>
var category_list = '{pigcms{$category_list}';
$(document).ready(function(){
	$(".add_properties").click(function(){
		var i = $('.properties').size();
		var t = '<div class="question_box properties"><p class="question_info"><span>批发满：</span><input type="text" class="txt properties_name" value="" name="nums[]" style="width:50px"/><span>件，享受：</span><input type="text" class="txt properties_num" value="" name="discounts[]" style="width:50px"/>折优惠　'
				+'<a href="javascript:;" class="box_del">删除</a></p></div>';
		$(".add_properties").before(t);
	});
	$(document).on('click', '.box_del', function(){
	    $(this).parents('.properties').remove();
	});

	if (category_list != undefined) {
		var father_category_list = $.parseJSON(category_list);
		var son_category_list = null, cat_fid = parseInt($('#choose_category').attr('cat_fid')), cat_id = parseInt($('#choose_category').attr('cat_id'));
		var area_dom = '<select id="cat_fid" name="cat_fid" class="col-sm-1" style="margin-right:10px;">';
		if (cat_fid == 0) {
			area_dom += '<option value="0" selected="selected" >选择分类</option>';
		} else {
			area_dom += '<option value="0">选择分类</option>';
		}
		$.each(father_category_list, function(i, item){
			if (item.id == cat_fid) {
				if (item.son_list != undefined) {
					son_category_list = item.son_list;
					show_son_category(item.son_list, cat_id)
				}
				area_dom += '<option value="'+item.id+'" selected="selected" >'+item.name+'</option>';
			} else {
				area_dom += '<option value="'+item.id+'">'+item.name+'</option>';
			}
		});
		area_dom+= '</select>';
		$('#choose_category').prepend(area_dom);
//		if (son_category_list != null) show_son_category(son_category_list, cat_id);
	}
	$('#cat_fid').change(function(){
		var cat_fid = $(this).val(), father_category_list = $.parseJSON(category_list);
		var this_son_list = null;
		$.each(father_category_list, function(i, item){
			if (item.id == cat_fid) {
				if (item.son_list != undefined) {
					this_son_list = item.son_list;
					show_son_category(item.son_list, 0);
				}
			}
		});
		if (this_son_list == null) {
			$('.sortproperties').remove();
			if (document.getElementById('cat_id')) {
				$('#cat_id').replaceWith('');
			} else if(document.getElementById('cat_fid')) {
				$('#cat_fid').after('');
			} else {
				$('#choose_category').prepend('');
			}
		}
	});
});
function show_son_category(son_category_list, cat_id)
{
    var area_dom = '<select id="cat_id" name="cat_id" class="col-sm-1" style="margin-right:10px;">', now_cat_id = 0, isFirst = 0;
    $.each(son_category_list, function(i, item){
        if (cat_id == 0) {
            if (isFirst == 0) {
                now_cat_id = item.id;
                area_dom += '<option value="'+item.id+'" selected="selected" >'+item.name+'</option>';
            } else {
                area_dom += '<option value="'+item.id+'">'+item.name+'</option>';
            }
            isFirst ++;
        } else {
            if (item.id == cat_id) {
                now_cat_id = item.id;
                area_dom += '<option value="'+item.id+'" selected="selected" >'+item.name+'</option>';
            } else {
                area_dom += '<option value="'+item.id+'">'+item.name+'</option>';
            }
        }
    });
    area_dom += '</select>';
    if (document.getElementById('cat_id')) {
        $('#cat_id').replaceWith(area_dom);
    } else if(document.getElementById('cat_fid')) {
        $('#cat_fid').after(area_dom);
    } else {
        $('#choose_category').prepend(area_dom);
    }
}
</script>
<include file="Public:footer"/>
