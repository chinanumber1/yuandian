<include file="Public:header"/>
<style>
	.station{width: 150px; height: 40px; float: left;}
	#village_list a,#schools a,#bus_line_list a,#bus_station_list a,#metro_line_list a,#metro_station_list a{margin-right: 5px;}
	.active {color:red;}
</style>
<div class="mainbox">
	<input type="hidden" id="label_id" value="{pigcms{$label.id}"/>
	<div id="nav" class="mainnav_title">
		<ul>
			<a href="{pigcms{:U('Label/index')}">栏目列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Label/column_add')}','添加栏目',600,300,true,false,false,addbtn,'add',true);">添加栏目</a>|
					<a href="{pigcms{:U('Label/label_list')}">标签列表</a>
					<a href="{pigcms{:U('Label/label_add')}" class="on">添加标签</a>
		</ul>
	</div>
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		
		<tr>
			<td width="80">标签名称</td>
			<td>
				<input type="text" class="input fl" id="label_name" value="{pigcms{$label.title}" placeholder="请填写一个标签名称" validate="required:true"></td>
		</tr>
		<tr>
			<td width="80">所属栏目</td>
			<td>
				<select id="pid">
					<option value="0">请选择栏目</option>
					<if condition="$label_column_list">
					<volist name="label_column_list" id="vo">
						<option value="{pigcms{$vo.id}" <?php echo $vo['id']==$label['pid']?'selected':'';?>>{pigcms{$vo.title}</option>
					</volist>
					</if>
				</select>
			</td>
		</tr>
		<tr>
			<td width="80">标签URL</td>
			<td>
				<input type="text" class="input fl" id="label_url" value="{pigcms{$label.url}" placeholder="请填写标签URL" validate="required:true">（填写该项时，该标签将链接到对应的URL，不受标签属性的影响）</td>
		</tr>

		<tr>
			<td width="80">城市</td>
			<td class="label_property" id="choose_city" province_id="{pigcms{$label.binds.province_id}" city_id="{pigcms{$label.binds.city_id}" area_id="{pigcms{$label.binds.area_id}"></td>
		</tr>

		<tr>
			<td width="80">小区</td>
			<td id="village_list" village_ids="<?php echo isset($label['binds']['villages'])?implode(',',$label['binds']['villages']):'';?>"></td>
		</tr>
		
		<tr>
			<td width="80">学校</td>
			<td id="school_list" school_ids="<?php echo isset($label['binds']['schools'])?implode(',',$label['binds']['schools']):'';?>"></td>
		</tr>

		<tr>
			<td width="80">公交线</td>
			<td id="bus_line_list" bus_line_ids="<?php echo isset($label['binds']['bus_lines'])?implode(',',$label['binds']['bus_lines']):'';?>"></td>
		</tr>
		<tr>
			<td width="80">公交站</td>
			<td id="bus_station_list" bus_station_ids="<?php echo isset($label['binds']['bus_stations'])?implode(',',$label['binds']['bus_stations']):'';?>"></td>
		</tr>
		<tr>
			<td width="80">地铁线</td>
			<td id="metro_line_list" metro_line_ids="<?php echo isset($label['binds']['metro_lines'])?implode(',',$label['binds']['metro_lines']):'';?>"></td>
		</tr>
		<tr>
			<td width="80">地铁站</td>
			<td id="metro_station_list" metro_station_ids="<?php echo isset($label['binds']['metro_stations'])?implode(',',$label['binds']['metro_stations']):'';?>"></td>
		</tr>

		<tr>
			<td width="80">状态</td>
			<td class="radio_box" id="status">
				<span class="cb-enable">
					<label class="cb-enable <?php echo $label['status']==0?'selected':'';?>">
						<span>正常</span>
						<input type="radio" name="status" value="0"></label>
				</span>

				<span class="cb-disable">
					<label class="cb-disable <?php echo $label['status']==1?'selected':'';?>">
						<span>禁止</span>
						<input type="radio" name="status" value="1"></label>
				</span>
			</td>
		</tr>
	</table>
	<div>
		<button class="button" onclick="save()">保存</button>
	</div>
</div>

	<script type="text/javascript">
	show_province();
	// 加载省
	function show_province(){
		var province_url = '/admin.php?g=System&c=Area&a=ajax_province';
		var cur_province_id = $('#choose_city').attr('province_id');
		$.get(province_url,{},function(response){
			if(response.error>0){
				alert(response.info);
				return;
			}
			var html = '<select id="province" onchange="show_city($(this).val())"><option value="0">选择省</option>';
			$.each(response.list,function(i,v){
				var ischecked = v.id==cur_province_id?'selected':'';
				html += '<option value="'+v.id+'" '+ischecked+'>'+v.name+'</option>';
			});
			html += '</select>';
			$('#choose_city').append(html);
			show_city(cur_province_id);
		},'json');
	}

	// 加载城市
	function show_city(province_id){
		var city_url = '/admin.php?g=System&c=Area&a=ajax_city';
		var cur_city_id = $('#choose_city').attr('city_id');
		if(province_id==0){
			return;
		}
		$.post(city_url,{'id':province_id},function(response){
			if(response.error>0){
				alert(response.info);
				return;
			}
			var html = '<select id="city" onchange="show_area($(this).val())"><option value="0">选择城市</option>';
			$.each(response.list,function(i,v){
				var ischecked = v.id==cur_city_id?'selected':'';
				html += '<option value="'+v.id+'" '+ischecked+'>'+v.name+'</option>';
			});
			html += '</select>';
			if(document.getElementById('city')){
				$('#city').replaceWith(html);
			}else{
				$('#choose_city').append(html);
			}
			show_area(cur_city_id);
		},'json');
	}

	// 加载区域
	function show_area(city_id){
		var area_url = '/admin.php?g=System&c=Area&a=ajax_area';
		var cur_area_id = $('#choose_city').attr('area_id');
		if(city_id==0){
			return;
		}
		$.post(area_url,{'id':city_id},function(response){
			if(response.error>0){
				alert(response.info);
				return;
			}
			var html = '<select id="area" onchange="area_changed($(this).val())"><option value="0">选择区域</option>';
			$.each(response.list,function(i,v){
				var ischecked = v.id==cur_area_id?'selected':'';
				html += '<option value="'+v.id+'" '+ischecked+'>'+v.name+'</option>';
			});
			html += '</select>';
			if(document.getElementById('area')){
				$('#area').replaceWith(html);
			}else{
				$('#choose_city').append(html);
			}
			area_changed(cur_area_id);
		},'json');
	}

	function area_changed(area_id){
		// 加载小区
		show_village(area_id);
		// 加载学校
		show_school(area_id);
		// 加载公交线、公交站
		show_bus_line_stations(area_id);
		// 加载地铁线、地铁站
		show_metro_line_stations(area_id);
	}

	// 加载小区
	function show_village(area_id){
		var village_url = '/admin.php?g=System&c=Label&a=get_village';
		if(area_id==0){
			return;
		}
		$.get(village_url,{'area_id':area_id},function(response){
			if(response.error>0){
				alert(response.info);
				return;
			}
			var _village_ids = $('#village_list').attr('village_ids');
			_village_ids = _village_ids.split(',');
			var html = '<div id="villages">';
			$.each(response.list,function(i,v){
				var isactive = $.inArray(v.village_id,_village_ids)>-1?'class="active"':'';
				html += '<a href="javascript:;" onclick="choose_item(this)" village_id="'+v.village_id+'" '+isactive+'>'+v.name+'</a>';
			});
			html += '</div>';
			if(document.getElementById('villages')){
				$('#villages').replaceWith(html);
			}else{
				$('#village_list').append(html);
			}
		},'json');

	}

	// 加载学校
	function show_school(area_id){
		var school_url = '/admin.php?g=System&c=Label&a=get_school';
		if(area_id==0){
			return;
		}
		$.get(school_url,{'area_id':area_id},function(response){
			if(response.error>0){
				alert(response.info);
				return;
			}
			var _school_ids = $('#school_list').attr('school_ids');
			_school_ids = _school_ids.split(',');
			var html = '<div id="schools">';
			$.each(response.list,function(i,v){
				var isactive = $.inArray(v.school_id,_school_ids)>-1?'class="active"':'';
				html += '<a href="javascript:;" onclick="choose_item(this)" school_id="'+v.school_id+'" '+isactive+'>'+v.school_name+'</a>';
			});
			html += '</div>';
			if(document.getElementById('schools')){
				$('#schools').replaceWith(html);
			}else{
				$('#school_list').append(html);
			}
		},'json');
	}

	// 加载公交线、公交站
	function show_bus_line_stations(area_id){
		var bus_lines_statioins_url = '/admin.php?g=System&c=Label&a=get_bus_lines_statioins';
		if(area_id==0){
			return;
		}
		$.get(bus_lines_statioins_url,{'area_id':area_id},function(response){
			if(response.error>0){
				alert(response.info);
				return;
			}
			
			var _bus_line_ids = $('#bus_line_list').attr('bus_line_ids');
			_bus_line_ids = _bus_line_ids.split(',');
			// 公交线
			var bus_line_html = '<div id="bus_lines">';
			$.each(response.list.bus_line_list,function(i,v){
				var isactive = $.inArray(v.line_id,_bus_line_ids)>-1?'class="active"':'';
				bus_line_html += '<a href="javascript:;" onclick="choose_item(this)" bus_line_id="'+v.line_id+'" '+isactive+'>'+v.line_name+'</a>';
			});
			bus_line_html += '</div>';
			if(document.getElementById('bus_lines')){
				$('#bus_lines').replaceWith(bus_line_html);
			}else{
				$('#bus_line_list').append(bus_line_html);
			}

			var _bus_station_ids = $('#bus_station_list').attr('bus_station_ids');
			_bus_station_ids = _bus_station_ids.split(',');
			// 公交站
			var bus_station_html = '<div id="bus_stations">';
			$.each(response.list.bus_station_list,function(ii,vv){
				var isactive = $.inArray(vv.station_id,_bus_station_ids)>-1?'class="active"':'';
				bus_station_html += '<a href="javascript:;" onclick="choose_item(this)" bus_station_id="'+vv.station_id+'" '+isactive+'>'+vv.station_name+'</a>';
			});
			bus_station_html += '</div>';
			if(document.getElementById('bus_stations')){
				$('#bus_stations').replaceWith(bus_station_html);
			}else{
				$('#bus_station_list').append(bus_station_html);
			}

		},'json');
	}

	// 加载地铁线、地铁站
	function show_metro_line_stations(area_id){
		var metro_lines_statioins_url = '/admin.php?g=System&c=Label&a=get_metro_lines_statioins';
		if(area_id==0){
			return;
		}
		$.get(metro_lines_statioins_url,{'area_id':area_id},function(response){
			if(response.error>0){
				alert(response.info);
				return;
			}
			var _metro_line_ids = $('#metro_line_list').attr('metro_line_ids');
			_metro_line_ids = _metro_line_ids.split(',');
			// 地铁线
			var metro_line_html = '<div id="metro_lines">';
			$.each(response.list.metro_line_list,function(i,v){
				var isactive = $.inArray(v.id,_metro_line_ids)>-1?'class="active"':'';
				metro_line_html += '<a href="javascript:;" onclick="choose_item(this)" metro_line_id="'+v.id+'" '+isactive+'>'+v.name+'</a>';
			});
			metro_line_html += '</div>';
			if(document.getElementById('metro_lines')){
				$('#metro_lines').replaceWith(metro_line_html);
			}else{
				$('#metro_line_list').append(metro_line_html);
			}

			var _metro_station_ids = $('#metro_station_list').attr('metro_station_ids');
			_metro_station_ids = _metro_station_ids.split(',');
			// 地铁站
			var metro_station_html = '<div id="metro_stations">';
			$.each(response.list.metro_station_list,function(ii,vv){
				var isactive = $.inArray(vv.id,_metro_station_ids)>-1?'class="active"':'';
				metro_station_html += '<a href="javascript:;" onclick="choose_item(this)" metro_station_id="'+vv.id+'" '+isactive+'>'+vv.name+'</a>';
			});
			metro_station_html += '</div>';
			if(document.getElementById('metro_stations')){
				$('#metro_stations').replaceWith(metro_station_html);
			}else{
				$('#metro_station_list').append(metro_station_html);
			}
		},'json');
	}


	// 选中
	function choose_item(obj){
		if($(obj).hasClass('active')){
			$(obj).removeClass('active');
		}else{
			$(obj).addClass('active');
		}
	}


	// 保存
	function save(){
		var label_name = $.trim($('#label_name').val());
		var pid = $('#pid').val();
		var label_url = $.trim($('#label_url').val());
		var province_id = $('#province').val();
		var city_id = $('#city').val();
		var area_id = $('#area').val();
		// 小区
		var villages = [];
		var village_items = $('#villages a[class="active"]');
		if(village_items!=undefined && village_items.length>0){
			$.each(village_items,function(i,v){
				villages.push($(v).attr('village_id'));
			});
		}
		// 学校
		var schools = [];
		var school_items = $('#schools a[class="active"]');
		if(school_items!=undefined && school_items.length>0){
			$.each(school_items,function(i,v){
				schools.push($(v).attr('school_id'));
			});
		}

		//公交线
		var bus_lines = [];
		var bus_line_items = $('#bus_lines a[class="active"]');
		if(bus_line_items!=undefined && bus_line_items.length>0){
			$.each(bus_line_items,function(i,v){
				bus_lines.push($(v).attr('bus_line_id'));
			});
		}
		// 公交站
		var bus_stations = [];
		var bus_station_items = $('#bus_stations a[class="active"]');
		if(bus_station_items!=undefined && bus_station_items.length>0){
			$.each(bus_station_items,function(i,v){
				bus_stations.push($(v).attr('bus_station_id'));
			});
		}
		// 地铁线
		var metro_lines = [];
		var metro_line_items = $('#metro_lines a[class="active"]');
		if(metro_line_items!=undefined && metro_line_items.length>0){
			$.each(metro_line_items,function(i,v){
				metro_lines.push($(v).attr('metro_line_id'));
			});
		}
		// 地铁站
		var metro_stations = [];
		var metro_station_items = $('#metro_stations a[class="active"]');
		if(metro_station_items!=undefined && metro_station_items.length>0){
			$.each(metro_station_items,function(i,v){
				metro_stations.push($(v).attr('metro_station_id'));
			});
		}
		
		if(label_name==''){
			alert('请填写一个标签名称');
			return;
		}
		if(pid<=0){
			alert('请选择栏目');
			return;
		}
		if(label_url=='' && villages.length <= 0 && schools.length <= 0 && bus_lines.length <= 0 && bus_stations.length <= 0 && metro_lines.length <= 0 && metro_stations.length <= 0){
			alert('标签URL和标签属性不能同时为空');
			return;
		}

		var data = new Object();
		data.label_id = $('#label_id').val();
		data.label_name = label_name;
		data.pid = pid;
		data.province_id = province_id;
		data.city_id = city_id;
		data.area_id = area_id;
		data.label_url = label_url;
		data.villages = villages;
		data.schools = schools;
		data.bus_lines = bus_lines;
		data.bus_stations = bus_stations;
		data.metro_lines = metro_lines;
		data.metro_stations = metro_stations;
		data.status = $('#status :radio[checked]').val();
		$.post("{pigcms{:U('Label/save_label')}",data,function(response){
			if(response.err_code>0){
				alert(response.err_msg);
			}else{
				alert(response.err_msg);
				window.location.reload();
			}
		},'json');

	}

	</script>

	<div class="btn hidden">
		<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
		<input type="reset" value="取消" class="button" />
	</div>
</form>
<include file="Public:footer"/>