<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Shop/index')}">{pigcms{$config.shop_alias_name}管理</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Shop/goods_sort',array('store_id'=>$now_store['store_id']))}">分类列表</a></li>
			<li class="active"><a href="{pigcms{:U('Shop/goods_list',array('sort_id'=>$now_sort['sort_id']))}">{pigcms{$now_sort.sort_name}</a></li>
			<li class="active">修改商品</li>
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
								<a data-toggle="tab" href="#basicinfo">基本信息</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtintro">商品详情</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtimage">商品图片</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtattr">商品规格</a>
							</li>
							<li>
								<a data-toggle="tab" href="#seckill">限时优惠</a>
							</li>
							<if condition="$now_store['store_theme'] AND $category_list">
							<li>
								<a data-toggle="tab" href="#category">商城属性设置</a>
							</li>
							</if>
							<if condition="!empty($_SESSION['system']) AND $config['shop_goods_score_edit'] eq 1">
							<li>
								<a data-toggle="tab" href="#score">积分设置</a>
							</li>
							</if>
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post">
						<input type="hidden" value="{pigcms{$now_goods.goods_id}" id="goods_id" />
						<input type="hidden" value="{pigcms{$_GET['page']}" id="page" name="page">
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
									<input class="col-sm-1" size="20" name="name" id="name" type="text" value="{pigcms{$now_goods.name}"/>
									<span class="form_tips">必填。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="name">商品条形码</label></label>
									<input class="col-sm-1" size="20" name="number" id="number" type="text" value="{pigcms{$now_goods.number}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="unit">商品单位</label></label>
									<input class="col-sm-1" size="20" name="unit" id="unit" type="text" value="{pigcms{$now_goods.unit}"/>
									<span class="form_tips">必填。如个、斤、份</span>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1" for="Food_status">商品分类</label>
									<fieldset id="choose_sort"></fieldset>
								</div>
								<!--div class="form-group">
									<label class="col-sm-1"><label for="price">商品原价</label></label>
									<input class="col-sm-1" size="20" name="old_price" id="old_price" type="text" value="{pigcms{$now_goods.old_price|floatval}"/>
									<span class="form_tips">原价可不填，不填和现价一样</span>
								</div-->
								<div class="form-group">
									<label class="col-sm-1"><label for="price">商品进价</label></label>
									<input class="col-sm-1" size="20" name="cost_price" id="cost_price" type="text" value="{pigcms{$now_goods.cost_price|floatval}"/>
									<span class="form_tips">进货价用户是看不到</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="price">商品现价</label></label>
									<input class="col-sm-1" size="20" name="price" id="price" type="text" value="{pigcms{$now_goods.price|floatval}"/>
									<if condition="$config.open_extra_price eq 1 AND $now_goods.extra_pay_price gt 0">
										元 + <input class="col-sm-1" maxlength="30" name="extra_pay_price" type="text" value="{pigcms{$now_goods.extra_pay_price}" style="float:none"/>{pigcms{$config.extra_price_alias_name}
										<span class="form_tips">如果填写{pigcms{$config.extra_price_alias_name}字段，商品价格将变为：金额+{pigcms{$config.extra_price_alias_name}数</span>
									</if>
									<span class="form_tips">必填。</span>
								</div>
                                <div class="form-group">
                                    <label class="col-sm-1"><label for="price">限购类型</label></label>
                                    <select name="limit_type" id="limit_type">
                                        <option value="0" <if condition="$now_goods['limit_type'] eq 0"> selected</if>>每单限购</option>
                                        <option value="1" <if condition="$now_goods['limit_type'] eq 1"> selected</if>>每个ID限购</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1"><label id="label_limit"><if condition="$now_goods['limit_type'] eq 0">每单限购<else/>每个ID限购</if></label></label>
                                    <input class="col-sm-1" size="20" name="max_num" id="max_num" type="text" value="{pigcms{$now_goods.max_num}"/>
                                    <span class="form_tips" id="label_tips">每个<if condition="$now_goods['limit_type'] eq 0">订单<else/>用户</if>该商品最多可订购的数量，0为不限制</span>
                                </div>
								<div class="form-group">
									<label class="col-sm-1"><label for="price">{pigcms{$now_store['pack_alias']|default='打包费'}</label></label>
									<input class="col-sm-1" size="20" name="packing_charge" id="packing_charge" type="text" value="{pigcms{$now_goods.packing_charge|floatval}"/>
								</div>
										
								<div class="form-group">
									<label class="col-sm-1"><label for="price">商品库存</label></label>
									<input class="col-sm-1" size="20" name="stock_num" id="stock_num" type="text" value="{pigcms{$now_goods.stock_num}"/>
									<span class="form_tips">-1表示无限量。数量小于10时，商品详细页面会显示库存。</span>
								</div>
                                <div class="form-group">
                                    <label class="col-sm-1"><label for="min_num">最小购买数量</label></label>
                                    <input class="col-sm-1" size="10" name="min_num" id="min_num" type="text" value="{pigcms{$now_goods.min_num}"/>
                                    <span class="form_tips">用户订购此商品时最小购买量</span>
                                </div>
								<div class="form-group">
									<label class="col-sm-1"><label for="sort">商品排序</label></label>
									<input class="col-sm-1" size="10" name="sort" id="sort" type="text" value="{pigcms{$now_goods.sort|default='0'}"/>
									<span class="form_tips">默认添加顺序排序！手动调值，数值越大，排序越前</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1" for="is_discount">是否参与折扣</label>
									<select name="is_discount" id="is_discount">
										<option value="1" <if condition="$now_goods['is_discount'] eq 1"> selected</if>>参与折扣</option>
										<option value="0" <if condition="$now_goods['is_discount'] eq 0"> selected</if>>不参与折扣</option>
									</select>
                                    <span class="form_tips" style="color: red;">该商品是否参与店铺/分类折扣;折扣包括店铺折扣与分类折扣</span>
								</div>
                                <div class="form-group">
                                    <label class="col-sm-1" for="is_use_coupon">是否可用商家优惠券</label>
                                    <select name="is_use_coupon" id="is_use_coupon">
                                        <option value="1" <if condition="$now_goods['is_use_coupon'] eq 1"> selected</if>>可用</option>
                                        <option value="0" <if condition="$now_goods['is_use_coupon'] eq 0"> selected</if>>不可用</option>
                                    </select>
                                    <span class="form_tips" style="color: red;">该商品是否享受商家优惠券的优惠策略</span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1" for="Food_status">商品状态</label>
                                    <select name="status" id="Food_status">
                                        <option value="1" <if condition="$now_goods['status'] eq 1"> selected</if>>正常</option>
                                        <option value="0" <if condition="$now_goods['status'] eq 0"> selected</if>>停售</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1"><label for="price">每天显示时段</label></label>
                                    <div>
                                        <input id="show_start_time" type="text" value="{pigcms{:substr($now_goods['show_start_time'], 0, -3)}" name="show_start_time" readonly style="width:70px"/>   至
                                        <input id="show_end_time" type="text" value="{pigcms{:substr($now_goods['show_end_time'], 0, -3)}" name="show_end_time" readonly style="width:70px"/>
                                        <span class="form_tips" style="color: red;">不填或都填写00:00表示24小时都显示</span>
                                    </div>
                                </div>
								
								<if condition="$print_list">
								<div class="form-group">
									<label class="col-sm-1" for="Food_status">归属打印机</label>
									<select name="print_id" id="print_id">
										<option value="0">选择打印机</option>
										<volist name="print_list" id="print">
										<option value="{pigcms{$print['pigcms_id']}" <if condition="$print['pigcms_id'] eq $now_goods['print_id']">selected</if>>{pigcms{$print['name']}</option>
										</volist>
									</select>
									<span class="form_tips" style="color:red;">如果选择了一台非主打印机的话，那么客户在下单的时候选择的打印机和主打印机同时打印，如果不选打印机或是选择了主打印机的话，那么就主打印机打印</span>
								</div>
								</if>
							</div>
							<div id="txtintro" class="tab-pane">
								<div class="form-group" >
									<label class="col-sm-1">商品描述：</label>
									<textarea name="des" id="content" style="width:702px;">{pigcms{$now_goods.des}</textarea>
								</div>
							</div>
							<div id="txtimage" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1">上传图片</label>
									<div style="display:inline-block;" id="J_selectImage">
										<div class="btn btn-sm btn-success" style="position:relative;width:78px;height:34px;">上传图片</div>
									</div>
									<span class="form_tips">第一张将作为列表页图片展示！最多上传5个图片！同一张图片不能选择【侧重文字模板图片宽度建议为：900px，高度建议为：500px】【侧重图片图片尺寸建议为：大于等于600*600px的正方形】</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">选择图片</label>
									<a href="#modal-table" class="btn btn-sm btn-success" onclick="selectImg('upload_pic_ul','goods')">选择图片</a>
								</div>
								<div class="form-group">
									<label class="col-sm-1">图片预览</label>
									<div id="upload_pic_box">
										<ul id="upload_pic_ul">
											<volist name="now_goods['pic_arr']" id="vo">
												<li class="upload_pic_li"><img src="{pigcms{$vo.url}"/><input type="hidden" name="pic[]" value="{pigcms{$vo.title}"/><br/><a href="#" onclick="deleteImage('{pigcms{$vo.title}',this);return false;">[ 删除 ]</a></li>
											</volist>
										</ul>
									</div>
								</div>
							</div>
							<div id="txtattr" class="tab-pane">
								<div class="alert alert-info" style="margin:10px 0;">
									<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
									规格，即大家熟悉的商品可选择类别。例如菜品有大份、中份、小份，衣服有颜色、尺码等，规格可以单独设置价格库存等信息。
									<br/><br/>
									属性，可以理解为用户下单时选择的标签。例如菜品中的过桥米线可以选2个荤菜和选5个素菜，一份菜品可选辣不辣，但是这些不涉及价格，需要标注的。
								</div>
								<div class="topic_box">
									<volist name="now_goods['spec_list']" id="row" key="ii">
									<div class="question_box spec">
										<p class="question_info"><span>规格名称：</span>
											<input type="text" class="txt spec_name" value="{pigcms{$row['name']}" name="specs[]"/>
											<input type="hidden" name="spec_id[]" value="{pigcms{$row['id']}"/>
											<a href="javascript:;" class="box_del">删除</a>
										</p>
										<ul id="1" class="optionul">
											<volist name="row['list']" id="r">
											<li>
												<u>规格属性值：</u>
												<input type="hidden" class="hide_txt spec_val_id" name="spec_val_id[{pigcms{$ii-1}][]" value="{pigcms{$r['id']}"> 
												<input type="text" class="txt spec_val" name="spec_val[{pigcms{$ii-1}][]" value="{pigcms{$r['name']}"/> 
												<a class="list_del" href="javascript:;" title="删除这个选项">×</a>
											</li>
											</volist>
										</ul>
										<p class="bot_add"><a href="javascript:;" class="btn btn-sm btn-success">  添加规格的属性值</a></p>
									</div>
									</volist>
									<p class="add_spec"><a href="javascript:;" title="添加" class="btn btn-sm btn-success" <if condition="count($now_goods['spec_list']) egt 3">style="display:none"</if>>添加规格</a></p>
								</div>
							
								<div class="topic_box">
									<volist name="now_goods['properties_status_list']" id="ro" key="ik">
									<div class="question_box properties">
										<p class="question_info">
											<span>属性名称：</span>
											<input type="text" class="txt properties_name" value="{pigcms{$ro['name']}" name="properties[]"/>
											<span>可选个数：</span><input type="text" class="txt properties_num" value="{pigcms{$ro['num']}" name="properties_num[]" style="width:50px"/>
											<input type="hidden" name="properties_id[]" value="{pigcms{$ro['id']}">
											<a href="javascript:;" class="box_del">删除</a>
										</p>
										<ul id="1" class="optionul">
											<volist name="ro['val_status']" id="ra" key="iii">
											<li>
												<u>属性的属性值：</u>
												<input type="text" class="txt properties_val" name="properties_val[{pigcms{$ik-1}][]" value="{pigcms{$ra[0]}"/> 
												<a class="list_del" href="javascript:;" title="删除这个选项">×</a>
                                                <label class="statusSwitch" style="display:inline-block;">
                                                    <input name="properties_val_status_{pigcms{$ik-1}_{pigcms{$iii-1}" class="ace ace-switch ace-switch-6" type="checkbox" value="1" <if condition="$ra[1]">checked</if>/>
                                                    <span class="lbl"></span>
                                                </label>
											</li>
											</volist>
										</ul>
										<p class="bot_add"><a href="javascript:;" class="btn btn-sm btn-success">  添加属性的属性值</a></p>
									</div>
									</volist>
									<p class="add_properties"><a href="javascript:;" title="添加" class="btn btn-sm btn-success">添加属性</a></p>
								</div>
								
								<div class="topic_box">
									<p class="add_table" <if condition="!$now_goods['spec_list']">style="display:none"</if>><a href="javascript:;" title="添加" class="btn btn-sm btn-success" >生成规格关系</a></p>
									<table class="table table-striped table-bordered table-hover" id="table_list">
									<if condition="$now_goods['spec_list']">
									<tbody>
										<tr>
											<th>商品条形码</th>
											<volist name="now_goods['spec_list']" id="gs">
											<th>{pigcms{$gs['name']}</th>
											</volist>
											<th style="display:none">原价</th><th>进价</th><th>现价</th><th>限时价</th><th><if condition="$now_goods['limit_type'] eq 0">每单限购<else/>每个ID限购</if></th><th>库存</th>
											<volist name="now_goods['properties_list']" id="gp">
											<th>{pigcms{$gp['name']}(可选个数)</th>
											</volist>
										</tr>
										
										<volist name="now_goods['list']" id="gl" key="num">
											<tr id="{pigcms{$gl['index']}">
												<td><input type="text" class="txt" name="numbers[]" value="{pigcms{$gl['number']}" style="width:100%;"></td>
												<volist name="gl['spec']" id="g">
												<td>{pigcms{$g['spec_val_name']}</td>
												</volist>
												
												<td style="display:none"><input type="text" class="txt" name="old_prices[]" value="{pigcms{$gl['old_price']}" style="width:80px;"></td>
												<td><input type="text" class="txt" name="cost_prices[]" value="{pigcms{$gl['cost_price']}" style="width:80px;"></td>
												<td><input type="text" class="txt" name="prices[]" value="{pigcms{$gl['price']}" style="width:80px;"></td>
												<td><input type="text" class="txt" name="seckill_prices[]" value="{pigcms{$gl['seckill_price']}" style="width:80px;"></td>
                                                <td><input type="text" class="txt" name="max_nums[]" value="{pigcms{$gl['max_num']|default='0'}" style="width:80px;"></td>
												<td><input type="text" class="txt" name="stock_nums[]" value="{pigcms{$gl['stock_num']}" style="width:80px;"></td>
												
												<volist name="gl['properties']" id="gpp" key="num">
												<td><input type="text" class="txt" name="num{pigcms{$num-1}[]" value="{pigcms{$gpp['num']}" style="width:80px;"></td>
												</volist>
											</tr>
										</volist>
									</tbody>
									</if>
									</table>
								</div>
							</div>
							<div id="seckill" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1"><label for="price">商品限时价</label></label>
									<input class="col-sm-1" size="20" name="seckill_price" id="seckill_price" type="text" value="{pigcms{$now_goods.seckill_price|default=0}"/>
									<span class="form_tips">0表示无限时价。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="price">限时价库存</label></label>
									<input class="col-sm-1" size="20" name="seckill_stock" id="seckill_stock" type="text" value="{pigcms{$now_goods.seckill_stock}"/>
									<span class="form_tips">-1表示无限量。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="price">限时价类型</label></label>
									<span><label><input id='seckill_type0' name="seckill_type" <if condition="$now_goods['seckill_type'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;<span>固定时间段</span>&nbsp;</label></span>
									<span><label><input id='seckill_type1' name="seckill_type" <if condition="$now_goods['seckill_type'] eq 1 ">checked="checked"</if> value="1" type="radio" >&nbsp;<span>每天的时间段</span></label></span>
								</div>

								<div class="form-group datetime" <if condition="$now_goods['seckill_type'] eq 1 ">style="display:none"</if>>
									<label class="col-sm-1"><label for="price">限时段</label></label>
									<div>
										<input id="goods_seckill_open_datetime" type="text" value="{pigcms{$now_goods['seckill_open_time']|date='Y-m-d H:i',###}" name="seckill_open_datetime" readonly/>	至
										<input id="goods_seckill_close_datetime" type="text" value="{pigcms{$now_goods['seckill_close_time']|date='Y-m-d H:i',###}" name="seckill_close_datetime" readonly/>
									</div>
								</div>
                                
                                <div class="form-group time" <if condition="$now_goods['seckill_type'] eq 0 ">style="display:none"</if>>
                                    <label class="col-sm-1"><label for="price">限时段</label></label>
                                    <div>
                                        <input id="goods_seckill_open_time" type="text" value="{pigcms{:date('H:i' , $now_goods['seckill_open_time'])}" name="seckill_open_time" readonly style="width:70px"/>   至
                                        <input id="goods_seckill_close_time" type="text" value="{pigcms{:date('H:i' , $now_goods['seckill_close_time'])}" name="seckill_close_time" readonly style="width:70px"/>
                                    </div>
                                </div>
							</div>
							<div id="category" class="tab-pane">
								<div class="alert alert-info" style="margin:10px 0;">
									<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
									运费模版：用户在选购该商品的时候，不同的区域有不同的运费。
									<br/><br/>
									其他区域运费：指的是用户选择的配送区域不在运费模板的区域内的其他城市的运费！（如果不选择运费模板的话，那么该商品的运费就是这个地方设置的值）
									<br/><br/>
									运费计算方式：1、按最大值算：就是用户同时买了该店铺的多个商品，则本次购物的运费只收取商品运费最高的那个。
									<br/><br/>
									　　　　　　　2、单独计算：指的是用户在购买该商品的时候运费单独另外收取，不与其他商品合并计算运费。
								</div>
								<div class="form-group">
									<label class="col-sm-1">运费模版</label>
									<select name="freight_template" id="freight_template">
										<option value="0" <if condition="0 eq $now_goods['freight_template']">selected</if>>请选择运费模板...</option>
										<volist name="express_template" id="express">
										<if condition="$express['id'] eq intval($now_goods['freight_template'])">
										<option value="{pigcms{$express['id']}" selected>{pigcms{$express['name']}</option>
										<else />
										<option value="{pigcms{$express['id']}">{pigcms{$express['name']}</option>
										</if>
										</volist>
									</select>
									<a href="{pigcms{:U('Express/add')}">+新建</a>
								</div>
								<div class="form-group">
									<label class="col-sm-1">其他区域运费</label>
									<div><input name="freight_value" type="text" value="{pigcms{$now_goods.freight_value|floatval}" /></div>
								</div>
								<div class="form-group">
									<label class="col-sm-1">运费计算方式</label>
									<span><label><input name="freight_type" <if condition="$now_goods['freight_type'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;<span>按最大值算</span>&nbsp;</label></span>
									<span><label><input name="freight_type" <if condition="$now_goods['freight_type'] eq 1 ">checked="checked"</if> value="1" type="radio" >&nbsp;<span>单独计算</span></label></span>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1" for="Food_status">商品分类</label>
									<fieldset id="choose_category" cat_fid="{pigcms{$now_goods.cat_fid}" cat_id="{pigcms{$now_goods.cat_id}"></fieldset>
								</div>
							</div>
							<if condition="!empty($_SESSION['system']) AND $config['shop_goods_score_edit'] eq 1">
							<div id="score" class="tab-pane">
								
								<div class="form-group">
									<label class="col-sm-1">消费1元获得积分</label>
									<div>
										<input name="score_percent" type="text" value="{pigcms{$now_goods.score_percent}" /><b style="color:red">(* 请填写>0的数字，设置百分比请填写%，如2%)</b>
									</div>
								</div>	
								<div class="form-group">
									<label class="col-sm-1">积分最大使用数</label>
									<div>
										
										<input name="score_max_type" type="radio" value="0" <if condition="$now_goods.score_max eq 0">checked</if>/>跳过
										<input name="score_max_type" type="radio" value="1" <if condition="$now_goods.score_max gt 1">checked</if>/>设置
										<input name="score_max" id="score_max" type="text" value="{pigcms{$now_goods.score_max|floatval}" <if condition="$now_goods.score_max eq 0">style="display:none"</if>/><b style="color:red">(* 请填写>0的整数，如10)</b>
									</div>
								</div>
							</div>
							</if>
							</div>
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
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="./static/js/upyun.js"></script>
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script>
var uploaderHas = false;
var diyVideo = "{pigcms{:U('Article/diyVideo')}";
$('#myTab li a').click(function(){
	if(uploaderHas == false && $(this).attr('href') == '#txtimage'){
		setTimeout(function(){
			var  uploader = WebUploader.create({
					auto: true,
					swf: '{pigcms{$static_public}js/Uploader.swf',
					server: "{pigcms{:U('Shop/ajax_upload_pic', array('store_id' => $now_store['store_id']))}",
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
			
		},20);
		uploaderHas = true; 
	}
});
var formathtml = new Array();
var format_value = new Array();
var json = '{pigcms{$now_goods['json']}', sortList = '{pigcms{$sort_list}', selectIds = '{pigcms{$select_ids}';
var category_list = '{pigcms{$category_list}', ajax_goods_properties = "{pigcms{:U('Shop/ajax_goods_properties')}";
var session_index = 'goods_{pigcms{$now_sort["sort_id"]}';

var uploadJson = "{pigcms{:U('Shop/ajax_upload_pic', array('store_id' => $now_store['store_id']))}";
var cssPath = "{pigcms{$static_path}css/group_editor.css";
var upload_image = "{pigcms{:U('Shop/ajax_upload_pic', array('store_id' => $now_store['store_id']))}";
function deleteImage(path,obj){
	$.post("{pigcms{:U('Shop/ajax_del_pic')}",{path:path});
	$(obj).closest('.upload_pic_li').remove();
}
window.sessionStorage.setItem(session_index, json);
$(document).ready(function(){
	$('#freight_value1').change(function(){
		if ($(this).val() != 0) {
			$('input[name=freight_type][value=1]').attr("checked",'checked');
		} else {
			$('input[name=freight_type][value=0]').attr("checked",'checked');
		}
	});
	
	$('input[name="score_max_type"]').click(function(){
		if($(this).val()==1){
			$('#score_max').show();
		}else{
			$('#score_max').hide();
		}
	});
    
    $('#limit_type').change(function(){
        if ($(this).val()==1) {
            $('#label_limit').html('每个ID限购');
            $('#label_tips').html('每个用户该商品最多可订购的数量，0为不限制');
        } else {
            $('#label_limit').html('每单限购');
            $('#label_tips').html('每个订单该商品最多可订购的数量，0为不限制');
        }
    });
});
</script>
<script type="text/javascript" src="{pigcms{$static_path}js/goods.js?t=1"></script>
<include file="Public:footer"/>
