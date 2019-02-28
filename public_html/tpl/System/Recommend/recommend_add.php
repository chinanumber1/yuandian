<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE')}"></script>
<style type="text/css">
	p span {color:gray;font-size:12px;}
</style>
<div>
<p><input type="text" id="name" placeholder="请输入楼盘名称" ><button onclick="search()">搜索</button></p>
<p><span>推荐栏目：</span>
	<select id="column">
		<option value="0">请选择推荐栏目</option>
		<if condition="$column_list">
		<volist name="column_list" id="vo">
		<option value="{pigcms{$vo.id}">{pigcms{$vo.title}</option>
		</volist>
		</if>
	</select>
</p>
<p><span>排&nbsp;&nbsp;序：&nbsp;</span><input type="text" id="ord" value="0"></p>
<p id="building_list">
<span>推荐楼盘：</span>
	<select id="building">
		<option value=0>请选择楼盘</option>
	</select>
</p>
</div>
<div class="btn" style="display:none;">
	<input type="submit" name="dosubmit" id="dosubmit" onclick="save()" value="提交" class="button" />
	<input type="reset" value="取消" class="button" />
</div>

<script type="text/javascript">
function search(){
	var name = $.trim($('#name').val());
	if(name==''){
		return;
	}
	$.get("{pigcms{:U('Recommend/search_building')}",{'name':name},function(response){
		if(response.err_code>0){
			alert(response.err_msg);
			return;
		}

		var html = '<option value=0>请选择楼盘</option>';
		$.each(response.err_msg,function(i,v){
			html += '<option value="'+v.id+'">'+v.title+'</option>';
		});
		$('#building').html(html);
	},'json');
}

function save(){
	var building_id = $('#building').val();
	var column_id = $('#column').val();
	var ord = $.trim($('#ord').val());
	if(building_id<=0){
		alert('请选择楼盘');
		return;
	}
	if(column_id<=0){
		alert('请选择推荐栏目');
		return;
	}
	$.post("{pigcms{:U('Recommend/save')}",{'building_id':building_id,'column_id':column_id,'ord':ord},function(response){
		alert(response.err_msg);
		if(response.err_code==0){
			window.location.reload();
		}
	},'json');
}
</script>