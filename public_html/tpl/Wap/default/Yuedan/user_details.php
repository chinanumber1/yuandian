<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
    	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<title>个人主页</title>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}yuedan/css/details.css"/>
	</head>
	<body>
		<div class="header">
			<a href="javascript:history.back(-1)"></a>
			<dl>
				<dt><img style="border-radius: 50%;" src="{pigcms{$user_info.avatar}"/></dt>
				<dd>{pigcms{$user_info.nickname}</dd>
				<dd><i></i> <span>{pigcms{$user_info.juli}km</span></dd>
			</dl>
		</div>
		<div class="content">
			<div class="photo_list">
				<div class="photos">
					<span>个人相册</span>
					<div class="imgs">
						<volist name="service_photos" id="vo" offset="0" length='3'>
							<!-- <img src="{pigcms{$vo}"/> -->
							<p class="img_click" data-src="{pigcms{$vo}" style="display:inline-block; width:60px;background: transparent url({pigcms{$vo}) no-repeat 0% 0px;background-size:cover; height:60px;text-align: center;"></p>
						</volist>
					</div>
				</div>
				<a href="{pigcms{:U('Yuedan/user_photos',array('uid'=>$user_info['uid']))}"><p class="more"><span>更多</span> <i></i></p></a>
			</div>
			<div class="porson">
				<span>个人认证</span> <if condition="$user_info['phone'] neq ''"><i></i></if> <if condition="is_array($authentication)"><b></b></if> <if condition="is_array($gradeUser)"><em></em></if>
			</div>
			<div class="visit">
				<span>访&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;客</span>
				<span>{pigcms{$user_info.page_view}</span>
			</div>
			<div class="skill">
				<span>Ta的技能</span>
			</div>
			<div class="skills_list">
				<ul class="clear">
					<volist name="service_list" id="vo">
						<a href="{pigcms{:U('Yuedan/service_detail',array('rid'=>$vo['rid']))}">
							<li class="ft">
								<dl>
									<dt>
									<!-- <img src="{pigcms{$vo.listimg}" alt="{pigcms{$vo.title}"/> -->
										<p class="img_click" data-src="{pigcms{$vo}" style="display:inline-block; width:50px;background: transparent url({pigcms{$vo.listimg}) no-repeat 0% 0px;background-size:cover; height:50px;text-align: center;"></p>
									</dt>
									<dd>{pigcms{$vo.title}</dd>
								</dl>
							</li>
						</a>
					</volist>
				</ul>
			</div>
		</div>
	</body>
</html>
