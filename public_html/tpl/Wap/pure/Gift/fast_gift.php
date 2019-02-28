<!DOCTYPE html>
<html lang="en">
<head>
    <title>{pigcms{$config.gift_alias_name}快捷兑换列表</title>
<include file="Public:gift_header"/>
<body>
<section class="myPurse">
    <ul>
        <li class="purseText">
            <div class="desc">
                <h3>{pigcms{$config['score_name']}余额</h3>
                <p>{pigcms{$now_user['score_count']}</p>
            </div>
        </li>
        <li class="sign">
          <div class="wrap">
              <img src="{pigcms{$static_path}gift/images/myPurse.png"/>
          </div>
        </li>
        <li class="purseText">
            <div class="desc">
                <h3>现金余额</h3>
                <p>{pigcms{$now_user['now_money']}</p>
            </div>
        </li>
    </ul>
</section>
<nav class="topNav filterNav">
    <ul class="box">
        <!--li class="b-flex">
            <a <if condition='!empty($_GET["type"]) && ($_GET["type"] == "hot")'>class="active"</if> href="{pigcms{:U('fast_gift',array('cat_id'=>$_GET['cat_id'],'type'=>'hot'))}">
                <span>热门</span>
            </a>
        </li-->
		<li class="b-flex">
            <p>
                <span>热门兑换</span>
            </p>
        </li>
        <li class="b-flex">
            <a id="gift_sort" <if condition='!empty($_GET["order"]) && (in_array($_GET["order"],array("integral_desc","integral_asc")))'>class="active"</if> href="javascript:void(0)">
                <span>{pigcms{$config['score_name']}值 <if condition='$_GET["order"] eq "integral_desc"'><i class="down fa fa-long-arrow-down"></i><elseif condition='$_GET["order"] eq "integral_asc"' /><i class="down fa fa-long-arrow-up"></i></if></span>
            </a>
        </li>
        <!--li class="b-flex">
            <a <if condition='!empty($_GET["type"]) && ($_GET["type"] == "new")'>class="active"</if> href="{pigcms{:U('fast_gift',array('cat_id'=>$_GET['cat_id'],'type'=>'new'))}">
                <span>新品</span>
            </a>
        </li-->
    </ul>
</nav>


<section class="list">

    <ul>
		<if condition='!empty($fast_gift_list["list"])'>
			<volist name='fast_gift_list["list"]' id='gift'>
				<li class="item item2" onclick="location.href='{pigcms{:U('gift_detail',array('gift_id'=>$gift['gift_id']))}'">
					<div class="wrap">
						<div class="fl i-pic">
							<img src="{pigcms{$config.site_url}/upload/system/gift/{pigcms{$gift['wap_pic_list'][0]}"/>
						</div>
						<div class="ofh desc">
								<div class="wrap pr">
									<h2>{pigcms{$gift.gift_name}</h2>
									<if condition='in_array($gift["exchange_type"],array(0,2))'>
										<p>{pigcms{$gift.payment_pure_integral} <em>{pigcms{$config['score_name']}</em>
									<else />
										<p>{pigcms{$gift.payment_integral} <em>{pigcms{$config['score_name']}</em> + {pigcms{$gift.payment_money} <em>元</em></p>
									</if>
									<a href="{pigcms{:U('gift_detail',array('gift_id'=>$gift['gift_id']))}" class="aButton pa">马上兑换</a>
									<br />
									<small class="tip">已兑换
									<if condition='!empty($gift["exchanged_num"])'>
										<em>{pigcms{$gift["exchanged_num"]}</em>
									<else />
										<em>{pigcms{$gift["sale_count"]}</em>
									</if>
									件</small>
								</div>
							</div>
					</div>
				</li>
			</volist>
		<else />
			<p style=" text-align:center">暂无礼品</p>
		</if>
    </ul>
	
</section>
<include file="Public:gift_footer" />
<script type="text/javascript" language="javascript">
$(function(){
	$('#gift_sort').click(function(){
		var order = "{pigcms{$_GET['order']}";
		var url = "{pigcms{:U('fast_gift')}";
		if(order == 'integral_desc'){
			url += '&order=integral_asc';
			
		}else if(order == 'integral_asc'){
			url += '&order=integral_desc';
		}else{
			url += '&order=integral_desc';
		}
		location.href=url;
	});
	
})
</script>

<script type="text/javascript">
window.shareData = {  
			"moduleName":"Gift",
			"moduleID":"0",
			"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>", 
			"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Gift/fast_gift')}",
			"tTitle": "{pigcms{$config.gift_alias_name}快捷兑换列表",
			"tContent": "{pigcms{$config.site_name}"
};
</script>
{pigcms{$shareScript}
</body>
</html>