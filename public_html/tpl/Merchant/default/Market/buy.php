<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Market/market')}">进销存</a>
			</li>
			<li class="active">进货</li>
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
                    <div class="clearfix">
                        <a class="btn btn-success" href="{pigcms{:U('Market/cart')}" style="float:left;">购物车({pigcms{$count})</a>
                    </div>
                    <br/>
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">
							<li class="active">
								<a data-toggle="tab" href="#basicinfo">商品信息</a>
							</li>
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post">
						<input type="hidden" value="{pigcms{$now_goods.goods_id}" name="goods_id" />
						<input type="hidden" value="0" name="type" id="type"/>
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="name">商品名称</label></label>
									<label class="col-sm-1">{pigcms{$now_goods.name}</label>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="name">商品条形码</label></label>
									<label class="col-sm-1">{pigcms{$now_goods.number}</label>
								</div>
								<if condition="empty($now_goods['list'])">
								<div class="form-group">
									<label class="col-sm-1"><label for="wholesale_price">批发价</label></label>
									<label class="col-sm-1">{pigcms{$now_goods.price|floatval}(元)</label>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="stock">库存</label></label>
									<label class="col-sm-1">{pigcms{$now_goods.stock_num}({pigcms{$now_goods.unit})</label>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="min_num">最低批发数</label></label>
									<label class="col-sm-1">{pigcms{$now_goods.min_num}({pigcms{$now_goods.unit})</label>
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
        								    <input type="text" class="txt properties_name" value="{pigcms{$drow['num']}" style="width:50px" disabled/>
        								    <span>件，享受：</span>
        								    <input type="text" class="txt properties_num" value="{pigcms{$drow['discount']}" style="width:50px" disabled/>折优惠
    								    </p>
								    </div>
								    </volist>
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
											<th>批发价</th>
											<th>库存</th>
											<th>最低批发数</th>
											<th>本次批发数（正整数）</th>
										</tr>
										
										<volist name="now_goods['list']" id="gl" key="id_index" >
											<tr id="{pigcms{$gl['index']}">
												<td>{pigcms{$gl['number']}</td>
												<volist name="gl['spec']" id="g">
												<td>{pigcms{$g['spec_val_name']}</td>
												</volist>
												
												<td>{pigcms{$gl['price']}</td>
												<td>{pigcms{$gl['stock_num']}</td>
												<td>{pigcms{$gl['min_num']}</td>
												<td style="width: 30%;"><input type="text" class="txt" onkeyup="buy_num_keyup(this.value,this,'{pigcms{$gl.stock_num}')" name="buy_nums[{pigcms{$gl['index']}]" value="{pigcms{$gl['buy_num']}" style="width:80px;"><span class="form_tips" style="color: red; display: none; height: 20px;">最多不得超过{pigcms{$gl.stock_num}库存</span></td>
											</tr>
										</volist>
									</tbody>
									</if>
									</table>
								</div>
								
								<if condition="empty($now_goods['list'])">
								<div class="form-group">
									<label class="col-sm-1">本次批发数</label>
									<input class="col-sm-1" onkeyup="buy_num_keyup(this.value,this,'{pigcms{$now_goods.stock_num}')" name="buy_num" type="text" value="{pigcms{$now_goods.buy_num}"/><span class="form_tips" style="color: red; display: none;">最多不能超过{pigcms{$now_goods.stock_num}库存</span>
									<span class="form_tips">必填。(正整数,非整数的强制转换成整数)</span>
								</div>
								</if>

								<script>
									function buy_num_keyup(val,obj,stock_num){
										if(parseInt(val) > parseInt(stock_num)){
											$(obj).val(stock_num);
											$(obj).next("span").css("display", "");
										}else{
											$(obj).next("span").css("display", "none");
										}
									}
								</script>
								<div class="form-group">
									<label class="col-sm-1">应收总额</label>
									<label class="col-sm-1 red" id="totalMoney">{pigcms{$now_goods['totalMoney']|floatval} (元)</label>
								</div>
								<div class="form-group">
									<label class="col-sm-1">收货人</label>
									<input class="col-sm-1" name="username" type="text" value="{pigcms{$now_goods['username']}"/>
									<span class="form_tips">必填</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">联系电话</label>
									<input class="col-sm-1" name="userphone" type="text" value="{pigcms{$now_goods.userphone}"/>
									<span class="form_tips">必填</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">收货地址</label>
									<input class="col-sm-3" name="address" type="text" value="{pigcms{$now_goods.address}"/>
									<span class="form_tips">必填(填写的完整地址)</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">备注说明</label>
									<textarea class="col-sm-3" rows="5" cols="10" name="desc">{pigcms{$now_goods.desc}</textarea>
								</div>
							</div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa fa-check bigger-110"></i>直接购买
									</button>　　　
                                    <button class="btn btn-info" type="button" id="addCart">
                                        <i class="ace-icon fa fa-check bigger-110"></i>加入购物车
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
<script type="text/javascript">
$(document).ready(function(){
	$('input[class="txt"], input[name="buy_num"]').blur(function(){
		$.post('{pigcms{:U("Market/getTotalPrice", array("goods_id" => $now_goods["goods_id"]))}', $("form").serialize(), function(response){
			if (response.error == false && response.totalMoney > 0) {
				$('#totalMoney').html(response.totalMoney + '(元)');
			}
		}, 'json');
	});
    $('#addCart').click(function(){
        $('.form-horizontal').attr('action', '{pigcms{:U("Market/addCart")}');
        $('.form-horizontal').submit();
        return false;
        $.post('{pigcms{:U("Market/addCart")}', $('.form-horizontal').serialize(), function(respone){
            
        }, 'json');
        console.log($('.form-horizontal').serialize());
    });
    
});

</script>
<include file="Public:footer"/>
