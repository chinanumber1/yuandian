<include file="Public:gift_header"/>
<section class="mainSection">

    <div class="w1200">
        <div class="myOrder">
            <div class="backToIndex">
                <a href="{pigcms{:U('Index/Gift/index')}">
                    <div class="icon">
                        <i class="fa fa-home"></i>
                        <p>回到首页</p>
                    </div>
                </a>
            </div>
            <div class="orderInfo">
                <div class="orderDetail clearfix">
                        <i class="fl">
                        </i>
                    <div class="desc ofh">
                        <h3>恭喜您兑换成功了，我们将尽快为您配送！</h3>
                        <p>订单号：{pigcms{$now_order['order_id']}</p>
                    </div>
                </div>
                <div class="orderAddress">
                    <p>收货地址：{pigcms{$now_order['adress']}  {pigcms{$now_order['contact_name']}  {pigcms{$now_order['phone']}</p>
                    <!--p>您还可以查看 <a href="##">兑换记录</a><a href="##">查看订单详情</a> </p-->
                </div>
            </div>
        </div>
    </div>
</section>

<include file="Public:gift_footer"/>