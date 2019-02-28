<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>黄页申请 | {pigcms{$config.site_name}</title>
<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
<meta name="description" content="{pigcms{$config.seo_description}" />
<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
<link href="{pigcms{$static_path}css/meal_order_list.css"  rel="stylesheet"  type="text/css" />
<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
<script src="{pigcms{$static_public}layer/layer.js"></script>
	<script type="text/javascript">
	   var  meal_alias_name = "{pigcms{$config.meal_alias_name}";
	</script>
<script src="{pigcms{$static_path}js/common.js"></script>
<!--script src="{pigcms{$static_path}js/category.js"></script-->
<!--[if IE 6]>
<script  src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js" mce_src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js"></script>
<script type="text/javascript">
   DD_belatedPNG.fix('.enter,.enter a,.enter a:hover');
</script>
<script type="text/javascript">DD_belatedPNG.fix('*');</script>
<style type="text/css"> 
body{behavior:url("{pigcms{$static_path}css/csshover.htc");}
.category_list li:hover .bmbox {filter:alpha(opacity=50);}
.gd_box{display: none;}
</style>
<![endif]-->
<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
    <style type="text/css">
		.input-red{
			border:1px solid red!important;
		}
		.address-suggestlist {
		    background: #fff none repeat scroll 0 0;
		    border: 1px solid #ddd;
		    height: 150px;
		    left: 85px;
		    overflow: auto;
		    width: 420px;
			margin-left: 0px;
		    z-index: 100;
			top: 228px;
		}
	    .address-suggestlist li {
		    cursor: pointer;
		    height: 30px;
		   margin-top: 3px;
		    list-style: outside none none;
	   }
	   .address-suggestlist ul {
		    padding: 0;
		    margin: 0;
		    overflow-x: hidden;
		    text-align: left;
		}
	</style>
</head>
<body id="settings" class="has-order-nav" style="position:static;">
<include file="Public:header_top"/>
 <div class="body pg-buy-process"> 
	<div id="doc" class="bg-for-new-index">
		<article>
			<div class="menu cf">
				<div class="menu_left hide">
					<div class="menu_left_top">全部分类</div>
					<div class="list">
						<ul>
							<volist name="all_category_list" id="vo" key="k">
								<li>
									<div class="li_top cf">
										<if condition="$vo['cat_pic']"><div class="icon"><img src="{pigcms{$vo.cat_pic}" /></div></if>
										<div class="li_txt"><a href="{pigcms{$vo.url}">{pigcms{$vo.cat_name}</a></div>
									</div>
									<if condition="$vo['cat_count'] gt 1">
										<div class="li_bottom">
											<volist name="vo['category_list']" id="voo" offset="0" length="3" key="j">
												<span><a href="{pigcms{$voo.url}">{pigcms{$voo.cat_name}</a></span>
											</volist>
										</div>
									</if>
								</li>
							</volist>
						</ul>
					</div>
				</div>
				<div class="menu_right cf">
					<div class="menu_right_top">
						<ul>
							<pigcms:slider cat_key="web_slider" limit="10" var_name="web_index_slider">
								<li class="ctur">
									<a href="{pigcms{$vo.url}">{pigcms{$vo.name}</a>
								</li>
							</pigcms:slider>
						</ul>
					</div>
				</div>
			</div>
		</article>
		<div id="bdw" class="bdw">
			<div id="bd" class="cf">
				<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/order-nav.v0efd44e8.css" />
				<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/account.v1a41925d.css" />
				<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/table-section.v538886b7.css" />
				<include file="Public:sidebar"/>
				<div id="content" class="coupons-box">
					<div class="mainbox mine">
						<ul class="filter cf">
							<li class="current"><a href="{pigcms{:U('Yellow/index')}">黄页申请</a></li>
						</ul>
						<div class="address-div">
							<div class="prompt table-section">
								<table cellspacing="0" cellpadding="0" border="0">
									<caption class="">
										<span>申请加入黄页</span>
									</caption>
									<tbody>
										<tr class="edit-form">
											<input type="hidden" id="id" value="{pigcms{$apply_info.id}" />
											<td>
												<div class="address-field-list">
													<div class="form-field">
														<label for="address-detail"><em>*</em> 公司名称：</label>
														<input type="text" maxlength="60" size="60" id="title" class="f-text address-detail" value="{pigcms{$apply_info.title}">
													</div>

													<div class="form-field">
														<label for="address-detail"><em>*</em> 联系电话：</label>
														<input type="text" maxlength="60" size="60" id="tel" class="f-text address-detail" value="{pigcms{$apply_info.tel}">
													</div>

													<div class="form-field">
														<label for="address-detail"> 电子邮箱：</label>
														<input type="text" maxlength="60" size="60" id="email" class="f-text address-detail" value="{pigcms{$apply_info.email}">
													</div>

													<div class="form-field">
														<label for="address-detail"><em>*</em> 联系地址：</label>
														<input type="text" maxlength="60" size="60" id="address" class="f-text address-detail" value="{pigcms{$apply_info.address}">
													</div>

													<div class="form-field">
														<label for="address-province"><em>*</em> 行业分类：</label>
														<span id="area-container">
															<select id="parent_cate" class="address-province dropdown--small" autocomplete="off" onchange="get_child_cates(this)" pid="{pigcms{$apply_info.pid}">
																<option value="">请选择分类</option>
															</select>

															<select id="child_cate" class="address-province dropdown--small" autocomplete="off" cid="{pigcms{$apply_info.cid}">
																<option value="">请选择分类</option>
															</select>

														</span>
													</div>

													<div class="form-field">
														<label for="address-province"><em>*</em> 所在地区：</label>
														<span id="area-container">
															<select id="address-province" class="address-province dropdown--small" name="province" autocomplete="off" province="{pigcms{$apply_info.province}">
																<volist name="province_list" id="vo">
																	<option value="{pigcms{$vo.area_id}">{pigcms{$vo.area_name}</option>
																</volist>
															</select>
															<select id="address-city" class="address-city dropdown--small" name="city" autocomplete="off" city="{pigcms{$apply_info.city}">
																<volist name="city_list" id="vo">
																	<option value="{pigcms{$vo.area_id}">{pigcms{$vo.area_name}</option>
																</volist>
															</select>
															<select id="address-area" class="address-district dropdown--small" name="area" autocomplete="off" area={pigcms{$apply_info.area}>
																<volist name="area_list" id="vo">
																	<option value="{pigcms{$vo.area_id}">{pigcms{$vo.area_name}</option>
																</volist>
															</select>
														</span>
													</div>

													<div class="form-field">
														<label for="address-detail"><em>*</em> 位置：</label>
														<div><span><a href="javascript:;" onclick="get_map_lnglat()"  id="lbs_baidu">在地图上标注位置<if condition="$apply_info.lng neq ''"><span style="color:green;">【定位成功】</span></if></a></span></div>
														<input type="hidden" id="lng" value="{pigcms{$apply_info.lng}" />
														<input type="hidden" id="lat" value="{pigcms{$apply_info.lat}" />
														<div id="around-map"></div>
													</div>

													<div class="form-field">
														<label for="address-phone"> 公司Logo：</label>
														<form target="frame_img_logo" enctype="multipart/form-data" action="{pigcms{:U('Yellow/uplad_img',array('flag'=>1))}" method="post" style="display: none;">
															<input type="file" name="file_img" onchange="upload_img_logo(this)">
														</form>
														<iframe name="frame_img_logo" id="frame_img_logo" style="display: none;"></iframe>
														<a class="thumbnail col-xs-4" href="javascript:;" onclick="img_logo_click(this)" >
															<if condition="$apply_info.logo neq ''">
															<img id="img_logo" style="width:100px;" flag="1" src="{pigcms{$apply_info.logo}" imgurl="{pigcms{$apply_info.logo}"/>
															<span style=" padding: 5px; border: 1px solid #eee;  position: relative; top: -40px;">上传图片</span>
															<else/>
															<img id="img_logo" style="width:100px;" flag="0" src="{pigcms{$static_path}images/showqrcode.jpg" imgurl="{pigcms{$static_path}images/showqrcode.jpg"/>
															<span style="padding: 5px; border: 1px solid #eee; position: relative; top: -40px;">上传图片</span>
															</if>
														</a>
													</div>

													<div class="form-field">
														<label for="address-phone"> 微信二维码：</label>
														<form target="frame_img" enctype="multipart/form-data" action="{pigcms{:U('Yellow/uplad_img')}" method="post" style="display: none;">
															<input type="file" name="file_img" onchange="upload_img(this)">
														</form>
														<iframe name="frame_img" id="frame_img" style="display: none;"></iframe>
														<a class="thumbnail col-xs-4" href="javascript:;" onclick="img_click(this)" >
															<if condition="$apply_info.qrcode neq ''">
															<img id="img" style="width:100px;" flag="1" src="{pigcms{$apply_info.qrcode}" imgurl="{pigcms{$apply_info.qrcode}"/>
															<span style="padding: 5px; border: 1px solid #eee; position: relative; top: -40px;">上传图片</span>
															<else/>
															<img id="img" style="width:100px;" flag="0" src="{pigcms{$static_path}images/showqrcode.jpg" imgurl="{pigcms{$static_path}images/showqrcode.jpg"/>
															<span style="padding: 5px; border: 1px solid #eee; position: relative; top: -40px;">上传图片</span>
															</if>
														</a>
													</div>

													<if condition="$apply_info neq ''">
													<div class="form-field">
														<input type="hidden" id="custom_info_id" value="{pigcms{$custom_info.id}">
														<label for="address-detail"> 自定义信息：</label>
														<input type="submit" class="btn" onclick="add_custom_info()" value="添加自定义信息">
													</div>
													</if>

													<div class="form-field">
														<label><em>*</em> 服务内容：</label>
														<textarea id="service" class="f-text address-detail" style="width: 482px;height: 80px;" placeholder="不超过200字">{pigcms{$apply_info.service}</textarea>
													</div>
													
													<div class="form-field comfirm">
														<input type="submit" class="btn" onclick="save()" value="保存">
														<a href="javascript:void(0)" class="address-cancel inline-link">取消</a>
													</div>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>       
						</div>
                    </div>
				</div>
			</div> <!-- bd end -->
		</div>
	</div>
	<include file="Public:footer"/>
</body>
</html>
<script type="text/javascript">

	$(function(){
		get_cates();
		var province = $('#address-province').attr('province');
		var city = $('#address-city').attr('city');
		var area = $('#address-area').attr('area');
		if(province != ''){
			$('#address-province').val(province);
			show_city(province,false);
		}

		// 省市区选择
		$("#address-province").live('change',function(){
			show_city($(this).find('option:selected').attr('value'),false);
		});
		$("#address-city").live('change',function(){
			show_area($(this).find('option:selected').attr('value'),false);
		});

		function show_city(id,has_select){
			$.post("{pigcms{:U('Adress/select_area')}",{pid:id},function(result){
				result = $.parseJSON(result);
				if(result.error == 0){
					var area_dom = '';
					$.each(result.list,function(i,item){
						var ischk = '';
						if(city == item.area_id){
							ischk = 'selected';
						}
						area_dom+= '<option value="'+item.area_id+'" '+ischk+'>'+item.area_name+'</option>'; 
					});
					$("select[name='city']").html(area_dom);
					if(city){
						show_area(city,has_select);
					}else{
						show_area(result.list[0].area_id,has_select);
					}
				}
			});
		}
		function show_area(id,has_select){
			$.post("{pigcms{:U('Adress/select_area')}",{pid:id},function(result){
				result = $.parseJSON(result);
				if(result.error == 0){
					var area_dom = '';
					$.each(result.list,function(i,item){
						var ischk = '';
						if(area == item.area_id){
							ischk = 'selected';
						}
						area_dom+= '<option value="'+item.area_id+'" '+ischk+'>'+item.area_name+'</option>'; 
					});
					$("select[name='area']").html(area_dom);
				}else{
					$("select[name='area']").html('<option value="0">请手动填写区域</option>');
				}
				
				if(has_select){
					$.each($('#address-city option'),function(i,item){
						if($(item).attr('value') == now_city){
							$(item).prop('selected',true);
							has_province = true;
							return false;
						}
					});
					$.each($('#address-area option'),function(i,item){
						if($(item).attr('value') == now_area){
							$(item).prop('selected',true);
							has_province = true;
							return false;
						}
					});
				}
			});
		}
	});


	// 地图上标注位置
	function get_map_lnglat(){
		var lng = $('#lng').val();
		var lat = $('#lat').val();
		index_layer = layer.open({
			type: 2,
			title: '标注位置',
			shadeClose: true,
			shade: 0.8,
			offset:'100px',
			area: ['680px', '600px'],
			content: "{pigcms{:U('Yellow/baidu_map')}&lng="+lng+"&lat="+lat
		});
	}


	// 设置经纬度（由地图子页面调用）
	function setlnglat(lng,lat){
		$('#lng').val(lng);
		$('#lat').val(lat);
		layer.close(index_layer);
		$('#lbs_baidu').html('在地图上标注位置<span style="color:green;">【定位成功】</span>');
	}

	// 获取分类
	function get_cates(){

		$.get("{pigcms{:U('Yellow/ajax_get_categroy_list')}",{'rand':Math.random()},function(response){
			if(response.code > 0){
				return;
			}
			var pid = $('#parent_cate').attr('pid');
			var html = '<option value="">请选择分类</option>';
			$.each(response.data,function(i,v){
				var ischk = '';
				if(pid == v.cat_id){
					ischk = 'selected';
				}
				html += '<option value="'+v.cat_id+'" '+ischk+'>'+v.cat_name+'</option>';
			});
			$('#parent_cate').html(html);
			get_child_cates($('#parent_cate'));
		},'json');
	}

	// 获取子分类
	function get_child_cates(obj){
		var pid = $(obj).val();
		if(pid == '' || pid == 0){
			$('#child_cate').html('<option value="">请选择分类</option>');
			return;
		}
		$.get("{pigcms{:U('Yellow/ajax_get_categroy_list')}",{'pid':pid,'rand':Math.random},function(response){
			if(response.code > 0){
				return;
			}
			var cid = $('#child_cate').attr('cid');
			var html = '<option value="">请选择分类</option>';
			$.each(response.data,function(i,v){
				var ischk = '';
				if(cid == v.cat_id){
					ischk = 'selected';
				}
				html += '<option value="'+v.cat_id+'" '+ischk+'>'+v.cat_name+'</option>';
			});
			$('#child_cate').html(html);
		},'json');
	}

	// 上传图片
	function img_click(obj){
		$(obj).siblings('form').children('input').click();
	}

	function upload_img(obj){
		var index = layer.load(1, {
		  shade: [0.6,'#000'], //0.1透明度的白色背景
		  offset: '50px'
		});
		$(obj).parent('form').submit();
	}

	function upload_success(msg){
		$('#img').attr('src',msg).attr('imgurl',msg).attr('flag','1');
		layer.closeAll();
	}

	// 上传Logo图片
	function img_logo_click(obj){
		$(obj).siblings('form').children('input').click();
	}

	function upload_img_logo(obj){
		var index = layer.load(1, {
		  shade: [0.6,'#000'], //0.1透明度的白色背景
		  offset: '50px'
		});
		$(obj).parent('form').submit();
	}

	function upload_logo_success(msg){
		$('#img_logo').attr('src',msg).attr('imgurl',msg).attr('flag','1');
		layer.closeAll();
	}

	function upload_error(msg){
		layer.closeAll();
		layer.alert(msg);
	}

	// 添加自定义信息
	function add_custom_info(){
		var yellow_id = $('#id').val();
		var custom_info_id = $('#custom_info_id').val();
		layer_iframe = layer.open({
			type: 2,
			title: '自定义信息',
			shadeClose: true,
			shade: 0.8,
			area: ['680px', '90%'],
			content: "{pigcms{:U('Yellow/add_custom_info')}&info_id="+custom_info_id+'&yellow_id=' + yellow_id
		});
	}

	function close_iframe(){
		layer.close(layer_iframe);
	}

	function save(){
		var data = new Object();
		data.id = $('#id').val();
		data.title = $.trim($('#title').val());
		data.tel = $.trim($('#tel').val());
		data.email = $.trim($('#email').val());
		data.address = $.trim($('#address').val());
		data.parent_cate = $('#parent_cate').val();
		data.child_cate = $('#child_cate').val();
		data.address_province = $('#address-province').val();
		data.address_city = $('#address-city').val();
		data.address_area = $('#address-area').val();
		data.lng = $('#lng').val();
		data.lat = $('#lat').val();
		data.service = $.trim($('#service').val());

		var logo_flag = $('#img_logo').attr('flag');
		if(logo_flag != 0){
			data.logo = $('#img_logo').attr('src');
		}

		var qrcode_flag = $('#img').attr('flag');
		if(qrcode_flag != 0){
			data.qrcode = $('#img').attr('src');
		}

		if(data.title == ''){
			layer.alert('请填写公司名称');
			return;
		}
		if(data.tel == ''){
			layer.alert('请填写联系电话');
			return;
		}
		if(data.address == ''){
			layer.alert('请填写公司地址');
			return;
		}
		if(data.parent_cate == '' || data.parent_cate == '0' || data.child_cate == '' || data.child_cate == '0'){
			layer.alert('请选择业务类型');
			return;
		}
		if(data.logo == ''){
			layer.alert('请上传公司Logo');
		}

		if(data.address_province == '' || data.address_city == '' || data.address_area == ''){
			layer.alert('请选择所在区域');
			return;
		}
		if(data.lng == '' || data.lat == ''){
			layer.alert('请先标注地理位置');
			return;
		}
		if(data.service == ''){
			layer.alert('请填写服务内容');
			return;
		}

		$.post("{pigcms{:U('Yellow/save_apply')}",data,function(response){
			if(response.code > 0){
				layer.alert(response.msg);
			}else{
				layer.msg(response.msg);
			}
		},'json');

	}
</script>
