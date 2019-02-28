<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Config/store')}">店铺管理</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Diypage/index',array('store_id'=>$now_store['store_id']))}">【{pigcms{$now_store.name}】 自定义页面列表</a></li>
			<li class="active"><if condition="$_GET['page_id']">编辑<else/>新建</if>自定义页面</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="app-init-container">
                <div class="nav-wrapper--app"></div>
                <div class="app__content js-app-main">
					<div class="app-design clearfix">
						<div class="app-preview">
							<div class="app-header"></div>
							<div class="app-entry">
								<div class="app-config js-config-region">
									<div class="app-field clearfix editing">
										<h1><span>页面标题</span></h1>
									</div>
								</div>
								<div class="app-fields js-fields-region">
									<div class="app-fields ui-sortable"></div>
								</div>
							</div>
							<div class="js-add-region">
								<div>
									<div class="app-add-field">
										<h4>添加内容</h4>
										<ul>
											<li><a class="js-new-field rich-text" data-field-type="title">标题</a></li>
											<li><a class="js-new-field rich-text" data-field-type="rich_text">富文本</a></li>
											<li><a class="js-new-field rich-text" data-field-type="goods">商品</a></li>
											<li><a class="js-new-field rich-text" data-field-type="text_nav">文本<br>导航</a></li>
											<li><a class="js-new-field rich-text" data-field-type="search">商品<br>搜索</a></li>
											<li><a class="js-new-field rich-text" data-field-type="line">辅助线</a></li>
											<li><a class="js-new-field rich-text" data-field-type="white">辅助<br>空白</a></li>
											<li><a class="js-new-field rich-text" data-field-type="store">进入<br>店铺</a></li>
											<li><a class="js-new-field rich-text" data-field-type="notice">公告</a></li>
											<li><a class="js-new-field rich-text" data-field-type="tpl_shop">网店logo抬头</a></li>
											<li><a class="js-new-field rich-text" data-field-type="image_nav">图片<br>导航</a></li>
											<li><a class="js-new-field rich-text" data-field-type="image_ad">图片<br>广告</a></li>
											<li><a class="js-new-field rich-text" data-field-type="map">店铺地图</a></li>
											<li><a class="js-new-field rich-text" data-field-type="coupons">优惠券</a></li>
											<if condition="$config['wxapp_url']">
												<li><a class="js-new-field rich-text" data-field-type="new_activity_module">营销<br>活动</a></li>
											</if>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<div class="app-sidebars">
							<div class="app-sidebar" style="margin-top:72px;">
								<div class="arrow"></div>
								<div class="app-sidebar-inner js-sidebar-region"></div>
							</div>
						</div>
						<div class="app-actions bottom" style="display:block;bottom:0px;">
							<div class="form-actions text-center">
								<input class="btn btn-primary btn-save" type="submit" value="保存" data-loading-text="保存...">
							</div>
						</div>
					</div>
				</div>
            </div>
		</div>
	</div>
</div>
<if condition="$now_page">
	<div style="display:none;" id="edit_data" page-name="{pigcms{$now_page.page_name}" page-id="{pigcms{$now_page.page_id}" page-desc="{pigcms{$now_page.page_desc}" bgcolor="{pigcms{$now_page.bgcolor}"></div>
	<div style="display:none;" id="edit_custom" custom-field='{pigcms{:json_encode($now_page_custom)}'></div>
</if>
<link rel="stylesheet" href="{pigcms{$static_path}diypage/css/customField.css"/>
<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
	var group_alias_name = "{pigcms{$config.group_alias_name}";
	var shop_alias_name = "{pigcms{$config.shop_alias_name}";
	var staticpath = "{pigcms{$static_path}diypage/";
	var imageList = "{pigcms{:U('Diypage/imageList',array('store_id'=>$now_store['store_id']))}";
	var uploadJson = "{pigcms{:U('Diypage/upload')}";
	var store_id = "{pigcms{$now_store.store_id}";
	var store_name = "{pigcms{$now_store.name}";
	var store_logo = "{pigcms{$now_store.pic.0.url}";
	var add_url = "{pigcms{:U('page_add',array('store_id'=>$now_store['store_id']))}";
	var edit_url = "{pigcms{:U('page_add',array('store_id'=>$now_store['store_id']))}";
	var mycard_url = "{pigcms{$config.site_url}/wap.php?c=My_card&a=merchant_card&mer_id={pigcms{$now_store.mer_id}";
	var wap_home_url = "{pigcms{$config.site_url}/wap.php?c=Mall&a=store&store_id={pigcms{$now_store.store_id}";
	var coupon_url = "{pigcms{$config.site_url}/wap.php?c=My_card&a=merchant_coupon&mer_id={pigcms{$now_store.mer_id}";
	var wap_product_list_url = "{pigcms{$config.site_url}/wap.php?c=Mall&a=store&store_id={pigcms{$now_store.store_id}&show_own=1";
	var merchant_store_url = "{pigcms{$config.site_url}/wap.php?c=My_card&a=merchant_store&mer_id={pigcms{$now_store.mer_id}&show_own=1";
	var merchant_shop_list_url = "{pigcms{$config.site_url}/wap.php?c=My_card&a=merchant_shop_list&mer_id={pigcms{$now_store.mer_id}&show_own=1";
	var is_show_activity = <if condition="$config['wxapp_url']">1<else/>0</if>;
	var diyVideo = "{pigcms{:U('Article/diyVideo')}";
	var diyTool = "{pigcms{:U('Article/diytool')}";

</script>
<script type="text/javascript" src="{pigcms{$static_path}diypage/js/customField.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/laytpl.js"></script>
<script id="pageTitleTpl" type="text/html">
	<div>
		<form class="form-horizontal">
			<div class="control-group">
				<label class="control-label"><em class="required">*</em>页面名称：</label>	
				<div class="controls"><input class="input-xxlarge" type="text" name="title" value="{{ d.page_name }}"/></div>
			</div>
			<div class="control-group">
				<label class="control-label">页面描述：</label>
				<div class="controls"><input class="input-xxlarge" type="text" name="description" value="{{ d.page_desc }}" placeholder="用户通过微信分享给朋友时，会自动显示页面描述"/></div>
			</div>
			<div class="control-group">
				<label class="control-label">背景颜色：</label>
				<div class="controls">
					<input type="color" value="{{ d.bgcolor }}" name="color"/> 
					<button class="btn js-reset-bg" type="button">重置</button>
					<p class="help-desc">背景颜色只在手机端显示。</p>
				</div>
			</div>
		</form>
	</div>
</script>
<include file="Public:footer"/>