<!DOCTYPE html>
<html lang="en">
<head>
    <title>我的收藏</title>
<include file="Public:classify_header" />

<if condition="!empty($listsdatas)">
<div class="scTopBtn">
    <a href="##">编辑</a>
</div>
</if>

<div class="itemList">
<if condition="!empty($listsdatas)">
    <div class="list-block media-list">
        <ul>
			<volist name="listsdatas" id="vl">
				<li>
					<div class="item-link item-content">
						<div class="check fl collect-list">
							<input type="checkbox" name="items" value="{pigcms{$vl['id']}"/>
						</div>
						<div class="item-inner" onclick="location.href='{pigcms{:U('Classify/ShowDetail',array('vid'=>$vl['id']))}'">
							<div class="item-title-row">
								<div class="item-title">{pigcms{$vl['title']}</div>
							</div>
							
							<if condition='$vl["is_assure"] == 0'>
								<div class="item-subtitle">{pigcms{$vl['input1']}</div>
								<div class="item-subtitle">{pigcms{$vl['input2']}</div>
							<else />
								<div class="item-subtitle">担保支付</div>
							</if>
							<div class="item-texts"><span class="fr">{pigcms{$vl['timestr']}</span></div>
						</div>
					</div>
				</li>
			</volist>
        </ul>
    </div>
<else />
	<div class="list-block media-list"><p style="text-align:center; margin-top:1rem">暂无收藏</p></div>
</if>
</div>

<if condition="!empty($listsdatas)">
<section class="ftHeight"></section>
<section class="detailsFt scFTbtn">
    <label class="checkAll" for="checkAll"><input id="checkAll" type="checkbox">全选</label>
    <a href="javascript:void(0)" onclick="del_collect()" class="btn3"><i class="fa fa-trash-o"></i>删除</a>
</section>
</if>
<include file="Public:classify_footer" />
<script>
    $(function(){
       $(".scTopBtn a").tap(function(){
         if($(this).hasClass("on")){
             $(this).text("编辑");
             $(".check,.scFTbtn").hide();
             $(this).removeClass("on");
         }else{
             $(this).text("完成");
             $(".check,.scFTbtn").show();
             $(this).addClass("on");
         }
       });

        $('#checkAll').on('change',function (e) {
            e.preventDefault();
            if ($('#checkAll').is(":checked")){
                $('.check input[type=checkbox]').prop('checked',true);
            } else {
                $('.check input[type=checkbox]').prop('checked',false);
            }
        })
    });
	
	function del_collect(){
			var arr = [];
			$('.collect-list input').each(function(){
				if($(this).is(":checked")){
					arr.push($(this).val());
				}
				$.post("{pigcms{:U('Classify/emptyC')}",{'id_arr':arr},function(data){
					if(!data['error']){
						alert('清空成功！');
						location.reload();
					}else{
						alert('清空失败！');
					}
				},'JSON');
			})
		}
</script>
</body>
</html>