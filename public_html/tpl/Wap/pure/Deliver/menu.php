<!-- 悬浮 -->
<div class="suspension">
    <div class="suspension_n">
        <ul>
            <li><a href="{pigcms{:U('Deliver/index')}"><img src="{pigcms{$static_path}images/ht_03.png">首页</a></li>
            <li><a href="{pigcms{:U('Deliver/tongji')}"><img src="{pigcms{$static_path}images/ht_06.png">统计</a></li>
            <li><a href="{pigcms{:U('Deliver/info')}"><img src="{pigcms{$static_path}images/ht_09.png">我的</a></li>
            <li><a href="{pigcms{:U('Deliver/grab')}"><img src="{pigcms{$static_path}images/ht_13.png">待抢订单</a></li>
            <li><a href="{pigcms{:U('Deliver/pick')}"><img src="{pigcms{$static_path}images/ht_17.png">处理中订单</a></li>
            <li><a href="{pigcms{:U('Deliver/finish')}"><img src="{pigcms{$static_path}images/ht_20.png">完成订单</a></li>
        </ul>
    </div>
    <div class="susp-img"></div>
</div>
<script type="text/javascript">
    $(".susp-img").click(function(){
        $(".suspension_n").toggle(100);
    })
    $(".suspension_n li").last().css("border","none");
</script>
<!-- 悬浮 -->