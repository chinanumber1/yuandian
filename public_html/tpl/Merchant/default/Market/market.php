<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Market/sell_order')}">进销存</a>
			</li>
			<li class="active">批发市场</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
                    <div class="form-group">
                        <form action="{pigcms{:U('Market/market')}" method="get">
                        <input type="hidden" name="c" value="Market"/>
                        <input type="hidden" name="a" value="market"/>
                        <fieldset id="choose_cityarea" province_id="{pigcms{$province_id}" city_id="{pigcms{$city_id}" area_id="{pigcms{$area_id}">
                        <label class="col-sm-1" style="width: 82px;padding-top: 6px;">商品分类</label>
                        <fieldset id="choose_category" cat_fid="{pigcms{$cat_fid}" cat_id="{pigcms{$cat_id}">
                        商品名称
                        <input type="text" name="goods_name" placeholder="搜索商品" class="input-text" value="{pigcms{$_GET['goods_name']}"/>
                        <input type="submit" value="筛选" class="btn btn-success" style="border: none"/>
                        <a class="btn btn-success" href="{pigcms{:U('Market/cart')}" style="float:right;">购物车({pigcms{$count})</a>
                        </fieldset></fieldset>
                        </form>
                        
                    </div>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="50">商品条形码</th>
									<th width="100">父分类名称</th>
									<th width="100">分类名称</th>
									<th width="100">商品名称</th>
									<th width="50">商品图片</th>
									<th width="100" class="button-column">批发单价（元）</th>
									<th width="100" class="button-column">库存</th>
									<th width="100" class="button-column">最低批发数</th>
									<th width="100" class="button-column">已售</th>
									<th width="100">优惠明细</th>
									<th width="100">卖家信息</th>
									<th width="100" class="button-column">操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$orders">
									<volist name="orders" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.number}</td>
											<td>{pigcms{$vo.cat_fname}</td>
											<td>{pigcms{$vo.cat_name}</td>
											<td>{pigcms{$vo.name}</td>
											<td><img src="{pigcms{$vo.pic}" width="50" height="50"></td>
											<td class="button-column">{pigcms{$vo.price|floatval}</td>
											<td class="button-column">{pigcms{$vo.stock_num}({pigcms{$vo.unit})</td>
											<td class="button-column">{pigcms{$vo.min_num}({pigcms{$vo.unit})</td>
											<td class="button-column">{pigcms{$vo.sell_count}({pigcms{$vo.unit})</td>
											<td>{pigcms{$vo.discount_info_txt}</td>
											<td>
											                 商家名:{pigcms{$vo.merchant_name}<br/>
											                 商家电话:<span style="color:green">{pigcms{$vo.merchant_phone}</span><br/>
											                 店铺名:{pigcms{$vo.store_name}<br/>
											                 店铺电话:<span style="color:green">{pigcms{$vo.store_phone}</span><br/>
											</td>
											<td class="button-column">
									            <a title="" class="green" style="padding-right:8px;" href="{pigcms{:U('Market/buy',array('goods_id' => $vo['goods_id']))}">进货</a>
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="12" >暂无商品信息</td></tr>
								</if>
							</tbody>
						</table>
						{pigcms{$pagebar}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	#cat_fid{
		width:100px;
	}
</style>
<script type="text/javascript">
var static_public="{pigcms{$static_public}",static_path="{pigcms{$static_path}",choose_province="{pigcms{:U('Area/ajax_province')}",choose_city="{pigcms{:U('Area/ajax_city')}",choose_area="{pigcms{:U('Area/ajax_area')}";
</script>
<script type="text/javascript" src="{pigcms{$static_path}js/market_area.js"></script>
<script>
var category_list = '{pigcms{$category_list}';
$(document).ready(function(){
    if (category_list != undefined) {
        var father_category_list = $.parseJSON(category_list);
        var son_category_list = null, cat_fid = parseInt($('#choose_category').attr('cat_fid')), cat_id = parseInt($('#choose_category').attr('cat_id'));
        var area_dom = '<select id="cat_fid" name="cat_fid" class="col-sm-1" style="margin-right:10px;">';
        if (cat_fid == 0) {
            area_dom += '<option value="0" selected="selected" >全部分类</option>';
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
//      if (son_category_list != null) show_son_category(son_category_list, cat_id);
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