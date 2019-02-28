<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>{pigcms{$worker_session['name']}-个人信息</title>
<meta name="description" content="{pigcms{$config.seo_description}"/>
<link href="{pigcms{$static_path}css/worker_deliver.css" rel="stylesheet"/>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<style>
    .titleImg{
        width:25px;
        height:25px;
        margin-right:10px;
    }
</style>
</head>
<body>
    <section class="MyEx">
        <div class="MyEx_top">
				<if condition="$worker_session['avatar_path']">
				 <span class="bjt" style="background: url({pigcms{$config.site_url}/upload/appoint/{pigcms{$worker_session['avatar_path']}) center no-repeat; background-size: contain;"></span>  
                <else />
				<span class="bjt" style="background: url({pigcms{$config.site_logo}) center no-repeat; background-size: contain;"></span>  
                </if>
            <h2>{pigcms{$worker_session['name']}</h2>
        </div>
        <div class="MyEx_end">
            <ul>
                <li class="cfe">
                    <h2>{pigcms{$finish_total|default=0}</h2>
                    <p>累计完成数量</p>
                </li>
                <li class="c65">
                    <h2>{pigcms{$total|default=0}</h2>
                    <p>累计抢单数量</p>
                </li>
				<li class="c66" onclick="location.href='{pigcms{:U('comment_list')}'">
                    <h2>{pigcms{$comment_total|default=0}</h2>
                    <p>累计评论数量</p>
                </li>
            </ul> 
           
        </div>

        <div id ="transaction" style="border-bottom:1px solid #e5e5e5;padding:0px 10px 12px 15px;margin-top: 10px;background-color: white;">
            <img class="titleImg" style="margin-top:10px;float:left;" src="{pigcms{$static_path}images/new_my/money.png" />
            <div  style="padding-top:13px;width:60%;">我的钱包</div>
            <if condition="$config.open_score_fenrun eq 0"><img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" style="float:right;margin-top:-19px;width:10px;" /></if>
        </div>

        <a href="{pigcms{:U('logout')}" class="Setup"></a>
		  <a href="{pigcms{:U('setting')}" class="Setup1"></a>
    </section>
    <section class="bottom">
        <div class="bottom_n">
            <ul>
                <li class="Statistics fl">
                    <a href="{pigcms{:U('tongji')}">统计</a>
                </li>
                <li class="home fl">
                      <a href="{pigcms{:U('index')}">
                        <i></i>首页
                      </a>
                </li>
                <li class="My Myon fl">
                    <a href="{pigcms{:U('info')}">我的</a>
                </li>
            </ul>
        </div>
    </section>
    <script type="text/javascript">
        $('#transaction').on('click',function(){
            location.href =	"{pigcms{:U('Workerstaff/money_record')}";
        });
    </script>
</body>
</html>
