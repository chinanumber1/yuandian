<!DOCTYPE html>
<html lang="en">
<head>
    <title>{pigcms{$config.gift_alias_name}订单成功</title>
<include file="Public:gift_header" />
</head>
<body>
<section class="orderSucced">
    <div class="wrap">
        <span class="dib vm icon"><i class="fa fa-check-circle"></i></span>
        <div class="dib vm desc">
            <h2>恭喜您，兑换成功!</h2>
            <p>您还可以查看<a href="{pigcms{:U('My/integral')}">兑换记录</a><!--a href="##">查看订单详情</a--></p>
        </div>
    </div>
</section>
<section class="order">

    <div class="orderRow">
        <ul>
            <li>
                <div class="wrap">
                    <p class="fr">快递（免邮）</p>
                    <span>配送方式</span>
                </div>
            </li>
            <li>
                <div class="wrap">
                    <p class="fr">{pigcms{$now_gift_order.order_time|date='Y-m-d H:i:s',###}</p>
                    <span>成交时间</span>
                </div>
            </li>
            <li>
                <div class="wrap">
                    <p class="fr">{pigcms{$now_gift_order.order_id}</p>
                    <span>兑换记录ID</span>
                </div>
            </li>
        </ul>
    </div>

</section>

<section class="ftHeight"></section>
<footer class="deatilFtBtn">
    <div class="number tc fl" id="share">
        <!--span class="dib tit"><i class="share fa fa-share-square-o"></i>分享赚取更多{pigcms{$config['score_name']}</span-->
    </div>
    <div class="buyNow fl">
        <a href="{pigcms{:U('Gift/index')}">回到首页</a>
    </div>
</footer>
<section class="fullBg"></section>
<!--section class="shareMod JSshareMod">
    <div class="listRect">
        <ul class="clearfix">
            <li>
                <a href="##">
                    <i><img src="{pigcms{$static_path}gift/images/placeholder/s1.png"></i>
                    <h2>微信</h2>
                </a>
            </li>
            <li>
                <a href="##">
                    <i><img src="{pigcms{$static_path}gift/images/placeholder/s2.png"></i>
                    <h2>新浪微博</h2>
                </a>
            </li>
            <li>
                <a href="##">
                    <i><img src="{pigcms{$static_path}gift/images/placeholder/s3.png"></i>
                    <h2>腾讯微博</h2>
                </a>
            </li>
            <li>
                <a href="##">
                    <i><img src="{pigcms{$static_path}gift/images/placeholder/s4.png"></i>
                    <h2>朋友圈</h2>
                </a>
            </li>
            <li>
                <a href="##">
                    <i><img src="{pigcms{$static_path}gift/images/placeholder/s5.png"></i>
                    <h2>QQ好友</h2>
                </a>
            </li>
            <li>
                <a href="##">
                    <i><img src="{pigcms{$static_path}gift/images/placeholder/s6.png"></i>
                    <h2>QQ空间</h2>
                </a>
            </li>
            <li>
                <a href="##">
                    <i><img src="{pigcms{$static_path}gift/images/placeholder/s7.png"></i>
                    <h2>短信</h2>
                </a>
            </li>
            <li>
                <a href="##">
                    <i><img src="{pigcms{$static_path}gift/images/placeholder/s8.png"></i>
                    <h2>复制链接</h2>
                </a>
            </li>
        </ul>
    </div>
    <div class="btn">
        <a href="javascript:;" class="JSbtn">取消分享</a>
    </div>
</section-->
<include file="Public:gift_footer" />
<script>

$(function(){
    $("#share").tap(function(){
       $(".fullBg").show();
        $(".JSshareMod").animate({
          height:'auto'
        }, 500, 'ease');
    });

    $(".JSbtn,.fullBg").tap(function(){
        $(".fullBg").hide();
        $(".JSshareMod").animate({
            height:'0'
        }, 500, 'ease');
    });
});
</script>
</body>
</html>