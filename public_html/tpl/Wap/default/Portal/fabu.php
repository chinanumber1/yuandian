<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
<title>发布信息</title>
<link rel="stylesheet" href="../addons/xc_bianmin/static/info/css/public.css"/>
<link rel="stylesheet" href="../addons/xc_bianmin/static/info/css/FL_fabu.css?v=0712"/>
<script type="text/Javascript" src="../addons/xc_bianmin/static/info/js/jquery.min.js"></script>
<script>

    $(function(){
        var h=$(window).height();
        $("#xuzhimaincontent").css("min-height",(h-44)+"px");
        $(".righttext").click(function(){
            $("#fabumain").hide();
            $("#xuzhimain").show();
        });
        $("#xuzhimain .arrow-wrap").click(function(){
            $("#fabumain").show();
            $("#xuzhimain").hide();
        });
        var catSheet = $('#cat-sheet');
        function hideActionSheet() {
            if (catSheet.hasClass('weui-actionsheet_toggle')) {
                catSheet.removeClass('weui-actionsheet_toggle');
            }
            $(".weui-mask").fadeOut();
            return false;
        }
        $('#cat-sheet-cancel,.weui-mask').on('click', hideActionSheet);
        $(".cat-item").on("click",
		function() {
		    var id = $(this).data('id');
		    var catEle = $('#child-' + id);
		    var catHTML = $.trim(catEle.html());
		    if(catHTML!="")
		    {
		        $('#cat-sheet-menu').html(catHTML);
		        $(".weui-mask").fadeIn();
		        catSheet.addClass('weui-actionsheet_toggle');
		        return false;
		    }
		});
    })
</script>
</head>
<body>
<div id="fabumain">
<header class="page-header zindex3">
<a class="arrow-wrap" href="javascript:window.history.back(-1);">
<span class="arrow-lefts"></span><i class="fanhuib">返回</i>
</a>
<div class="text">发布信息</div>
<div class="righttext">发布须知</div>
</header>
<div class="mainer">
<div class="wrap">
<div class="weui-cells__title">
<section>免责声明：平台发布的所有信息（收费、免费）；平台只负责发布、展示，与平台本身无关，平台不负任何责任。</section>
</div>
<!-- <div class="weui-cells"> <a class="weui-cell weui-cell_access" href="/store/join?i=214">
<div class="weui-cell__bd weui-cell_primary align-center"> <img src="../addons/xc_bianmin/static/info/images/sjrz.png">
<p> 我是商家，入驻阿旗信息网&nbsp; <img src="../addons/xc_bianmin/static/info/images/rzhot.png" height="16"> <br>
<span> 超低成本，本地宣传，简单有效，方便快捷！ </span> </p>
</div>
<span class="weui-cell__ft"> </span> </a> </div> -->
<div class="clear10"> </div>
<div class="clear10"> </div>
<div class="clear10"> </div>
<div class="weui-loadmore weui-loadmore_line"> <span class="weui-loadmore__tips"> 请选择您要发布的栏目 </span> </div>
<section class="nav-list box-sd">
<section class="nav-li">
<ul>
{loop $modellist $i $list}
    {if empty($list['modellinks'])}
    <li>
    	<a class="cat-item" data-id="{$list['id']}" href="javascript:;"> 
    		<i class="nav-icon"> <img src="{php echo tomedia($list['image'])}"/> </i>
    		<p> {$list['title']} </p>
    	</a>
    </li>
    <div id="child-{$list['id']}" style="display:none">
    	{loop $modellist1  $l}
            {if $l['parentid']==$list['id']}
            <a class="weui-actionsheet__cell" href="{php echo $this->createMobileUrl('postdetail', array('mdid' => $l['id']))}"> {$l['title']} </a>
        	{/if}
        {/loop}
	</div>
    {/if}
{/loop}

</ul>
<section class="clear"> </section>
</section>
</section>
</div>
</div>
<div class="weui-actionsheet" id="cat-sheet" style="max-height:450px;overflow:auto;">
<header class="header on in2">
<section class="wrap">
<h2> 请选择发布栏目 </h2>
</section>
</header>
<div class="weui-actionsheet__menu" id="cat-sheet-menu"> </div>
<div class="clear"> </div>
<div class="weui-actionsheet__action">
<div class="weui-actionsheet__cell" id="cat-sheet-cancel"> 取消 </div>
</div>
</div>
<div class="weui-mask" style="display: none"></div>
<style>.page-footer{width:100%;background-color:#fff;border-top:1px solid #dfdfdf;position:fixed;margin:0;padding:0;bottom:0;left:0;height:50px;z-index:1;}.page-footer ul{width:100%;background-color:#f0f0f0;margin:0;padding:0;}.page-footer ul li{float:left;width:20%;text-align:center;padding:2% 0;list-style-type:none;}.page-footer ul li a{width:100%;display:block}.page-footer ul li a p{font-size:12px;line-height:20px;margin:0;padding:0;}.page-footer ul li i{display:block;margin:0 auto}.page-footer ul a p{color:#888888;}.page-footer ul .active p,.page-footer ul .current p{color:#ed414a;}.page-footer ul .footo a .shouye{background:url(../addons/xc_bianmin/static/info/images/noshouye.png) no-repeat 0px 0px;background-size:cover;width:22px;height:22px}.page-footer ul .foott a .fabuall{background:url(../addons/xc_bianmin/static/info/images/fabuall.png) no-repeat 0px 0px;background-size:55px;width:55px;height:55px;top:-12px;display:block;margin:-16px auto;position:relative;}.page-footer ul .foott a .shangjia{background:url(../addons/xc_bianmin/static/info/images/nofenlei.png) no-repeat 0px 0px;background-size:cover;width:22px;height:22px}.page-footer ul .footf a .wode{background:url(../addons/xc_bianmin/static/info/images/nowode.png) no-repeat 0px 0px;background-size:cover;width:22px;height:22px}.page-footer ul .footfi a .fulisy{background:url(../addons/xc_bianmin/static/info/images/nofulidd.png) no-repeat 0px 0px;background-size:cover;width:22px;height:22px}.page-footer ul .footfi a .xiaoxi{background:url(../addons/xc_bianmin/static/info/images/nohongbao.png) no-repeat 0px 0px;background-size:cover;width:22px;height:22px}.page-footer ul .active .shouye{background:url(../addons/xc_bianmin/static/info/images/shouye.png) no-repeat 0px 0px!important;background-size:cover!important;width:22px!important;height:22px!important;}.page-footer ul .active .shangjia{background:url(../addons/xc_bianmin/static/info/images/fenlei.png) no-repeat 0px 0px!important;background-size:cover!important;width:22px!important;height:22px!important}.page-footer ul .active .wode{background:url(../addons/xc_bianmin/static/info/images/wode.png) no-repeat 0px 0px!important;background-size:cover!important;width:22px!important;height:22px!important}.page-footer ul .active .fulisy{background:url(../addons/xc_bianmin/static/info/images/fulidd.png) no-repeat 0px 0px!important;background-size:cover!important;width:22px!important;height:22px!important}.page-footer ul .active .xiaoxi{background:url(../addons/xc_bianmin/static/info/images/hongbao.png) no-repeat 0px 0px!important;background-size:cover!important;width:22px!important;height:22px!important}</style>
<footer class="page-footer">
    <ul>
        <li class="footo {if $pagefg=='index'}active{/if}">
            <a href="{php echo $this->createMobileUrl('fenmian')}">
                <i class="shouye"></i>
                <p>首页</p>
            </a>
        </li>
        <li class="foott {if $pagefg=='list'}active{/if}">
        {php $first=$pmodel[0]}
            {if empty($first['modellinks'])}
        <a href="{php echo $this->createMobileUrl('list', array('mdid' => $first['id'],'mdtitle'=>$first['title']))}">
        {else}
        <a href="{php echo $first['modellinks']}" class="grid" >
        {/if}
                <i class="shangjia"></i>
                <p>{$first['title']}</p>
            </a>
        </li>
         
        <li class="foott {if $pagefg=='post'}active{/if}">
        <a href="{php echo $this->createMobileUrl('post')}">
                <i class="fabuall"></i>
                <p>发布</p>
            </a>
        </li>
        <li class="footfi {if $op=='redpacket'}active{/if}">
            <a href="{php echo $this->createMobileUrl('list',array('op'=>'redpacket'))}">
                <i class="xiaoxi"></i>
                <p>福利</p>
            </a>
        </li>
        <li class="footf  {if $pagefg=='my'}active{/if}" id="bottom_wode">
            <a href="{php echo $this->createMobileUrl('my')}">
                <i class="wode"></i>
                <p>个人中心</p>
            </a>
        </li>
    </ul>
</footer>
</div>
<div id="xuzhimain" style="display:none;">
<header class="page-header zindex3">
<a class="arrow-wrap" href="javascript:;">
<span class="arrow-lefts"></span><i class="fanhuib">返回</i>
</a>
<div class="text">发布须知</div>
<div class="righttext"></div>
</header>
<div style="background:#fff;" id="xuzhimaincontent" class="content">
<article style="padding:10px;">
{if empty($tiao)}
                    <section>
                        <p>一、本服务协议双方为趣美客和趣美客用户，本服务协议具有合同效力。您确认本服务协议后，本服务协议即在您和趣美客之间产生法律效力。请您务必在使用之前认真阅读全部。
                        </p><p>二、用户在趣美客上交易平台上不得发布下列违法信息：
                    </p><p>1、反对宪法所确定的基本原则的；
                </p><p>2、危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；
            </p><p>3、损害国家荣誉和利益的；
         </p><p>4、煽动民族仇恨、民族歧视，破坏民族团结的；
    </p><p>5、破坏国家宗教政策，宣扬邪教和封建迷信的；
</p><p>6、散布谣言，扰乱社会秩序，破坏社会稳定的；
</p><p>7、散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的；
</p><p>8、侮辱或者诽谤他人，侵害他人合法权益的；
</p><p>9、含有法律、行政法规禁止的其他内容的。
</p><p>三、禁止但不限于：五黑类（药品、医疗器械、丰胸、减肥以及增高产品）、集赞、刷单、贷款理财、传销众筹、信用卡养卡等内容发布！凡是用户发布的内容出现以下情况之一，平台有权不提前通知用户直接删除，并有权对其作出禁言，甚至追究其相应的法律责任。
</p><p>1、发表以上禁止发布、传播的违法内容；
</p><p>2、发表不符合版面的主题，或者无内容灌水的内容；
</p><p>3、同一内容多次出现；
</p><p>4、发布的内容或者个人签名。昵称包含有严重影响用户浏览的内容或格式的；
</p><p>5不真实的、有着很大负面影响的虚假信息；
对于违规及重复内容，趣美客管理员有权直接屏蔽显示并不退还任何信息发布费用！</p>
<p>四、免责声明</p>
<p>1、在趣美客提供的在线支付交易相关信息经本平台认真审核，保证在线交易的服务真实性、时效性，但不一定能全部准确，并对在线交易的内容和金额负责，对于通过平台沟通达成线下交易，请大家自行进行判断，若判断失误造成的损失自行负责，趣美客不承担任何法律责任。</p>
<p>2、趣美客若因线路及趣美客控制范围外的硬件故障或其它不可抗力而导致暂停服务,于暂停服务期间给用户造成的一切损失,趣美客不承担任何法律责任.</p>
<p>五、趣美客不担保服务不会受中断，对服务的及时性，安全性，出错发生都不作担保，但会在能力范围内，避免出错。</p>
<p>六、 本声明之订立,修改,更新及最终解释权趣美客所有.</p>
<p>客服电话：0393-6228555</p>
<p>河南鸿客网络科技有限公司</p>
</section>
{else}
<section>
    {$tiao}
</section>
{/if}</section> </article>
</div>
</div>
</body>
</html>