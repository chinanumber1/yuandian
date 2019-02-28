<include file="Public:header"/>
<style>
	.station{width: 80px; height: 40px; float: left;}
</style>
<form id="myform" method="post" action="{pigcms{:U('Metro/updateLine')}" >
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		

		<tr>
			<th width="80">城市</th>
			<td id="choose_cityarea" circle_id="-1" area_id="-1" province_id="{pigcms{$info.province_id}" city_id="{pigcms{$info.city_id}"></td>
		</tr>

		<tr>
			<td width="80">线路名称</td>
			<td>
				<input type="text" class="input fl" name="name" value="{pigcms{$info.name}" placeholder="请填写一个线路名" validate="required:true"></td>
		</tr>


		<tr>
			<td width="80">是否推荐热门</td>
			<td class="radio_box">
				<span class="cb-enable">
					<label class="cb-enable <eq name="info['is_hot']" value="1">selected</eq>">
						<span>推荐</span>
						<input type="radio" name="is_hot" value="1" <eq name="info['is_hot']" value="1">checked="checked"</eq>></label>
				</span>

				<span class="cb-disable">
					<label class="cb-enable <eq name="info['is_hot']" value="0">selected</eq>">
						<span>不推荐</span>
						<input type="radio" name="is_hot" value="0" <eq name="info['is_hot']" value="0">checked="checked"</eq>></label>
				</span>
			</td>
		</tr>

		<tr>
			<td width="80">状态</td>
			<td class="radio_box">
				<span class="cb-enable">
					<label class="cb-enable <eq name="info['status']" value="1">selected</eq>">
						<span>正常</span>
						<input type="radio" name="status" value="1" <eq name="info['status']" value="1">checked="checked"</eq>></label>
				</span>

				<span class="cb-disable">
					<label class="cb-disable <eq name="info['status']" value="0">selected</eq>">
						<span>禁止</span>
						<input type="radio" name="status" value="0" <eq name="info['status']" value="0">checked="checked"</eq>></label>
				</span>
			</td>
		</tr>

		<tr>
			<td width="80">排序</td>
			<td>
				<input type="text" class="input fl" name="sort" value="{pigcms{$info.sort|default=0}" placeholder="请填写一个数值" validate="number:true"></td>
		</tr>

		<tr class="" id="stationHidden">
			<td width="80">所选站点</td>
			<td id="stationVal">
				<volist id="station" name="stations">
					<div class='station'>
						<input type='checkbox' class='stationIdVal' name='station[{pigcms{$station.station_id}][stationId]' value='{pigcms{$station.station_id}' checked='checked'>{pigcms{$station.name}&nbsp;&nbsp;<input type='text' name='station[{pigcms{$station.station_id}][sort]' value='{pigcms{$station.sort}' class='input' style=' width:25px;'>
					</div>
				</volist>
			</td>
		</tr>

		<tr>
			<td width="80">选择地铁站：</td>
			<td>
				<input type="text" class="input" id="searchKeyUp" name=""  value="" placeholder="请填写一个站点名称"> 
				
				<select id="stationSelect" class="">
					<option value='xz'>== 请选择站点 ==</option>
					<!-- <option value='tj'>添加站点</option> -->
				</select>
			</td>
		</tr>

	</table>


	<script type="text/javascript">

		var stationUrl = "{pigcms{:U('Metro/apiStation')}";
		var flag;

		$('#searchKeyUp').keyup(function(){
			clearTimeout(flag);

	        flag = setTimeout(function(){
	        	var name = $("#searchKeyUp").val();
	        	if(name){
	        		$.post(stationUrl,{'name':name},function(json){
						$("#stationSelect").empty(); 
						// $("#stationSelect").removeClass('hidden');
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
				// alert(stationIdVal);
				if(i>1){
					window.top.msg(0,'已经添加到列表了',true);
				}else{
					$("#stationHidden").removeClass('hidden');
					var html = "<div class='station'><input type='checkbox' class='stationIdVal' name='station["+stationId+"][stationId]' value='"+stationId+"' checked='checked'>"+stationName+"&nbsp;&nbsp;<input type='text' name='station["+stationId+"][sort]' value='0' class='input' style=' width:25px;'></div>";
					$("#stationVal").append(html);
				} 
			}else if( stationId == 'tj'){
			   // window.top.artiframe('/admin.php?g=System&c=Metro&a=createStation','添加地铁站',600,500,true,false,false,addbtn,'store_add',true);
			}
			
		});



	</script>

	<div class="btn hidden">
		<input type="hidden" name="id" value="{pigcms{$_GET['id']}" />
		<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
		<input type="reset" value="取消" class="button" />
	</div>
</form>
<include file="Public:footer"/>