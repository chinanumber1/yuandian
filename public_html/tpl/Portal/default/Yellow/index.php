<include file="Public/header" />
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/huangye2015.css">

<div class="content w-1200 clearfix">
        </div>
		<div class="banner_bg">
			<div class="w-1200 po_re">
				<div class="search clearfix">
					<div action="?" method="get" id="myform">
						<input type="hidden" name="mySle2" value="0" />
						<input class="s_ipt" type="text" id="_key" value="{pigcms{$title}" placeholder="输入您要查找的服务机构关键字" />
						<button class="s_btn" onclick="txt_search()" >搜索</button>
					</div>
				</div>
				<div class="search2"><a href="/index.php?g=User&c=Yellow&a=index" target="_blank">申请加入</a></div>
			</div>
		</div>

	<div class="w-1200">
    <div class="clearfix">
		<div class="hy_col_sub">
			<div id="fixed">
				<div class="list_nav_2017" id="list_nav_2013">
					<ul>
						<li class="all"><a href="{pigcms{:U('Yellow/index')}" class="t">全部服务机构</a></li>
						<if condition="$all_category_list neq ''">
						<volist name="all_category_list" id="vo">
						<li class="item <?=$vo['cat_id']==$pid?'open_foreven':'';?>" id="item_{pigcms{$vo.cat_id}"><span class="sp"><a href="{pigcms{:U('Yellow/index',array('pid'=>$vo['cat_id']))}">{pigcms{$vo.cat_name}</a><s class="rights"></s></span>
							<ul class="ul">
								<li><a href="{pigcms{:U('Yellow/index',array('pid'=>$vo['cat_id']))}" class="allCat">全部分类</a></li>
								<if condition="$vo['category_list']">
								<volist name="vo['category_list']" id="vv">
								<li <?=$vv['cat_id']==$cid?'class="cur"':'';?>><a href="{pigcms{:U('Yellow/index',array('cid'=>$vv['cat_id'],'parent_id'=>$vo['cat_id']))}"   data-id="{pigcms{$vv.cat_id}" id="s_{pigcms{$vv.cat_id}">{pigcms{$vv.cat_name}</a></li>
								</volist>
								</if>
							</ul>
						</li>
						</volist>
						</if>
					</ul>
				</div>
			</div>
		</div>
		<div class="hy-list-box"  id="fixed_can">
			<div class="hy-area-select">
				<dl class="clearfix">
					<dt>区域：</dt>
					<dd id="dd_area">
					<a href='javascript:;' onclick="area_click(this)" area_id="0" <if condition="$area_id eq 0">class='selected'</if>>全部区域</a>
					<if condition="$area_list">
					<volist name="area_list" id="vo">
					<a href='JavaScript:;' onclick="area_click(this)" area_id="{pigcms{$vo.area_id}" <if condition="$area_id eq $vo['area_id']">class="selected"</if> >{pigcms{$vo.area_name}</a>
					</volist>
					</if>
					</dd>
				</dl>
			</div>
			<div class="title clearfix">
				<span class="zt">信息主题</span>
				<span class="update">发布时间</span>
				<!--<span class="score">评分</span>-->
			</div>
			<div class="list">
				<ul id="hover_bg">
					<div class="bdK clearfix">
				<ul>
				<if condition="$yellow_list" >
				<volist name="yellow_list" id="vo">
				<li class="item clearfix">
				    <div class="pic">
				        <a href="{pigcms{:U('Yellow/detail',array('yid'=>$vo['id']))}" target="_blank"><img src="{pigcms{$vo.logo}"></a>
				    </div>
				    <div class="con">
				        <div class="tit clearfix">
				            <h3><a href="{pigcms{:U('Yellow/detail',array('yid'=>$vo['id']))}" target="_blank">{pigcms{$vo.title}</a></h3>
				            <span class="sort">【<a target="_blank" href="{pigcms{:U('Yellow/index',array('pid'=>$vo['pid']))}">{pigcms{$vo.parent_cat_name}</a> <a href="{pigcms{:U('Yellow/index',array('cid'=>$vo['cid']))}" target="_blank">{pigcms{$vo.child_cat_name}</a>】</span>
							<i class="zd" style="display:<?=$vo['top_time']?'block':'none';?>">置顶</i>
				        </div>
				        <div class="address">{pigcms{$vo.address}</div>
				    </div>
				    <span class="update">{pigcms{$vo.dateline|date="m-d",###}</span>
					<div class="po clearfix">
						<div class="hymobile" onMouseOver="show(this,1)" onMouseOut="show(this,2)" ><a href="javascript:void(0);">联系</a>
							<div class="pos">{pigcms{$vo.tel}<s class="s"></s></div>
						</div>
						<div class="hymail" onMouseOver="show(this,1)" onMouseOut="show(this,2)"><a href="javascript:void(0);">邮箱</a>
							<div class="pos">{pigcms{$vo.email}<s class="s"></s></div>
						</div>
						<div class="hyweixin" onMouseOver="show(this,1)" onMouseOut="show(this,2)"><a href="javascript:void(0);">微信公众号</a>
							<div class="pos"><img src="{pigcms{$vo.qrcode}"><s class="s"></s></div>
						</div>
					</div>
					<script>
						function show(obj,type){
							if(type == 1){
								$(obj).addClass('open');
							}else{
								$(obj).removeClass('open');
							}
						}
					</script>
				</li>
				</volist>
				</if>
				</ul>
			</div>
				</ul>
			</div>
			<div class="pageNavigation">{pigcms{$pagebar}</div>
		</div>
	</div>
</div>

<include file="Public/footer" />

<script>
$(function() {
    $('#mySle').selectbox();
    $(document).modCity();
    $('#fabu').showMore();
    $('#weixin').showMore();
    $.returnTop();
	//$('#fixed').fixed($('#fixed_can'));
    $('#mySle2').selectbox();
   // $('#list_nav_2013').listNav2();
    $('#mySle2_container').delegate('li','click',function(){
        $(this).html() === '服务机构'?($('#bmsearch').attr('action','index.html')):($('#bmsearch').attr('action','qiyelist.html'));
    });
	$('#hover_bg').find('.item').hover(function(){
		$(this).addClass('hover');
	},function(){
		$(this).removeClass('hover');
	}).end().find('.mobile').hover(function(){
		$(this).toggleClass('open');
	}).end().find('.mail').hover(function(){
		$(this).toggleClass('open');
	}).end().find('.weixin').hover(function(){
		$(this).toggleClass('open');
	});

});
function bianminUrl(){
    var url_obj = {'colname':'0'};
    if(url_obj['colname'] !== '' && url_obj['colname'] !== '0'){
       $('#s_'+url_obj['colname']).addClass('cur').parent().parent().addClass('open_foreven');
	   $('#item_'+url_obj['colname']).addClass('open_foreven');
    }
}
bianminUrl();


// 区域搜索
function area_click(obj){
	$(obj).addClass('selected').siblings('a').removeClass('selected');
	do_search();
}

// 公司名称搜索
function txt_search(){
	do_search();
}

// 执行搜索
function do_search(){
	var search_url = "{pigcms{:U('Yellow/index')}";
	var area_id = $('#dd_area').children('a[class="selected"]').attr('area_id');
	if(area_id != undefined){
		search_url += '&area_id='+area_id;
	}
	
	var title = $.trim($('#_key').val());
	if(title != ''){
		search_url += '&wd='+title;
	}
	window.location.href = search_url;
}
</script>