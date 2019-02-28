<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('service_info')}">便民服务</a>
			</li>
			<li class="active">便民列表</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
        <div class="page-content-area"> 
        	
            <div class="row"> 
                
                <button class="btn btn-success" style="margin-bottom: 10px" onclick="location.href='{pigcms{:U('service_info_add')}'" <if condition="!in_array(173,$house_session['menus'])">disabled="disabled"</if>>添加信息</button> 
                <table class="search_table" width="100%">
                    <tr>
                        <td>
                            <form action="{pigcms{:U('service_info')}" method="get">
                                <input type="hidden" name="c" value="Service"/>
                                <input type="hidden" name="a" value="service_info"/>
                                
                                标题: <input type="text" name="title" class="input-text" value="{pigcms{$_GET['title']}"  style="height:42px"/>&nbsp;&nbsp;
                                分类: 
                                <select name="cate" id="cate" style="height:42px">
                                    <option value="0" <if condition="$_GET['cate'] eq '0'">selected="selected"</if>>所有</option>
                                    <volist name="cate_list" id="vo">
                                    <option value="{pigcms{$key}" <if condition="$_GET['cate'] eq $key">selected="selected"</if>>{pigcms{$vo}</option> 
                                    </volist>
                                </select>
                                <select name="subcate" id="subcate" style="height:42px">
                                    <option value="0" <if condition="$_GET['subcate'] eq '0'">selected="selected"</if>>所有</option>
                                </select>
                                状态：
                                <select name="status" style="height:42px">
                                    <option value="-1" <if condition="$_GET['status'] eq '-1'">selected="selected"</if>>所有</option>
                                    <option value="1" <if condition="$_GET['status'] eq '1'">selected="selected"</if>>开启</option> 
                                    <option value="0" <if condition="$_GET['status'] eq '0'">selected="selected"</if>>关闭</option>
                                </select>&nbsp;&nbsp;
                                <button class="btn btn-success" type="submit">查询</button>&nbsp;&nbsp;
                                <button class="btn btn-success" type="button" onclick="location.href='{pigcms{:U('service_info')}'">重置</button>&nbsp;&nbsp;
                            </form>
                        </td>
                    </tr>
                </table>
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="20%">标题</th>
                                    <th width="20%">分类</th>
                                    <th width="20%">链接</th>
                                    <th width="15%">状态</th>
                                    <th class="button-column" width="15%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$list['list']">
                                    <volist name="list['list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.id}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.title}</div></td>
                                           	<td><div class="tagDiv">{pigcms{$vo.cat_name}</div></td>
                                            <td><div class="tagDiv"><a href="{pigcms{$vo.url}" target="_blank">查看链接</a></div></td>
                                            <td><div class="tagDiv">
                                           		<if condition='$vo["status"] eq 0'>
                                                	<div class="tagDiv red">关闭</div>
                                                <else />
                                                	<div class="tagDiv green">开启</div>
                                                </if>
                                            </div></td>
                                            
                                            <td class="button-column">
                                            <!--<a style="width: 60px;" class="label label-sm label-info handle_btn" title="详情" href="{pigcms{:U('service_info_detail',array('id'=>$vo['id']))}">详情</a>-->
                                           <a style="width: 60px;" class="label label-sm label-info" title="修改" href="{pigcms{:U('service_info_edit',array('id'=>$vo['id']))}">修改</a>&nbsp;
                                            <if condition="in_array(175,$house_session['menus'])">
                                                <a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onClick="if(confirm('确认删除该条信息？')){location.href='{pigcms{:U('service_info_del',array('id'=>$vo['id']))}'}">删除</a>
                                            </if>
                                           </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="8" >没有任何信息。</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$list.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
$('.handle_btn').live('click',function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'信息详情',
				padding: 0,
				width: 720,
				height: 520,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: null,
				left: '50%',
				top: '38.2%',
				opacity:'0.4'
			});
			return false;
		});
    
    var cat_id = $('#cate').val();
    get_son_category(cat_id);

    $('#cate').change(function(){
        
        var cat_id = $(this).val();
        if(!cat_id){
            return;
        }
        get_son_category(cat_id);
        
    });
    
    function get_son_category(cat_id){
        var url = "{pigcms{:U(ajax_get_category)}";
        $.post(url,{'cat_id':cat_id},function(result){
            var html = '<option value="0">所有</option>';
            if(result['status'] == 1){
                var cat_list = result.cat_list;
                for(var i in cat_list){
                    if (cat_list[i]['id'] == '{pigcms{$_GET['subcate']}' ) {
                        html +='<option value="'+ cat_list[i]['id'] +'" data-url="'+cat_list[i]['cat_url']+'" selected=selected>' + cat_list[i]['cat_name'] + '</option>';  
                    }else{
                        html +='<option value="'+ cat_list[i]['id'] +'" data-url="'+cat_list[i]['cat_url']+'">' + cat_list[i]['cat_name'] + '</option>';  
                    }
                }
                $('#subcate').html(html);
            } else {  
                $("#subcate").html(html);
            }
        },'json');
    }
		
</script>
<include file="Public:footer"/>