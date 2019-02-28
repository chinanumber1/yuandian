<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>我的收藏</title>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}yuedan/css/my_fabu.css"/>
    <script src="{pigcms{$static_path}yuedan/js/jquery-1.9.1.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
</head>
<body>
	<header>
		<a href="JavaScript:history.back(-1)" class="ft"><i></i></a>
		<span>我的收藏</span>
	</header>
	<div class="personality fuwu">
		<volist name="collectionList" id="vo">
			<div class="sevice after">
				<a href="{pigcms{:U('Yuedan/service_detail',array('rid'=>$vo['rid']))}">
					<div class="top">
						<img src="{pigcms{$vo.listimg}"/>
						<ul>
							<li>{pigcms{$vo.title}</li>
							<li>￥{pigcms{$vo.price}/{pigcms{$vo.unit}</li>
						</ul>
					
					</div>
				</a>
			</div>
		</volist>
	</div>
</body>
</html>