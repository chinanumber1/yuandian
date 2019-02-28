<include file="Public:header"/>
<form id="myform" method="post" action="{pigcms{:U('Yuedan/cat_modify')}" enctype="multipart/form-data">
	<input type="hidden" name="fcid" value="{pigcms{$fcid}"/>
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<tr>
			<th width="80">分类名称</th>
			<td>
				<input type="text" class="input fl" name="cat_name" id="cat_name" size="25" placeholder="" validate="maxlength:8,required:true" tips=""/>
			</td>
		</tr>
		<tr>
			<th width="80">分类排序</th>
			<td>
				<input type="text" class="input fl" name="cat_sort" value="0" size="10" placeholder="分类排序" validate="maxlength:6,required:true,number:true" tips="默认添加时间排序！手动排序数值越大，排序越前。"/>
			</td>
		</tr>

		<tr>
			<th width="80">分类状态</th>
			<td>
				<span class="cb-enable">
					<label class="cb-enable selected">
						<span>启用</span>
						<input type="radio" name="status" value="1" checked="checked" />
					</label>
				</span>
				<span class="cb-disable">
					<label class="cb-disable">
						<span>关闭</span>
						<input type="radio" name="status" value="0" />
					</label>
				</span>
			</td>
		</tr>

		<tr>
			<th width="80">分类图片</th>
			<td><input type="file" class="input fl" name="icon" style="width:200px;" placeholder="请上传图片" tips="不修改请不上传！上传新图片，老图片会被自动删除！"/></td>
		</tr>
		<if condition='empty($_GET["fcid"])'>
			<tr>
				<th width="80">分类点击图片</th>
				<td><input type="file" class="input fl" name="click_icon" style="width:200px;" placeholder="请上传图片" tips="不修改请不上传！上传新图片，老图片会被自动删除！"/></td>
			</tr>
		</if>
		

		<if condition='$_GET["fcid"]'>
			<tr>
				<th width="80">抽成比例</th>
				<td><input type="text" tips="抽成比例，百分比返现，最多两位小数！" style="width:100px;" value="" name="cut_proportion" class="input fl"></td>
			</tr>
			
			<tr>
				<th width="80">取消订单方式</th>
				<td>
					<span class="cb-enable">
						<label class="cb-enable selected">
							<span>免费取消</span>
							<input type="radio" name="is_free" value="0" checked="checked" />
						</label>
					</span>
					<span class="cb-disable">
						<label class="cb-disable">
							<span>不免费取消</span>
							<input type="radio" name="is_free" value="1" />
						</label>
					</span>
				</td>
			</tr>
			
			<tr>
				<th width="80">违约金比例</th>
				<td><input type="text" name="cancel_proportion" value="" tips="取消抽成比例，百分比返现，最多两位小数！" style="width:100px;" class="input fl"></td>
			</tr>
			
			<tr>
				<th width="80">取消时间</th>
				<td><input type="text" name="cancel_time" value="" tips="免费取消时间，超过时间按比例扣除取消费用！ /（小时）" style="width:100px;" class="input fl"></td>
			</tr>


		</if>
		<tr>
			<th width="80">是否热门</th>
			<td>
				<span class="cb-enable">
					<label class="cb-enable selected">
						<span>启用</span>
						<input type="radio" name="is_hot" value="1" checked="checked" />
					</label>
				</span>
				<span class="cb-disable">
					<label class="cb-disable">
						<span>关闭</span>
						<input type="radio" name="is_hot" value="0" />
					</label>
				</span>
			</td>
		</tr>
	</table>
	<div class="btn hidden">
		<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
		<input type="reset" value="取消" class="button" />
	</div>
</form>
<include file="Public:footer"/>
