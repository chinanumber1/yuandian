<!doctype html>
<html>
	<head>
		<meta charset="utf-8"/> 
		<title>优惠券挂件</title>
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
						$('body').load('?c=Diypage&a=coupon&type=more',{p:$(this).data('page-num'),'keyword':input_val,'selecteditems':"{pigcms{$_GET['selecteditems']}"},function(){
							$('.js-modal iframe',parent.document).height($('body').height());
						});
					}
				});

//				搜索
				$('.js-modal-search').live('click',function(e){
					var input_val = $('.js-modal-search-input').val();
					$('body').html('<div class="loading-more"><span></span></div>');
					$('body').load('?c=Diypage&a=coupon&type=more',{'keyword':input_val,'selecteditems':"{pigcms{$_GET['selecteditems']}"},function(){
						$('.js-modal iframe',parent.document).height($('body').height());
					});
					return false;
				});

				//回车提交搜索
				$(window).keydown(function(event){
					if (event.keyCode == 13 && $('.js-modal-search-input').is(':focus')) {
						var input_val = $('.js-modal-search-input').val();
						$('body').html('<div class="loading-more"><span></span></div>');
						$('body').load('?c=Diypage&a=coupon&type=more',{'keyword':input_val,'selecteditems':"{pigcms{$_GET['selecteditems']}"},function(){
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
				<li class="active"><a href="javascript:void(0);" class="js-modal-tab">已发布的优惠券</a> </li>
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
										<span>面值</span> <a class="js-update" href="javascript:window.location.reload();">刷新</a>
									</div>
								</th>
								<th class="time" style="background-color:#f5f5f5;">
									<div class="td-cont">
										<span>优惠券名称</span>
									</div>
								</th>
								<th class="quantity" style="background-color:#f5f5f5;">
									<div class="td-cont">
										<span>已领取/总共</span>
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
							<volist name="coupon_list" id="vo">
								<tr>
									<td class="title" style="max-width:300px;">
										<div class="td-cont">
											<a target="_blank" class="new_window" href="javascript:">{pigcms{$vo.discount}</a>
										</div>
									</td>
									<td class="time">
										<div class="td-cont">
											<span>{pigcms{$vo.name}</span>
										</div>
									</td>
									<td class="time">
										<div class="td-cont">
											<span>{pigcms{$vo.had_pull}/{pigcms{$vo.num}</span>
										</div>
									</td>
									<td class="opts">
										<div class="td-cont">
											<if condition="in_array($vo['coupon_id'],$selecteditemsArr)">
												<button class="btn js-choose" data-id="{pigcms{$vo.coupon_id}" data-facemoney="{pigcms{$vo.discount}" data-title="{pigcms{$vo.name}" data-condition="" data-number="{pigcms{$vo.had_pull}" data-total_amount="10" disabled>已选过</button>
											<else/>
												<button class="btn js-choose" data-id="{pigcms{$vo.coupon_id}" data-facemoney="{pigcms{$vo.discount}" data-title="{pigcms{$vo.name}" data-condition="" data-number="{pigcms{$vo.had_pull}" data-total_amount="{pigcms{$vo.num}">选取</button>
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