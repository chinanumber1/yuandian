<include file="Public:header"/>
<style>
	.station{width: 80px; height: 40px; float: left;}
</style>
<form id="myform" method="post" action="{pigcms{:U('Village/store')}" >
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">

		<tr>
			<th width="80">城市</th>
			<td id="choose_cityarea"></td>
		</tr>

		<tr>
			<td width="80">小区名称</td>
			<td>
				<input type="text" class="input fl" name="name" value="" placeholder="请填写一个小区名称" validate="required:true"></td>
		</tr>

		<tr>
			<td width="80">小区名称首字母</td>
			<td>
				<input type="text" class="input fl" name="first_word" value="" placeholder="" tips="用于筛选" validate="required:true"></td>
		</tr>

		<tr>
			<td width="80">详细地址</td>
			<td>
				<input type="text" class="input fl" name="address" value="" placeholder="" validate="required:true"></td>
		</tr>

		<tr>
			<th width="80">坐标经纬度</th>
			<td id="choose_map"></td>
		</tr>

		<tr>
			<td width="80">物业公司名称</td>
			<td><input type="text" class="input fl" name="administrator" value="" placeholder="物业公司名称" validate="required:true"></td>
		</tr>

		<tr>
			<td width="80">物业公司电话</td>
			<td><input type="text" class="input fl" name="administrator_tel" value="" placeholder="物业公司电话" validate="required:true"></td>
		</tr>

		<tr>
			<td width="80">物业费</td>
			<td><input type="text" class="input fl" name="property_price" value="" placeholder="物业费"></td>
		</tr>

		<tr>
			<td width="80">物业类型</td>
			<td><input type="text" class="input fl" name="property_type" value="" placeholder="物业类型"></td>
		</tr>
		<tr>
			<td width="80">建筑年代</td>
			<td><input type="text" class="input fl" name="building_age" value="" placeholder="建筑年代"></td>
		</tr>

		<tr>
			<td width="80">小区均价</td>
			<td><input type="text" class="input fl" name="average_price" value="" placeholder="小区均价"></td>
		</tr>

		<tr>
			<td width="80">开发商</td>
			<td>
				<input type="text" class="input fl" name="developers" value="" placeholder="开发商"></td>
		</tr>

		<tr>
			<td width="80">列表图片</td>
			<td><input type="text" class="input fl" style=" width: 350px;" name="list_img" value="" id="listImg" placeholder="图片地址"><a href="javascript:void(0)" class="button" id="image3">浏览</a></td>
		</tr>

		<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
		<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
		<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
		<script>
	        KindEditor.ready(function(K) {
	            var editor = K.editor({
	                allowFileManager : true
	            });
	            K('#image3').click(function() {
	                editor.uploadJson = "{pigcms{:U('Village/ajax_upload_pic')}";
	                editor.loadPlugin('image', function() {
	                    editor.plugin.imageDialog({
	                        showRemote : false,
	                        imageUrl : K('#url3').val(),
	                        clickFn : function(url, title, width, height, border, align) {
	                            K('#listImg').val(url);
	                            editor.hideDialog();
	                        }
	                    });
	                });
	            });
	        });
	    </script>



		<tr class="" id="stationHidden">
			<td width="80">已选地铁站：</td>
			<td id="stationVal">
				<volist id="station" name="metro_stations">
					<div class='station'>
						<input type='checkbox' class='stationIdVal' name='station[{pigcms{$station.id}][stationId]' value='{pigcms{$station.id}' checked='checked'>{pigcms{$station.name}&nbsp;&nbsp;
					</div>
				</volist>
			</td>
		</tr>

		<tr>
			<td width="80">周边地铁站：</td>
			<td>
				<input type="text" class="input" id="searchKeyUp" name=""  value="" placeholder="请填写一个地铁站名称"> 
				
				<select id="stationSelect" class="">
					<option value='xz'>== 请选择站点 ==</option>
					<!-- <option value='tj'>添加站点</option> -->
				</select>
			</td>
		</tr>


		<tr>
			<td width="80">已选公交站：</td>
			<td id="bus_value">
				<volist id="station" name="bus_stations">
					<div class='station'>
						<input type='checkbox' class='bus_value' name='bus[{pigcms{$station.station_id}][stationId]' value='{pigcms{$station.station_id}' checked='checked'>{pigcms{$station.station_name}&nbsp;&nbsp;
					</div>
				</volist>
			</td>
		</tr>

		<tr>
			<td width="80">周边公交站：</td>
			<td>
				<input type="text" class="input" id="choose_bus" name=""  value="" placeholder="请填写一个公交站名称"> 
				
				<select id="bus_select" class="">
					<option value='xz'>== 请选择站点 ==</option>
					<!-- <option value='tj'>添加站点</option> -->
				</select>
			</td>
		</tr>

		<tr>
			<td width="80">已选学校：</td>
			<td id="school_value">
				<volist id="school" name="schools">
					<div class='station'>
						<input type='checkbox' class='school_value' name='school[{pigcms{$school.school_id}][stationId]' value='{pigcms{$school.school_id}' checked='checked'>{pigcms{$school.school_name}&nbsp;&nbsp;
					</div>
				</volist>
			</td>
		</tr>

		<tr>
			<td width="80">周边学校：</td>
			<td>
				<input type="text" class="input" id="chose_school" name=""  value="" placeholder="请填写一个学校名称"> 
				
				<select id="school_select" class="">
					<option value='xz'>== 请选择学校 ==</option>
				</select>
			</td>
		</tr>
	</table>
<script type="text/javascript">

		var stationUrl = "{pigcms{:U('Metro/apiStation')}";
		var flag;
		var busUrl = "{pigcms{:U('Bus/bus_station_data')}";
		var flag_bus;
		var schoolUrl = "{pigcms{:U('School/apiSchool')}";
		var flag_school;

		$('#chose_school').keyup(function(){
			clearTimeout(flag_bus);

	        flag_bus = setTimeout(function(){
	        	var name = $("#chose_school").val();
	        	if(name){
	        		$.post(schoolUrl,{'name':name},function(json){
						$("#school_select").empty(); 
						// $("#stationSelect").removeClass('hidden');
						$("#school_select").append(json.data); 
						
					},'json');

	        	}
				
	      	}, 500);

		});

		$("#school_select").change(function(){
			var stationId = $("#school_select").val();
			var stationName = $("#school_select option:selected").text();

			if(!isNaN(stationId)){

				var school_value = new Array();
				var i =1;
				$(".school_value").each(function(){
					if($(this).val() == stationId){
						i++;
					}
					school_value.push($(this).val());
				})
				if(i>1){
					window.top.msg(0,'已经添加到列表了',true);
				}else{
					// $("#stationHidden").removeClass('hidden');
					var html = "<div class='station'><input type='checkbox' class='school_value' name='school["+stationId+"][stationId]' value='"+stationId+"' checked='checked'>"+stationName+"&nbsp;&nbsp;</div>";
					$("#school_value").append(html);
				} 
			
			}
			
		});
				
		$('#choose_bus').keyup(function(){
			clearTimeout(flag_bus);

	        flag_bus = setTimeout(function(){
	        	var name = $("#choose_bus").val();
	        	if(name){
	        		$.post(busUrl,{'name':name},function(json){
						$("#bus_select").empty(); 
						$("#bus_select").append(json.data); 
						
					},'json');

	        	}
				
	      	}, 500);

		});

		$("#bus_select").change(function(){
			var stationId = $("#bus_select").val();
			var stationName = $("#bus_select option:selected").text();

			if(!isNaN(stationId)){

				var bus_value = new Array();
				var i =1;
				$(".bus_value").each(function(){
					if($(this).val() == stationId){
						i++;
					}
					bus_value.push($(this).val());
				})
				if(i>1){
					window.top.msg(0,'已经添加到列表了',true);
				}else{
					// $("#stationHidden").removeClass('hidden');
					var html = "<div class='station'><input type='checkbox' class='bus_value' name='bus["+stationId+"][stationId]' value='"+stationId+"' checked='checked'>"+stationName+"&nbsp;&nbsp;</div>";
					$("#bus_value").append(html);
				} 
			
			}
			
		});

		$('#searchKeyUp').keyup(function(){
			clearTimeout(flag);

	        flag = setTimeout(function(){
	        	var name = $("#searchKeyUp").val();
	        	if(name){
	        		$.post(stationUrl,{'name':name},function(json){
						console.log(json.data);
						$("#stationSelect").empty(); 
						$("#stationSelect").append(json.data); 
						
					},'json');

	        	}
				
	      	}, 500);

		});

		$("#stationSelect").change(function(){
			var stationId = $("#stationSelect").val();
			var stationName = $("#stationSelect option:selected").text();

			if(!isNaN(stationId)){

				var stationIdVal = new Array();
				var i =1;
				$(".stationIdVal").each(function(){
					if($(this).val() == stationId){
						i++;
					}
					stationIdVal.push($(this).val());
				})
				if(i>1){
					window.top.msg(0,'已经添加到列表了',true);
				}else{
					$("#stationHidden").removeClass('hidden');
					var html = "<div class='station'><input type='checkbox' class='stationIdVal' name='station["+stationId+"][stationId]' value='"+stationId+"' checked='checked'>"+stationName+"&nbsp;&nbsp;</div>";
					$("#stationVal").append(html);
				} 
			}else if( stationId == 'tj'){
			   // window.top.artiframe('/admin.php?g=System&c=Metro&a=createStation','添加地铁站',600,500,true,false,false,addbtn,'store_add',true);
			}
			
		});



	</script>

	<div class="btn hidden">
		<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
		<input type="reset" value="取消" class="button" />
	</div>
</form>
<include file="Public:footer"/>