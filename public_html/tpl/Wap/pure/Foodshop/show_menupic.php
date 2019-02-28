<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>全部商品</title>	
    <style>
    	*{
    		margin: 0;padding: 0;
    	}
    	ul,ol,dl,dt,dd{list-style: none;}
    	.content{
    		background: #fff;
    	}
    	a{
    		text-decoration: none;
    	}
    	.content ul li{
    		display: -webkit-flex;
			display: flex;
			-webkit-box-pack: justify;
			-webkit-justify-content: space-between;
			justify-content: space-between;
			-webkit-box-align: center;
			/*-webkit-align-items: center;*/
			/*align-items: center;*/
			border-bottom: 1px solid #DFDFDF;
			padding: 10px;
    	}
    	.content ul li img{
    		width: 110px;
    		height: 61.11px;
    	}
    	.content ul li dl{
    		display: flex;
  			flex-direction: column;
  			justify-content: space-between;
  			width: 65%;
  			font-size: 14px;
  			margin-top: 5px;
    	}
    	.content ul li dl dt{
    		color: #303030;
    	}
    	.content ul li dl dd{
    		color: #F8552C;
    		font-size: 15px;
    	}
    </style>
</head>
<body>
	<div class="content">
		<ul>
            <volist name="goods_list" id="rowset">
            <volist name="rowset['goods_list']" id="goods">
			<a href="{pigcms{:U('Foodshop/show_detail', array('goods_id' => $goods['goods_id']))}">
				<li>
					<img src="{pigcms{$goods['pic_arr'][0]['url']['s_image']}"/>
					<dl>
						<dt>{pigcms{$goods['name']}</dt>
						<dd>￥ {pigcms{$goods['price']}/{pigcms{$goods['unit']}</dd>
					</dl>
				</li>
			</a>
            </volist>
            </volist>
		</ul>
	</div>
</body>
</html>