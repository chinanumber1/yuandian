<div class="header-wrap">
    <div class="header">
        <a href="{pigcms{:U('Service/index')}" style="background:url({pigcms{$config.site_merchant_logo}) 0 center no-repeat;" class="logo">到位平台</a>
        <div class="head-right">
            <if condition="$user_session['uid']">
                <a href="javascript:;" class="ico btn-nav-down"><i id="quote_match_num" class="ico"></i></a><a href="javascript:;" class="ico btn-nav-up hidden"></a>
                <else/>
                <a class="login-key " href="{pigcms{:U('Login/index')}">一键登录</a>
            </if>
        </div>
    </div>
</div>