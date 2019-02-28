<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>我参与过的活动</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	<style>
	    dl.list dd.dealcard {
	        overflow: visible;
	        -webkit-transition: -webkit-transform .2s;
	        position: relative;
	    }
	    .dealcard.orders-del {
	        -webkit-transform: translateX(1.05rem);
	    }
	    .dealcard-block-right {
	        height: 1.68rem;
	        position: relative;
	    }
	    .dealcard .dealcard-brand {
	        margin-bottom: .18rem;
	    }
	    .dealcard small {
	        font-size: .24rem;
	        color: #666;
	    }
	    .dealcard weak {
	        font-size: .24rem;
	        color: #999;
	        position: absolute;
	        bottom: 0;
	        left: 0;
	        display: block;
	        width: 100%;
	    }
	    .dealcard weak b {
	        color: #FDB338;
	    }
	    .dealcard weak a.btn{
	        margin: -.15rem 0;
	    }
	    .dealcard weak b.dark {
	        color: #fa7251;
	    }
	    .hotel-price {
	        color: #ff8c00;
	        font-size: .24rem;
	        display: block;
	    }
	    .del-btn {
	        display: block;
	        width: .45rem;
	        height: .45rem;
	        text-align: center;
	        line-height: .45rem;
	        position: absolute;
	        left: -.85rem;
	        top: 50%;
	        background-color: #EC5330;
	        color: #fff;
	        -webkit-transform: translateY(-50%);
	        border-radius: 50%;
	        font-size: .4rem;
	    }
	    .no-order {
	        color: #D4D4D4;
	        text-align: center;
	        margin-top: 1rem;
	        margin-bottom: 2.5rem;
	    }
	    .icon-line {
	        font-size: 2rem;
	        margin-bottom: .2rem;
	    }
	    .orderindex li {
	        display: inline-block;
	        width:50%;
	        text-align:center;
	        position: relative;
	    }
	    .orderindex li .react {
	        padding: .28rem 0;
	    }
	    .orderindex .text-icon {
	        display: block;
	        font-size: .6rem;
	        margin-bottom: .18rem;
	    }
	    .orderindex .amount-icon {
	        position: absolute;
	        left: 50%;
	        top: .16rem;
	        color: white;
	        background: #EC5330;
	        border-radius: 50%;
	        padding: .08rem .06rem;
	        min-width: .28rem;
	        font-size: .24rem;
	        margin-left: .1rem;
	        display: none;
	    }
	    .order-icon {
	        display: inline-block;
	        width: .5rem;
	        height: .5rem;
	        text-align: center;
	        line-height: .5rem;
	        border-radius: .06rem;
	        color: white;
	        margin-right: .25rem;
	        margin-top: -.06rem;
	        margin-bottom: -.06rem;
	        background-color: #F5716E;
	        vertical-align: initial;
	        font-size: .3rem;
	    }
	    .order-all {
	        background-color: #2bb2a3;
	    }
	    .order-zuo,.order-jiudian {
	        background-color: #F5716E;
	    }
	    .order-fav {
	        background-color: #0092DE;
	    }
	    .order-card {
	        background-color: #EB2C00;
	    }
	    .order-lottery {
	        background-color: #F5B345;
	    }
	    .color-gray{
	    	color:gray;
	    	border-color:gray;
	    }
	    .color-gray:active{
	    	background-color:gray;
	    }
	    .orderindex li .react.hover{
	    	color:#FF658E;
	    }
	</style>
    <style>
	    .address-container {
	        font-size: .3rem;
	        -webkit-box-flex: 1;
	    }
	    .kv-line h6 {
	        width:auto;
	    }
	    .btn-wrapper {
	        margin: .2rem .2rem;
	        padding: 0;
	    }
	
	    .address-wrapper a {
	        display: -webkit-box;
	        display: -moz-box;
	        display: -ms-flex-box;
	    }
	
	    .address-select {
	        display: -webkit-box;
	        display: -moz-box;
	        display: -ms-flex-box;
	        padding-right: .2rem;
	        -webkit-box-align: center;
	        -webkit-box-pack: center;
	        -moz-box-align: center;
	        -moz-box-pack: center;
	        -ms-box-align: center;
	        -ms-flex-pack: justify;
	    }
	
	    .list.active dd {
	        background-color: #fff5e3;
	    }
	
	    .confirmlist {
	        display: -webkit-box;
	        display: -moz-box;
	        display: -ms-flex-box;
	    }
	
	    .confirmlist li {
	        -ms-flex: 1;
	        -moz-box-flex: 1;
	        -webkit-box-flex: 1;
	        height: .88rem;
	        line-height: .88rem;
	        border-right: 1px solid #C9C3B7;
	        text-align: center;
	    }
	
	    .confirmlist li a {
	        color: #2bb2a3;
	    }
	
	    .confirmlist li:last-child {
	        border-right: none;
	    }
		.btn-wrapper {
  margin: 0px;
}
		.btn-wrapper .tab {
	  width: 100%;
	}
		ul.tab {
  border: none;
  border-bottom: 1px solid #ccc;
  height: .8rem;
}
.btn-wrapper .tab {
  width: 100%;
}
.btn-wrapper .tab li {
  width: 50%;
  box-sizing: border-box;
}
.tab a.react {
  height: .8rem;
  line-height: .8rem;
}
	</style>
	<style>
		.dealcard .title{  height: .36rem;}
		.w-goods-price{
			color: #9E9E9E;
			font-size: .26rem;
			  height: .46rem;
		}
		/*.w-progressBar {
  margin-right: 50px;
}*/
		.w-progressBar .wrap {
  position: relative;
  margin-bottom: 8px;
  height: 5px;
  border-radius: 5px;
  background-color: #efeeee;
  overflow: hidden;
}
.w-progressBar .bar, .w-progressBar .color {
  display: block;
  height: 100%;
  border-radius: 4px;
}
.w-progressBar .bar {
  overflow: hidden;
}
.w-progressBar .color {
  width: 100%;
  background: #FFA538;
  background: -webkit-gradient(linear,left top,right top,from(#FFCB3D),to(#FF8533));
  background: -moz-linear-gradient(left,#FFCB3D,#FF8533);
  background: -o-linear-gradient(left,#FFCB3D,#FF8533);
  background: -ms-linear-gradient(left,#FFCB3D,#FF8533);
}
.w-progressBar .txt {
  overflow: hidden;
}
.w-progressBar li {
  float: left;
  color: #9E9E9E;
			font-size: .26rem;
}
.w-progressBar .txt b {
  font-weight: normal;
}
.w-progressBar .txt-r {
  float: right;
  border: 0;
  text-align: right;
}
.finish_tip {
  display: inline-block;
  /* margin-left: 30px; */
  color: red;
}
.txt-blue {
  color: #0079fe;
}
	</style>
</head>
<body id="index">
        <div id="tips" class="tips"></div>
		<div class="btn-wrapper">
			<ul class="tab">
				<li class="active"><a class="react" href="{pigcms{:U('My/join_activity')}">平台活动</a>
				</li><li><a class="react" href="{pigcms{:U('My/join_lottery')}">商家活动</a>
				</li>
			</ul>
		</div>
		<if condition="$order_list">
			<div>
				<dl class="list" id="orders">
					<dd>
						<dl>
							<volist name="order_list" id="vo">
								<dd class="dealcard dd-padding" onclick="window.location.href = '{pigcms{$vo.url}';">
									<div class="dealcard-img imgbox">
										<img src="{pigcms{$vo.list_pic}" style="width:100%;height:100%;"/>
									</div>
									<div class="dealcard-block-right">
										<div class="dealcard-brand single-line">{pigcms{$vo.merchant_name}</div>
										<div class="title text-block">[{pigcms{$vo.type_txt}] {pigcms{$vo.product_name}</div>
										<if condition="$vo['type'] eq 1">
											<p class="w-goods-price">总需：{pigcms{$vo.all_count} 人次</p>
										<else/>
											<p class="w-goods-price">
												<if condition="$vo['money']">
													{pigcms{$vo.money} 元
												<else/>
													{pigcms{$vo.mer_score} {pigcms{$config['score_name']}
												</if>
											</p>
										</if>
										<if condition="!$vo['is_finish']">
											<div class="w-progressBar">
												<p class="wrap">
													<span class="bar" style="width:{pigcms{:ceil($vo['part_count']/$vo['all_count']*100)}%"><i class="color"></i></span>
												</p>
												<ul class="txt">
													<li class="txt-l"><p><b>{pigcms{$vo.part_count}</b>已参与</p></li>
													<li class="txt-r"><p>剩余&nbsp;<b class="txt-blue">{pigcms{$vo['all_count']-$vo['part_count']}</b></p></li>
												</ul>
											</div>
										<else/>
											<p class="w-goods-price"><span class="finish_tip">[已结束]</span></p>
										</if>
										<if condition="isset($vo['juli'])">
											<div class="location_list">约<em>{pigcms{:round($vo['juli']/1000,1)}</em>km</div>
										</if>
									</div>
								</dd>
							</volist>
						</dl>
					</dd>
				</dl>
				<if condition="$pagebar">
					<dl class="list">
						<dd>
							<div class="pager">{pigcms{$pagebar}</div>
						</dd>
					</dl>
				</if>
			</div>
		<else/>
			<div class="no-deals">您还没有参加过活动呢</div>
		</if>
    	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<include file="Public:footer"/>
{pigcms{$hideScript}
</body>
</html>