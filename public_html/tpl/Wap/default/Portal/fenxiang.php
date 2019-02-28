<div class="mask" id="mask" style="display:none;"></div>
<div class="share_fd_po" id="bdsharebuttonbox" style="display:none;">
<div class="bdsharehd">分享到</div>
<ul class="bdsharebuttonbox bdshare-button-style0-32" data-bd-bind="1488953750721">
    <li>
        <a class="bds_wxquan" href="javascript:void(0);" onclick="MSGwindowShow(&#39;share&#39;,&#39;0&#39;,&#39;在微信中点击右上角“...”,或在APP客户端中使用本功能！&#39;,&#39;&#39;,&#39;&#39;);return false;" id="wx_timeline">朋友圈</a>
    </li>
    <li>
        <a class="bds_wxfriend" href="javascript:void(0);" onclick="MSGwindowShow(&#39;share&#39;,&#39;0&#39;,&#39;在微信中点击右上角“...”,或在APP客户端中使用本功能！&#39;,&#39;&#39;,&#39;&#39;);return false;" id="wx_message">微信好友</a>
    </li>
    <li>
        <a class="bds_qqfriend" href="javascript:void(0);" onclick="MSGwindowShow(&#39;share&#39;,&#39;0&#39;,&#39;在微信中点击右上角“...”,或在APP客户端中使用本功能！&#39;,&#39;&#39;,&#39;&#39;);return false;" id="wx_shareQQ">QQ好友</a>
    </li>
    <li>
        <a class="bds_tsina" href="http://share.baidu.com/code?qq-pf-to=pcqq.group#" data-cmd="tsina">新浪微博</a>
    </li>
    <li>
        <a class="bds_qzone" href="http://share.baidu.com/code?qq-pf-to=pcqq.group#" data-cmd="qzone">QQ空间</a>
    </li>
    <li>
        <a class="bds_tqq" href="http://share.baidu.com/code?qq-pf-to=pcqq.group#" data-cmd="tqq" id="wx_tqq">腾讯微博</a>
    </li>
    <li>
        <a class="bds_tieba" href="http://share.baidu.com/code#" data-cmd="tieba" id="wx_tieba">百度贴吧</a>
    </li>
    <li>
        <a class="bds_renren" href="http://share.baidu.com/code?qq-pf-to=pcqq.group#" data-cmd="renren">人人网</a>
    </li>
</ul>
<div class="bdshareft">
    <a href="#" class="cancal">取消</a>
</div>
<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"0","bdSize":"32"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='https://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
</div>





<script>
    $.fn.share2015 = function(){
        var t = $(this),node = $('#bdsharebuttonbox');
        node.find('.cancal').click(function(e){
            e.preventDefault();
            node.slideUp();
            $('#mask').hide();
        });
        $('#share2015').click(function(e){
            e.preventDefault();
            node.slideDown();
            $('#mask').show();
        });
    }
</script>