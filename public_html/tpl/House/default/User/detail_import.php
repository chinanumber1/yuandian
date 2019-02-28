<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('User/index')}">业主管理</a>
            </li>
            <li class="active">导入业主每月帐单明细</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
    	<form id="myform" method="post" action="{pigcms{:U('User/detail_import')}" enctype="multipart/form-data">
        <div class="page-content-area">
        <input class="col-sm-2 Wdate" type="text" name="paytime" style="height:30px;" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月',vel:'start_time'})" value="{pigcms{:date('Y年m月',$_SERVER['REQUEST_TIME'])}"/>
            <style>
                .ace-file-input a {display:none;}
            </style>
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                    
						<input type="hidden" name="cat_id" value="{pigcms{$now_category.cat_id}"/>
						<table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover" width="100%">
							<tr>
								<th width="80">用户欠费表格下载</th>
								<td><a target="_blank" href="{pigcms{:U('payment_list')}" class="button" style="margin-left:0px;margin-right:10px;">点击下载</a>备注：可直接修改用户欠费状态及明细</td>
							</tr>
							<tr>
								<th width="80">Excel导入</th>
								<td><input type="file" class="input fl" name="pic" style="width:200px;" placeholder="请上传excel表格" validate="required:true"/></td>
							</tr>
						</table>
						<div class="clearfix form-actions">
							<div class="col-md-offset-3 col-md-9">
								<button class="btn btn-info" type="submit">
									<i class="ace-icon fa fa-check bigger-110"></i>
									保存
								</button>
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>
 		</form>
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
<include file="Public:footer"/>