<?php if(empty($_GET['not_first'])){ ?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8"/> 
		<title>微页面</title>
		<link href="{pigcms{$static_path}diypage/css/customField.css" type="text/css" rel="stylesheet"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript">
			var pageForm = '{pigcms{$_GET.pageFrom}';
			var max_num  = {pigcms{$_GET.max_num|intval=###};
			$(function(){
				$('.js-modal iframe',parent.document).height($('body').height()+16);
				$('.modal-header .close').live('click',function(){
					if(pageForm == 'shop_fitment'){
						var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
						parent.layer.close(index); //再执行关闭
					}else{
						parent.login_box_close();
					}
				});
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
					<?php }else{ ?>
						parent.login_box_after('{pigcms{$_GET.number}','good',$(this).data('title'),'{pigcms{$config.site_url}/wap.php?c=Mall&a=detail&goods_id='+$(this).data('id'));
					<?php } ?>
				});
				$('.js-page-list a').live('click',function(e){
					if(!$(this).hasClass('active')){
						var input_val = $('.js-modal-search-input').val();
						$('body').html('<div class="loading-more"><span></span></div>');
						$('body').load("{pigcms{:U('good',array('type'=>$_GET['type'],'selecteditems'=>$_GET['selecteditems'],'store_id'=>$now_store['store_id'],'number'=>$_GET['number'],'not_first'=>1))}",{page:$(this).data('page-num'),'keyword':input_val},function(){
							$('.js-modal iframe',parent.document).height($('body').height());
						});
					}
				});
				$('.js-modal-search').live('click',function(e){
					var input_val = $('.js-modal-search-input').val();
					$('body').html('<div class="loading-more"><span></span></div>');
					$('body').load("{pigcms{:U('good',array('type'=>$_GET['type'],'selecteditems'=>$_GET['selecteditems'],'store_id'=>$now_store['store_id'],'number'=>$_GET['number'],'not_first'=>1))}",{'keyword':input_val},function(){
						$('.js-modal iframe',parent.document).height($('body').height());
					});
					return false;
				});
				$('.js-confirm-choose').live('click',function(){
					var data_arr = [];
					$.each($('.js-choose.btn-primary'),function(i,item){
						data_arr[i] = {'id':$(item).data('id'),'title':$(item).data('title'),'image':$(item).data('image'),'price':$(item).data('price'),'url':'{pigcms{$config.site_url}/wap.php?c=Mall&a=detail&goods_id='+$(this).data('id')};
					});
					if(pageForm == 'shop_fitment'){
						var selected = '{pigcms{$_GET.selecteditems}';
						selectedArr = selected.split(',');
						var selectedAllNum = selectedArr.length + data_arr.length;
						if(max_num > 0 && selectedAllNum > max_num){
							parent.layer.msg('最多仅能添加'+max_num+'个商品。<br/>您现在已经添加了 ' + selectedAllNum + ' 个，请先取消再进行操作。');
							return false;
						}
						console.log(data_arr);
						parent.frames[parent.subject_win_name].build_good_save(data_arr);
						$('.modal-header .close').trigger('click');
					}else{
						parent.widget_box_after('{pigcms{$_GET.number}',data_arr);
					}
				});
			});
		</script>
	</head>
	<body style="background-color:#ffffff;">
<?php } ?>
		<div class="modal-header">
			<a class="close js-news-modal-dismiss">×</a>
			<!-- 顶部tab -->
			<ul class="module-nav modal-tab">
				<li class="active"><a href="javascript:void(0);" class="js-modal-tab">商品列表</a> |</li>
				<li><a href="{pigcms{:U('Shop/goods_sort',array('store_id'=>$_GET['store_id']))}" target="_blank" class="new_window">新建商品</a></li>
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
										<span>最后修改时间</span>
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
							<volist name="good_list" id="vo">
								<tr>
									<td class="title" style="max-width:300px;">
										<div class="td-cont">
											<a target="_blank" class="new_window" href="{pigcms{$config.site_url}/wap.php?c=Mall&a=detail&goods_id={pigcms{$vo.goods_id}">{pigcms{$vo.name}</a>
										</div>
									</td>
									<td class="time">
										<div class="td-cont">
											<span>{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}</span>
										</div>
									</td>
									<td class="opts">
										<div class="td-cont">
											<if condition="in_array($vo['goods_id'],$selecteditemsArr)">
												<button class="btn js-choose" data-id="{pigcms{$vo.goods_id}" data-title="{pigcms{$vo.name}" data-price="{pigcms{$vo.price}" data-image="{pigcms{$vo.pic_arr.0.url.m_image}" disabled>选取</button>
											<else/>
												<button class="btn js-choose" data-id="{pigcms{$vo.goods_id}" data-title="{pigcms{$vo.name}" data-price="{pigcms{$vo.price}" data-image="{pigcms{$vo.pic_arr.0.url.m_image}">选取</button>
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
<?php if(empty($_GET['not_first'])){ ?>
	</body>
</html>
<?php } ?>