<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Shop/index')}">{pigcms{$config.shop_alias_name}管理</a>
			</li>
			<li class="active">店铺列表</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="50">编号</th>
									<th width="50">排序</th>
									<th width="100">店铺名称</th>
									<th width="150">店铺电话</th>
									<th class="button-column" width="140">查看二维码</th>
									<th class="button-column" width="140">完善店铺信息</th>
									<th class="button-column" width="140">订单查看</th>
									<th class="button-column" width="140">商品管理</th>
									<th class="button-column" width="140">店铺优惠</th>
									<php>if(empty($merchant_session['store_id'])) {</php>
										<th class="button-column" width="140">克隆商品</th>
										<th class="button-column" width="140">同步商品到{pigcms{$config['meal_alias_name']}店铺</th>
									<php>}</php>
									<th class="button-column" width="140">开通商城</th>
                                    <php>if ($config['meituan_sign_key']) {</php>
										<th class="button-column" width="140">美团外卖</th>
                                    <php>}</php>
                                    <php>if ($config['eleme_app_key']) {</php>
										<th class="button-column" width="140">饿了么</th>
                                    <php>}</php>
                                    <php>if ($config['zbw_sAppKey']) {</php>
                                        <th class="button-column" width="140">智百威</th>
                                    <php>}</php>
								</tr>
							</thead>
							<tbody>
								<php>if ($store_list) {</php>
									<volist name="store_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td><div class="tagDiv">{pigcms{$vo.store_id}</div></td>
											<td><div class="tagDiv">{pigcms{$vo.sort}</div></td>
											<td><div class="shopNameDiv">{pigcms{$vo.name}</div></td>
											<td>{pigcms{$vo.phone}</td>
											
											<php>if(empty($vo['sid'])){</php>
												<td></td>
												<td class="button-column">
													<a style="width:80px;" class="label label-sm label-pink" title="完善店铺信息" href="{pigcms{:U('Shop/shop_edit',array('store_id'=>$vo['store_id']))}">完善信息</a>
												</td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<php>if($config['meituan_sign_key']){</php>
													<td></td>
												<php>}</php>
												<php>if ($config['eleme_app_key']) {</php>
													<td></td>
												<php>}</php>
                                                <php>if ($config['zbw_sAppKey']) {</php>
                                                    <td></td>
                                                <php>}</php>
											<php>}else{</php>
												<td class="button-column">
													<a href="{pigcms{$config.site_url}/index.php?g=Index&c=Recognition&a=see_qrcode&type=shop&id={pigcms{$vo['store_id']}" class="see_qrcode">二维码</a>
												</td>
												<td class="button-column">
													<a style="width:80px;" class="label label-sm label-success" title="修改" href="{pigcms{:U('Shop/shop_edit',array('store_id'=>$vo['store_id']))}">修改信息</a>
												</td>
												<td class="button-column">
													<a style="width:80px;" class="label label-sm label-warning" title="查看店铺订单" href="{pigcms{:U('Shop/order',array('store_id'=>$vo['store_id']))}">查看订单</a>
												</td>
												<td class="button-column">
													<a style="width: 60px;" class="label label-sm label-purple" title="商品分类" href="{pigcms{:U('Shop/goods_sort',array('store_id'=>$vo['store_id']))}">商品分类</a>
												</td>
												<td class="button-column">
													<a style="width: 60px;" class="label label-sm label-info" title="商品分类" href="{pigcms{:U('Shop/discount',array('store_id'=>$vo['store_id']))}">店铺优惠</a>
												</td>
												<php>if(empty($merchant_session['store_id'])){</php>
													<td class="button-column">
														<a style="width: 60px;" class="label label-sm label-info handle_btn" title="克隆商品至其他店铺" href="{pigcms{:U('Shop/store',array('store_id'=>$vo['store_id']))}">克隆商品</a>
													</td>
                                                    <td class="button-column">
                                                        <a style="width: 60px;" class="label label-sm label-info handle_btn" title="同步商品" href="{pigcms{:U('Shop/foodshop',array('store_id'=>$vo['store_id']))}">同步商品</a>
                                                    </td>
												<php>}</php>
												<td class="button-column">
													<label class="statusSwitch" style="display:inline-block;">
														<input name="switch-field-1" class="ace ace-switch ace-switch-6" type="checkbox" data-id="{pigcms{$vo['store_id']}" <php>if ($vo['store_theme'] == 1) {</php>checked="checked" data-status="OPEN"<php>}else{</php>data-status="CLOSED"<php>}</php>/>
														<span class="lbl"></span>
													</label>
												</td>
												<php>if($config['meituan_sign_key']){</php>
													<td class="button-column">
														<php>if ($vo['meituan_token']) {</php>
															<a style="width: 60px;" class="label label-sm label-success js-wxauth-btn" target="_blank" data-url="{pigcms{$vo['meituan_cancel_url']}" href="{pigcms{$vo['meituan_cancel_url']}">关闭</a>
														<php>}else{</php>
															<a style="width: 60px;" class="label label-sm label-success js-wxauth-btn" target="_blank" data-url="{pigcms{$vo['meituan_url']}" href="{pigcms{$vo['meituan_url']}">开通</a>
														<php>}</php>
													</td>
												<php>}</php>
												<php>if ($config['eleme_app_key']) {</php>
													<td class="button-column">
														<php>if ($vo['eleme_shopId']) {</php>
															<b style="color: green">已开通</b>
														<php>}else{</php>
															<a style="width: 60px;" class="label label-sm label-success eleme">开通</a>
														<php>}</php>
													</td>
												<php>}</php>
                                                <php>if ($config['zbw_sAppKey']) {</php>
                                                <td class="button-column">
                                                    <php>if (empty($vo['zbw_sBranchNo'])) { </php>
                                                    <a style="width: 60px;" class="label label-sm label-success zbw_btn" href="{pigcms{:U('Shop/zbw',array('store_id'=>$vo['store_id']))}">开通</a>
                                                    <php> } else {</php>
                                                    <a style="width: 60px;" class="label label-sm label-success synczbw" data-store_id="{pigcms{$vo['store_id']}">同步</a>
                                                    <php>}</php>
                                                </td>
                                                <php>}</php>
											<php>}</php>
										</tr>
									</volist>
								<php>}else{</php>
									<tr class="odd"><td class="button-column" colspan="15" >您没有添加店铺，或店铺没开启{pigcms{$config.shop_alias_name}功能，或店铺正在审核中。</td></tr>
								<php>}</php>
							</tbody>
						</table>
						{pigcms{$pagebar}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
	$(function(){
		$('.see_qrcode').click(function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'查看渠道二维码',
				padding: 0,
				width: 430,
				height: 433,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: null,
				left: '50%',
				top: '38.2%',
				opacity:'0.4'
			});
			return false;
		});
		$('.handle_btn').live('click',function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'克隆店铺商品至其他店铺',
				padding: 0,
				width: 720,
				height: 520,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: null,
				left: '50%',
				top: '38.2%',
				opacity:'0.4'
			});
			return false;
		});
        $('.zbw_btn').live('click',function(){
            art.dialog.open($(this).attr('href'),{
                init: function(){
                    var iframe = this.iframe.contentWindow;
                    window.top.art.dialog.data('iframe_handle',iframe);
                },
                id: 'handle',
                title:'完善智百威ERP对接信息',
                padding: 0,
                width: 320,
                height: 200,
                lock: true,
                resize: false,
                background:'black',
                button: null,
                fixed: false,
                close: null,
                left: '50%',
                top: '38.2%',
                opacity:'0.4'
            });
            return false;
        });
		$('.eleme').click(function(){
		    art.dialog({
		        title: "提示",
		        content: "<b>目前饿了么外卖平台仅支持已有门店的绑定开通</b> <br/> 如您在饿了么上已建有门店，请联系客服人员提交绑定申请",
		        lock: true,
		        fixed: true
		    });
		});

        $('.synczbw').click(function(){
            var index = layer.load(1, {shade: [0.7,'#000']}), store_id = $(this).data('store_id');
            $.post("{pigcms{:U('Shop/syncZbw')}", {'store_id':$(this).data('store_id')}, function(res){
                if (res.sCode == 0) {
                    syncGoods(store_id, 1, index);
                } else {
                    layer.msg(res.sError);
                    layer.close(index)
                }
            },'json');
        });
	});

    function syncGoods(store_id, page, index)
    {
        $.post("{pigcms{:U('Shop/syncZbwGoods')}", {'store_id':store_id, 'page':page}, function(res){
            if (res.sCode == 0) {
                if (res.page == 0) {
                    layer.msg('同步完成');
                    layer.close(index);
                } else {
                    syncGoods(store_id, res.page, index);
                }
            } else {
                layer.msg(res.sError);
                layer.close(index);
            }
        },'json');
    }
    
	$(function(){
		/*店铺状态*/
		updateStatus(".statusSwitch .ace-switch", ".statusSwitch", "OPEN", "CLOSED", "store_theme");
	});
	function updateStatus(dom1, dom2, status1, status2, attribute){
		$(dom1).each(function(){
			if($(this).attr("data-status")==status1){
				$(this).attr("checked",true);
			}else{
				$(this).attr("checked",false);
			}
			$(dom2).show();
		}).click(function(){
			var _this = $(this), type = 'open', id = $(this).attr("data-id");
			_this.attr("disabled",true);
			if(_this.attr("checked")){	//开启
				type = 'open';
			}else{		//关闭
				type = 'close';
			}
			$.ajax({
				url:"{pigcms{:U('Shop/change_mall')}",
				type:"post",
				data:{"type":type,"id":id,"status1":status1,"status2":status2,"attribute":attribute},
				dataType:"text",
				success:function(d){
					if(d != '1'){		//失败
						if(type=='open'){
							_this.attr("checked",false);
						}else{
							_this.attr("checked",true);
						}
						bootbox.alert("操作失败");
					}
					_this.attr("disabled",false);
				}
			});
		});
	}
</script>
<include file="Public:footer"/>