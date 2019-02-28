<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<title>约单手册</title>
		<style>
			*{margin: 0;padding: 0;}
			ul,ol,li{list-style:none;}
			body{background: #f4f4f4;}	
			header {
			    height: 50px;
			    background-color: #06c1ae;
			    color: white;
			    line-height: 50px;
			    text-align: center;
			    position: relative;
			    font-size: 16px;
			}
			header #backBtn {
			    position: absolute;
			    width: 50px;
			    height: 100%;
			    top: 0;
			    left: 0;
			}
			header #backBtn:after {
			    display: block;
			    content: "";
			    border-top: 2px solid white;
			    border-left: 2px solid white;
			    width: 12px;
			    height: 12px;
			    -webkit-transform: rotate(315deg);
			    background-color: transparent;
			    position: absolute;
			    top: 19px;
			    left: 19px;
			}
			.item{
				/*width: 100%;*/
				margin: 10px 0;
				background: white;
				padding: 12px;
				font-size: 15px;
				color: #333;
				display: -webkit-flex;
			    display: flex;
			    -webkit-box-pack: justify;
			    -webkit-justify-content: space-between;
			    justify-content: space-between;
			    -webkit-box-align: center;
			    -webkit-align-items: center;
			    align-items: center;
			}
			.item b{
				display: inline-block;
				width: 18px;height: 18px;
				background: url({pigcms{$static_path}yuedan/images/j1.png) center no-repeat;
				background-size: contain;
			}
		</style>
	</head>
	<body>
		<header class="pageSliderHide"><div id="backBtn" onclick="javascript :history.back(-1);"></div>约单手册</header>
		<div class="content">
			<div class="lists">
				<volist name="handbook_list" id="vo">
					<a href="{pigcms{:U('Yuedan/handbook_detail',array('handbook_id'=>$vo['handbook_id']))}" style="text-decoration:none;">
						<div class="item"> <span>{pigcms{$vo.title}</span> <b></b> </div>
					</a>
				</volist>
			</div>
		</div>
	</body>
</html>