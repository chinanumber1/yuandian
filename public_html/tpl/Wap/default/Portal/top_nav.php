<div class="nav_APP" id="nav_APP">
    <ul class="clearfix">
        <li>
            <a href="{pigcms{:U('Portal/index')}">
                首页
                <s class="s" style="background-color:#ffc230; background-image:url({pigcms{$static_path}portal/images/201603031026514893905.png);"></s>
            </a>
        </li>
        <li>
            <a href="{pigcms{:U('Takeout/index')}">
                外卖
                <s class="s" style="background-color:#5adcc8; background-image:url({pigcms{$static_path}portal/images/201603031035348719045.png);"></s>
            </a>
        </li>
        <li>
            <a href="{pigcms{:U('Home/index')}">
                团购
                <s class="s" style="background-color:#34aef4; background-image:url({pigcms{$static_path}portal/images/201603031031173057056.png);"></s>
            </a>
        </li>
        <li>
            <a href="{pigcms{:U('Classify/index')}">
                招聘
                <s class="s" style="background-color:#ff5f45; background-image:url({pigcms{$static_path}portal/images/201603031032450876840.png);"></s>
            </a>
        </li>
        <li>
            <a href="{pigcms{:U('Shop/index')}">
                商家
                <s class="s" style="background-color:#d81e06; background-image:url({pigcms{$static_path}portal/images/201701091601176021129.png);"></s>
            </a>
        </li>
        <li>
            <a href="{pigcms{:U('Portal/activity')}">
                活动
                <s class="s" style="background-color:#7778b5; background-image:url({pigcms{$static_path}portal/images/201701101210535444969.png);"></s>
            </a>
        </li>
        <li>
            <a href="{pigcms{:U('Portal/yellow')}">
                黄页
                <s class="s" style="background-color:#87d140; background-image:url({pigcms{$static_path}portal/images/201603031036361226034.png);"></s>
            </a>
        </li>
        <li>
            <a href="{pigcms{:U('Portal/article')}">
                资讯
                <s class="s" style="background-color:#1bca4c; background-image:url({pigcms{$static_path}portal/images/201603031028335841178.png);"></s>
            </a>
        </li>
        <li>
            <a href="{pigcms{:U('Portal/tieba')}">
                贴吧
                <s class="s" style="background-color:#34aef4; background-image:url({pigcms{$static_path}portal/images/201603031041224861590.png);"></s>
            </a>
        </li>
    </ul>
    <span class="arrow-up"></span>
</div>



<script>
    // document.addEventListener('DOMContentLoaded',function(){
    //     var list = $('#content2').find('.cell');
    //     if(list.length > 0){
    //         $('#slide').show();
    //         var txt = '';
    //         $('#content2').find('.cell').each(function(i){
    //             if(i === 0){
    //                 txt += '<li class="active">1</li>';
    //             }else{
    //                 txt += '<li>'+(i+1)+'</li>';
    //             }
    //         });
    //         $('#indicator2').html(txt);
    //         var w_w = $(window).width();
    //         setTimeout(function(){new C_Scroll({container:'slide',content:'content2',ct:'indicator2',size:w_w,intervalTime:5000,lazyIMG:!!0});},20);
    //     }
    //     $('#nav_ico').click(function(e){
    //         e.preventDefault();
    //         $('#nav_APP').fadeToggle('fast');
    //     });
    // },false);


    document.addEventListener('DOMContentLoaded',function(){
        $('#nav_ico').click(function(e){
            e.preventDefault();
            $('#nav_APP').fadeToggle('fast');
        });
    },false);
</script>