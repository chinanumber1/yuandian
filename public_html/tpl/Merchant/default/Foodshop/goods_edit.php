<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Foodshop/index')}">{pigcms{$config.meal_alias_name}管理</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Foodshop/goods_sort',array('store_id'=>$now_store['store_id']))}">分类列表</a></li>
			<li class="active"><a href="{pigcms{:U('Foodshop/goods_list',array('sort_id'=>$now_sort['sort_id']))}">{pigcms{$now_sort.sort_name}</a></li>
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
							<!--li>
								<a data-toggle="tab" href="#seckill">限时优惠</a>
							</li-->
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post">
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
									<label class="col-sm-1"><label for="price">商品原价</label></label>
									<input class="col-sm-1" size="20" name="old_price" id="old_price" type="text" value="{pigcms{$now_goods.old_price|floatval}"/>
									<span class="form_tips">原价可不填，不填和现价一样</span>
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
								<!--div class="form-group">
									<label class="col-sm-1"><label for="price">{pigcms{$now_store['pack_alias']|default='打包费'}</label></label>
									<input class="col-sm-1" size="20" name="packing_charge" id="packing_charge" type="text" value="{pigcms{$now_goods.packing_charge|floatval}"/>
								</div-->
                                <div class="form-group">
                                    <label class="col-sm-1" for="update_stock_type">库存更新类型</label>
                                    <select name="update_stock_type" id="update_stock_type">
                                        <option value="0" <if condition="$now_goods['update_stock_type'] eq 0">selected</if>>每天更新</option>
                                        <option value="1" <if condition="$now_goods['update_stock_type'] eq 1">selected</if>>固定不变</option>
                                    </select>
                                </div>
										
								<div class="form-group">
									<label class="col-sm-1"><label for="price">原始库存</label></label>
									<input class="col-sm-1" size="20" name="original_stock" id="original_stock" type="text" value="{pigcms{$now_goods.original_stock|default='-1'}"/>
									<span class="form_tips" style="color:red;">当库存更新类型是每天更新的话，每天会将这个原始库存的值自动填充当前库存。（注：-1表示无限量）</span>
								</div>
                                        
                                <div class="form-group">
                                    <label class="col-sm-1"><label for="price">当前库存</label></label>
									<input class="col-sm-1" size="20" name="stock_num" id="stock_num" type="text" value="{pigcms{$now_goods.stock_num}"/>
									<span class="form_tips" style="color:red;">-1表示无限量。数量小于10时，商品详细页面会显示库存。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="sort">商品排序</label></label>
									<input class="col-sm-1" size="10" name="sort" id="sort" type="text" value="{pigcms{$now_goods.sort|default='0'}"/>
									<span class="form_tips">默认添加顺序排序！手动调值，数值越大，排序越前</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1" for="Food_status">商品状态</label>
									<select name="status" id="Food_status">
										<option value="1" <if condition="$now_goods['status'] eq 1">selected</if>>正常</option>
										<option value="0" <if condition="$now_goods['status'] eq 0">selected</if>>停售</option>
									</select>
								</div>
							
								<div class="form-group">
									<label class="col-sm-1">是否必点：</label>
									<label><input type="radio" name="is_must" value="0" <if condition="$now_goods['is_must'] eq 0">checked="checked"</if>>&nbsp;&nbsp;否</label>&nbsp;&nbsp;&nbsp;
									<label><input type="radio" name="is_must" value="1" <if condition="$now_goods['is_must'] eq 1">checked="checked"</if>>&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;
									<span class="form_tips" style="color:red;">必点是在菜单中看不到的，用户下单是按照用餐人数来算份数，如餐具。推荐商品页面中不会显示必点菜。</span>
								</div>
							
								<div class="form-group">
									<label class="col-sm-1">是否推荐：</label>
									<label><input type="radio" name="is_hot" value="0" <if condition="$now_goods['is_hot'] eq 0">checked="checked"</if>>&nbsp;&nbsp;否</label>&nbsp;&nbsp;&nbsp;
									<label><input type="radio" name="is_hot" value="1" <if condition="$now_goods['is_hot'] eq 1">checked="checked"</if>>&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;
								    <span class="form_tips" style="color:red;">确定推荐后，此商品会出现在前台点餐页面指定推荐位置供用户查看</span>
                                </div>
                                <if condition="$config['is_open_merchant_discount'] eq 1 AND $merchant['is_discount'] eq 1 AND $merchant['discount_percent'] gt 0">
                                <div class="form-group">
                                    <label class="col-sm-1">是否参与商家折扣：</label>
                                    <label><input type="radio" name="is_discount" value="0" <if condition="$now_goods['is_discount'] eq 0">checked="checked"</if>>&nbsp;&nbsp;否</label>&nbsp;&nbsp;&nbsp;
                                    <label><input type="radio" name="is_discount" value="1" <if condition="$now_goods['is_discount'] eq 1">checked="checked"</if>>&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;
                                    <span class="form_tips" style="color:red;">设置参与，则该菜品在结算的时候会享受商家折扣</span>
                                </div>
                                </if>

                                <if condition="$config['is_open_merchant_foodshop_discount'] eq 1">
                                    <div class="form-group">
                                        <label class="col-sm-1">是否参与商家桌台折扣：</label>
                                        <label><input type="radio" name="is_table_discount" value="0" <if condition="$now_goods['is_table_discount'] eq 0">checked="checked"</if>>&nbsp;&nbsp;否</label>&nbsp;&nbsp;&nbsp;
                                        <label><input type="radio" name="is_table_discount" value="1" <if condition="$now_goods['is_table_discount'] eq 1">checked="checked"</if>>&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;
                                        <span class="form_tips" style="color:red;">设置参与，则该菜品在结算的时候会享受商家桌台折扣</span>
                                    </div>
                                </if>
								
								<if condition="$print_list">
								<div class="form-group">
									<label class="col-sm-1" for="Food_status">归属打印机</label>
									<select name="print_id" id="print_id">
										<option value="0" selected>选择打印机</option>
										<volist name="print_list" id="print">
										<option value="{pigcms{$print['pigcms_id']}" <if condition="$print['pigcms_id'] eq $now_goods['print_id']">selected</if>>{pigcms{$print['name']}</option>
										</volist>
									</select>
									<span class="form_tips" style="color:red;">如果选择了一台非主打印机的话，那么客户在下单的时候选择的打印机和主打印机同时打印，如果不选打印机或是选择了主打印机的话，那么就主打印机打印</span>
								</div>
								</if>
                                
                                <if condition="$labels">
                                <div class="form-group">
                                    <label class="col-sm-1" for="Food_status">商品标签</label>
                                    <select name="label" id="label">
                                        <option value="" selected>无标签</option>
                                        <volist name="labels" id="label">
                                        <option value="{pigcms{$label['name']}" <if condition="$label['name'] eq $now_goods['label']">selected</if>>{pigcms{$label['name']}</option>
                                        </volist>
                                    </select>
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
									<span class="form_tips">图片宽度建议为：900px，高度建议为：500px</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">选择图片</label>
									<a href="#modal-table" class="btn btn-sm btn-success" onclick="selectImg('upload_pic_ul','foodshop_goods')">选择图片</a>
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
                                <if condition="$now_store['template'] eq 1">
                                <div class="form-group">
                                    <label class="col-sm-1">图片显示：</label>
                                    <label><input type="radio" name="show_type" value="0" <if condition="$now_goods['show_type'] eq 0">checked="checked"</if>>&nbsp;&nbsp;小图</label>&nbsp;&nbsp;&nbsp;
                                    <label><input type="radio" name="show_type" value="1" <if condition="$now_goods['show_type'] eq 1">checked="checked"</if>>&nbsp;&nbsp;大图</label>&nbsp;&nbsp;&nbsp;
                                    <span class="form_tips" style="color:red;">该设置项是控制前台点餐页面商品展示的图片是大图还是小图</span>
                                </div>
                                </if>
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
									<p class="add_table_foodshop" <if condition="!$now_goods['spec_list']">style="display:none"</if>><a href="javascript:;" title="添加" class="btn btn-sm btn-success" >生成规格关系</a></p>
									<table class="table table-striped table-bordered table-hover" id="table_list">
									<if condition="$now_goods['spec_list']">
									<tbody>
										<tr>
											<th>商品条形码</th>
											<volist name="now_goods['spec_list']" id="gs">
											<th>{pigcms{$gs['name']}</th>
											</volist>
											<th>原价</th><th>现价</th><th style="display:none">限时价</th><th>当前库存</th><th>原始库存</th>
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
												
												<td><input type="text" class="txt" name="old_prices[]" value="{pigcms{$gl['old_price']}" style="width:80px;"></td>
												<td><input type="text" class="txt" name="prices[]" value="{pigcms{$gl['price']}" style="width:80px;"></td>
												<td style="display:none;"><input type="text" class="txt" name="seckill_prices[]" value="{pigcms{$gl['seckill_price']}" style="width:80px;display:none"></td>
												<td><input type="text" class="txt" name="stock_nums[]" value="{pigcms{$gl['stock_num']}" style="width:80px;"></td>
												<td><input type="text" class="txt" name="original_stocks[]" value="{pigcms{$gl['original_stock']}" style="width:80px;"></td>
												
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
									<input class="col-sm-1" size="20" name="seckill_stock" id="seckill_stock" type="text" value="{pigcms{$now_goods.seckill_stock|default=-1}"/>
									<span class="form_tips">-1表示无限量。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="price">限时价类型</label></label>
									<span><label><input id='seckill_type0' name="seckill_type" <if condition="$now_goods['seckill_type'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;<span>固定时间段</span>&nbsp;</label></span>
									<span><label><input id='seckill_type1' name="seckill_type" <if condition="$now_goods['seckill_type'] eq 1 ">checked="checked"</if> value="1" type="radio" >&nbsp;<span>每天的时间段</span></label></span>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="price">限时段</label></label>
									<div>
										<input id="goods_seckill_open_time" type="text" value="{pigcms{$now_goods['seckill_open_time']|date='Y-m-d H:i',###}" name="seckill_open_time" readonly/>	至
										<input id="goods_seckill_close_time" type="text" value="{pigcms{$now_goods['seckill_close_time']|date='Y-m-d H:i',###}" name="seckill_close_time" readonly/>
										<div class="errorMessage" id="Config_shop_start_time_em_" style="display:none"></div>
										<div class="errorMessage" id="Config_shop_stop_time_em_" style="display:none"></div>
									</div>
								</div>
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
$('#myTab li a').click(function(){
	if(uploaderHas == false && $(this).attr('href') == '#txtimage'){
		setTimeout(function(){
			var  uploader = WebUploader.create({
					auto: true,
					swf: '{pigcms{$static_public}js/Uploader.swf',
					server: "{pigcms{:U('Foodshop/ajax_upload_pic', array('store_id' => $now_store['store_id']))}",
					pick: {
						id:'#J_selectImage',
						multiple:false
					},
					accept: {
						title: 'Images',
						extensions: 'gif,jpg,jpeg,png',
						mimeTypes: 'image/*'
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
var json = '{pigcms{$now_goods['json']}';
var session_index = 'foodshop_goods_{pigcms{$now_sort["sort_id"]}';
var diyVideo = "{pigcms{:U('Article/diyVideo')}";
var uploadJson = "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=group/content";
var cssPath = "{pigcms{$static_path}css/group_editor.css";
var upload_image = "{pigcms{:U('Foodshop/ajax_upload_pic', array('store_id' => $now_store['store_id']))}";
function deleteImage(path,obj){
	$.post("{pigcms{:U('Foodshop/ajax_del_pic')}",{path:path});
	$(obj).closest('.upload_pic_li').remove();
}
window.sessionStorage.setItem(session_index, json);
</script>
<script type="text/javascript" src="{pigcms{$static_path}js/goods.js"></script>
<include file="Public:footer"/>
