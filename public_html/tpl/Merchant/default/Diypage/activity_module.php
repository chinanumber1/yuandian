<!doctype html>
<html>
	<head>
		<meta charset="utf-8"/> 
		<title>营销系统活动</title>
		<link href="{pigcms{$static_path}diypage/css/customField.css" type="text/css" rel="stylesheet"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript">
			$(function(){
//				设置高度
				$('.js-modal iframe',parent.document).height($('body').height()+16);

//				关闭
				$('.modal-header .close').live("click", function(){
					parent.login_box_close();
				});

//				选取
				$('button.js-choose').live('click',function(){
					<?php if($_GET['type'] == 'more'){ ?>
						if($(this).hasClass('btn-primary')){
							$(this).removeClass('btn-primary').html('选取');
						}else{
							$(this).addClass('btn-primary').html('取消');
						}
						if($('.js-choose.btn-primary').size() > 0){
							$('.js-confirm-choose').show();
						}else{
							$('.js-confirm-choose').hide();
						}
						return false;
					<?php }else{ ?>
						parent.login_box_after('{pigcms{$_GET.number}','activity_module',$(this).data('title'),'{pigcms{$config.site_url}/wap.php?c=Wxapp&a=location_href&id='+$(this).data('id'));
					<?php } ?>
				});

//				确定选取
				$('.js-confirm-choose').live('click',function(){
					var data_arr = [];
					$.each($('.js-choose.btn-primary'),function(i,item){
						data_arr[i] = {'id':$(item).data('id'),'title':$(item).data('title'),'face_money':$(item).data('facemoney'),'condition':$(item).data('condition'),'number':$(item).data('number'),'total_amount':$(item).data('total_amount')};
					});

					parent.widget_box_after('{pigcms{$_GET['number']}',data_arr);
				});

//				分页
				$('.js-page-list a').live('click',function(e){
					if(!$(this).hasClass('active')){
						var input_val = $('.js-modal-search-input').val();
						$('body').html('<div class="loading-more"><span></span></div>');
						
						$('body').load('?c=Diypage&a=activity_module&type=more&store_id={pigcms{$now_store.store_id}&selecteditems={pigcms{$_GET['selecteditems']}&number={pigcms{$_GET['number']}',{page:$(this).data('page-num'),'keyword':input_val},function(){
							$('.js-modal iframe',parent.document).height($('body').height());
						});
					}
				});

//				搜索
				$('.js-modal-search').live('click',function(e){
					var input_val = $('.js-modal-search-input').val();
					$('body').html('<div class="loading-more"><span></span></div>');
					$('body').load('?c=Diypage&a=activity_module&type=more&store_id={pigcms{$now_store.store_id}&selecteditems={pigcms{$_GET['selecteditems']}&number={pigcms{$_GET['number']}',{'keyword':input_val},function(){
						$('.js-modal iframe',parent.document).height($('body').height());
					});
					return false;
				});

				//回车提交搜索
				$(window).keydown(function(event){
					if (event.keyCode == 13 && $('.js-modal-search-input').is(':focus')) {
						var input_val = $('.js-modal-search-input').val();
						$('body').html('<div class="loading-more"><span></span></div>');
						$('body').load('?c=Diypage&a=activity_module&type=more&store_id={pigcms{$now_store.store_id}&selecteditems={pigcms{$_GET['selecteditems']}&number={pigcms{$_GET['number']}',{'keyword':input_val},function(){
							$('.js-modal iframe',parent.document).height($('body').height());
						});
						return false;
					}
				})
			});
		</script>
	</head>
	<body style="background-color:#ffffff;">
		<div class="modal-header">
			<a class="close js-news-modal-dismiss">×</a>
			<!-- 顶部tab -->
			<ul class="module-nav modal-tab">
				<li class="active"><a href="javascript:void(0);" class="js-modal-tab">营销活动</a> </li>
			</ul>
		</div>
		<div class="modal-body">
			<div class="tab-content">
				<div id="js-module-feature" class="tab-pane module-feature active">
					<table class="table">
						<colgroup>
							<col class="modal-col-title">
							<col class="modal-col-time" span="2">
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
										<span>描述</span>
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
							<volist name="wxapp_list" id="vo">
								<tr>
									<td class="title" style="max-width:300px;">
										<div class="td-cont">
											<a target="_blank" class="new_window" href="javascript:">{pigcms{$vo.title}</a>
										</div>
									</td>
									<td class="time">
										<div class="td-cont">
											<span>{pigcms{$vo.info}</span>
										</div>
									</td>
									<td class="opts">
										<div class="td-cont">
											<if condition="in_array($vo['pigcms_id'],$selecteditemsArr)">
												<button class="btn js-choose" data-id="{pigcms{$vo.pigcms_id}" data-atype="4" data-title="{pigcms{$vo.title}" disabled>已选过</button>
											<else/>
												<button class="btn js-choose" data-id="{pigcms{$vo.pigcms_id}" data-atype="4" data-title="{pigcms{$vo.title}">选取</button>
											</if>
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