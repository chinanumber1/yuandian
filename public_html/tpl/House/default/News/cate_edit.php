<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-tablet"></i>
				<a href="{pigcms{:U('News/cate')}">新闻分类管理</a>
			</li>
			<li class="active">分类信息设置</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
				.ccc { color:#ccc}
				.line-p { padding:10px; line-height:34px;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<form  class="form-horizontal" method="post" id="edit_form" action="{pigcms{:U('News/cate_edit_do')}">
					<input type="hidden" value="{pigcms{$cate_id}" name='c_id'/>
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="cat_name">分类名称</label></label>
									<input class="col-sm-2" size="20" name="cat_name" id="cat_name" type="text" placeholder="分类名称" value="{pigcms{$cate_info.cat_name}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="phone">分类排序</label></label>
									<input class="col-sm-2" size="10" name="cat_sort" id="cat_sort" type="text" placeholder="数字越大，分类越靠前" value="{pigcms{$cate_info.cat_sort}" /><span class="ccc line-p">数字越大越靠前</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="water_price">分类状态</label></label>
									<label><input name="cat_status"  type="radio" value="1" <if condition="$cate_info.cat_status eq 1">checked</if> />&nbsp;&nbsp;正常</label>
									&nbsp;&nbsp;&nbsp;
									<label><input name="cat_status"  type="radio" value="0" <if condition="$cate_info.cat_status eq 0">checked</if> />&nbsp;&nbsp;关闭</label>
								</div>
							</div>
						</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit" <if condition="!in_array(156,$house_session['menus']) && !in_array(157,$house_session['menus']) ">disabled="disabled"</if>>
										<i class="ace-icon fa fa-check bigger-110"></i>
										保存
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
.BMap_cpyCtrl{display:none;}
input.ke-input-text {
background-color: #FFFFFF;
background-color: #FFFFFF!important;
font-family: "sans serif",tahoma,verdana,helvetica;
font-size: 12px;
line-height: 24px;
height: 24px;
padding: 2px 4px;
border-color: #848484 #E0E0E0 #E0E0E0 #848484;
border-style: solid;
border-width: 1px;
display: -moz-inline-stack;
display: inline-block;
vertical-align: middle;
zoom: 1;
}
.form-group>label{font-size:12px;line-height:24px;}
#upload_pic_box{margin-top:20px;height:150px;}
#upload_pic_box .upload_pic_li{width:130px;float:left;list-style:none;}
#upload_pic_box img{width:100px;height:70px;border:1px solid #ccc;}
</style>
<include file="Public:footer"/>