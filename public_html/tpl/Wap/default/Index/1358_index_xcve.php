<!DOCTYPE html>
<html lang="cn"><head>
<if condition="$zd['status'] eq 1">
            {pigcms{$zd['code']}
        </if>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  	<meta charset="UTF-8">
  	<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
  	<meta name="apple-mobile-web-app-capable" content="yes">
  	<meta name="format-detection" content="telephone=no, email=no">
  	<title>{pigcms{$tpl.wxname}</title>
 <style>
  .spinner {
    width: 32px;
    height: 32px;
    position: absolute;
    z-index: 9998;
  }
  .cube1, .cube2 {
    width: 10px;
    height: 10px;
    position: absolute;
    top: 0;
    left: 0;
    -webkit-animation: cubemove 1.8s infinite ease-in-out;
    animation: cubemove 1.8s infinite ease-in-out;
  }
  .aassdd{
      display: none;
  }

  .cube1 {
    -webkit-animation-delay: -1.8s;
    animation-delay: -1.8s;
  }
  .cube2 {
    -webkit-animation-delay: -0.9s;
    animation-delay: -0.9s;
  }
  @-webkit-keyframes cubemove {
    25% {
      -webkit-transform: translateX(42px) rotate(-90deg) scale(0.5)
    }
    50% {
      -webkit-transform: translateX(42px) translateY(42px) rotate(-180deg)
    }
    75% {
      -webkit-transform: translateX(0px) translateY(42px) rotate(-270deg) scale(0.5)
    }
    100% {
      -webkit-transform: rotate(-360deg)
    }
  }
  @keyframes cubemove {
    25% {
      transform: translateX(42px) rotate(-90deg) scale(0.5);
      -webkit-transform: translateX(42px) rotate(-90deg) scale(0.5);
    }
    50% {
      transform: translateX(42px) translateY(42px) rotate(-179deg);
      -webkit-transform: translateX(42px) translateY(42px) rotate(-179deg);
    }
    50.1% {
      transform: translateX(42px) translateY(42px) rotate(-180deg);
      -webkit-transform: translateX(42px) translateY(42px) rotate(-180deg);
    }
    75% {
      transform: translateX(0px) translateY(42px) rotate(-270deg) scale(0.5);
      -webkit-transform: translateX(0px) translateY(42px) rotate(-270deg) scale(0.5);
    }
    100% {
      transform: rotate(-360deg);
      -webkit-transform: rotate(-360deg);
    }
  }
  .mod-menu {
      position: absolute;
  }
  </style>

  <script>
    var jsTimer = [];
    jsTimer.push(new Date().getTime()/1000); //jsTimer_0
  </script>
</head>

<body style="overflow: hidden; width: 320px; height: 681px; max-height: 681px;">
  <div id="bg_img_data" style="display:none;"><volist name="flashbg" id="so">,{pigcms{$so.img}</volist></div>
  <script type="text/javascript">
		var app = {
		data: {"f_cover_templated_id":"4","f_cover_pic_after":[],"module":[]}  };
		jsTimer.push(new Date().getTime()/1000);
		window.onload=function(){
  			var bg_img = document.getElementById("bg_img_data").innerHTML.replace(/(^\s*)|(\s*$)/g, "").substring(1),bg_img_arr = bg_img.split(','),bg_img_count = bg_img_arr.length,bg_img_rand_n = Math.floor(Math.random()*bg_img_count);
			document.getElementById("bg-img").style.cssText="background-image:url("+bg_img_arr[bg_img_rand_n]+");background-repeat: repeat-x;";
		}
  </script>
  <script src="{pigcms{$static_path}tpl/1358/js/vstar_main_tpl4.js"></script>
<div data-view-cid="view-0">
	<div class="bg-img" id="bg-img" style="width:100%;height:100%;"></div>
</div>
<div class="swiper-container" style="padding-top: 340.5px; overflow: hidden;">
  <div class="swiper-wrapper" style="padding-top: 0px; padding-bottom: 0px; width: 1631.75px;height:100%">
      <div class="arrows"></div>
      <volist name="info" id="vo">
      <if condition="$i lt 31">
      <div class="swiper-slide" data-item="0" data-delay="0" style="-webkit-transform: rotate(15deg) skew(15deg) translate3d(0px, 0px, 0px); opacity: 1;">
        <a style="color:white" href="<if condition="$vo['url'] eq ''">{pigcms{:U('Wap/Index/lists',array('classid'=>$vo['id'],'token'=>$vo['token']))}<else/>{pigcms{$vo.url|htmlspecialchars_decode}</if>">
	        <div class="swiper-item slide-bg" style="background-image: url({pigcms{$vo.img})">
	          <div class="swiper-item-bg"></div>
	           <div class="swiper-item-name" style="left:1%;">
	            <div class="d-left" style="height: 26px; overflow: hidden;">
	              <span class="sub-title">{pigcms{$vo.name}</span>
	            </div>
	            <div class="swiper-item-white d-left" style=""><div class="swiper-item-red"></div></div>
	          </div>
	         <div class="swiper-item-con" style="left: 30px;">{pigcms{$vo.info}</div>
        	</div>
       	</a>
      </div>
      </if>
      </volist>

      <div class="swiper-slide swiper-slide-visible swiper-slide-active" style="height: 700px;" ></div>
      
      <div class="arrows2"></div>
  </div>

</div></div>
<div class="copyright" style="display:none;">
<if condition="$iscopyright eq 1">
{pigcms{$homeInfo.copyright}
<else/>
{pigcms{$siteCopyright}
</if>
</div>  
<include file="$cateMenuFileName"/>
<include file="Index:share" />
</body>
</html>