<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('Unit/index')}">物业管理</a>
            </li>
            <li class="active">车辆管理</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <style>
                .ace-file-input a {display:none;}
                .btn-success{
                    margin-left:13px;
                }
                .select{
                    width:65px;
                    height: 30px;
                    margin-left:20px;
                }
                .input-text{
                    margin-top:15px;
                }
            </style>
            <div class="row">
            <span><button class="btn btn-success" type="button" onclick="location.href='{pigcms{:U('vehicle_add')}'" <if condition="!in_array(56,$house_session['menus'])">disabled="disabled"</if>>添加</button></span>
            <span><button class="btn btn-success" type="button" onclick="location.href='{pigcms{:U('vehicle_import_add')}'" <if condition="!in_array(56,$house_session['menus'])">disabled="disabled"</if>>导入</button></span>
            <span><button class="btn btn-success" type="button" onclick="location.href='{pigcms{:U('vehicle_export',$_GET)}'" <if condition="!in_array(263,$house_session['menus'])">disabled="disabled"</if>>EXCEL导出</button></span>
			<table class="search_table" width="100%" style="margin-left: 15px;">
				<tr>
					<td>
						<form action="{pigcms{:U('vehicle_management')}" method="get">
							<input type="hidden" name="c" value="Unit"/>
							<input type="hidden" name="a" value="vehicle_management"/>
                             <select name="search_type" style="width:100px;">
                                <option value="1" <if condition="$_GET['search_type'] eq '1'">selected</if>>车牌号</option>                                
                                <option value="2" <if condition="$_GET['search_type'] eq '2'">selected</if>>停车卡号</option>                                
                                <option value="3" <if condition="$_GET['search_type'] eq '3'">selected</if>>车位号</option>                                
                                <option value="4" <if condition="$_GET['search_type'] eq '4'">selected</if>>车主姓名</option>                                
                                <option value="5" <if condition="$_GET['search_type'] eq '5'">selected</if>>车主手机号</option>                                
                             </select>
                             <input type="text"  class="input-text" name="search_value" value="{pigcms{$_GET['search_value']}" />
<!-- 
							停车卡号: <input type="text"  class="input-text" name="car_stop_num" placeholder="{pigcms{$_GET['keyword']}" />
                            车牌号: <input type="text"  class="input-text" name="car_number" placeholder="{pigcms{$_GET['keyword']}" />
                            车主姓名: <input type="text"  class="input-text" name="car_user_name" placeholder="{pigcms{$_GET['keyword']}" />
                            车主手机号: <input type="text"  class="input-text" name="car_user_phone" placeholder="{pigcms{$_GET['keyword']}" />
                            车位号: <input type="text"  class="input-text" name="position_num" value="{pigcms{$_GET['keyword']}" /> -->
                           <!--  车位状态: <select name="status" style="width:100px;">
                                        <option value="1">自用</option>                                
                                        <option value="2">空闲</option>                                
                                     </select> -->
							<!-- 时间筛选：
							<input type="text" name="begin_time" class="input-text" value="{pigcms{$_GET['begin_time']}"  style="height:42px" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="开始时间"/>-
							<input type="text" name="end_time" class="input-text" value="{pigcms{$_GET['end_time']}"  style="height:42px" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="结束时间"/> -->
							<button class="select" type="submit">查询</button>
						</form>
					</td>
				</tr>
			</table>
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="3%"><input type="checkbox" id="select_all">全选</th>
                                    <th width="6%">编号</th>
                                    <th width="5%">车位号</th>
                                    <th width="10%">车牌号码</th>
                                    <th width="5%">停车卡号</th>
                                    <th width="5%">车主姓名</th>
                                    <th width="5%">车主手机号</th>
                                    <th width="8%">车辆排量(升)</th>
                                    <th width="15%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$info_list['info_list']">
                                    <volist name="info_list['info_list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><input type="checkbox" name="is_check" value="{pigcms{$vo.car_id}"></td>
                                            <td>{pigcms{$vo.car_id}</td>
                                            <td>{pigcms{$vo.position_num}</td>
                                            <td>{pigcms{$vo.province}{pigcms{$vo.car_number}</td>
                                            <td>{pigcms{$vo.car_stop_num}</td>
                                            <td>{pigcms{$vo.car_user_name}</td>
                                            <td>{pigcms{$vo.car_user_phone}</td>
                                            <td>{pigcms{$vo.car_displacement}</td>
											<td>
                                                <a class="label label-sm label-info" title="编辑" href="{pigcms{:U('vehicle_edit',array('car_id'=>$vo[car_id]))}">编辑</a>
                                                <if condition="in_array(255,$house_session['menus'])">
                                                &nbsp;<a class="label label-sm label-info bind_user" title="绑定住户" href="javascript:void(0)" value="{pigcms{$vo.car_id}">绑定住户</a>
                                                </if>
                                                <if condition="in_array(59,$house_session['menus'])">
                                                &nbsp;<a class="label label-sm label-info" title="查看绑定住户" href="{pigcms{:U('vehicle_detail',array('car_id'=>$vo[car_id]))}">查看绑定住户</a>
                                                </if>
                                                <if condition="in_array(58,$house_session['menus'])">
                                                &nbsp;<a class="label label-sm label-info" title="打印" href="javascript:void(0)" onclick="one_del({pigcms{$vo.car_id},this)">删除</a>
                                                </if>
                                            </td>
                                        </tr>
                                    </volist>
                                <else />
                                    <tr class="odd"><td class="button-column" colspan="15" >没有任何数据。</td></tr>
								</if>
                            </tbody>
                        </table>
                        <if condition="in_array(58,$house_session['menus'])">
                        <div>
                            <input type="button" value="删除" class="btn" id="getValue">
                        </div>
                        </if>
                        {pigcms{$info_list.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="font-size: 30px; padding: 15px;"></div>
<style type="text/css">
    #searchBox{
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
    #searchBox .ant-select-dropdown-menu-item-active {
        background-color: #e6f7ff;
    }
    #searchBox .ant-select-dropdown-menu-item {
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
    #searchBox .ant-select-dropdown-menu-item{line-height:35px;list-style: none;border:none !important;}
    #searchBox .ant-select-dropdown-menu-item:hover {
        background-color: #e6f7ff !important;
    }

    #searchBox .ant-select-dropdown-menu-item:first-child {
        border-radius: 4px 4px 0 0;
    }

    #searchBox .ant-select-dropdown-menu-item:last-child {
        border-radius: 0 0 4px 4px
    }

</style>
<script type="text/javascript">
    $("#select_all").click(function () { 
        $("input[name='is_check']").each(function () {
            this.checked=!this.checked;
        });
    });

    function one_del(id,obj){
        if (!id) {
            layer.msg('参数传递错误!');
        }
        layer.confirm('确认删除信息？', {
          btn: ['确定','取消'] //按钮
        }, function(){
            var del_url = "{pigcms{:U('car_del')}";
            $.post(del_url,{'car_id':id},function(data){
                if(data.code == 1){
                    layer.msg(data.msg,{icon: 1},function(){
                        location.reload();
                    });
                }
                if(data.code == 2){
                    layer.msg(data.msg,{icon: 2},function(){
                        location.reload();
                    });
                }
            },'json');
        }, function(){
          
        });
    }

    $("#getValue").click(function () { 
        var car_id = '';
        $("input[name='is_check']").each(function () {
            if($(this).attr("checked")){
                car_id += $(this).val()+',';
            }
        });

        car_id=car_id.substring(0,car_id.length-1);

        if(!car_id){
            layer.msg('请选择您要删除的信息。');
            return false;
        }

        layer.confirm('确认删除选中的信息？', {
          btn: ['确定','取消'] //按钮
        }, function(){
            var del_url = "{pigcms{:U('car_del')}";
            $.post(del_url,{'car_id':car_id},function(data){
                if(data.code == 1){
                    layer.msg(data.msg,{icon: 1},function(){
                        location.reload();
                    });
                }
                if(data.code == 2){
                    layer.msg(data.msg,{icon: 2});
                }
            },'json');
        }, function(){
          
        });
    });
    

    var arr = new Array();
    var res;
    $('.bind_user').click(function(){
        var car_id = $(this).attr('value');
        arr = [];
        layer.prompt({
            type: 1,
            title:'绑定住户',
            skin: 'layui-layer', //加上边框
            area: ['650px', '350px'], //宽高
            btn: ['确认', '取消'],
            content: '<div style="position: relative;margin-top:10px">\
                        <label class="col-sm-2" style="margin-left:130px;margin-top:80px;"><label for="select_user">选择业主</label></label>\
                        <div class="col-sm-5" style="padding:0px ;position:relative;margin-top:80px;">\
                            <input placeholder="输入手机号查询要绑定的业主，可多选" name="find_value" id="find_value" type="text" autocomplete="off" style="width:100%;"/>\
                            <div id="searchBox" class="dropdown-menus" ></div>\
                        </div>\
                    </div>\
                    <div class="selected" style="position: relative;margin-top:10px;display:none;">\
                        <label class="col-sm-2" style="margin-left:130px;margin-top: 10px;"><label for="select_user">已选业主</label></label>\
                    </div>\
                    <div  id="float_right_div" style=" padding-top: 10px; margin-bottom: 4px;float:left;"></div>'
            ,yes: function(){
                if(!arr || arr.length == 0 ){
                    layer.msg('数据获取错误!',{icon: 0});
                    return;
                }
                $.post("{pigcms{:U('vehicle_management')}",{'position_bind_user_ids':res,'car_id':car_id},function(data){
                    if(data.code == 1){
                        layer.msg(data.msg,{icon: 1},function(){
                            location.reload();
                        });
                    }
                    if(data.code == 2){
                        layer.msg(data.msg,{icon: 2});
                    }
                },'json');
            }
        }); 
    })
    $(function(){
        $('#find_value').live('keyup',function(){
            var find_type  = '2';//请求类型2 手机号
            var find_value= $.trim(this.value);  
            if(find_value!=""){//检测键盘输入的内容是否为空，为空就不发出请求
                $.post("{pigcms{:U('Cashier/ajax_user_list')}",{'find_type':find_type,'find_value':find_value,type:'true'},function(data){
                    if (data.user_list && data.user_list!=null && data.user_list.length> 0) {//检测返回的结果是否为空
                        var str = '<div><ul class="findtype-menu"  style="    overflow-y: auto;max-height: 255px; ">';
                        for(var i in data['user_list']){
                            var id=data["user_list"][i]["pigcms_id"];
                            var final= $.inArray(parseInt(id),arr);
                            str += '<li class="ant-select-dropdown-menu-item " value="'+data["user_list"][i]["pigcms_id"]+'" phone="'+data["user_list"][i]["phone"]+'">'+data["user_list"][i]["name"];
                            if(final >= 0){
                                str+='<span style="float:right;">已选</span></li>';
                            }else{
                                str+='</li>';
                            }
                        }; 

                        str+="</ul></div>";  
  
                        $("#searchBox").html(str).show();//将搜索到的结果展示出来

                    } else {
                        $("#searchBox").html("").hide();
                    }  
                },'json');
            }else{
                $("#searchBox").html("").hide();  //没有查询结果就隐藏搜索框
            }  
        });

        $('#find_value').live('focus',function(){
            var find_type  = '2';//请求类型2 手机号
            var find_value= $.trim(this.value);  
            if(find_value!=""){//检测键盘输入的内容是否为空，为空就不发出请求
                $.post("{pigcms{:U('Cashier/ajax_user_list')}",{'find_type':find_type,'find_value':find_value,type:'true'},function(data){
                    if (data.user_list && data.user_list!=null && data.user_list.length> 0) {//检测返回的结果是否为空
                        var str = '<div><ul class="findtype-menu"  style="    overflow-y: auto;max-height: 255px; ">';
                        for(var i in data['user_list']){
                            var id=data["user_list"][i]["pigcms_id"];
                            var final= $.inArray(parseInt(id),arr);
                            str += '<li class="ant-select-dropdown-menu-item " value="'+data["user_list"][i]["pigcms_id"]+'" phone="'+data["user_list"][i]["phone"]+'">'+data["user_list"][i]["name"];
                            if(final >= 0){
                                str+='<span style="float:right;">已选</span></li>';
                            }else{
                                str+='</li>';
                            }
                        }; 

                        str+="</ul></div>";  
  
                        $("#searchBox").html(str).show();//将搜索到的结果展示出来

                    } else {
                        $("#searchBox").html("").hide();
                    }  
                },'json');
            }else{
                $("#searchBox").html("").hide();  //没有查询结果就隐藏搜索框
            }  
        });
        
        $(document).on("click",'.ant-select-dropdown-menu-item',function(e){
            var pigcms_id = $(this).val();
            var name = $(this).text();
            var phone = $(this).attr('phone');
            var result = $.inArray(pigcms_id,arr);
            if(result<'0'){
                arr.push(pigcms_id);
                res = JSON.stringify(arr);
                $('#float_right_div').append('<div style="margin-bottom: 3px;">'+phone+'[<font style="color:#2b7dbc">'+name+'</font>]<span style="padding-left:20px" onclick="_remove(this,'+pigcms_id+')"><a href="javascript:void(0)" style="color:red;">删除</a></span><br/></div>');
                $("#searchBox").html("").hide(); //输入框失去焦点的时候就隐藏搜索框
                $(".selected").show();            
            }
            

        })
        $(document).bind("click",function(e){
            var target=$(e.target);
            if(target.closest("#dropdown-menu").length==0){
                $("#searchBox").html("").hide();
            }
        })

        
    });
        //移除div
        function _remove(obj,id){
            arr.splice($.inArray(id,arr),1);
            res='';
            res = JSON.stringify(arr);
            $(obj).parent().remove();
        }
</script>
<include file="Public:footer"/>
