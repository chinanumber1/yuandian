<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-user"></i>
				<a href="{pigcms{:U('User/audit_unbind')}">申请解绑列表</a>
			</li>
			<li class="active">编辑申请解绑</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
				.line-p { line-height:34px; font-size:14px;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<form  class="form-horizontal" method="post" id="edit_form" action="__SELF__" onsubmit="return chk_submit()">
						<input  name="itemid" type="hidden"  value="{pigcms{$edit.itemid}"/>
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								
								<div class="form-group">
									<label class="col-sm-1"><label>申请姓名：</label></label>
									<p class="line-p">{pigcms{$edit.name}</p>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label>手机号码：</label></label>
									<p class="line-p">{pigcms{$edit.phone}</p>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>单元/房间：</label></label>
									<p class="line-p">{pigcms{$edit.address}</p>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label>所属角色：</label></label>
									<p class="line-p">
                                    <if condition='$edit["type"] eq 0'>
                                        房主&nbsp;&nbsp;<span  class="red">( 注意：审核通过将删除绑定的房主信息 以及房主绑定的亲属/租客都会解绑 )</span>
                                    <elseif condition='$edit["type"] eq 1' />
                                        亲属
                                    <elseif condition='$edit["type"] eq 2' />
                                        租客
                                    <elseif condition='$edit["type"] eq 3' />
                                        替换房主    
                                    </if>
                                    </p>
								</div>
                                
                                <div class="form-group">
									<label class="col-sm-1"><label>解绑原因：</label></label>
									<p class="line-p">{pigcms{$edit.note}</p>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="unittype">申请/操作：</label></label>
									<p class="line-p">{pigcms{$edit.addtime|date='Y-m-d H:i:s',###}&nbsp;&nbsp;&nbsp;&nbsp;{pigcms{$edit.edittime|date='Y-m-d H:i:s',###}</p>
								</div>

								<div class="form-group">
									<label class="col-sm-1" for="status">审核状态</label>
									
									<label style="padding-left:0px;padding-right:20px;"><input type="radio" name="status" value="1" class="ace" <if condition="$edit['status']==1">checked="checked"</if>><span class="lbl red" style="z-index: 1">审核中</span></label>
                                    <label style="padding-left:0px;padding-right:20px;"><input type="radio" name="status" value="2" class="ace" <if condition="$edit['status']==2">checked="checked"</if>><span class="lbl " style="z-index: 1">未通过</span></label>
									<label style="padding-left:0px;"><input type="radio" name="status" value="3" class="ace" <if condition="$edit['status']==3">checked="checked"</if>><span class="lbl green" style="z-index: 1" >已通过</span>&nbsp;&nbsp;<span  class="red">( 注意：审核通过后该信息将不可再编辑 )</span></label>
								</div>
							</div>
						</div>

						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
                                	<if condition="$edit['status']==3">
                                    <button class="btn btn-info" type="button" onclick="location.href='{pigcms{:U('User/audit_unbind')}'">
										<i class="ace-icon fa fa-check bigger-110"></i>
										返回列表
									</button>
                                    <else />
									<button class="btn btn-info" type="submit" <if condition="!in_array(108,$house_session['menus'])">disabled="disabled"</if>>
										<i class="ace-icon fa fa-check bigger-110"></i>
										保存
									</button>
                                    </if>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" language="javascript">
function chk_submit(){
	if(!confirm('确认进行修改?')){
		return false;
	}
}
</script>
<include file="Public:footer"/>