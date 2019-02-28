<include file="Public:header"/>
<script type="text/javascript" src="//apps.bdimg.com/libs/layer/2.1/layer.js"></script>
<div class="mainbox">
	<div id="nav" class="mainnav_title">
		<ul>
			<a href="{pigcms{:U('Portal/yellow')}">黄页列表</a>|
			<a href="{pigcms{:U('Portal/yellow_add')}" class="on">添加黄页</a>
		</ul>
	</div>
	<div class="container">
		<input type="hidden" id="id" value="{pigcms{$Yellow_detail.id}">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">公司名称</th>
				<td><input type="text" class="input fl" id="title" size="50" validate="maxlength:100,required:true" value="{pigcms{$Yellow_detail.title}" /></td>
			</tr>
			<tr>
				<th width="80">联系电话</th>
				<td><input type="text" class="input fl" id="tel" size="50" validate="required:true" value="{pigcms{$Yellow_detail.tel}" /></td>
			</tr>
			<tr>
				<th width="80">电子邮箱</th>
				<td><input type="text" class="input fl" id="email" size="50" validate="maxlength:50,required:true" value="{pigcms{$Yellow_detail.email}"/></td>
			</tr>
			<tr>
				<th width="80">联系地址</th>
				<td><input type="text" class="input fl" id="address" size="50" validate="maxlength:100,required:true" value="{pigcms{$Yellow_detail.address}"/></td>
			</tr>
			<tr>
				<th width="80">行业分类</th>
				<td>
					<select id="parent_cate" class="address-province dropdown--small" autocomplete="off" onchange="get_child_cates(this)" pid="{pigcms{$Yellow_detail.pid}">
						<option value="">请选择分类</option>
					</select>

					<select id="child_cate" class="address-province dropdown--small" autocomplete="off" cid="{pigcms{$Yellow_detail.cid}">
						<option value="">请选择分类</option>
					</select>
				</td>
			</tr>
			<tr>
				<th width="80">所在地区</th>
				<td>
					<select id="address-province" class="address-province dropdown--small" name="province" autocomplete="off" province="{pigcms{$Yellow_detail.province}">
						<volist name="province_list" id="vo">
							<option value="{pigcms{$vo.area_id}">{pigcms{$vo.area_name}</option>
						</volist>
					</select>
					<select id="address-city" class="address-city dropdown--small" name="city" autocomplete="off" city="{pigcms{$Yellow_detail.city}">
						<volist name="city_list" id="vo">
							<option value="{pigcms{$vo.area_id}">{pigcms{$vo.area_name}</option>
						</volist>
					</select>
					<select id="address-area" class="address-district dropdown--small" name="area" autocomplete="off" area={pigcms{$Yellow_detail.area}>
						<volist name="area_list" id="vo">
							<option value="{pigcms{$vo.area_id}">{pigcms{$vo.area_name}</option>
						</volist>
					</select>
				</td>
			</tr>
			<tr>
				<th width="80">位置</th>
				<td>
					<div><span><a href="javascript:;" onclick="get_map_lnglat('{pigcms{$Yellow_detail.lng}','{pigcms{$Yellow_detail.lat}')" id="lbs_baidu">在地图上标注位置<if condition="$Yellow_detail.lng neq ''"><span style="color:green;">【定位成功】</span></if></a></span></div>
					<input type="hidden" id="lng" value="{pigcms{$Yellow_detail.lng}" />
					<input type="hidden" id="lat" value="{pigcms{$Yellow_detail.lat}" />
					<div id="around-map"></div>
				</td>
			</tr>
			<tr>
				<th width="80">公司Logo</th>
				<td>
					<form target="frame_img_logo" enctype="multipart/form-data" action="{pigcms{:U('Portal/uplad_img',array('flag'=>1))}" method="post" style="display: none;">
						<input type="file" name="file_img" onchange="upload_img_logo(this)">
					</form>
					<iframe name="frame_img_logo" id="frame_img_logo" style="display: none;"></iframe>
					<a class="thumbnail col-xs-4" href="javascript:;" onclick="img_logo_click(this)" >
						<if condition="$Yellow_detail.logo neq ''">
						<img id="img_logo" style="width:100px;" flag="1" src="{pigcms{$Yellow_detail.logo}" imgurl="{pigcms{$Yellow_detail.logo}"/>
						<else/>
						<img id="img_logo" style="width:100px;" flag="0" src="{pigcms{$static_public}images/photo/noneimg.jpg" imgurl="{pigcms{$static_path}images/photo/noneimg.jpg"/>
						</if>
					</a>
				</td>
			</tr>
			<tr>
				<th width="80">二维码</th>
				<td>
					<form target="frame_img" enctype="multipart/form-data" action="{pigcms{:U('Portal/uplad_img')}" method="post" style="display: none;">
						<input type="file" name="file_img" onchange="upload_img(this)">
					</form>
					<iframe name="frame_img" id="frame_img" style="display: none;"></iframe>
					<a class="thumbnail col-xs-4" href="javascript:;" onclick="img_click(this)" >
						<if condition="$Yellow_detail.qrcode neq ''">
						<img id="img" style="width:100px;" flag="1" src="{pigcms{$Yellow_detail.qrcode}" imgurl="{pigcms{$Yellow_detail.qrcode}"/>
						<else/>
						<img id="img" style="width:100px;" flag="0" src="{pigcms{$static_public}images/photo/noneimg.jpg" imgurl="{pigcms{$static_path}images/showqrcode.jpg"/>
						</if>
					</a>
				</td>
			</tr>
			<tr>
				<th width="80">服务内容</th>
				<td>
					<textarea id="service" class="f-text address-detail" style="width: 482px;height: 80px;" placeholder="不超过200字">{pigcms{$Yellow_detail.service}</textarea>
				</td>
			</tr>
			<tr>
				<th width="80">审核</th>
				<td id="checking">
					<span class="cb-enable"><label class="<if condition="($Yellow_detail['status'] eq 0) OR ($Yellow_detail['status'] eq 1)">cb-enable selected<else/>cb-disable</if>"><span>通过</span><input type="radio" name="status" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="<if condition="$Yellow_detail['status'] eq 2">cb-enable selected<else/>cb-disable</if>"><span>拒绝</span><input type="radio" name="status" value="2" /></label></span>
				</td>
			</tr>
		</table>
		<button style="width:80px;height: 30px;background-color: #2a6496;border: solid 1px #2a6496;border-radius: 4px;color:#fff;" onclick="save()">提交</button>
	</div>
</div>

<include file="Public:footer"/>
<script type="text/javascript">

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
	data.status = $('#checking input[type="radio"]:checked').val();


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
	if(data.address_province == '' || data.address_city == '' || data.address_area == ''){
		layer.alert('请选择所在区域');
		return;
	}
	if(data.lng == '' || data.lat == ''){
		layer.alert('请先标注地理位置');
		return;
	}

	var logo_flag = $('#img_logo').attr('flag');
	if(logo_flag != 0){
		data.logo = $('#img_logo').attr('src');
	}else{
		layer.alert('请上传公司Logo');
		return false;
	}

	var qrcode_flag = $('#img').attr('flag');

	if(qrcode_flag != 0){
		data.qrcode = $('#img').attr('src');
	}else{
		layer.alert('请上传公司二维码');
		return false;
	}

	
	if(data.service == ''){
		layer.alert('请填写服务内容');
		return;
	}

	$.post("{pigcms{:U('Portal/save_apply')}",data,function(response){
		if(response.code > 0){
			layer.alert(response.msg);
		}else{
			layer.msg(response.msg);
			setTimeout(function(){window.location.href="{pigcms{:U('Portal/yellow')}"},1000);
		}
	},'json');

}
</script>

<script type="text/javascript">

	get_cates();
	// 获取分类
	function get_cates(){

		$.get("{pigcms{:U('Portal/ajax_get_yellow_categroy_list')}",{'rand':Math.random()},function(response){
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
		$.get("{pigcms{:U('Portal/ajax_get_yellow_categroy_list')}",{'pid':pid,'rand':Math.random},function(response){
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

	// 省市区选择
	$(function(){
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
			$.post("{pigcms{:U('Portal/select_area')}",{pid:id},function(result){
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
			$.post("{pigcms{:U('Portal/select_area')}",{pid:id},function(result){
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
	function get_map_lnglat(lng,lat){
		index_layer = layer.open({
			type: 2,
			title: '标注位置',
			shadeClose: true,
			shade: 0.8,
			offset:'100px',
			area: ['680px', '600px'],
			content: "{pigcms{:U('Portal/yellow_baidu_map')}&lng="+lng+"&lat="+lat
		});
	}

	// 设置经纬度（由地图子页面调用）
	function setlnglat(lng,lat){
		$('#lng').val(lng);
		$('#lat').val(lat);
		layer.close(index_layer);
		$('#lbs_baidu').html('在地图上标注位置 <span style="color:green;">【定位成功】</span>');
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

</script>