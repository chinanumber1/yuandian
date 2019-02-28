<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('Unit/parking_management')}">车位管理</a>
            </li>
            <li class="active">车库管理</li>
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
            
            <span><button class="btn btn-success" type="button" onclick="location.href='{pigcms{:U('garage_add',$_GET)}'" <if condition="!in_array(52,$house_session['menus'])">disabled="disabled"</if>>增加车库</button></span>
			
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="1%"><input type="checkbox" id="select_all">全选</th>
                                    <th width="5%">车库名称</th>
                                    <th width="10%">车库地址</th>
                                    <th width="5%">备注</th>
                                    <th width="5%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$info_list">
                                    <volist name="info_list['info_list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><input type="checkbox" name="is_check" value="{pigcms{$vo.garage_id}"></td>
                                            <td>{pigcms{$vo.garage_num}</td>
                                            <td>{pigcms{$vo.garage_position}</td>
                                            <td>{pigcms{$vo.garage_remark}</td>
											<td>
                                                <a href="{pigcms{:U('garage_edit',array('garage_id'=>$vo[garage_id]))}">修改</a>
                                                <if condition="in_array(54,$house_session['menus'])">
                                                |<a href="javascript:void(0)" onclick="one_del({pigcms{$vo.garage_id},this)">删除</a>
                                                </if>
                                            </td>
                                        </tr>
                                    </volist>
                                <else />
                                    <tr class="odd"><td class="button-column" colspan="15" >没有任何数据。</td></tr>
								</if>
                            </tbody>
                        </table>
                        <div>
                            <if condition="in_array(54,$house_session['menus'])">
                            <input type="button" value="删除" class="btn" id="getValue">
                            </if>
                        </div>
                        {pigcms{$info_list.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="font-size: 30px; padding: 15px;"></div>
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
            var del_url = "{pigcms{:U('garage_del')}";
            $.post(del_url,{'garage_id':id},function(data){
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
        var garage_id = '';
        $("input[name='is_check']").each(function () {
            if($(this).attr("checked")){
                garage_id += $(this).val()+',';
            }
        });

        garage_id=garage_id.substring(0,garage_id.length-1);

        if(!garage_id){
            layer.msg('请选择您要删除的信息。');
            return false;
        }

        layer.confirm('确认删除选中的信息？', {
          btn: ['确定','取消'] //按钮
        }, function(){
            var del_url = "{pigcms{:U('garage_del')}";
            $.post(del_url,{'garage_id':garage_id},function(data){
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
    });


    
</script>
<include file="Public:footer"/>
