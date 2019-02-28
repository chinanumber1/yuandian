<!doctype html>
<html>
	<head>
		<meta charset="utf-8"/> 
		<title>团购列表</title>
		<link href="{pigcms{$static_path}diypage/css/customField.css" type="text/css" rel="stylesheet"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript">
			$(function(){
				$('.js-modal iframe',parent.document).height($('body').height()+16);
				$('.modal-header .close').live('click',function(){
					parent.login_box_close();
				});
				$('button.js-choose').live('click',function(){
					parent.login_box_after('{pigcms{$_GET.number}','group',$(this).data('title'),'{pigcms{$config.site_url}/wap.php?c=Group&a=detail&group_id='+$(this).data('id'));
				});
				$('.js-page-list a').live('click',function(e){
					if(!$(this).hasClass('active')){
						var input_val = $('.js-modal-search-input').val();
						$('body').html('<div class="loading-more"><span></span></div>');
						$('body').load("{pigcms{:U('group',array('store_id'=>$now_store['store_id']))}",{page:$(this).data('page-num'),'keyword':input_val},function(){
							$('.js-modal iframe',parent.document).height($('body').height());
						});
					}
				});
				$('.js-modal-search').live('click',function(e){
					var input_val = $('.js-modal-search-input').val();
					$('body').html('<div class="loading-more"><span></span></div>');
					$('body').load("{pigcms{:U('group',array('store_id'=>$now_store['store_id']))}",{'keyword':input_val},function(){
						$('.js-modal iframe',parent.document).height($('body').height());
					});
					return false;
				});
			});
		</script>
	</head>
	<body style="background-color:#ffffff;">
		<div class="modal-header">
			<a class="close js-news-modal-dismiss">×</a>
			<!-- 顶部tab -->
			<ul class="module-nav modal-tab">
				<li class="active"><a href="javascript:void(0);" class="js-modal-tab">团购列表</a> |</li>
				<li><a href="{pigcms{:U('Group/index')}" target="_blank" class="new_window">新建团购</a></li>
			</ul>
		</div>
		<div class="modal-body">
			<div class="tab-content">
				<div id="js-module-feature" class="tab-pane module-feature active">
					<table class="table">
						<colgroup>
							<col class="modal-col-title">
							<col class="modal-col-time">
							<col class="modal-col-action">
						</colgroup>
						<!-- 表格头部 -->
						<thead>
							<tr>
								<th class="title" style="background-color:#f5f5f5;">
									<div class="td-cont">
										<span>标题</span> <a class="js-update" href="javascript:window.location.reload();">刷新</a>
									</div>
								</th>
								<th class="time" style="background-color:#f5f5f5;">
									<div class="td-cont">
										<span>创建时间</span>
									</div>
								</th>
								<th class="opts" style="background-color:#f5f5f5;">
									<div class="td-cont" style="padding:7px 0 3px 10px;">
										<form class="form-search" onsubmit="return false;">
											<div class="input-append">
												<input class="input-small js-modal-search-input" type="text" style="border-radius:4px 0px 0px 4px;"/><a href="javascript:void(0);" class="btn js-fetch-page js-modal-search" style="border-radius:0 4px 4px 0;margin-left:0px;">搜</a>
											</div>
										</form>
									</div>
								</th>
							</tr>
						</thead>
						<!-- 表格数据区 -->
						<tbody>
							<volist name="group_list" id="vo">
								<tr>
									<td class="title" style="max-width:300px;">
										<div class="td-cont">
											<a target="_blank" class="new_window" href="{pigcms{$config.site_url}/wap.php?c=Group&a=detail&group_id={pigcms{$vo.group_id}">{pigcms{$vo.group_name}</a>
										</div>
									</td>
									<td class="time">
										<div class="td-cont">
											<span>{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}</span>
										</div>
									</td>
									<td class="opts">
										<div class="td-cont">
											<button class="btn js-choose" data-id="{pigcms{$vo.group_id}" data-title="{pigcms{$vo.group_name}">选取</button>
										</div>
									</td>
								</tr>
							</volist>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<div style="display:none;" class="js-confirm-choose left">
				<input type="button" class="btn btn-primary" value="确定使用">
			</div>
			<div class="pagenavi js-page-list" style="margin-top:0;padding-top:2px;">{pigcms{$page_bar}</div>
		</div>
	</body>
</html>