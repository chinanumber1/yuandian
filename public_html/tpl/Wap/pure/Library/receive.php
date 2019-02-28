<!DOCTYPE html>
<html style="font-size: 20px;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title></title>
    <style type="text/css">
    	*{margin: 0;padding: 0;}
    	body{background: #F4F4F4;}
    	.ft{float:left;}
    	.rg{float:right;}
    	.clear{content: " ";display:block;clear: both;}
    	.take_express{
    		display: block;
    		width: 90%;margin: 0 5%;margin-top:80px;
    		height: 100px;
    		border-radius: 50px;
    		background:-moz-linear-gradient(left,#43BAFF,#2DD0FF);/*Mozilla*/
			background:-webkit-gradient(left,#43BAFF,#2DD0FF);/*Old gradient for webkit*/
			background:-webkit-linear-gradient(left,#43BAFF,#2DD0FF);/*new gradient for Webkit*/
			background:-o-linear-gradient(left,#43BAFF,#2DD0FF); /*Opera11*/
			line-height: 100px;
    	}
    	.take_express span,.help_express span{margin-left: 5%;font-size: 22px;font-weight: bold;color: #fff;}
    	.take_express i{
    		display: inline-block;
    		width: 75px;height: 75px;
    		background: url({pigcms{$static_path}img/1-3_15.png) center no-repeat;
    		background-size: cover;
    		margin-right: 4%;
    		margin-top: 12.5px;
    	}
    	.help_express{
    		display: block;
    		width: 90%;margin: 0 5%;margin-top:60px;
    		height: 100px;
    		border-radius: 50px;
    		background:-moz-linear-gradient(left,#FF5C9C,#FFA465);/*Mozilla*/
			background:-webkit-gradient(left,#FF5C9C,#FFA465);/*Old gradient for webkit*/
			background:-webkit-linear-gradient(left,#FF5C9C,#FFA465);/*new gradient for Webkit*/
			background:-o-linear-gradient(left,#FF5C9C,#FFA465); /*Opera11*/
			line-height: 100px;
    	}
    	.help_express i{
    		display: inline-block;
    		width: 75px;height: 75px;
    		background: url({pigcms{$static_path}img/1-3_18.png) center no-repeat;
    		background-size: cover;
    		margin-right: 4%;
    		margin-top: 12.5px;
    	}
    </style>
</head>
<body>
	<div class="contanir">
		<a href="{pigcms{:U('express_service_list',array('village_id'=>$_GET['village_id']))}" class="take_express clear">
			<span class="ft">自己取</span>
			<i class="rg"></i>
		</a>
		<a href="{pigcms{:U('fetch_code')}" class="help_express clear">
			<span class="ft">帮别人取</span>
			<i class="rg"></i>
		</a>
	</div>
</body>
</html>