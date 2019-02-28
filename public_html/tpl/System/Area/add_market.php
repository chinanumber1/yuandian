<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Area/add_market')}" enctype="multipart/form-data" >
		<input type="hidden" name="area_id" value="{pigcms{$_GET['area_id']}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">商场名称</th>
				<td><input type="text" class="input fl" name="market_name" id="market_name" size="20" placeholder="请输入名称" validate="maxlength:30,required:true"/></td>
			</tr>
			<tr>
				<th width="80">商场图片</th>
				<td><input type="file" class="input fl" name="img" style="width:200px;" placeholder="请上传图片" validate="required:true"  tips="请上传图片的尺寸控制在900*500之内"/></td>
			</tr>
			<tr>
				<th width="80">商场经纬度</th>
				<td id="choose_map"></td>
			</tr>
			<tr>
				<th width="80">商场介绍</th>
				<td><textarea style="height:50px;" class="input fl" name="introduce" id="introduce_one" placeholder="老乡鸡，重庆打旺火锅" tips="介绍商场里的美食"></textarea></td>
			</tr>
			<tr>
				<th width="80">排序</th>
				<td><input type="text" class="input fl" name="market_sort" size="10" value="0" validate="required:true,number:true,maxlength:6" tips="数值越大，排序越前"/></td>
			</tr>
			<tr>
				<th width="80">热门</th>
				<td>
					<span class="cb-enable"><label class="cb-enable"><span>是</span><input type="radio" name="is_hot" value="1" /></label></span>
					<span class="cb-disable"><label class="cb-disable selected"><span>否</span><input type="radio" name="is_hot" value="0" checked="checked"/></label></span>
				</td>
			</tr>
			<tr>
				<th width="80">状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>显示</span><input type="radio" name="is_open" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>隐藏</span><input type="radio" name="is_open" value="0" /></label></span>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
	<script type="text/javascript">
//		get_first_word('area_name','area_url','first_pinyin');
	</script>
<include file="Public:footer"/>