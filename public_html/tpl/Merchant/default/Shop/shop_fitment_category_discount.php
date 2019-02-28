<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 装修优惠分类</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
		<style>
			a:hover,a:visited{color:#666;}
			table th{padding:17px 10px 19px;}
			table td{padding:17px 10px 19px 15px;}
			#good_list li{list-style: none;}
			#good_list li .del{color:blue;float:right;cursor:pointer;-webkit-user-select: none;-webkit-touch-callout: none;}
			#good_list li:hover{color:red;}
			#good_list li .del:hover{text-decoration:underline;color:red;}
		</style>
	</head>
	<body>
		<form id="myform" method="post" action="{pigcms{:U('shop_fitment_category_discount_save')}" autocomplete="off">
			<input type="hidden" name="store_id" value="{pigcms{$_GET.store_id}"/>
			<table>
				<tr>
					<th width="20%">是否展示</th>
					<td width="80%" colspan="3">
						<select name="cat_discount_status" id="cat_discount_status" style="width:150px;">
							<option value="1" <if condition="$subject_info['cat_discount_status'] eq '1'">selected="selected"</if>>展示</option>
							<option value="0" <if condition="$subject_info['cat_discount_status'] eq '0'">selected="selected"</if>>隐藏</option>
						</select>
					</td>
				</tr>
				<tr>
					<th width="20%">分类名称</th>
					<td width="80%" colspan="3">
						<input type="text" class="input" name="cat_discount_name" id="cat_discount_name" value="{pigcms{$subject_info.cat_discount_name|default='优惠'}" style="width:200px;"/>
						<span>限制2-4字</span>
					</td>
				</tr>
				<tr>
					<th width="20%">分类描述</th>
					<td width="80%" colspan="3">
						<textarea class="input" name="cat_discount_desc" id="cat_discount_desc" style="width:200px;height:50px;">{pigcms{$subject_info.cat_discount_desc|default='美味又实惠，大家快来抢'}</textarea>
						<span>50字以内</span>
					</td>
				</tr>
				<tr>
					<th width="20%">排序方式</th>
					<td width="80%" colspan="3">
						<select name="cat_discount_sort" id="cat_discount_sort" style="width:150px;">
							<option value="0" <if condition="$subject_info['cat_discount_sort'] eq '0'">selected="selected"</if>>默认排序</option>
							<option value="1" <if condition="$subject_info['cat_discount_sort'] eq '1'">selected="selected"</if>>价格从高到低排序</option>
							<option value="2" <if condition="$subject_info['cat_discount_sort'] eq '2'">selected="selected"</if>>价格从低到高排序</option>
							<option value="3" <if condition="$subject_info['cat_discount_sort'] eq '3'">selected="selected"</if>>销量从高到低排序</option>
							<option value="4" <if condition="$subject_info['cat_discount_sort'] eq '4'">selected="selected"</if>>销量从低到高排序</option>
						</select>
					</td>
				</tr>
				<tr>
					<th width="20%">关联商品</th>
					<td width="80%" colspan="3">
						<div>
							<ul id="good_list">
								<volist name="goodArr" id="vo">
									<li>
										<input class="good_id" type="hidden" name="good_id[]" value="{pigcms{$vo.goods_id}"/>
										<span>{pigcms{$vo.name}</span>
										<div class="del">[删除]</div>
									</li>
								</volist>
							</ul>
							<button type="button" id="choose_good_btn" style="margin-left:0px;">选择商品</button>
							<span style="margin-left:20px;"></span>
						</div>
					</td>
				</tr>
			</table>
			<div class="btn">
				<button type="submit" id="submit" style="margin-bottom:30px;">保存</button>
			</div>
		</form>
		<script type="text/javascript" src="{pigcms{$static_public}layer/layer.js"></script>
		<script>
			if($('#good_list li').size() > 0){
				$('#good_list').css('margin-bottom','20px');
			}
			parent.subject_win_name = window.name;
			function build_good_save(goodData){
				$('#good_list').css('margin-bottom','20px');
				console.log(goodData);
				for(var i in goodData){
					$('#good_list').append('<li><input class="good_id" type="hidden" name="good_id[]" value="'+goodData[i].id+'"/><span>'+goodData[i].title+'</span><div class="del">[删除]</div></li>');
				}
			}
			$(function(){
				$('#good_list .del').live('click',function(){
					$(this).closest('li').remove();
					if($('#good_list li').size() == 0){
						$('#good_list').css('margin-bottom','0px');
					}
				});
				$('#choose_good_btn').click(function(){
					var selecteditemsArr = [];
					$.each($('#good_list .good_id'),function(i,item){
						selecteditemsArr.push($(item).val());
					});
					if(selecteditemsArr.length >= 8){
						parent.layer.msg('最多仅能添加8个商品。<br/>请先删除再进行操作。');
						return false;
					}
					parent.layer.open({
						title:false,
						closeBtn: 0,
						type:2,
						content:'{pigcms{$this->config['site_url']}/merchant.php?c=Diypage&a=good&store_id={pigcms{$_GET.store_id}&type=more&pageFrom=shop_fitment&max_num=8&number='+new Date().getTime()+'&selecteditems='+selecteditemsArr.join(','),
						area:['650px','576px'],
						shade: 0.2,
						cancel:function(){
							
						},
						move:false
					});
				});
				$('#myform').submit(function(){
					$('#cat_discount_name').val($.trim($('#cat_discount_name').val()));
					var cat_discount_name = $('#cat_discount_name').val();
					if(cat_discount_name == ''){
						parent.layer.msg('请填写分类名称');
						return false;
					}
					if(cat_discount_name.length < 2 || cat_discount_name.length > 4){
						parent.layer.msg('分类名称限制2-4字');
						return false;
					}
					if($('#cat_discount_status').val() == '1' && $('#good_list li').size() == 0){
						parent.layer.msg('请关联至少1个商品，或者关闭分类显示');
						return false;
					}
					if($('#good_list li').size() > 8){
						parent.layer.msg('最多仅能关联8个商品，现在关联了 ' + $('#good_list li').size() + ' 个，请先删除。');
						return false;
					}
					
					$('#submit').prop('disabled',true).html('保存中...');
					$.post($('#myform').attr('action'),$('#myform').serialize(),function(result){
						if(result.status == 1){
							parent.layer.alert(result.info,{
								end:function(){
									$('#fitment_cat_discount_status',parent.document).html($('#cat_discount_status option:selected').html());
									$('#fitment_cat_discount_sort',parent.document).html($('#cat_discount_sort option:selected').html());
									$('#fitment_cat_discount_desc',parent.document).html($('#cat_discount_desc').val());
									window.parent.layer.closeAll();
								}
							});
						}else{
							parent.layer.alert(result.info);
							$('#submit').prop('disabled',false).html('保存');
						}
					});
					return false;
				});
			});
		</script>
	</body>
</html>