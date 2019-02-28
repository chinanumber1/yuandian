<!-- 悬浮 -->
<div class="suspension">
    <div class="suspension_n">
        <ul>
            <li><a href="{pigcms{:U('index')}"><img src="{pigcms{$static_path}images/workerstaff/ht_03.png">首页</a></li>
            <li><a href="{pigcms{:U('tongji')}"><img src="{pigcms{$static_path}images/workerstaff/ht_06.png">统计</a></li>
            <li><a href="{pigcms{:U('info')}"><img src="{pigcms{$static_path}images/workerstaff/ht_09.png">我的</a></li>
            <li><a href="{pigcms{:U('grab')}"><img src="{pigcms{$static_path}images/workerstaff/ht_13.png">待服务订单</a></li>
            <li><a href="{pigcms{:U('pick')}"><img src="{pigcms{$static_path}images/workerstaff/ht_17.png">服务中订单</a></li>
            <li><a href="{pigcms{:U('finish')}"><img src="{pigcms{$static_path}images/workerstaff/ht_20.png">已服务订单</a></li>
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