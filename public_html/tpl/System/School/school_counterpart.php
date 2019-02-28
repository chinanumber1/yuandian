<include file="Public:header"/>
<style>
	.station{width: 150px; height: 40px; float: left;}
</style>
<form id="myform" method="post" action="{pigcms{:U('school_counterpart_save')}" >
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
	
	<input type="hidden" name="school_id" value="{pigcms{$schoolInfo.school_id}" id="school_id">
		<tr>
			<td width="90">学校名称：</td>
			<td>
				<input type="text" class="input"   value="{pigcms{$schoolInfo.school_name}" readonly="readonly"> 
			</td>
		</tr>

		<tr>
			<td width="90">选择对口学校：</td>
			<td>
				<input type="text" class="input" id="schoolKeyUp" name=""  value="" placeholder="请填写一个站点名称"> 
				<select id="schoolSelect" class="">
					<option value='xz'>== 请选择学校 ==</option>
				</select>
			</td>
		</tr>

		<tr class="<if condition="is_array($counterpartList)"><else/>hidden</if>" id="stationHidden">
			<td width="80">对口学校</td>
			<td id="schoolVal">
				<volist id="counterpartList" name="counterpartList">
					<div class='station'>
						<input type='checkbox' class='counterpartIdVal' name='counterpart[]' value='{pigcms{$counterpartList.school_id}' checked='checked'>{pigcms{$counterpartList.school_name}
					</div>
				</volist>
			</td>
		</tr>

	</table>


	<script>
		$("#schoolSelect").change(function(){
			var schoolId = $("#schoolSelect").val();
			var schoolName = $("#schoolSelect option:selected").text();

			if(!isNaN(schoolId)){

				var counterpartIdVal = new Array();
				var i =1;
				$(".counterpartIdVal").each(function(){
					if($(this).val() == schoolId){
						i++;
					}
					counterpartIdVal.push($(this).val());
				})
				// alert(counterpartIdVal);
				if(i>1){
					window.top.msg(0,'已经添加到列表了',true);
				}else{
					$("#stationHidden").removeClass('hidden');
					var html = "<div class='station'><input type='checkbox' class='counterpartIdVal' name='counterpart[]' value='"+schoolId+"' checked='checked'>"+schoolName;
					$("#schoolVal").append(html);
				} 
			}
			
		})
		var schoolUrl = "{pigcms{:U('School/school_data')}";
		var flag;
		$('#schoolKeyUp').keyup(function(){
			clearTimeout(flag);
	        flag = setTimeout(function(){
	        	var name = $("#schoolKeyUp").val();
	        	var school_id = $("#school_id").val();
	        	if(name){
	        		$.post(schoolUrl,{'name':name,'school_id':school_id},function(data){
						$("#schoolSelect").empty(); 
						$("#schoolSelect").removeClass('hidden');
						$("#schoolSelect").append(data.data); 
					},'json');
	        	}
				
	      	}, 500);

		});

	</script>

	<div class="btn hidden">
		<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
		<input type="reset" value="取消" class="button" />
	</div>
</form>
<include file="Public:footer"/>