<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
                <i class="ace-icon fa fa-file-excel-o"></i>
                <a href="{pigcms{:U('Bbs/index')}">社区论坛</a>
            </li>
            <li><a href="{pigcms{:U('Bbs/index')}">分类管理</a></li>
            <li><a href="{pigcms{:U('Bbs/aricle_list',array('cat_id'=>$aBbsAricel['cat_id']))}">文章列表</a></li>
            <li>更改状态</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<form  class="form-horizontal" method="post" id="edit_form" action="{pigcms{:U('Bbs/aricle_status')}">
					<input type="hidden" value="{pigcms{$aBbsAricel}" name='c_id'/>
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-2 col-md-1 col-lg-1 col-xs-2"><label for="aricle_id">文章ID</label></label>
									<input class="col-sm-5 col-md-5" readonly size="20" name="aricle_id" id="aricle_id" type="text" value="{pigcms{$aBbsAricel.aricle_id}" />
									<input type="hidden" value="{pigcms{$aBbsAricel.cat_id}" name='cat_id'/>
								</div>
								<div class="form-group">
									<label class="col-sm-2 col-md-1 col-lg-1 col-xs-2"><label for="aricle_title">文章标题</label></label>
									<input class="col-sm-5 col-md-5" readonly size="20" name="aricle_title" id="aricle_title" type="text" value="{pigcms{$aBbsAricel.aricle_title}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-2 col-md-1 col-lg-1 col-xs-2"><label for="aricle_sort">排序</label></label>
									<input class="col-sm-5 col-md-5" size="20" name="aricle_sort" id="aricle_sort" type="text" value="{pigcms{$aBbsAricel.aricle_sort}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-2 col-md-1 col-lg-1 col-xs-2"><label for="water_price">文章状态</label></label>
									<label><input name="aricle_status"  type="radio" value="1" <if condition="$aBbsAricel.aricle_status eq 1">checked</if> />&nbsp;&nbsp;审核通过</label>
									&nbsp;&nbsp;&nbsp;
									<label><input name="aricle_status"  type="radio" value="3" <if condition="$aBbsAricel.aricle_status eq 3">checked</if> />&nbsp;&nbsp;审核不通过</label>
								</div>
							</div>
						</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit" <if condition="!in_array(132,$house_session['menus'])">disabled="disabled"</if>>
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