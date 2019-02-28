<include file="Public:header"/>

<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-tablet"></i>
				<a href="{pigcms{:U('Role/role_list')}">权限管理</a>
			</li>
			<li class="active">编辑管理员</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<div class="tabbable">
						<ul id="myTab" class="nav nav-tabs">
							<li class="active">
								<a href="#basicinfo" data-toggle="tab">基本设置</a>
							</li>
							<li>
								<a href="#powerinfo" data-toggle="tab">权限设置</a>
							</li>
						</ul>
					</div>
					<form  class="form-horizontal" method="post" action="__SELF__" onsubmit="return check_submit()">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="worker">工作人员</label></label>
									<div class="col-sm-2" style="padding:0px ;position:relative">
										<input class="col-sm-2" size="20" name="worker" id="worker" type="text"  value="" autocomplete="off" style="width:100%" placeholder="输入姓名或手机号搜索" />
										<div id="searchBox" style="display: none;border:1px solid #F59942;position:absolute;left:0px;width:100%;max-height:300px;overflow-y:auto">
	                            		</div>
										<div id="dropdown-menu" class="dropdown-menus " style="display:none"></div>
									</div>
									<label class="col-sm-2">从工作人员中添加管理员</label>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="account">登录账号</label>&nbsp;<span style="color: red">*</span></label>
									<input class="col-sm-2" size="20" name="account" id="account" type="text"  value="{pigcms{$role.account}" />
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="pwd">登录密码</label></label>
									<input class="col-sm-2" size="20" name="pwd" id="pwd" type="password"  value="" placeholder="不修改则不填写" />
								</div>
							<!-- 	<div class="form-group">
									<label class="col-sm-1"><label for="repwd">确认密码</label></label>
									<input class="col-sm-2" size="20" name="repwd" id="repwd" type="password"  value="" placeholder="不修改则不填写" />
								</div> -->
								<div class="form-group">
									<label class="col-sm-1"><label for="realname">姓名</label></label>
									<input class="col-sm-2" size="20" name="realname" id="realname" type="text"  value="{pigcms{$role.realname}" />
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="phone">手机号</label></label>
									<input class="col-sm-2" size="20" name="phone" id="phone" type="text"  value="{pigcms{$role.phone}" />
								</div>
								<!-- 	<div class="form-group">
									<label class="col-sm-1"><label for="email">邮箱</label></label>
									<input class="col-sm-2" size="20" name="email" id="email" type="text"  value="{pigcms{$role.email}" />
								</div> -->
								<div class="form-group">
									<label class="col-sm-1"><label for="remarks">备注</label></label>
									<textarea name="remarks" id="remarks"  style="width:400px; height:150px">{pigcms{$role.remarks}</textarea>
								</div>
							</div>
							<div id="powerinfo" class="tab-pane">
								<table class="table table-border" width="100%">
									<input type="hidden" name="admin_id" value="{pigcms{$admin.id}"/>
									<tr><td colspan="2"><label><input type="checkbox" id="all"/> 全选</label></td></tr>
									<volist name="menus" id="rowset">
        								<php>if ($house_session['is_open_estate'] == 1 || ($house_session['is_open_estate'] == 0 && $rowset['id'] !=19)) { </php>
										<tr>
											<th width="160px" style="border-right: 1px solid #ddd">
												<label style="font-size: 18px;color:#999"><input type="checkbox" class="menu_{pigcms{$rowset['id']} father_menu" value="{pigcms{$rowset['id']}" name="menus[]" <if condition="in_array($rowset['id'],$role['menus'])">checked</if>>　{pigcms{$rowset['name']}</label>
											</th>
											<td>
											<volist name="rowset['child']" id="row" key="k">
												<ul>
													<if condition="$k eq $rowset['count']">
														<li>
													<else/>
														<li style="border-bottom: 1px solid #ddd">
													</if>
														<label style="margin-bottom: 4px;padding-top: 5px;font-size: 16px;color:#2b7dbc">
															<input type="checkbox" class="child_menu_{pigcms{$row['fid']} cf_menu_{pigcms{$row['id']} child_menu" value="{pigcms{$row['id']}"  name="menus[]" data-fid="{pigcms{$row['fid']}" data-id="{pigcms{$row['id']}" <if condition="in_array($row['id'],$role['menus'])">checked</if> >
															　{pigcms{$row['name']}
														</label>　
														<if condition="$row['child']">
															<div class="" style="padding-left: 30px;">
															<volist name="row['child']" id="level_3">
																<label><input type="checkbox" class="child_menu2_{pigcms{$level_3['fid']} child_menu2" value="{pigcms{$level_3['id']}"  name="menus[]" data-fid="{pigcms{$level_3['fid']}" <if condition="in_array($level_3['id'],$role['menus'])">checked</if> >　{pigcms{$level_3['name']}</label>　
															</volist>
															</div>
														</if>
													</li>
												</ul>
											</volist>
											</td>
										</tr>
										<php> } </php>
									</volist>
								</table>
							</div>
						</div>
						<!-- <div class="space"></div> -->
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<if condition="in_array(10,$house_session['menus'])">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa fa-check bigger-110"></i>
										保存
									</button>
									<else/>
									<button class="btn btn-info" type="submit" disabled="disabled">
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
<style>
	#dropdown-menu{
		font-family: Monospaced Number,Chinese Quote,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,PingFang SC,Hiragino Sans GB,Microsoft YaHei,Helvetica Neue,Helvetica,Arial,sans-serif;
		line-height: 1.5;
		color: rgba(0,0,0,.65);
		margin: 0;
		padding: 0;
		list-style: none;
		-webkit-box-shadow: 0 2px 8px rgba(0,0,0,.15) !important;
		box-shadow: 0 2px 8px rgba(0,0,0,.15) !important;
		border-radius: 4px;
		-webkit-box-sizing: border-box;
		box-sizing: border-box;
		outline: none;
		font-size: 14px;
		min-width: auto;right:0px;left:0px;
		top:33px;
		position:absolute;
		z-index: 10;
		display: block;
		background: #fff;
	}
	#dropdown-menu .ant-select-dropdown-menu-item-active {
		background-color: #e6f7ff;
	}
	#dropdown-menu .ant-select-dropdown-menu-item {
		position: relative;
		display: block;
		padding: 5px 12px;
		line-height: 22px;
		font-weight: 400;
		color: rgba(0,0,0,.65);
		white-space: nowrap;
		cursor: pointer;
		overflow: hidden;
		text-overflow: ellipsis;
		-webkit-transition: background .3s ease;
		transition: background .3s ease;
	}
	#dropdown-menu .ant-select-dropdown-menu-item{line-height:35px;list-style: none;border:none !important;}
	#dropdown-menu .ant-select-dropdown-menu-item:hover {
		background-color: #e6f7ff
	}

	#dropdown-menu .ant-select-dropdown-menu-item:first-child {
		border-radius: 4px 4px 0 0;
	}

	#dropdown-menu .ant-select-dropdown-menu-item:last-child {
		border-radius: 0 0 4px 4px
	}

</style>
<script type="text/javascript">

$(document).ready(function(){
	$('#all').click(function(){
		if ($(this).attr('checked')) {
			$('.father_menu, .child_menu, .child_menu2').attr('checked', true);
		} else {
			$('.father_menu, .child_menu, .child_menu2').attr('checked', false);
		}
	});
	$('.father_menu').click(function(){
		var fid = $(this).val();
		if ($(this).attr('checked')) {
			$('.child_menu_' + fid).attr('checked', true);
			$('.child_menu_' + fid).each(function(){
				var id = $(this).val()
				$('.child_menu2_' + id).attr('checked', true);
			});
		} else {
			$('.child_menu_' + fid).attr('checked', false);
			$('.child_menu_' + fid).each(function(){
				var id = $(this).val()
			$('.child_menu2_' + id).attr('checked', false);
			});
		}
	});
	$('.child_menu').click(function(){
		var fid = $(this).attr('data-fid');
		var id = $(this).attr('data-id');
		if ($(this).attr('checked')) {
			$('.menu_' + fid).attr('checked', true);
			$('.child_menu2_' + id).attr('checked', true);
		} else {
			var flag = false;
			$('.child_menu_' + fid).each(function(){
				if ($(this).attr('checked')) {
					flag = true;
				}
			});
			$('.menu_' + fid).attr('checked', flag);
			$('.child_menu2_' + id).attr('checked', false);
		}
	});
	$('.child_menu2').click(function(){
		var fid = $(this).attr('data-fid');
		if ($(this).attr('checked')) {
			$('.cf_menu_' + fid).attr('checked', true);
			var ffid = $('.cf_menu_' + fid).attr('data-fid');
			$('.menu_' + ffid).attr('checked', true);
		} else {
			// var flag = false;
			// $('.child_menu2_' + fid).each(function(){
			// 	if ($(this).attr('checked')) {
			// 		flag = true;
			// 	}
			// });
			// $('.cf_menu_' + fid).attr('checked', flag);

			// var flag = false;
			// var ffid = $('.cf_menu_' + fid).attr('data-fid');
			// $('.child_menu_' + ffid).each(function(){
			// 	if ($(this).attr('checked')) {
			// 		flag = true;
			// 	}
			// });
			// $('.menu_' + ffid).attr('checked', flag);
		}
	});
});

function submitCallBack(info){
		window.top.msg(1,info,true);
	  top.art.dialog({id:"menu"}).close();
}

function check_submit(){
	var account = $('#account').val();
	var pwd = $('#pwd').val();
	var repwd = $('#repwd').val();
	if (account=='') {
		layer.alert('请填写账号');
		return false;
	}
	// if (pwd && pwd!=repwd) {
	// 	layer.alert('两次输入的密码不一样');
	// 	return false;
	// }
}

$('#worker').keyup(function() {
		var search= $.trim(this.value);
		get_worker(search);
	}).blur(function(){
		 $("#searchBox").html("").hide(); //输入框失去焦点的时候就隐藏搜索框
	});

	$('#worker').focus(function() {
		var search= $.trim(this.value);
		get_worker(search);
	})

	$(document).on("click",'.ant-select-dropdown-menu-item',function(e){
        position_id = $(this).val();
        $('#car_position_id').val(position_id);
        var name = $(this).attr('name');
        var phone = $(this).attr('phone');
        $('#account').val(phone);
        $('#phone').val(phone);
        $('#realname').val(name);
        $('#worker').val(name);
        
        $("#dropdown-menu").html("").hide(); //输入框失去焦点的时候就隐藏搜索框

    })
    $(document).bind("click",function(e){
        var target=$(e.target);
        if(target.closest("#dropdown-menu").length==0){
            $("#dropdown-menu").html("").hide();
        }
    })

function get_worker(search){
	if(search!=""){//检测键盘输入的内容是否为空，为空就不发出请求
        $.post("{pigcms{:U('Index/ajax_get_worker')}",{'search':search},function(data){

            if (data && data!=null && data.length> 0) {//检测返回的结果是否为空
                var str = '<div><ul class="findtype-menu" style="    overflow-y: auto;max-height: 400px;">';
                for(var i in data){ 
                    str += '<li class="ant-select-dropdown-menu-item " value="'+data[i]["wid"]+'" phone="'+data[i]["phone"]+'" name="'+data[i]["name"]+'">'+data[i]["name"]+'|'+data[i]["phone"]+'</li>';
                }; 

                str+="</ul></div>";  
				console.log(str);
                $("#dropdown-menu").html(str).show();//将搜索到的结果展示出来
            } else {
                $("#dropdown-menu").html("").hide();
            }  
        },'json');
    }else{
       $("#dropdown-menu").html("").hide();  //没有查询结果就隐藏搜索框
    }
}
</script>

<include file="Public:footer"/>