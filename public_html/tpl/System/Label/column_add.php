<include file="Public:header"/>
<style>
	.station{width: 150px; height: 40px; float: left;}
</style>
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<input type="hidden" id="column_id" value="{pigcms{$label_column.id}" />

		<tr>
			<input type="hidden" id="province_id" value="{pigcms{$label_column.province_id}"/>
			<input type="hidden" id="city_id" value="{pigcms{$label_column.city_id}"/>
			<th width="80">城市</th>
			<td>
				<select onclick="province_changed($(this).val())">
                <option value="0">选择省</option>
                <if condition="$area_list">
                <volist name="area_list" id="vo">
                    <option value="{pigcms{$vo.area_id}" <?php echo $label_column['province_id']==$vo['area_id']?'selected':'';?>>{pigcms{$vo.area_name}</option>
                </volist>
                </if>
                </select>
                <select id="city"></select>
			</td>
		</tr>

		<tr>
			<td width="80">栏目名称</td>
			<td>
				<input type="text" class="input fl" id="column_name" value="{pigcms{$label_column.title}" placeholder="请填写一个标签名称" validate="required:true"></td>
		</tr>
		<tr>
			<td width="80">URL</td>
			<td>
				<input type="text" class="input fl" id="column_url" value="{pigcms{$label_column.url}" placeholder="请填写栏目链接" validate="required:true">
			</td>
		</tr>

		<tr>
			<td width="80">状态</td>
			<td class="radio_box" id="status">
				<span class="cb-enable">
					<label class="cb-enable selected">
						<span>正常</span>
						<input type="radio" name="status" value="0" checked="checked"></label>
				</span>

				<span class="cb-disable">
					<label class="cb-disable">
						<span>禁止</span>
						<input type="radio" name="status" value="1"></label>
				</span>
			</td>
		</tr>
	</table>

	<script type="text/javascript">

		inits();

		function save(){
			var column_id = $('#column_id').val();
			var city_id = $('#city').val();
			var column_name = $.trim($('#column_name').val());
			var column_url = $.trim($('#column_url').val());
			var status = $('#status :radio[checked]').val();
			if(column_name==''){
				alert('栏目名称不能为空');
				return;
			}
			$.post("{pigcms{:U('Label/save_column')}",{'column_id':column_id,'city_id':city_id,'column_name':column_name,'column_url':column_url,'status':status},function(response){
				alert(response.err_msg);
				if(response.err_code==0){
					window.location.reload();
				}
			},'json');
		}

		function province_changed(province_id){
            var province = parseInt(province_id);
            if(isNaN(province)){
                layer.alert('省份错误');
                return;
            }
            if(province==0){
                $('#city').html('');
                return;
            }

            $.get("{pigcms{:U('Recommend/get_citys')}",{'province_id':province},function(response){
                if(response.code>0){
                    layer.alert(response.msg);
                    return;
                }
                var city_id = $('#city_id').val();
                var html = '';
                $.each(response.msg,function(i,v){
                	var isselected = city_id==v.area_id?'selected':'';
                    html += '<option value="'+v.area_id+'" area_url="'+v.area_url+'" '+isselected+'>'+v.area_name+'</option>';
                });
                $('#city').html(html);
            },'json');
        }

        function inits(){
        	var province_id = $('#province_id').val();
			if(province_id=='0'){
				return;
			}
			province_changed(province_id);
        }
	</script>

	<div class="btn hidden">
		<input type="submit" name="dosubmit" id="dosubmit" onclick="save()" value="提交" class="button" />
		<input type="reset" value="取消" class="button" />
	</div>
<include file="Public:footer"/>