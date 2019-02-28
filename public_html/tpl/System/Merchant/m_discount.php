<include file="Public:header"/>
	<form id="myform" frame="true"  refresh="true">
		<input type="hidden" name="mer_id" value="{pigcms{$merchant.mer_id}"/>

		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" align="center">
            <tr>
                <th>桌台</th>
                <th>折扣率</th>
                <th>抽成比例</th>
            </tr>
            <tr>
                <td>其他桌台：</td>
                <td><input type="text"  value="{pigcms{$merchant['other_discount']}" name="other_discount"/></td>
                <td><input type="text" value="{pigcms{$merchant['other_scale']}" name="other_scale"/></td>
            </tr>
                <volist name="merchant['tables']" id="row">
                    <tr class="tables">
                        <td>第<input type="text" value="{pigcms{$row['0']}" name="tables[]" readonly style="width:20px;text-align: center;"/>桌：</td>
                        <td><input type="text"  value="{pigcms{$row['1']}" name="table_discount[]"/></td>
                        <td><input type="text" value="{pigcms{$row['2']}" name="table_scale[]"/><a href="javascript:;" class="box_del"> 删除</a></td>
                    </tr>
                </volist>
            <input type="hidden" id="goon"/>
		</table>

        <p style="width:100%;text-align:center;">
            <a class="add_spec" style="border:1px solid gray;border-radius:5px;width:100px;padding: 5px;" href="javascript:;" title="添加" class="btn btn-sm btn-success">添加桌位优惠+</a>
        </p>

        <div class="btn">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
		</div>
	</form>
<style>
.frame_form td label{
	margin: 5px;
    display: inline-block;
}
.add_spec:hover{
    color: red;
    border-color: red !important;
}
</style>


<script type="text/javascript">
$(document).ready(function() {

    $('#dosubmit').click(function () {
        $.post('/admin.php?g=System&c=Merchant&a=savem_discount', $('#myform').serialize(), function (data) {
            if (data.status == 1) {
                console.log(data);
                window.top.msg(2, data.info, true, 2);
            } else {
                window.top.msg(0, data.info, true, 2);
            }
            window.top.art.dialog({id:'m_discount'}).close();
        });
    });
});

$(".add_spec").click(function(){
    var i = $('.tables').size();
    i++;
    var t = '<tr class="tables"><td>第<input type="text" value="'+i+'" name="tables[]" readonly style="width:20px;text-align: center"/>桌</td>' +'<td><input type="text" name="table_discount[]" /></td>'+
        '<td><input type="text" name="table_scale[]" />'+'<a href="javascript:;" class="box_del"> 删除</a></td></tr>';
    $("#goon").before(t);
});

$(document).on('click', '.box_del', function(){
    var now_num = ($(this).parent().siblings('td').eq(0).find('input:first').val());
    var k = $('.tables').size();
    var index = now_num-1;
    $('.tables').eq(index).remove();
    for(var ii=index;ii<k;ii++){
        $('.tables').eq(ii).find('input:first').val(ii+1);
    }
});
</script>
<include file="Public:footer"/>