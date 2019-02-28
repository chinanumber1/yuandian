<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Shop/goods_modify')}" enctype="multipart/form-data">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">商品条形码</th>
				<td><input type="text" class="input fl" name="number" size="20" placeholder="请输入商品条形码" validate="maxlength:20,required:true"/></td>
			</tr>
			<tr>
				<th width="80">商品名称</th>
				<td><input type="text" class="input fl" name="name" size="20" placeholder="请输入商品名称" validate="maxlength:20,required:true"/></td>
			</tr>
			<tr>
				<th width="80">所属分类</th>
				<td>
				<select name="sort_id">
    				<volist name="sort_list" id="sort">
    				<option value="{pigcms{$sort['sort_id']}">{pigcms{$sort['name']}</option>
    				</volist>
    			</select>
				</td>
			</tr>
			<tr>
				<th width="80">商品单位</th>
				<td><input type="text" class="input fl" name="unit" size="20" placeholder="请输入商品单位" validate="required:true"/></td>
			</tr>
			<tr>
				<th width="80">商品进价</th>
				<td><input type="text" class="input fl" name="cost_price" id="cost_price" style="width:120px;" placeholder="请输入商品的进价" tips=""/></td>
			</tr>
			<tr>
				<th width="80">零售价</th>
				<td><input type="text" class="input fl" name="price" id="price" style="width:120px;" placeholder="请输入商品的零售价" tips=""/></td>
			</tr>
			<tr>
				<th width="80">商品图片</th>
				<td><input type="file" class="input fl" name="image" style="width:200px;" placeholder="请上传图片" validate="required:true"/></td>
			</tr>
			
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>