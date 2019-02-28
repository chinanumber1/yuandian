
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>推广用户列表</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
   
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?211"/>
	<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
	<script src="{pigcms{$static_path}layer/layer.m.js?232s"></script>
	<style>
   
	.img_list{
		width:1.4rem;
		height:1.4rem;
		float:left;
		margin-left:10px;
		margin-top:10px;
	}
	
	
    .spread{
		border-top:1px solid #dcd8d8;
        width:100%;
    }
    ul{
        list-style:none;
    }
    .li_name{
		border-bottom:1px solid #dcd8d8;
        width: 100%;
        height: 1.8rem;
       
        display: inline-table;
    }

    .spread_title{
        float: left;
        margin-top: 29px;
    }
    .spread_user_name{
        margin: 21px 2px 8px 10px;
    }
	
	.spread_num{
		margin: 6px 0px 8px 10px;
		font-size: 10px;
		color: gray;
	}
	
	.spread_money{
		margin: 21px 2px 8px 0px;
		color: #ea6808;
	}
	
	.total{
		
		font-size: 10px;
		color: gray;
	}
	
    .spread_user{
		width:20%;
		height:100%;
    display: inline-block;
    float: left;
	}
	.spread_info{

		width: 2.2rem;
		height: 1.8rem;
		float:left;
	}
	.spread_info2{

		height: 1.8rem;
		width: 2.2rem;
		text-align: right;
		float:right;
	}

	
	.next{

		width: 0.4rem;
		height: 1.8rem;
		float:right;
		
		background-repeat: repeat-x;
	}
	.next img{
		    margin-top: 0.6rem;
	}
	
	.title{
	height: 1rem;
    width: 100%;
    font-size: 19px;
    margin: 10px 0 -13px 14px;
	}

</style>
</head>
<body id="index">
    <div class="timeline">
		<div class="title">
		<if condition="$_GET['uid']">
			{pigcms{$user.nickname}的推广用户({pigcms{$res.spread_user_list|count})
		<else />
			我的推广用户({pigcms{$res.spread_user_list|count})
		</if>
		</div>
       
		<div class="spread">
    		<ul>
        		<volist name="res.spread_user_list" id="vo">
        			<li class="li_name">
						<div class='spread_user'>
							<img class="img_list" src="{pigcms{$vo.avatar}" />
							
						</div>
						<div class="spread_info">
							<div class="spread_user_name" date-type="{pigcms{$vo.uid}">{pigcms{$vo.nickname}</div>
							<div class="spread_num" date-type="{pigcms{$vo.spread_count}">他有{pigcms{$vo.spread_count}个推广用户</div>
						</div>
						<div class="next">
							<if condition="$vo.spread_count gt 0">
								<img src="{pigcms{$static_path}images/tubiao2_11.png" />
							</if>
						</div>
						<div class="spread_info2">
							<div class="spread_money"><if condition="empty($vo['spread_money'])">0.00<else />{pigcms{$vo.spread_money}</if></div>
							<div class="total">推广{pigcms{$config.money_name}总额</div>
						</div>
						
                    </li>
        		</volist>
                
            </ul>
    	</div>  
    </div>
	<script>
		$('.li_name').click(function(){
			if($(this).find('.spread_num').attr('date-type')>0){
				window.location.href = "{pigcms{:U('My/spread_user_list')}&uid="+$(this).find('.spread_user_name').attr('date-type');
			}else{
				layer.open({
					title:['错误提示','background-color:#8DCE16;color:#fff;'],
					content:'该用户没有推广用户',
					btn: ['确定'],
				});
			}
		});
	</script>
	
	<php>$no_footer = true;</php>
	<include file="Public:footer"/>

	{pigcms{$hideScript}
	</body>
</html>