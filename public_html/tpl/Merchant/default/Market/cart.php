<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Market/market')}">批发-供货</a>
			</li>
			<li class="active">购物车</li>
		</ul>
	</div>
	<!-- 商品详细内容 -->
    <form action="{pigcms{:U('Market/goPay')}" method="post">
    <input type="hidden" name="fid" value="{pigcms{$fid}">
	<div class="shopContent">
		<div class="shopTop row">
			<div class="col-xs-12" style="color:#666">
				<div class="col-xs-1"><label style="margin-left: -1px"><if condition="empty($fid)"><input type="checkbox" id="allCheckbox" style='float:left'></if>操作</label></div>
				<div class="col-xs-6">商品信息</div>
				<div class="col-xs-1">批发单价(元)</div>
				<div class="col-xs-1">购买总量</div>
				<div class="col-xs-2">总价</div>
				<div class="col-xs-1">操作</div>
			</div>
		</div>
        <php> foreach ($cartList as $rowset) { </php>
        <div class="merchant">
        <h4> 商家: <span>{pigcms{$rowset['name']}</span></h4>
        <div class="foodCont row">
            <volist name="rowset['cartlist']" id="row">
			<div class="col-xs-12">
				<div class="col-xs-1">
					<if condition="empty($fid)"><input type="checkbox" class="checkbox" name="cartids[]" value="{pigcms{$row['cartid']}" data-money="{pigcms{$row['money']|floatval}"></if>
				</div>
                <div class="col-xs-6 mesage">
                    <div class="clearfix">
                        <img src="{pigcms{$row['image']}" class="pull-left">
                        <span class="pull-left">{pigcms{$row['name']}</span>
                    </div>
                    <if condition="$row['discount_info']">
                    <div class="question_box properties">
                        <p><i class=""></i> 批发满{pigcms{$row['discount_info']['num']}件,享{pigcms{$row['discount_info']['discount']}折优惠</p>
                    </div>
                    </if>
                    <if condition="$row['spec_list']">
                    <h5>商品规格详情</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>商品条形码</th>
                                <volist name="row['spec_list']" id="gs">
                                <th>{pigcms{$gs['name']}</th>
                                </volist>
                                <th>批发价</th>
<!--                                 <th>库存</th> -->
                                <th>最低批发数</th>
                                <th>本次批发数（正整数）</th>
                                <th>总价</th>
                            </tr>
                        </thead>
                        <tbody>
                            <volist name="row['list']" id="gl" key="id_index" >
                                <tr id="{pigcms{$gl['index']}">
                                    <td>{pigcms{$gl['number']}</td>
                                    <volist name="gl['spec']" id="g">
                                    <td>{pigcms{$g['spec_val_name']}</td>
                                    </volist>
                                    
                                    <td>{pigcms{$gl['price']}</td>
                                    <td>{pigcms{$gl['min_num']}</td>
                                    <td>{pigcms{$gl['stock_num']}</td>
                                    <td>{pigcms{$gl['stock_num'] * $gl['price']}</td>
<!--                                     <td>{pigcms{$gl['min_num']}</td> -->
<!--                                     <td><input type="number" value="{pigcms{$gl['stock_num']}"></td> -->
                                    <!--td style="width: 30%;"><input type="text" class="txt" onkeyup="buy_num_keyup(this.value,this,'{pigcms{$gl.stock_num}')" name="buy_nums[{pigcms{$gl['index']}]" value="{pigcms{$gl['buy_num']}" style="width:80px;"><span class="form_tips" style="color: red; display: none; height: 20px;">最多不得超过{pigcms{$gl.stock_num}库存</span></td-->
                                </tr>
                            </volist>
                        </tbody>
                    </table>
                    </if>
                </div>
                <if condition="$row['spec_list']">
                <div class="col-xs-1 red"></div>
                <else />
                <div class="col-xs-1 red">{pigcms{$row['price']|floatval}</div>
                </if>
                <div class="col-xs-1">{pigcms{$row['num']}</div>
                <div class="col-xs-2 red">{pigcms{$row['money']|floatval}</div>
                <if condition="empty($fid)"><a href="javascript:void(0);" class="col-xs-1 delete" data-cartid="{pigcms{$row['cartid']}">删除</a></if>
            </div>
            </volist>
            </div>
        </div>
        <php> } </php>
		<div class="priceBot row">
			<div class="col-xs-12 clearfix">
				<if condition="empty($fid)"><div class="col-xs-6 reset acc clear" >清空购物车</div></if>
				<div class="col-xs-2 fontColor acc">已选商品<span id="totalCount">{pigcms{$totalCount|intval}</span>件</div>
				<div class="col-xs-2 fontColor acc">商品总价:<span id="totalMoney">￥{pigcms{$totalMoney|floatval}</span></div>
				<div class="jie pull-right">
					<a href="javascript:void(0)" id="gopay">去结算</a>
				</div>
			</div>
		</div>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-11">
					<div class="form-group">
						<label class="col-sm-1">收货人</label>
						<input class="col-sm-1" name="username" type="text" value="{pigcms{$username}"/>
						<span class="form_tips">必填</span>
					</div>
					<div class="form-group">
						<label class="col-sm-1">联系电话</label>
						<input class="col-sm-1" name="userphone" type="text" value="{pigcms{$userphone}"/>
						<span class="form_tips">必填</span>
					</div>
					<div class="form-group">
						<label class="col-sm-1">收货地址</label>
						<input class="col-sm-3" name="address" type="text" value="{pigcms{$address}"/>
						<span class="form_tips">必填(填写的完整地址)</span>
					</div>
					<div class="form-group">
						<label class="col-sm-1">备注说明</label>
						<textarea class="col-sm-3" rows="5" cols="10" name="desc">{pigcms{$desc}</textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
    </form>
</div>
<style>
.shopContent{overflow:hidden;}
.foodCont .col-xs-12{    
	padding-right: 12px;
    padding-left: 12px;
  /*  align-items: center;
   display: flex; */
}
.foodCont .col-xs-1.red{padding-left: 3px;}
.foodCont .col-xs-2.red{padding-left: 5px;}
    .shopContent .row{
    	width: 80%;
		padding: 10px;
		margin-left: 30px;
    }
	.shopTop.row{
		margin-top: 20px;
		width: 80%;
		padding: 10px;
		margin-left: 30px;
		border:1px solid #eee;
		height: 40px;
        background-color: #f5f5f5;
	}
	.col-xs-1,.col-xs-6,.col-xs-2{
		padding: 0;
	}
	.shopContent h4{
		width: 80%;
		padding: 10px;
		margin-left: 30px;
		background: #F5F5F5;
		color: #666;
		font-size: 16px;
	}
	.shopContent h4 span{color: #333;}
	.mesage span{
		margin-left: 15px;
		font-size: 16px;
		line-height: 60px;
	}
	.mesage img{
		width: 60px;
		height: 60px;
	}
	.mesage p{
		margin: 15px 0;
		font-size: 14px;
		color: #D81E06;
	}
	.mesage h5{
		color: #999;
	}

	 .foodCont div {
		padding: 0;
	}
	.priceBot{
		position: relative;
		padding:0px!important;
		background: #F7F7F7;
	}
	.acc{
		line-height: 65px;
	}
	.reset{
		color: #256DB1;
	}
	.fontColor span{
		font-size: 17px;
		font-weight:700;
		color: #06C1AF;
	}
	.jie a{
		display: inline-block;
		padding: 20px;
		text-align: center;
		background: #06C1AF;
		color: #fff;
		width: 130px;
		height: 65px;
		line-height: 2;
		position: absolute;
		right: 0;
	}
	.red{
		color: #FF0000;
	}
    .foodCont .col-xs-12{
        border-bottom: 1px solid #eee;
        margin-bottom: 20px;
    }
    .foodCont .col-xs-12:last-child{
        border-bottom: 0px solid #eee;
    }
</style>
<script type="text/javascript">
var fid = '{pigcms{$fid}';
$(document).ready(function(){
    $('#allCheckbox').click(function(){
        if ($(this).is(':checked')) {
            $('.checkbox').prop('checked', true);
        } else {
            $('.checkbox').prop('checked', false);
        }
        cntFun();
    });
    $('.checkbox').click(function(){cntFun()});
    $('.delete').click(function(){
        var cartid = $(this).data('cartid'), obj = $(this);
        $.post('{pigcms{:U("Market/delCart")}', {'cartid':cartid}, function(res){
            if (res.errCode) {
                layer.msg(res.msg);
            } else {
                if (obj.parents('.merchant').find('.col-xs-12').size() < 2) {
                    obj.parents('.merchant').remove();
                } else {
                    obj.parents('.col-xs-12').remove();
                }
                cntFun();
            }
        }, 'json');
    });
    
    $('.clear').click(function(){
        $.post('{pigcms{:U("Market/delCart")}', {'cartid':-1}, function(res){
            if (res.errCode) {
                layer.msg(res.msg);
            } else {
                $('.foodCont').html('');
                $('.merchant').remove();
                cntFun();
            }
        }, 'json');
    });
    
    $('#gopay').click(function(){
        if (fid < 1) {
            var totalCount = 0;
            $('.checkbox').each(function(){
                if ($(this).is(':checked')) {
                    totalCount ++;
                }
            });
            if (totalCount < 1) {
                layer.msg('请选择商品');
                return false;
            }
        }
        if ($('input[name=username]').val().length < 1) {
            layer.msg('收货人姓名必填');
            return false;
        }
        if ($('input[name=userphone]').val().length < 1) {
            layer.msg('收货人电话必填');
            return false;
        }
        if ($('input[name=address]').val().length < 1) {
            layer.msg('收货地址必填');
            return false;
        }
        $('form').submit();
        return false;
    });
});

function cntFun()
{
    var totalCount = 0, totalMoney = 0;
    $('.checkbox').each(function(){
        if ($(this).is(':checked')) {
            totalCount ++;
            totalMoney += parseFloat($(this).data('money'));
        }
    });
    $('#totalCount').html(totalCount);
    $('#totalMoney').html('￥' + parseFloat(parseFloat(totalMoney).toFixed(2)));
}
</script>
<include file="Public:footer"/>