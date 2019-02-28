<include file="Public/header" />
<link href="{pigcms{$static_path}css/huangye2015.css" type="text/css" rel="stylesheet" />
<link href="{pigcms{$static_path}css/highslide.css" type="text/css" rel="stylesheet" />
<script>var nowdomain="";var tplPath = '{pigcms{$static_path}images/';</script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=0Rg88PISLHEhyvn6syEzlmGT"></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/TextIconOverlay/1.2/src/TextIconOverlay_min.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/MarkerClusterer/1.2/src/MarkerClusterer_min.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/mustache.js"></script>
<link rel="stylesheet" href="http://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.css" />
<script type="text/javascript" src="//apps.bdimg.com/libs/layer/2.1/layer.js"></script>

<div class="content w-1200 clearfix">
		<input type="hidden" id="yellow_detail_id" value="{pigcms{$detail.id}">
        <div class="crumb-bar clearfix">
            <span>当前位置：</span><a href="/">首页</a> <a href="{pigcms{:U('Yellow/index')}">便民黄页</a><a href="{pigcms{:U('Yellow/index')}">服务机构</a> <span class="cur_tit">{pigcms{$detail.title}</span>
        </div>
        <div class="orange"><!-- orange | blue | green | purple | brown | red -->
        <div class="company-tit clearfix">
            <div class="cname">{pigcms{$detail.title}
			<span class="rz rz_1 ">手机认证</span>
			<span class="rz rz_2 ">邮箱认证</span>
			<span class="rz rz_3 ">身份验证</span>
			<span class="rz rz_4 ">执照验证</span>
			</div>
            
            <ul>
                <li>登记：{pigcms{$detail.dateline|date="Y-m-d",###}</li>
                <li class="line">|</li>
                <li>浏览：{pigcms{$detail.PV}</li>
            </ul>
        </div>
        </div>
        <div class="hy-company-info clearfix">
            <div class="operate clearfix">
                <div class="share">分享到：<div class="bdsharebuttonbox"><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a></div><script>
window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"16"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
</script></div>
                <div class="btn">
                	<a href="#TabAdS02" class="reply" onClick="showpinglun();">评论</a>
                </div>
            </div>
            <div class="con2">
                <dl class="clearfix">
                    <dt><em>机构名称</em>{pigcms{$detail.title}</dt>
                    <dt><em>行业分类</em>{pigcms{$detail.parent_cat_name}&gt;{pigcms{$detail.child_cat_name}</dt>
                    <dt><em>联系电话</em><span class="phone">{pigcms{$detail.tel}</span></dt>
                    <dt><em>电子邮件</em>{pigcms{$detail.email}</dt>
                </dl>
            </div>

			<div style="position: absolute; right: 15px; top: 58px; text-align: center; font-size: 12px; color: #aaa;" class="weixin">微信公众号<br/><img style="width: 100px; height: 100px;" src="{pigcms{$detail.qrcode}" id="weixinImg" /></div>

        </div>
        <div class="hy-content" id="TabAdS02">
            <div class="tabs">
                <ul class="clearfix tab-hd">
	                <li class="select">
                		<a href="javascript:;">服务内容</a>
                	</li>
                	<li>
                		<a href="javascript:;">{pigcms{$custome_info.title1}</a>
                	</li>
                	<li>
                		<a href="javascript:;">{pigcms{$custome_info.title2}</a>
                	</li>
                	<li>
                		<a href="javascript:;">{pigcms{$custome_info.title3}</a>
                	</li>
                	<li>
                		<a href="javascript:;">{pigcms{$custome_info.title4}</a>
                	</li>
                	<li>
                		<a href="javascript:;">{pigcms{$custome_info.title5}</a>
                	</li>
                	<li>
                		<a href="javascript:;">网友评论</a>
                	</li>
                </ul>
            </div>
            <div class="wen_all tab-cont"  >{pigcms{$detail.service|htmlspecialchars_decode}</div>
            <div class="wen_all tab-cont"  >{pigcms{$custome_info.msg1|htmlspecialchars_decode}</div>
            <div class="wen_all tab-cont"  >{pigcms{$custome_info.msg2|htmlspecialchars_decode}</div>
            <div class="wen_all tab-cont"  >{pigcms{$custome_info.msg3|htmlspecialchars_decode}</div>
            <div class="wen_all tab-cont"  >{pigcms{$custome_info.msg4|htmlspecialchars_decode}</div>
            <div class="wen_all tab-cont"  >{pigcms{$custome_info.msg5|htmlspecialchars_decode}</div>

			<div class="wen_all tab-cont" style="display: block;">
				<div class="comment">
					<div class="inner">
						<div class="hds clearfix"><h6 class="left">全部评论</h6><p class="right"><em id="show_total_revert">{pigcms{$recomment_list|count}</em>条评论</p></div>
						<div class="comment_zone" id="showcomment">
							<div id="total_revert" data-num="4">
								<if condition="$recomment_list">
								<volist name="recomment_list" key="k" id="vo">
								<div class="comment_item">
									<div class="comment_face">
										<img src="{pigcms{$vo.avatar}">
									</div>
									<div class="comment_box">
										<div class="comment_user clearfix"><span class="right">{pigcms{$vo.dateline|date="Y-m-d H:i:s",###}</span> [{pigcms{$k}楼] <span class="userName">{pigcms{$vo.nickname}</span></div>
										<p class="comment_content">{pigcms{$vo.msg}</p>
									</div>
								</div>
								</volist>
								</if>

							</div>
							<input type="hidden" id="isuserpinglun" value="1">
						</div>
					</div>
					<div class="write2014">
						<ul id="login_info_cm" class="login_info_cm clearfix"></ul>
						<input type="hidden" id="isrep" value="" />
						<input type="hidden" id="istg" value="" />
						<input type="hidden" id="parentid" value="" />
						<div id="myform">
							<input id="chrmarkForm" name="chrmark" type="hidden" />
							<div class="cmt_txt" id="cmt_txt" contenteditable="true"></div>
							<div class="cmt_control clearfix">
								<div class="left">
									<!--
									<div class="emot po_re">
										<a href="#" onClick="return insertEmot(this,'cmt_txt');" class="emot_btn">插入表情</a>
									</div>
									-->
								</div>
								<div class="right">　<button onclick="save_recomment()" class="cmt_btn">提交</button></div>
								<div class="right">文明上网 礼貌发帖　<span id="cmt_tip">最多200字</span></div>
							</div>
						</div>
					</div>
				</div>
			</div>
            <script type="text/javascript" >
                function ResizeImages()
                {
                   var myimg,oldwidth,oldheight;
                   var maxwidth=1162;
                   var maxheight=1000;
                   var imgs = document.getElementById('TabAdS02').getElementsByTagName('img');   
                   
                   for(i=0;i<imgs.length;i++){
                     myimg = imgs[i];
                
                     if(myimg.width > myimg.height)
                     {
                         if(myimg.width > maxwidth)
                         {
                            oldwidth = myimg.width;
                            myimg.height = myimg.height * (maxwidth/oldwidth);
                            myimg.width = maxwidth;
                            
                         }
                     }else{
                         if(myimg.height > maxheight)
                         {
                            oldheight = myimg.height;
                            myimg.width = myimg.width * (maxheight/oldheight);
                            myimg.height = maxheight;
                            
                         }
                     }
                   }
                }
                ResizeImages();

                // 发表评论
                function save_recomment(){
                	var msg = $('#cmt_txt').text();
                	if(msg == ''){
                		return;
                	}
                	var yellow_detail_id = $('#yellow_detail_id').val();
                	$.post("{pigcms{:U('Yellow/save_recomment')}",{'yellow_detail_id':yellow_detail_id,'msg':msg},function(response){
                		if(response.code>0){
                			layer.alert(response.msg);
                		}else{
                			layer.msg(response.msg);
                			setTimeout(function(){window.location.reload();},1000);
                		}
                	},'json');
                }
                </script>
        </div>
		
		<div class="fang_map clearfix">
		  <div class="tit"><span>周边地图</span></div>
		  <div class="map"><div id="allmap" style="width:830px; height:435px;"></div></div>
		  <div class="zb_info">
			<div class="tabs clearfix">
			  <ul id="mapSel">
				<li class="current" data-val="0"><i class="i_1"></i>交通</li>
				<li data-val="1"><i class="i_2"></i>商业</li>
				<li data-val="2"><i class="i_3"></i>教育</li>
				<li data-val="3"><i class="i_4"></i>医疗</li>
			  </ul>
			</div>
			<!--交通-->
			<div class="con">
			 <ul id="r-result"></ul>
			</div>
		  </div>
		  <!--地址-->
		  <div class="address">地址：{pigcms{$detail.address}</div>
		</div>
</div>

<include file="Public/footer" />
<div id="mask" style="display:none"></div>
<script type="text/template" id="tp">
<li data-x="{{point.lng}}" data-y="{{point.lat}}">
             <dl>
              <dt class="s_title">{{title}}</dt>
              <dd classs="s_distance">{{distance}}</dd>
             </dl>
             <p class="s_address">{{address}}</p>
</li>
</script>
<script src="{pigcms{$static_path}public/js/select.jQuery.js"></script>
<script src="{pigcms{$static_path}public/js/jquery.form.js"></script>
<script src="{pigcms{$static_path}public/js/comments2014.js"></script>
<script src="{pigcms{$static_path}public/js/emotData.js"></script>
<script src="{pigcms{$static_path}public/js/highslide-with-gallery.js"></script>
<script src="{pigcms{$static_path}public/js/jquery.SuperSlide.2.1.1.js"></script>
<script src="{pigcms{$static_path}public/js/fc2015_map.js"></script>
<script>
var nowdomain = '';
window['Default_tplPath'] = "{pigcms{$static_path}images/";

var obj_xyz = {'x':{pigcms{$detail.lng},'y':{pigcms{$detail.lat}};
var icity = '北京';
var zb_style = 0;
var zb_style_arr = [{'key':'公交','img':'gongjiao.gif'},{'key':'超市','img':'chaoshi.gif'},{'key':'学校','img':'xuexiao.gif'},{'key':'医院','img':'yiyuan.gif'}];
var map = new BMap.Map("allmap",{enableMapClick:false}),mPoint;
map.enableScrollWheelZoom();    //启用滚轮放大缩小，默认禁用
map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用
map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
map.addControl(new BMap.MapTypeControl({anchor: BMAP_ANCHOR_TOP_RIGHT}));
if(!obj_xyz.x||!obj_xyz.y||obj_xyz.x==='0'||obj_xyz.y==='0'){
	map.centerAndZoom(icity , 13);
}else{
	mPoint = new BMap.Point(obj_xyz.x,obj_xyz.y);  
	map.centerAndZoom(mPoint, 16);
	var icon = new BMap.Icon("{pigcms{$static_path}images/markerred.gif", new BMap.Size(20, 20),{anchor: new BMap.Size(10, 10)});
	var mkr = new BMap.Marker(mPoint,{icon:icon});
	map.addOverlay(mkr);
	var myLabel = new BMap.Label("{pigcms{$detail.title}",{offset:new BMap.Size(14,-9),position:mPoint});
	myLabel.setStyle({fontSize:"12px",border:"1px solid #36c"});
	map.addOverlay(myLabel);                             //把label添加到地图上
	//检索初始化
	var local = new BMap.LocalSearch(map, options);
	local.searchNearby(zb_style_arr[0].key,mPoint,500);
	$('#mapSel li').click(function(e){
		e.preventDefault();
		$('#r-result').empty();
		$(this).siblings('li').removeClass('current');
		$(this).addClass('current');
		zb_style = parseInt($(this).attr('data-val'));
		local.searchNearby(zb_style_arr[zb_style].key,mPoint,500);
	});
}



$.fn.picsa = function(selector){
	var t = $(this),node = $(selector),imglist = node.find('img'),txt='';
	
	if(imglist.length < 1){return;}
	t.show();
	imglist.each(function(){
		txt+='<li class="item"><a href="'+$(this).attr('src')+'" class="highslide" onclick="return hs.expand(this)"><img src="'+$(this).attr('src')+'" alt="" /></a></li>';
	});
	$('#picsa').html(txt);
	$(this).slide({mainCell:".bd",autoPage:true,effect:"left",autoPlay:false,scroll:1,vis:5,delayTime:500});
	hs.graphicsDir = '{pigcms{$static_path}images/';
	hs.align = 'center';
	hs.transitions = ['expand', 'crossfade'];
	hs.outlineType = 'rounded-white';
	hs.fadeInOut = true;
	hs.addSlideshow({
		interval: 5000,
		repeat: false,
		useControls: true,
		fixedControls: 'fit',
		overlayOptions: {
			opacity: 0.75,
			position: 'bottom center',
			hideOnMouseOut: true
		}
	});
}
$('#picsaWrap').picsa('#TabAdS02');
$(function(){
    $('#mySle').selectbox();
    $(document).modCity();
    $('#fabu').showMore();
    $('#weixin').showMore();
    $.returnTop();
    $("#TabAdS02").TabADS();
	// $("#myform").chackTextarea(600,"cmt_txt","cmt_tip","cmt_btn",postRevertPage);
});
function showpinglun(){
	$("#TabAdS02 .tab-hd li:last-child").trigger('mouseover');
}
function showMap(mapdomid){
	var wrap_node = document.createElement('div');
	wrap_node.className = 'map_iframe';
	wrap_node.id='map_iframe';
	wrap_node.style.display='none';
	
	var myiframe = '<a href="javascript:close_map();" class="close_map">关闭</a><iframe src="'+nowdomain+'ezmarker/map.aspx?action=bm&id='+mapdomid+'" scrolling="no" frameBorder="0" width="700" height="500"></iframe>';
	if(document.getElementById('map_iframe')){
		document.getElementById('map_iframe').style.display='block';
		return false;
	}
	wrap_node.innerHTML=myiframe;
	document.getElementsByTagName('body')[0].appendChild(wrap_node);
	document.getElementById('map_iframe').style.display='block';
	return false;
}
function close_map(){
	document.getElementById('map_iframe').style.display='none';
}
</script>