<!DOCTYPE html>
<html lang="en">
<head>
    <title>选择类别</title>
<include file="Public:classify_header" />
<div class="scrollFbBtn">
    <a href="{pigcms{:U('Classify/myfabu',array('uid'=>$uid))}">
        <i class="fa fa-paper-plane-o"></i>已发布
    </a>
</div>

<div class="scrollTags">
</div>
<div class="barNav fl" id="scrollBar">
    <div id="scroller1">
	 <if condition="!empty($Zcategorys)">
        <ul>
		<volist name="Zcategorys" id="zv">
            <li <if condition="$_GET['cid'] eq $zv['cid']">class="on"</if>><a href="javascript:void(0)"> <h2><if condition="$zv['cat_pic']"><i><img src="{pigcms{$config.site_url}/upload/system/{pigcms{$zv['cat_pic']}"></i></if><span>{pigcms{$zv['cat_name']}</span></h2></a></li>
		</volist>
        </ul>
	</if>
    </div>
</div>

<div class="tagsSection pa">
<if condition="!empty($Zcategorys)">
		<volist name="Zcategorys" id="zv">
    <div class="tagsRow" <if condition="$_GET['cid'] eq $zv['cid']">style="display:block"</if>>
	
			<if condition="!empty($zv['subdir'])">
				<div class="content-padded">
					<div class="row">
						<volist name="zv['subdir']" id="sv">
							<a class="col-50" href="{pigcms{:U('Classify/fabu',array('cid'=>$sv['cid'],'fcid'=>$sv['fcid'],'pfcid'=>$sv['pfcid']))}">{pigcms{$sv['cat_name']}</a>
						</volist>
					</div>
				</div>
			</if>
		
    </div>
	</volist>
	</if>
</div>

<section class="ftHeight"></section>

<include file="Public:classify_footer" />
<script type="text/javascript">
    $(function(){
   //    new IScroll('#scrollBar', { mouseWheel: true });
        var h=$(window).height()-$(".scrollFbBtn").height()-$(".ftHeight").height();
        var leftW=$("#scrollBar").width();
//        alert(h);
        $(".scrollTags .barNav,.tagsSection").height(h);

        $("#scroller1").height($("#scroller1 ul").height());

        $(".tagsSection").width($(window).width()-leftW);
        tab("#scroller1 li",".tagsSection .tagsRow","on");
    });

    var tab=function(a,b,c){
            var len = $(a);
            len.bind("click", function() {
                var index = 0;
                index = len.index(this);
                $(b).eq(index).show().siblings().hide();
                return false
            })

    }


</script>

</body>
</html>