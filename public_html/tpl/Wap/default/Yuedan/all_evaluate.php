<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>评价列表</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-touch-fullscreen" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="address=no">
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}yuedan/css/list_details.css"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}yuedan/css/all_details.css"/>
		
		<script src="{pigcms{$static_path}yuedan/js/jquery-1.9.1.min.js" type="text/javascript" charset="utf-8"></script>
	</head>
	<body>
		<header>
			<a href="JavaScript:history.back(-1)" class="ft"><i></i> </a>
			<span>评价</span>
		</header>
		<div class="overall_evaluation">
		
			<span><p class="stars"><span class="add"><i class="active" style="width: {pigcms{$totalGrade*10*2}%"></i><span class="fen">{pigcms{$totalGrade}</span></span>
			</p></span>

			<!-- 总体评价 <span><i class="active" style="width: {pigcms{$totalGrade*10*2}%"></i>  </span> -->
		</div>
		<!--评价-->
		<div class="evaluate">
			<volist name="commentList" id="vo">
				<div class="content">
					<div class="xing">
						<p>{pigcms{$vo.nickname}</p>
						<p class="stars1"><span class="acc"><i style="width: {pigcms{$vo[total_grade]*10}%"></i></span></p>
					</div>
					<p>{pigcms{$vo.add_time|date="Y-m-d",###}</p>
					<div class="lun">
						{pigcms{$vo.content}
					</div>
				</div>
			</volist>
		</div>
	</body>
</html>
