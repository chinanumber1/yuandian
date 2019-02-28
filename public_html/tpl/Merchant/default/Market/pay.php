<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Market/market')}">进销存</a>
			</li>
			<li class="active">支付</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<form enctype="multipart/form-data" class="form-horizontal" method="post">
						<input type="hidden" value="{pigcms{$now_goods.goods_id}" id="goods_id" />
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="name">商品名称</label></label>
									<label class="col-sm-1">{pigcms{$now_goods.name}</label>
								</div>
								<if condition="empty($now_goods['list'])">
								<div class="form-group">
									<label class="col-sm-1"><label for="name">商品条形码</label></label>
									<label class="col-sm-1">{pigcms{$now_goods.number}</label>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="wholesale_price">批发价</label></label>
									<label class="col-sm-1">{pigcms{$now_goods.price}</label>
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
								<if condition="$now_goods['discount_info']">
								<div class="form-group topic_box">
								    <div class="question_box properties">
    								    <p class="question_info">
        								    <span>批发满：</span>
        								    <input type="text" class="txt properties_name" value="{pigcms{$now_goods['discount_info']['num']}" style="width:50px" disabled/>
        								    <span>件，享受：</span>
        								    <input type="text" class="txt properties_num" value="{pigcms{$now_goods['discount_info']['discount']}" style="width:50px" disabled/>折优惠
    								    </p>
								    </div>
								</div>
								</if>
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
											<th>本次批发数</th>
											<th>总价</th>
										</tr>
										
										<volist name="now_goods['list']" id="gl" key="id_index" >
											<tr id="{pigcms{$gl['index']}">
												<td>{pigcms{$gl['number']}</td>
												<volist name="gl['spec']" id="g">
												<td>{pigcms{$g['spec_val_name']}</td>
												</volist>
												<td>{pigcms{$gl['price']}</td>
												<td>{pigcms{$gl['stock_num']}</td>
												<td>{pigcms{$gl['stock_num'] * $gl['price']}</td>
											</tr>
										</volist>
									</tbody>
									</if>
									</table>
								</div>
								<div class="form-group">
									<label class="col-sm-1">批发总数</label>
									<label class="col-sm-1">{pigcms{$now_goods.num}({pigcms{$now_goods.unit})</label>
								</div>
								<div class="form-group">
									<label class="col-sm-1">总金额</label>
									<label class="col-sm-1">{pigcms{$now_goods.total_price|floatval}(元)</label>
								</div>
								<if condition="$now_goods['discount_info']">
								<div class="form-group">
									<label class="col-sm-1">优惠后的总价</label>
									<label class="col-sm-1">{pigcms{$now_goods.money|floatval}(元)</label>
								</div>
								</if>
								<div class="form-group">
									<label class="col-sm-1">您当前的余额</label>
									<label class="col-sm-1">{pigcms{$merchant.money|floatval}(元)</label>
								</div>
								<div class="form-group">
									<label class="col-sm-1">收货人</label>
									<label class="col-sm-1">{pigcms{$now_goods.username}</label>
								</div>
								<div class="form-group">
									<label class="col-sm-1">联系电话</label>
									<label class="col-sm-1">{pigcms{$now_goods.userphone}</label>
								</div>
								<div class="form-group">
									<label class="col-sm-1">收货地址</label>
									<label class="col-sm-3">{pigcms{$now_goods.address}</label>
								</div>
								<div class="form-group">
									<label class="col-sm-1">备注说明</label>
									<label class="col-sm-3">{pigcms{$now_goods.desc}</label>
								</div>
								
							</div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa fa-check bigger-110"></i>
										支付
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
<include file="Public:footer"/>
