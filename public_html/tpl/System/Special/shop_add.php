<include file="Public:header"/>
		<script type="text/javascript" src="{pigcms{$static_public}js/color-picker/main.js"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/color-picker/main.css" />
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/custom-ui.css" />
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('shop')}">快店专题列表</a>|
					<a href="{pigcms{:U('shop_add')}" class="on">创建专题页面</a>|
				</ul>
			</div>
			<div class="app-design clearfix">
				<div class="app-preview">
					<div class="app-header"></div>
					<div class="app-entry">
						<div class="app-config js-config-region" data-name="专题名称" data-desc="" data-bgcolor="#f9f9f9">
							<div class="app-field clearfix">
								<h1><span>专题名称</span></h1>
							</div>
						</div>
						<div class="app-fields js-fields-region">
							<div class="app-fields ui-sortable">
								<div class="app-field clearfix">
									<div class="control-group">
										<section id="listHeader" class="roundBg" style="background-color: rgba(6, 193, 174, 0);">
											<div id="listBackBtn" class="listBackBtn hide"><div></div></div>
											<div id="locationBtn" class="page-link" data-url="address" data-url-type="openRightFloatWindow">
												<span class="location"></span>
												<span id="locationText">合肥广电中心</span>
												<span class="go"></span>
											</div>
											<div id="searchBtn" class="listSearchBtn page-link" data-url="shopSearch"><div></div></div>
										</section>				
										
										<div class="custom-image-swiper" title="点击上传图片，不上传则留空" data-image="" style="margin-top: -50px;">
											<img style="display:block;width:100%;" src="{pigcms{$static_path}images/default_special_img.png"/>
										</div>
									</div>
								</div>
							</div>
							<div class="app-fields ui-sortable">
								<div class="app-field clearfix">
									<div class="control-group">
										<div class="coupon_list clearfix"></div>
										<div class="addCouponBtn" title="点击添加优惠券"></div>
									</div>
								</div>
							</div>
							<div class="app-fields ui-sortable addCategoryBox">
								<div class="app-field clearfix">
									<div class="control-group">
										<div class="addCategoryBtn" title="点击添加分类">
											<ul>
												<li class="curr">店铺分类</li>
											</ul>
											<div class="right">+</div>
										</div>
									</div>
								</div>
							</div>
							<div class="app-fields addProductBox">
								<div class="app-field clearfix">
									<div class="control-group">
										<div class="addProductBtn" title="点击添加店铺"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="app-sidebars">
					<div class="app-sidebar">
						<div class="arrow"></div>
						<div class="app-sidebar-inner js-sidebar-region"></div>
					</div>
				</div>
				<div class="app-actions">
					<div class="form-actions text-center">
						<input class="btn btn-primary btn-save" type="submit" value="保存"/>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/laytpl.js"></script>
		<script type="text/javascript">
			$(function(){
				$('.js-config-region').click(function(){
					$('.js-sidebar-region').empty();
					$('.app-sidebar').css('margin-top',$('.js-config-region').offset().top - $('.app-preview').offset().top);
					var theme_data = {'name':$('.js-config-region').data('name'),'desc':$('.js-config-region').data('desc'),'bgcolor':$('.js-config-region').data('bgcolor')};
					laytpl($('#headerTpl').html()).render(theme_data, function(html){
						$('.app-sidebar').show();
						$('.js-sidebar-region').html(html);
						
						$('.js-sidebar-region input[name="name"]').blur(function(){
							$('.js-config-region').data('name',$(this).val());
							$('.js-config-region h1 span').html($(this).val());
						});
						$('.js-sidebar-region input[name="desc"]').blur(function(){
							$('.js-config-region').data('desc',$(this).val());
						});
						$('.js-sidebar-region input[name="color"]').colpick({
							color:$('.js-config-region').data('bgcolor'),
							onChange:function(hsb,hex,rgb,el,bySetColor) {
								$(el).css('border-color','#'+hex);
								if(!bySetColor) $(el).val('#'+hex);
								$('.app-entry').css('background','#'+hex);
								$('.js-config-region').data('bgcolor','#'+hex);
							}
						}).keyup(function(){
							$(this).colpickSetColor(this.value);
						});
						
						$('.js-sidebar-region .js-reset-bg').click(function(){
							$(this).siblings('input[name="color"]').val('#f9f9f9');
							$('.js-config-region').data('bgcolor','#f9f9f9');
							$('.app-preview .app-entry').css('background','#f9f9f9');
						});
					});
				});
				$('.js-config-region').trigger('click');
				
				var  uploader = WebUploader.create({
					auto: true,
					swf: '{pigcms{$static_public}js/Uploader.swf',
					server: "{pigcms{:U('ajax_upload_pic')}",
					pick: {
						id:'.custom-image-swiper',
						multiple:false
					},
					accept: {
						title: 'Images',
						extensions: 'gif,jpg,jpeg,png',
						mimeTypes: 'image/*'
					}
				});
				var site_url = "{pigcms{$config.site_url}";
				uploader.on('uploadSuccess',function(file,response){
					if(response.error_code == '0'){
						$('.custom-image-swiper img').attr('src',site_url+response.url);
						$('.custom-image-swiper').data('image',site_url+response.url);
						$('.custom-image-swiper img').load(function(){
							$('.custom-image-swiper div:last').height($('.custom-image-swiper img').height());
							changeAddFixed();
						});
					}else{
						alert(response.message);
					}
				});
				$('.addCouponBtn').click(function(){
					if($('.couponRow').size() == 9){
						top.msg(0,'为了保证网页打开速度，优惠券最多只能添加9个。',true);
						return false;
					}
					window.top.artiframe("{pigcms{:U('choose_shop_coupon')}",'选择优惠券',800,500,true,false,'black',null,'choose_shop_coupon');
				});
				$('.addProductBtn').click(function(){
					window.top.artiframe("{pigcms{:U('choose_shop')}",'选择店铺',800,500,true,false,'black',null,'choose_shop');
				});
				
				$('.js-fields-region .action.delete').live('click',function(){
					var regionDom = $(this).closest('.app-fields');
					$('.ui-popover').remove();
					var theme_data = {title:'确定删除？',top:$(this).offset().top-16,left:$(this).offset().left + $(this).width() + 10};
					laytpl($('#popoverTpl').html()).render(theme_data, function(html){
						$('body').append(html);
						$('.ui-popover .js-save').click(function(){
							$('.ui-popover').remove();
							regionDom.remove();
							changeAddFixed();
						});
						$('.ui-popover .js-cancel').click(function(){
							$('.ui-popover').remove();
						});
						$('.ui-popover').bind('click',function(){
							return false;
						});
						
						$('body').unbind('click');
						$('body').bind('click',function(){
							$('.ui-popover').remove();
							$('body').unbind('click');
						});
					});
				});
				$('.addCategoryBtn li').live('click',function(){
					$(this).addClass('curr').siblings().removeClass('curr');
					$('.productRow').hide();
					$('.productRow.cat-'+$(this).index()).show();
				});
				
				$('.addCategoryBtn .right').click(function(){
					$('.js-sidebar-region').empty();
					$('.app-sidebar').css('margin-top',$('.addCategoryBtn').offset().top - $('.app-preview').offset().top);
					
					var catData = [];
					$.each($('.addCategoryBtn ul li'),function(i,item){
						catData.push($(item).html());
					});
					var theme_data = {catData:catData};
					laytpl($('#categoryTpl').html()).render(theme_data, function(html){
						$('.js-sidebar-region').html(html);
						$('.app-sidebar').show();
						$('.js-sidebar-region .js-add-option').click(function(){
							if($('.js-sidebar-region .js-collection-region ul li').size() >= 5){
								top.msg(0,'为了保证网页打开速度，最多添加5个分类。',true);
								return false;
							}
							laytpl($('#categoryLiTpl').html()).render(theme_data, function(html){
								$('.js-sidebar-region .js-collection-region ul').append(html);
								$('.addCategoryBtn ul').append('<li>店铺分类</li>');
								$('.addCategoryBtn').attr('class','addCategoryBtn col-'+$('.addCategoryBtn li').size());
							});
						});
					});
					return false;
				});
				$('.js-sidebar-region .js-collection-region ul .delete').live('click',function(){
					var nowIndex = $(this).closest('li').index();
					if($('.js-sidebar-region .js-collection-region ul li').size() == 1){
						top.msg(0,'必须要保留一个分类',true);
						return false;
					}
					var nowLiDom = $('.addCategoryBtn li').eq(nowIndex);
					
					$('.productRow.cat-'+nowIndex).remove();
					
					for(var i = nowIndex;i<5;i++){
						$('.productRow.cat-'+(i+1)).attr('class','app-fields ui-sortable productRow cat-'+i).attr('data-catindex',i);
					}
					
					
					if(nowLiDom.hasClass('curr')){
						nowLiDom.remove();
						$('.addCategoryBtn li:eq(0)').addClass('curr').trigger('click');
					}else{
						nowLiDom.remove();
					}
					
					$(this).closest('li').remove();
					
					$('.addCategoryBtn').attr('class','addCategoryBtn col-'+$('.addCategoryBtn li').size());	
				});
				$('.js-sidebar-region .js-collection-region ul .add').live('click',function(){
					if($('.js-sidebar-region .js-collection-region ul li').size() >= 5){
						top.msg(0,'为了保证网页打开速度，最多添加5个分类。',true);
						return false;
					}
					var nowDom = $(this).closest('li');
					laytpl($('#categoryLiTpl').html()).render({}, function(html){
						nowDom.after(html);
						$('.addCategoryBtn li').eq(nowDom.index()).after('<li>店铺分类</li>');
						$('.addCategoryBtn').attr('class','addCategoryBtn col-'+$('.addCategoryBtn li').size());
					});
				});
				$('.js-sidebar-region .js-collection-region ul input[name="title"]').live('blur',function(){
					$('.addCategoryBtn li').eq($(this).closest('li').index()).html($(this).val());
				});
				$('.couponRow .delete').live('click',function(){
					var regionDom = $(this).closest('.couponRow');
					$('.ui-popover').remove();
					var theme_data = {title:'确定删除？',top:$(this).offset().top+($(this).height()-48)/2,left:$(this).offset().left + $(this).width()-5};
					laytpl($('#popoverTpl').html()).render(theme_data, function(html){
						$('body').append(html);
						$('.ui-popover .js-save').click(function(){
							$('.ui-popover').remove();
							regionDom.remove();
							$('.addCouponBtn').show();
							changeAddFixed();
						});
						$('.ui-popover .js-cancel').click(function(){
							$('.ui-popover').remove();
						});
						$('.ui-popover').bind('click',function(){
							return false;
						});
						
						$('body').unbind('click');
						$('body').bind('click',function(){
							$('.ui-popover').remove();
							$('body').unbind('click');
						});
					});
				});
				$('.app-actions .btn-save').click(function(){
					if($(this).hasClass('posting')){
						return false;
					}
					var postData = {};
					postData.type = '1';
					postData.name = $('.js-config-region').data('name');
					postData.desc = $('.js-config-region').data('desc');
					postData.bgcolor = $('.js-config-region').data('bgcolor');
					postData.image = $('.custom-image-swiper').data('image');
					
					postData.coupon = [];
					if($('.couponRow').size() > 0){
						$.each($('.couponRow'),function(i,item){
							postData.coupon.push({id:$(item).data('id'),name:$(item).data('name'),order_money:$(item).data('order_money'),discount:$(item).data('discount')});
						});
					}
					postData.product_list = [];
					$.each($('.addCategoryBtn ul li'),function(i,item){
						postData.product_list.push({name:$(item).html(),product:[]});
					});
					console.log(postData);
					
					if($('.productRow').size() == 0){
						top.msg(0,'您至少应该添加一个店铺',true);
						return false;
					}
					$.each($('.productRow'),function(i,item){
						postData.product_list[$(item).data('catindex')].product.push({id:$(item).data('id'),name:$(item).data('name'),image:$(item).data('image')});
					});
					top.msg(2,'正在提交中...',true,0);
					$.post("{pigcms{:U('motify')}",postData,function(result){
						if(result.status == 1){
							top.msg(1,result.info,true);
							location.href="{pigcms{:U('shop')}";
						}else{
							top.msg(0,result.info,true);
						}
					});
				});
			});
			function select_product(id,name,image){
				var catIndex = $('.addCategoryBtn li.curr').index();
				if($('.productRow.cat-'+catIndex).size() >= 100){
					top.closeiframebyid('choose_shop');
					top.msg(0,'为了保证网页打开速度，同一分类最多只能添加20个店铺。',true);
					return false;
				}
				var theme_data = {id:id,name:name,image:image,catIndex:catIndex};
				laytpl($('#productTpl').html()).render(theme_data, function(html){
					$('.addProductBtn').closest('.app-fields').before(html);
					changeAddFixed();
				});
			}
			function get_selected_product(){
				var catIndex = $('.addCategoryBtn li.curr').index();
				var product = [];
				$.each($('.productRow.cat-'+catIndex),function(i,item){
					product.push($(item).data('id'));
				});
				return product;
			}
			function get_selected_coupon(){
				var coupon = [];
				$.each($('.couponRow'),function(i,item){
					coupon.push($(item).data('id'));
				});
				return coupon;
			}
			function select_coupon(id,name,discount,order_money){
				if($('.couponRow').size() >= 9){
					top.closeiframebyid('choose_shop_coupon');
					top.msg(0,'为了保证网页打开速度，优惠券最多只能添加9个。',true);
					return false;
				}
				var theme_data = {id:id,name:name,discount:discount,order_money:order_money};
				laytpl($('#couponTpl').html()).render(theme_data, function(html){
					$('.coupon_list').append(html);
					$('.couponRow').css('margin-top',$('.coupon_list').width()*0.02+'px');
					$('.couponRow .delete').height($('.couponRow').height());
					$('.addCategoryBox').css('margin-top',$('.coupon_list').width()*0.02+'px');
					if($('.couponRow').size() == 9){
						$('.addCouponBtn').hide();
					}
					changeAddFixed();
				});
			}
			function changeAddFixed(){
				/*$('.addProductBox').removeClass('fixed');
				$('.productRow').removeClass('last-child');
				if($('.js-config-region').height() + $('.js-fields-region').height() > $('.app-entry').height() && $('.productRow').size() >= 2){
					$('.addProductBox').addClass('fixed').css('top',$('.app-entry').offset().top+478);
					$('.productRow:last').addClass('last-child');
				}else{
					$('.addProductBox').removeClass('fixed');
				}*/
			}
		</script>
		<script id="headerTpl" type="text/html">
			<div>
				<form class="form-horizontal">
					<div class="control-group">	
						<label class="control-label"><em class="required">*</em>专题名称：</label>
						<div class="controls"><input class="input-xxlarge" type="text" name="name" value="{{ d.name }}" placeholder="建议字数不超过20个"/></div>
					</div>
					<div class="control-group">	
						<label class="control-label">页面描述：</label>	
						<div class="controls"><input class="input-xxlarge" type="text" name="desc" value="{{ d.desc }}" placeholder="用户通过微信分享给朋友时，会自动显示页面描述"/></div>
					</div>
					<div class="control-group">
						<label class="control-label">背景颜色：</label>
						<div class="controls">
							<input type="text" value="{{ d.bgcolor }}" name="color" style="border-right:80px solid {{ d.bgcolor }}"/>
							<button class="btn js-reset-bg" type="button">重置</button>
						</div>
					</div>
				</form>
			</div>
		</script>
		<script id="productTpl" type="text/html">
			<div class="app-fields ui-sortable productRow cat-{{ d.catIndex }}" data-id="{{ d.id }}" data-name="{{ d.name }}" data-catIndex="{{ d.catIndex }}" data-image="{{ d.image }}">
				<div class="app-field clearfix">
					<div class="control-group product_dealcard">
						<div class="dealcard-img imgbox">
							<img src="{{ d.image }}" alt="{{ d.name }}">
						</div>
						<div class="dealcard-block-right">
							<div class="brand">{{ d.name }}<em class="location-right">xxx km</em></div>
							<div class="title">
								<span class="star"><i class="full"></i><i class="full"></i><i class="full"></i><i class="half"></i><i></i></span>
								<span>月售xx单</span>
								<em class="location-right">xx分钟</em>
							</div>
							<div class="price">
								<span>起送价 ￥xx</span>
								<span class="delivery">配送费 ￥xx</span>
								<em class="location-right">配送类型</em>
							</div>
							<div class="coupon">
								<ul>
									<li><em class="couponem"></em>优惠信息在手机版显示</li>            
								</ul>
							</div>
						</div>
					</div>
					<div class="actions">
						<div class="actions-wrap">
							<span class="action delete">删除</span>
						</div>
					</div>
					<div class="sort">
						<i class="sort-handler"></i>
					</div>
				</div>
			</div>
		</script>
		<script id="couponTpl" type="text/html">
			<a href="javascript:;" class="couponRow" data-id="{{ d.id }}" data-name="{{ d.name }}" data-order_money="{{ d.order_money }}" data-discount="{{ d.discount }}">
				<p class="coupon_name pull-left"><span>{{ d.name }}</span></p>
				<p class="coupon_monery pull-left">{{ d.order_money }}</p>
				<p class="coupon_use pull-left oneline">满{{ d.discount }}元可用</p>
				<span class="icon2"></span>
				<div class="delete" title="删除">×</div>
			</a>
		</script>
		<script id="categoryTpl" type="text/html">
			<div>
				<form class="form-horizontal">
					<p style="margin:5px 0 2px 10px;color:#999;">若只有一个分类，前台默认不显示。</p>
					<p style="margin:5px 0 2px 10px;color:#999;">为了保证网页打开速度，最多添加5个分类。</p>
					<div class="control-group js-collection-region">
						<ul class="choices ui-sortable">
							{{# for(var i in d.catData){ }}
								<li class="choice" style="padding-bottom:7px;">
									<div class="control-group" style="margin-bottom:0px;">
										<label class="control-label"><em class="required">*</em>分类名称：</label>
										<div class="controls"><input type="text" name="title" value="{{ d.catData[i] }}"/></div>
									</div>
									<div class="actions">
										<span class="action add close-modal" title="添加">+</span>
										<span class="action delete close-modal" title="删除">×</span>
									</div>
								</li>
							{{# } }}
						</ul>
					</div>
					<div class="control-group options">
						<a class="add-option js-add-option" href="javascript:void(0);"><i class="icon-add"></i> 添加一个文本导航</a>
					</div>
				</form>
			</div>
		</script>
		<script id="categoryLiTpl" type="text/html">
			<li class="choice" style="padding-bottom:7px;">
				<div class="control-group" style="margin-bottom:0px;">
					<label class="control-label"><em class="required">*</em>分类名称：</label>
					<div class="controls"><input type="text" name="title" value="店铺分类"/></div>
				</div>
				<div class="actions">
					<span class="action add close-modal" title="添加">+</span>
					<span class="action delete close-modal" title="删除">×</span>
				</div>
			</li>
		</script>
		<script id="popoverTpl" type="text/html">
			<div class="ui-popover ui-popover--confirm right-center" style="top:{{ d.top }}px;left:{{ d.left }}px">
				<div class="ui-popover-inner clearfix "> 
					<div class="inner__header clearfix">
						<div class="pull-left text-center" style="width:100px;line-height:28px;font-size:14px;">{{ d.title }}</div>
						<div class="pull-right">
							<a href="javascript:;" class="zent-btn zent-btn-primary zent-btn-small js-save">确定</a>
							{{# if(!d.noDeleteBtn){ }}<a href="javascript:;" class="zent-btn zent-btn-small js-cancel">取消</a>{{# } }}
						</div>
					</div>			
				</div>
				<div class="arrow"></div>
			</div>
		</script>
<include file="Public:footer"/>