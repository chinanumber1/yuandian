<include file="Public:gift_header"/>
<script type="text/javascript" language="javascript">
var exchange_type = "{pigcms{$_GET['exchange_type'] + 0}";
var payment_pure_integral = "{pigcms{$gift_detail['payment_pure_integral']}";
var payment_integral = "{pigcms{$gift_detail['payment_integral']}";
var payment_money = "{pigcms{$gift_detail['payment_money']}";
var exchange_limit_num = "{pigcms{$gift_detail.exchange_limit_num}"
var sku = "{pigcms{$gift_detail.sku}";
var sale_count = "{pigcms{$gift_detail.sale_count}";

if(parseInt(exchange_limit_num)){
	var total_sku = exchange_limit_num;
}else{
	var total_sku = parseInt(sku) - parseInt(sale_count);
}

</script>
<section class="breadNav">
    <!--div class="w1200">
        <div class="crumbs">
            <a href="#">全部</a>
            <a href="#">2016年05月11日</a>
            <a href="#">无形资产</a>
        </div>
    </div-->
</section>
<section class="mainSection">

    <div class="w1200">
	
        <div class="myOrder">
            <div class="orderList">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <!--th scope="col" class="check"><label><input type="checkbox"/>全选</label></th-->
						<th scope="col" class="check"></th>
                        <th scope="col" class="name">商品名称</th>
                        <th scope="col" class="price">单价</th>
                        <th scope="col" class="quantity">数量</th>
                        <th scope="col" class="price">小计</th>
                        <!--th scope="col" class="operate">操作</th-->
                    </tr>
                    <tr>
                        <!--td class="check"><input type="checkbox" class="itemCheck"/></td-->
						<td class="check"></td>
                        <td class="name"><a href="##">
                            <div class="i-pic fl">
                                <img src="{pigcms{$gift_detail['pc_pic_list'][0]['url']}"/>
                            </div>
                            <div class="desc">
                                <div class="wrap">
                                    <h3 class="bigTit">{pigcms{$gift_detail['gift_name']}</h3>
                                    <p class="subTit">已选择：<font class="memo">{pigcms{:urldecode($_GET['memo'])}</font></p>
									<if condition='$_GET["exchange_type"] eq 0'>
										<p class="bonusStyle mt10">{pigcms{$config['score_name']}</p>
									<elseif condition='$_GET["exchange_type"] eq 1' />
										<p class="bonusStyle mt10">{pigcms{$config['score_name']}<em>+</em>现金兑换</p>
									</if>
                                </div>
                            </div>
                        </a> </td>
						
						<if condition='$_GET["exchange_type"] eq 0'>
							<td class="price"><p class="bonus"><span>{pigcms{$gift_detail['payment_pure_integral']} <em>{pigcms{$config['score_name']}</em></span></p></td>
						<elseif condition='$_GET["exchange_type"] eq 1'/>
							<td class="price"><p class="bonus"><span>{pigcms{$gift_detail['payment_integral']} <em>{pigcms{$config['score_name']}</em></span><span><em>+</em>{pigcms{$gift_detail['payment_money']} <em>元</em></span></p></td>
						</if>
                        <td class="quantity"><div class="jf-bidNum clearfix">
                            <span class="dib reduce_buy">-</span><input type="text" value="{pigcms{$_GET['num']}" id="now_sku_buy" class="dib"/><span class="dib plus_buy">+</span>
                        </div>
						<if condition='$gift_detail["exchange_limit_num"] neq 0'>
							<p>每人限兑&nbsp;<em class='total_sku'>{pigcms{$gift_detail["exchange_limit_num"]}</em>&nbsp;件</p></td>
						</if>
                        <if condition='$_GET["exchange_type"] eq 0'>
							<td class="price"><p class="bonus"><span><font class="payment_pure_integral">{pigcms{$gift_detail['payment_pure_integral'] * $_GET['num']}</font><em>{pigcms{$config['score_name']}</em></span></p></td>
						<elseif condition='$_GET["exchange_type"] eq 1' />
							<td class="price"><p class="bonus"><span><font class="payment_integral">{pigcms{$gift_detail["payment_integral"] * $_GET['num']}</font> <em>{pigcms{$config['score_name']}</em></span><span><em>+</em><font class="payment_money">{pigcms{$gift_detail["payment_money"] * $_GET['num']}</font> <em>元</em></span></p></td>
						</if>
                        <!--td class="operate"><a href="javascript:;" class="fa fa-trash-o"></a> </td-->
                    </tr>
                </table>
            </div>
            <div class="pay mt20 clearfix">
                <!--span class="itemQuantity">共 <em>1</em> 件商品</span-->
                <button class="fr submitBtn">提交订单</button>
                <div class="fr total">
                    <span>合计：</span>
					 <if condition='$_GET["exchange_type"] eq 0'>
						<p class="bonus dib"><span><font class="payment_pure_integral">{pigcms{$gift_detail['payment_pure_integral'] * $_GET['num']}</font> <em>{pigcms{$config['score_name']}</em></span></p>
					<elseif condition='$_GET["exchange_type"] eq 1' />
						<p class="bonus dib"><span><font class="payment_integral">{pigcms{$gift_detail["payment_integral"] * $_GET['num']}</font> <em>{pigcms{$config['score_name']}</em></span><span><em>+</em><font class="payment_money">{pigcms{$gift_detail["payment_money"] * $_GET['num']}</font> <em>元</em></span></p>
					</if>
                </div>
            </div>
        </div>

<!--      暂时不需要，先注释 ——yang   <div class="recommend">
            <h3>同款推荐</h3>
            <div class="reScroll customScroll">
                <div class="showBox scrollList clearfix">
                    <ul>
						<volist name='recommend_gift_list["list"]' id='gift'>
							<li>
								<div class="i-pic">
									<img src="{pigcms{$config.site_url}/upload/system/gift/{pigcms{$gift['pc_pic_list'][0]}"/>
								</div>
								<div class="desc">
									<a href="{pigcms{:U('gift_detail',array('gift_id'=>$gift['gift_id']))}" class="fr btn">马上兑换</a>
									<div class="info">
										<h4><a href="##">{pigcms{$gift_detail['gift_name']}</a> </h4>
										<if condition='in_array($gift["exchange_type"],array(0,2))'>
											<p class="bonus"><span>{pigcms{$gift['payment_pure_integral']} <em>{pigcms{$config['score_name']}</em></span></p>
										</if>
										<if condition='in_array($gift["exchange_type"],array(1,2))'>
											<p class="bonus"><span>{pigcms{$gift['payment_integral']} <em>{pigcms{$config['score_name']}</em></span><span><em>+</em>{pigcms{$gift['payment_money']}  <em>元</em></span></p>
										</if>
									</div>
								</div>
							</li>
						</volist>
                    </ul>
                </div>
                <div class="btn">
                    <a href="javascript:;" class="prev left"></a>
                    <a href="javascript:;" class="next right"></a>
                </div>
            </div>
        </div> -->
    </div>
</section>
<include file="Public:gift_footer"/>
<script type="text/javascript" language="javascript">
$('.submitBtn').click(function(){
	var num = $('#now_sku_buy').val();
	var gift_id = "{pigcms{$gift_detail['gift_id']}";
	var memo = $('.memo').html();
	var exchange_type = "{pigcms{$_GET['exchange_type']}";

	var url = "{pigcms{:U('check_gift_buy')}" + '&num='+num +'&gift_id='+gift_id+'&memo='+memo+'&exchange_type='+exchange_type;
	location.href= url;
});

</script>