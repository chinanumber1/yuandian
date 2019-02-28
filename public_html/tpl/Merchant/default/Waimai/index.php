<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-cutlery"></i>
                <a href="{pigcms{:U('Waimai/index')}">{pigcms{$config.meal_waimai_name}管理</a>
            </li>
            <li class="active">店铺列表</li>
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
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">编号</th>
                                    <th width="5%">排序</th>
                                    <th width="10%">店铺名称</th>
                                    <th width="5%">联系电话</th>
                                    <th>店铺地址</th>
                                    <th class="button-column" width="12%">二维码</th>
                                    <th class="button-column" width="12%">订单</th>
                                    <th class="button-column" width="10%">优惠设置</th>
                                    <th class="button-column" width="10%">商品设置</th>
                                    <th class="button-column" width="10%">店铺设置</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$store_list">
                                    <volist name="store_list" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.store_id}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.sort}</div></td>
                                            <td><div class="shopNameDiv"><a target="_blank" href="{pigcms{$config.site_url}/index.php?g=Waimai&c=Store&a=shop&store_id={pigcms{$vo.store_id}">{pigcms{$vo.name}</a></div></td>
                                            <td>{pigcms{$vo.phone}</td>
                                            <td>{pigcms{$vo.area_name} - {pigcms{$vo.adress}</td>
                                            <td class="button-column">
                                                <a href="{pigcms{$config.site_url}/index.php?g=Index&c=Recognition&a=see_qrcode&type=waimai&id={pigcms{$vo['store_id']}" class="see_qrcode">查看</a>
                                            </td>
                                            <td class="button-column">
                                                <a style="width:120px;" title="查看店铺订单" href="{pigcms{:U('Waimai/order',array('store_id'=>$vo['store_id']))}">查看订单</a>
                                            </td>
                                            <td class="button-column">
                                                <a style="width:80px;" class="label label-sm label-info" title="{pigcms{$config.meal_alias_name}优惠设置" href="{pigcms{:U('Waimai/discount_setting',array('store_id'=>$vo['store_id']))}">优惠设置</a>
                                            </td>
                                            <td class="button-column">
                                                <a style="width: 60px;" class="label label-sm label-info" title="商品设置" href="{pigcms{:U('Waimai/goods',array('store_id'=>$vo['store_id']))}">商品设置</a>
                                            </td>
                                            <td class="button-column">
                                                <a style="width: 60px;" class="label label-sm label-info" title="店铺设置" href="{pigcms{:U('Waimai/store',array('store_id'=>$vo['store_id']))}">店铺设置</a>
                                            </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="9" >您没有添加店铺，或店铺没开启{pigcms{$config.waimai_alias_name}功能，或店铺正在审核中。</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){
    /*店铺状态*/
    updateStatus(".statusSwitch .ace-switch", ".statusSwitch", "OPEN", "CLOSED", "shopstatus");
    
    jQuery(document).on('click','#shopList a.red',function(){
        if(!confirm('确定要删除这条数据吗?不可恢复。')) return false;
    });
});
function CreateShop(){
    window.location.href = "{pigcms{:U('Config/store_add')}";
}
function updateStatus(dom1, dom2, status1, status2, attribute){
    $(dom1).each(function(){
        if($(this).attr("data-status")==status1){
            $(this).attr("checked",true);
        }else{
            $(this).attr("checked",false);
        }
        $(dom2).show();
    }).click(function(){
        var _this = $(this),
            type = 'open',
            id = $(this).attr("data-id");
        _this.attr("disabled",true);
        if(_this.attr("checked")){  //开启
            type = 'open';
        }else{      //关闭
            type = 'close';
        }
        $.ajax({
            url:"{pigcms{:U('Config/store_status')}",
            type:"post",
            data:{"type":type,"id":id,"status1":status1,"status2":status2,"attribute":attribute},
            dataType:"text",
            success:function(d){
                if(!d){     //失败
                    if(type=='open'){
                        _this.attr("checked",false);
                    }else{
                        _this.attr("checked",true);
                    }
                    bootbox.alert("操作失败");
                }
                _this.attr("disabled",false);
            }
        });
    });
}
</script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
    $(function(){
        $('.see_qrcode').click(function(){
            art.dialog.open($(this).attr('href'),{
                init: function(){
                    var iframe = this.iframe.contentWindow;
                    window.top.art.dialog.data('iframe_handle',iframe);
                },
                id: 'handle',
                title:'查看渠道二维码',
                padding: 0,
                width: 430,
                height: 433,
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
    });
</script>
<include file="Public:footer"/>
