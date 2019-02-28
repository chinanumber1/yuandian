$(function(){
    hotList();
});

function hotList()
{
    $.post('/index.php?g=Mall&c=Index&a=hotList', function(response){
        if (response.error == 0) {
            laytpl($('#mallCatBoxTpl').html()).render(response.data, function (html) {
                $('#contentList').html(html);
            });
            $('.variousClassHeader ul li').click(function(e){
                $(this).addClass('active').siblings().removeClass('active');
                mallGoods($(this).data('id'));
            });
        }
    }, 'json');
}
function mallGoods(catid)
{
    $.post('/index.php?g=Mall&c=Index&a=mallGoods', {'cateid':catid}, function(response){
        if(response.total > 0){
            laytpl($('#mallListBoxTpl').html()).render(response.goods_list, function (html) {
                $('#goods_' + response.fid).html(html);
            });
        } else {
            $('#goods_' + response.fid).html('');
        }
    }, 'json');
}