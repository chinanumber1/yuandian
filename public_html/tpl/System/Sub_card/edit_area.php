<include file="Public:header"/>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/dtree.css?{pigcms{:time()}" />
	<script type="text/javascript" src="{pigcms{$static_path}js/dtree.js?{pigcms{:time()}"></script>
	<form name="myform" id="myform" >
		<input type="hidden" name="sub_cardid" value="{pigcms{$_GET['sub_cardid']}">
 				
		<div class="dtree" id="dtree_div">
		
			<input id="dosearch_text" type="text" onkeydown="if(event.keyCode==13){return false;}" style="font-size:18px;"/>
			<input id="dosearch" type="button" value="查询" onClick="nodeSearching() " style="font-size:18px;"/>
			<script type="text/javascript">
		
				var static_path = "{pigcms{$static_path}" 
				var open_all = true,select_all=true;
				d = new dTree('d', true);      
				d.add(0, -1, '选择地区');
				<volist name="area_list" id="vo">
					
					d.add({pigcms{$vo.area_id}, 0, 'areaIds[]', '', '{pigcms{$vo.area_name}',<if condition="$vo.select eq 1">true<else />false</if>,'','','','','','',<if condition="$vo.select eq 1">true<else />false</if>);
				
					 <php> if ($vo['son_list']) {</php>
						<volist name="vo['son_list']" id="son">
							d.add({pigcms{$son.area_id}, {pigcms{$son.area_pid}, 'areaIds[]', {pigcms{$son.area_id}, '{pigcms{$son.area_name}', <if condition="$son.select eq 1">true<else />false</if>);
					   
					  
						</volist>
					   
					<php> } </php>
				
					
				</volist>
				document.write(d);
				d.openAll();
			</script>
		</div>

		<div class="btn ">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" style="float:right;" />
			<a href="javascript:void(0)" onclick="openall()" id="openall">全部展开</a>
			<a href="javascript:void(0)" onclick="selectall()" id="selectall">全选</a>
		</div>
	</form>
	


	<script>
    $(document).ready(function(){
        $('#dosubmit').click(function(){
				$.post('{pigcms{:U('Sub_card/edit_area')}', $('#myform').serialize(), function(data, textStatus, xhr) {
					if(data.status==1){
						window.top.msg(2,data.info,true,2);
					}else{
						window.top.msg(0,data.info,true,2);
					}
					window.top.art.dialog({id:'edit_area'}).close();
				});
				
			});
    });
	
	
	
	</script>
	
	<script type="text/javascript">
	function test() {
		var count = 0;
		var obj = document.all.authority;

		for (i = 0; i < obj.length; i++) {
			if (obj[i].checked) {
				alert(obj[i].value);
				count++;
			}
		}
	}
	//搜索节点并展开节点
	function nodeSearching() {
		var dosearch = $.trim($("#dosearch_text").val());//获取要查询的文字
		var dtree_div = $("#dtree_div").find(".dtree_node").show().filter(":contains('" + dosearch + "')");//获取所有包含文本的节点
		$.each(dtree_div, function (index, element) {
			var s = $(element).attr("node_id");
			d.openTo(s);//根据id打开节点
		});
	}
	
	
	function openall() {
		d.o_All(open_all);//根据id打开节点
		open_all && $('#openall').html('全部收缩')
		!open_all && $('#openall').html('全部展开')
		open_all=!open_all;
	}
	
	function selectall() {
		var code_Values = document.all['areaIds[]'];
		if (code_Values.length) {
			for ( var i = 0; i < code_Values.length; i++) {
				code_Values[i].checked = select_all;
			}
		} else {
			code_Values.checked = select_all;
		}
		select_all && d.o_All(select_all);
		select_all && $('#selectall').html('取消全选')
		!select_all && $('#selectall').html('全选')
		select_all=!select_all;
	}
	
	function select_son_all(obj){
		if($(obj).is(':checked')){
			var son_select_all = true;
		}else{
			var son_select_all = false;
		}
	
		$(obj).parents('.dTreeNode').next('.clip').find('.dTreeNode').each(function(i,index){
			$(index).find('input:checkbox').attr('checked',son_select_all)
			d.openTo($(index).find('.dtree_node').attr('node_id'))
		})
	}
	
	</script>

</script>
<include file="Public:footer"/>
