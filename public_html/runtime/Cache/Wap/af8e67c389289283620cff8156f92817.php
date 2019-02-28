<?php if (!defined('THINK_PATH')) exit(); if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>首页</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="<?php echo ($static_path); ?>css/common.css?21923"/>
		<link rel="stylesheet" type="text/css" href="<?php echo ($static_path); ?>css/index.css?1"/>
		<script type="text/javascript" src="<?php echo C('JQUERY_FILE_190');?>" charset="utf-8"></script>
		<script type="text/javascript" src="<?php echo ($static_path); ?>js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="<?php echo ($static_path); ?>js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="<?php echo ($static_path); ?>js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="<?php echo ($static_path); ?>layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="<?php echo ($static_path); ?>js/common.js?2112222" charset="utf-8"></script>
		<script type="text/javascript">var group_index_sort_url="<?php echo U('Home/group_index_sort');?>";<?php if($user_long_lat): ?>var user_long = "<?php echo ($user_long_lat["long"]); ?>",user_lat = "<?php echo ($user_long_lat["lat"]); ?>";<?php else: ?>var user_long = '0',user_lat  = '0';<?php endif; ?>var app_version="<?php echo ($_REQUEST['app_version']); ?>"</script>
		<script type="text/javascript" src="<?php echo ($static_path); ?>js/index.js?211" charset="utf-8"></script>
		<?php if($config["guess_content_type"] == 'shop'): ?><link rel="stylesheet" type="text/css" href="<?php echo ($static_path); ?>shop/css/home_shop.css?216"/>
		<?php elseif($config["guess_content_type"] == 'meal'): ?>
			<link rel="stylesheet" type="text/css" href="<?php echo ($static_path); ?>css/home_meal.css?216"/><?php endif; ?>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/eruda"></script>
    <script>eruda.init();</script>
		<script>
			var guess_num	=	"<?php echo ($guess_num); ?>";
			var guess_content_type	=	"<?php echo ($guess_content_type); ?>";
			var merchant_around_url  = "<?php echo U('Merchant/around');?>";
			var payqrcode_url  = "<?php echo U('My/pay_qrcode');?>";
			var scan_order_url  = "<?php echo U('My/scan_order');?>", deliverName = "<?php echo ($config['deliver_name']); ?>";
		</script>
        <style>
            body{
                background: #F4F4F4;
                
            }
            *{margin: 0;padding: 0;}
            .ft{
                float:left;
            }
            .rg{
                float:right;
            }
            .clear:after{
                content: " ";
                display:block;
                clear: both;
            }
            ul{list-style:none;}
            .hrash{background: #fff;width: 100%;    position: relative;}
            .left_scroll{
                overflow-x: scroll;
                position: relative;
            }
            .hrash ul{
                width: 660px;
            }
            .hrash ul li{
                padding: 10px 0;
                width: 80px;
                font-size: 14px;
                text-align: center;
                border-top: 1px solid #f1f1f1;
                border-right: 1px solid #f1f1f1;
                color: #666;
            }
            .hrash ul li.active{
                background: #FE5842;
                color: #fff;
                border-top: 1px solid #FE5842;
                border-right: 1px solid #FE5842;
            }
            .hrash p{
                position: absolute;
                right: 0;
                top: 0;
                background: #fff;
                padding: 10px 0;
                width: 60px;
                font-size: 14px;
                text-align: center;
                border-left: 1px solid #eee;
                color: #06C1AE;
            }
            .bg_mask{
                background: rgba(0,0,0,.8);
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                display: none;
            }
            .bg_mask ul{
                margin-top: 30px;
            }
            .bg_mask ul li{
                width: 25%;
                text-align: center;
                color: #fff;
                padding: 10px 0;
            }
            .bg_mask ul li.active{
                color: #FE5842;
            }
            .bg_mask .close{
                font-size: 50px;
                color: #fff;
                position: fixed;
                bottom: 10px;
                width: 100%;
                text-align: center;
            }
            .picadds{
                width: 100%;
            }
            .picContent{
                overflow-x: scroll;
            }
            .picContent ul{
                width: 440px;
                font-size: 14px;
                
            }
            .picContent ul li{
                width: 130px;
                background: #fff;
                padding:10px 10px 5px   10px;
                margin: 10px 0px 10px 10px;
                
            }
            .picContent ul li dt img{
                width: 130px;height: 130px;
                
            }
            .picContent ul li .sizes{
                width: 100%;
                overflow: hidden;
                text-overflow:ellipsis;
                white-space: nowrap;
                text-align: center;
                font-size: 14px;
                color: #333;
            }
            .picContent ul li .high{
                font-size: 12px;
                color: #999;
                padding: 2px 0;
            }
            .picContent ul li .high i{
                display: inline-block;
                width: 12px;
                height: 12px;
                background: url(<?php echo ($static_path); ?>images/z22.png) center no-repeat;
                background-size: contain;
                margin-right: 2px;
            }
            .picContent ul li .flexss{
                display: flex;
                -webkit-box-pack: justify;
                -webkit-justify-content: space-between;
                justify-content: space-between;
                -webkit-box-align: center;
                -webkit-align-items: center;
                align-items: center;

            }
            .picContent ul li .flexss>span{
                font-size: 16px;
                color: #990033;
                font-weight: 700;
            }
            .picContent ul li .flexss>span>span{
                font-size: 12px;
            }
            .picContent ul li .flexss p{
                font-size: 12px;
                color: #666;
            }

			.index_house{
				position:relative;
			}
			.index_house:after {
				display: block;
				content: "";
				border-top: 1px solid #BFBFBF;
				border-left: 1px solid #BFBFBF;
				width: 8px;
				height: 8px;
				-webkit-transform: rotate(135deg);
				background-color: transparent;
				position: absolute;
				top: 50%;
				right: 15px;
				margin-top: -5px;
			}
			.list_content .clear:after{
				content: " ";
				display: block;
				clear: both;{
			}
		</style>
	</head>
	<body>
		<header <?php if($config['many_city']): ?>class="hasManyCity"<?php endif; ?> style="z-index:111;">
			<?php if($config['many_city']): ?><div id="cityBtn" class="link-url" data-url="<?php echo U('Changecity/index');?>" data-top_domain="<?php echo ($config["many_city_top_domain"]); ?>" <?php if(msubstr($config['now_select_city']['area_name'],0,2) != $config['now_select_city']['area_name']): ?>style='width:68px;'<?php endif; ?>><?php echo msubstr($config['now_select_city']['area_name'],0,2);?></div>
			<?php else: ?>
				<div id="locaitonBtn" class="link-url" data-url="<?php echo U('Merchant/around');?>"></div><?php endif; ?>
			<div id="searchBox">
				<a href="<?php echo U('Search/index');?>">
					<i class="icon-search"></i>
					<span>请输入您想找的内容</span>
				</a>
			</div>
			<?php if($config["open_score_fenrun"] == 1 AND $config["fenrun_btn_location"] == 1): ?><div class="More_drop"></div>
			<div class="drop_list">
				<ul>
					<?php if($config['many_city']): ?><li class="nearby"><span>附近商户</span></li><?php endif; ?>
					<li class="scan qrcodeBtn"  ><span>扫一扫</span></li>
					<li class="payment"><span>付款码</span></li>
				</ul>
			</div>
			<?php elseif($config["open_score_fenrun"] != 1): ?>
				<div id="qrcodeBtn" class="qrcodeBtn"></div><?php endif; ?>
			
			<?php if($config["open_score_fenrun"] == 1): ?><div class="payment_code" >
				<div class="h2">向商家付款</div>
				<div class="h3">该功能用于向商家当面付款</div>
				<div class="con_img">
					<img id="paybarcode" src="">
					<img id="payqrcode"  src="">
					<div class="refresh">
						<span>每分钟自动刷新</span>
					</div>
				</div>
			</div><?php endif; ?>
			<div class="del"></div>
			<div class="mask"></div>
		</header>

		

		<div id="container" style="top:50px;-webkit-transform:translate3d(0,0,0)">
			<div id="scroller">
				<div id="pullDown">
					<span class="pullDownIcon"></span><span class="pullDownLabel">下拉可以刷新</span>
				</div>
				<?php if($wap_index_top_adver): ?><section id="banner_hei" class="banner">
						<div class="swiper-container swiper-container1">
							<div class="swiper-wrapper">
								<?php if(is_array($wap_index_top_adver)): $i = 0; $__LIST__ = $wap_index_top_adver;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
										<a href="<?php echo ($vo["url"]); ?>">
											<img src="<?php echo ($vo["pic"]); ?>"/>
										</a>
									</div><?php endforeach; endif; else: echo "" ;endif; ?>
							</div>
							<div class="swiper-pagination swiper-pagination1"></div>
						</div>
					</section><?php endif; ?>
				<?php if($config['open_score_fenrun'] == 1 AND $config['fenrun_btn_location'] == 2): ?><div class="nav_bar">
					<ul>
						<li>
							<a href="javascript:void(0)"  class="qrcodeBtn">
								<i>
									<img src="<?php echo ($static_path); ?>img/index/fenrun1.png">
								</i>
								<span>扫一扫</span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0)" class="payment">
								<i>
									<img src="<?php echo ($static_path); ?>img/index/fenrun2.png">
								</i>
								<span>付款码</span>
							</a>
						</li>
						<li>
							<a href="<?php echo U('Fenrun/user_free_award_list');?>">
								<i>
									<img src="<?php echo ($static_path); ?>img/index/fenrun4.png">
								</i>
								<span>佣金</span>
							</a>
						</li>
						<li>
							<a href="<?php echo U('Fenrun/fenrun_money_list');?>">
								<i>
									<img src="<?php echo ($static_path); ?>img/index/fenrun3.png">
								</i>
								<span>分润钱包</span>
							</a>
						</li>

					</ul>
				</div><?php endif; ?>


				<?php if(!empty($scroll_msg)): ?><div style=" height: 16px; margin-bottom: 10px; padding: 10px 15px;background: #ffffff;" class="scroll_msg" >
						<div style="background: url(<?php echo ($static_path); ?>images/lbt_03.png) left 1px no-repeat; background-size: 14px; padding-left: 20px;">
							<div class=""  id="scrollText" style="border-left: #cfcfcf 1px solid; padding-left: 8px; font-size: 12px; height: 15px;">
								<marquee  style="line-height: 16px;  white-space: nowrap;  " scrolldelay="100">
									<?php if(is_array($scroll_msg)): $i = 0; $__LIST__ = $scroll_msg;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div style="display:inline-block">
											<span style="padding-right:20px;color:#ff2c4d;">
												<a><?php echo ($vo["content"]); ?></a>
											</span>
										</div><?php endforeach; endif; else: echo "" ;endif; ?>
								</marquee>
							</div>
						</div>
					</div>
					<style>
					#scrollText div a{ color: #ff2c4d;}
					</style>
					<link rel="stylesheet" href="<?php echo ($static_public); ?>font-awesome/css/font-awesome.min.css"><?php endif; ?>

				<?php if($config['house_open']): ?><section class="invote index_house" style="margin-top:10px;<?php if(!empty($scroll_msg)): ?>margin-bottom:0px<?php endif; ?>">
						<a href="<?php echo U('House/village_list');?>">
							<img src="<?php echo ($config["wechat_share_img"]); ?>"/>
							我的<?php echo ($config["house_name"]); ?>服务
						</a>
					</section><?php endif; ?>
				
				<?php if($wap_index_slider): ?><section class="slider">
						<div class="swiper-container swiper-container2" style="height:168px;">
							<div class="swiper-wrapper">
								<?php if(is_array($wap_index_slider)): $i = 0; $__LIST__ = $wap_index_slider;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
										<ul class="icon-list num<?php echo ($wap_index_slider_number); ?>">
											<?php if(is_array($vo)): $i = 0; $__LIST__ = $vo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?><li class="icon">
													<a href="<?php echo ($voo["url"]); ?>">
														<span class="icon-circle">
															<img src="<?php echo ($voo["pic"]); ?>">
														</span>
														<span class="icon-desc"><?php echo ($voo["name"]); ?></span>
													</a>
												</li><?php endforeach; endif; else: echo "" ;endif; ?>
										</ul>
									</div><?php endforeach; endif; else: echo "" ;endif; ?>
							</div>
							<div class="swiper-pagination swiper-pagination2"></div>
						</div>
						<?php if($news_list): ?><div class="platformNews clearfix link-url" data-url="<?php echo U('Systemnews/index');?>">
								<div class="left ico"></div>
								<div class="left list">
									<ul>
										<?php if(is_array($news_list)): $i = 0; $__LIST__ = $news_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="num-<?php echo ($i); ?>" <?php if($i > 2): ?>style="display:none;"<?php endif; ?>>[<?php echo ($vo["name"]); ?>] <?php echo ($vo["title"]); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
									</ul>
								</div>
							</div><?php endif; ?>
					</section><?php endif; ?>
				<?php if($invote_array): ?><section class="invote">
						<a href="<?php echo ($invote_array["url"]); ?>">
							<img src="<?php echo ($invote_array["avatar"]); ?>"/>
							<?php echo ($invote_array["txt"]); ?>
							<button>关注我们</button>
						</a>
					</section>
				<?php elseif($share): ?>
					<section class="invote">
						<a href="<?php echo ($share["a_href"]); ?>">
							<?php if($share['image']): ?><img src="<?php echo ($share["image"]); ?>"/><?php endif; ?>
							<?php echo ($share["title"]); ?>
							<button><?php echo ($share['a_name']); ?></button>
						</a>
					</section><?php endif; ?>
				<?php if($activity_list): ?><section class="activity">
						<div class="activityBox">
							<div class="swiper-container swiper-container4">
								<div class="swiper-wrapper">
									<?php if(is_array($activity_list)): $i = 0; $__LIST__ = $activity_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
											<a href="<?php echo U('Wapactivity/detail',array('id'=>$vo['pigcms_id']));?>">
												<label>
													<span class="title">参与</span>
													<span class="number"><?php echo ($vo["part_count"]); ?></span>
												</label>
												<div class="clock"><span class="time_d"><?php echo ($time_array['d']); ?></span>天 <span class="timerBox"><span class="timer time_h"><?php echo ($time_array['h']); ?></span>:<span class="timer time_m"><?php echo ($time_array['m']); ?></span>:<span class="timer time_s"><?php echo ($time_array['s']); ?></span></span></div>
												<div class="icon">
													<img src="<?php echo ($vo["list_pic"]); ?>" alt="<?php echo ($vo["name"]); ?>"/>
												</div>
												<div class="desc">
													<div class="name"><?php echo ($vo["name"]); ?></div>
													<div class="price">
														<?php if($vo['type'] == 1): ?><strong class="yuan">剩<?php echo ($vo['all_count']-$vo['part_count']); ?></strong>
														<?php else: ?>
															<?php if($vo['mer_score']): ?><strong><?php echo ($vo["mer_score"]); echo ($config['score_name']); ?></strong>
															<?php else: ?>
																<strong>￥<?php echo ($vo["money"]); ?></strong><?php endif; endif; ?>
													</div>
												</div>
											</a>
										</div><?php endforeach; endif; else: echo "" ;endif; ?>
								</div>
							</div>
						</div>
					</section><?php endif; ?>
				<?php if($wap_index_center_adver || $config['wap_around_show_type']==1){ ?>
				<section class="recommend" style="height:auto;">
					<?php if($wap_index_center_adver): ?><div class="recommendBox">
                  <?php if($wap_index_center_adver['2']['sub_name'] == 1 and $wap_index_center_adver['2']['sub_name'] != ''): ?><div class="recommendLeft link-url">
                      <video width="100%" height="100%" autoplay muted>
                        <source src="<?php echo ($wap_index_center_adver["2"]["url"]); ?>" type="video/mp4">
                        您的浏览器不支持 video 标签。
                    </video>
                    </div>
                  <?php else: ?> 
                  <div class="recommendLeft link-url" data-url="<?php echo ($wap_index_center_adver["2"]["url"]); ?>">
								    <img src="<?php echo ($wap_index_center_adver["2"]["pic"]); ?>" alt="<?php echo ($wap_index_center_adver["2"]["name"]); ?>"/>
                    </div><?php endif; ?>
							<div class="recommendRight">
								<div class="recommendRightTop link-url" data-url="<?php echo ($wap_index_center_adver["1"]["url"]); ?>">
									<img src="<?php echo ($wap_index_center_adver["1"]["pic"]); ?>" alt="<?php echo ($wap_index_center_adver["1"]["name"]); ?>"/>
								</div>
								<div class="recommendRightBottom link-url" data-url="<?php echo ($wap_index_center_adver["0"]["url"]); ?>">
									<img src="<?php echo ($wap_index_center_adver["0"]["pic"]); ?>" alt="<?php echo ($wap_index_center_adver["0"]["name"]); ?>"/>
								</div>
							</div>
						</div><?php endif; ?>
					<?php if($config['wap_around_show_type'] == 1): if($wap_around): ?><div class="nearBox">
							<ul>
								<?php if(is_array($wap_around)): $i = 0; $__LIST__ = $wap_around;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
										<div class="nearBoxDiv merchant link-url" data-url="<?php echo ($vo["url"]); ?>">
											<div class="title"><?php if($vo['name']!='merchant'){ ?>附近<?php echo $config[$vo['name'].'_alias_name'];}else{ ?>附近商家<?php } ?></div>
											<div class="desc" style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;"><?php echo ($vo["des"]); ?></div>
											<div class="icon" style="background-image:url(<?php echo ($config["site_url"]); ?>/upload/wap/<?php echo ($vo["pic"]); ?>)"></div>
										</div>
									</li><?php endforeach; endif; else: echo "" ;endif; ?>
							</ul>
						</div>
						<?php else: ?>
						<div class="nearBox">
							<ul>
								<li>
									<div class="nearBoxDiv merchant link-url" data-url="<?php echo U('Merchant/around');?>">
										<div class="title">附近商家</div>
										<div class="desc">快速找到商家</div>
										<div class="icon"></div>
									</div>
								</li>
								<li>
									<div class="nearBoxDiv group link-url" data-url="<?php echo U('Group/index');?>">
										<div class="title">附近<?php echo ($config["group_alias_name"]); ?></div>
										<div class="desc">看得到的便宜</div>
										<div class="icon"></div>
									</div>
								</li>
								<li>
									<div class="nearBoxDiv store link-url" data-url="<?php echo U('Shop/index');?>">
										<div class="title">附近<?php echo ($config["shop_alias_name"]); ?></div>
										<div class="desc">购物无需等待</div>
										<div class="icon"></div>
									</div>
								</li>
							</ul>
						</div><?php endif; endif; ?>
				</section>
										<?php } ?>
				<?php if($classify_Zcategorys): ?><section class="classify">
						<div class="headBox"><?php echo ($config["classify_name"]); ?></div>
						<div class="classifyBox">
							<div class="swiper-container swiper-container3">
								<div class="swiper-wrapper">
									<?php if(is_array($classify_Zcategorys)): $i = 0; $__LIST__ = $classify_Zcategorys;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo['cat_pic']): ?><div class="swiper-slide">
												<a href="<?php echo U('Classify/index',array('cid'=>$vo['cid'],'ctname'=>urlencode($vo['cat_name'])));?>#ct_item_<?php echo ($vo['cid']); ?>">
													<span class="icon">
														<img src="<?php echo ($vo["cat_pic"]); ?>"/>
													</span>
													<span class="desc"><?php echo ($vo["cat_name"]); ?></span>
												</a>
											</div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
								</div>
							</div>
						</div>
					</section><?php endif; ?>
				<section class="youlike hide">
					<div class="headBox">猜你喜欢</div>
					<dl class="likeBox dealcard"></dl>
				</section>
				<div class="content_details">
					<div id="recommend_list">
					</div>
					<div id="no_data" style="display: none; text-align: center; color: red; font-size: 18px; padding: 5px;">暂无数据</div>
				</div>

        <div class="allChild">
            
            <!--div class="hrash">
                <div class="left_scroll">
                    <ul class="clear">
                            <li class="ft active">{{ d[i][ii].name }}</li>
                    </ul>
                </div>
                <p class="moreClass">更多</p>
            </div>
            <div class="picadds"> 
                <div class="picContent">
                    <ul class="clear">
                        <li class="ft">
                            <dl>
                                <dt><img src="imanges/13-_10.png"/></dt>
                                <dd class="sizes">【正品】秋冬加绒卫衣</dd>
                                <dd class="high"><i></i>好评率95%</dd>
                                <dd class="flexss"><span><span>￥</span>109</span><p>266件已售</p></dd>
                            </dl>
                        </li>
                        
                        <li class="ft">
                            <dl>
                                <dt><img src="imanges/13-_10.png"/></dt>
                                <dd class="sizes">【正品】秋冬加绒卫衣</dd>
                                <dd class="high"><i></i>好评率95%</dd>
                                <dd class="flexss"><span><span>￥</span>109</span><p>266件已售</p></dd>
                            </dl>
                        </li>
                        <li class="ft">
                            <dl>
                                <dt><img src="imanges/13-_10.png"/></dt>
                                <dd class="sizes">【正品】秋冬加绒卫衣</dd>
                                <dd class="high"><i></i>好评率95%</dd>
                                <dd class="flexss"><span><span>￥</span>109</span><p>266件已售</p></dd>
                            </dl>
                        </li>
                    </ul>
                </div>
            </div-->
        </div>
        <div class="bg_mask">
            <ul class="clear">
                <li class="ft active">手机数码</li>
                <li class="ft">品牌男装</li>
                <li class="ft">潮流女装</li>
                <li class="ft">电脑办公</li>
                <li class="ft">数码家电</li>
            </ul>
            <p class="close">×</p>
        </div>
        
				<script id="indexRecommendBoxTpl" type="text/html">
					<?php if($config["guess_content_type"] == 'group'): ?>{{# for(var i = 0, len = d.length; i < len; i++){ }}
						<dd class="recommend-link-url" data-group_id="{{ d[i].group_id }}" data-url="{{ d[i].url }}">
							{{# if(d[i].pin_num > 0){ }}<div class="pin_style"></div>{{# } }}
							<div class="dealcard-img imgbox">
								<img src="<?php echo ($config["site_url"]); ?>/index.php?c=Image&a=thumb&width=276&height=168&url={{ encodeURIComponent(d[i].list_pic) }}" alt="{{ d[i].s_name }}" style="height:auto;"/>
							</div>
							<div class="dealcard-block-right">
								<div class="brand">{{ d[i].s_name }}  {{# if(d[i].range){ }}<span class="location-right">{{ d[i].range }}</span>{{# } }}  </div>
								<div class="title">{{ d[i].intro }}</div>
								<div class="price">
									<strong>{{ d[i].price }}</strong><span class="strong-color">元{{# if(d[i].extra_pay_price!=''){ }}{{ d[i].extra_pay_price }}{{# } }}</span>{{# if(d[i].wx_cheap){ }}<span class="tag">微信再减{{ d[i].wx_cheap }}元</span>{{# }else{ }}<del>{{ d[i].old_price }}</del>{{# } }} <span class="line-right"> {{ d[i].sale_txt }}</span>
								</div>
							</div>
						</dd>
					{{# } }}
					<?php elseif($config["guess_content_type"] == 'shop'): ?>

						{{# for(var i = 0, len = d.length; i < len; i++){ }}
							<dd class="recommend-link-url" data-url="{{# if(d[i].is_mult_class == 1){ }}./wap.php?c=Shop&a=classic_shop&shop_id={{ d[i].store_id }}{{# }else{ }}./wap.php?g=Wap&c=Shop&a=index#shop-{{ d[i].store_id }} {{# } }}" data-url-type="openRightFloatWindow" {{# if(d[i].is_close){ }}style="opacity:0.6;"{{# } }}>

								<div class="dealcard-img imgbox">
									{{# if(d[i].isverify == 1){ }}
										<img src="./static/images/kd_rec.png" style="    width: 41px;height: 15px;position: absolute;z-index: 99;margin: 2px 0 0 0;">
									{{# } }}
									<img style="margin-left: 0px;position: absolute;"  src="{{ d[i].image }}" alt="{{ d[i].name }}">
									{{# if(d[i].is_close){ }}<div class="closeTip">休息中</div>{{# } }}
								</div>
								<div class="dealcard-block-right">
									<div class="brand">{{ d[i].name }}<em class="location-right">{{# if(user_long != '0'){ }}{{ d[i].range }}{{# } }}</em></div>
									<div class="title {{# if(!d[i].delivery){ }}pick{{# } }}" style="margin-bottom:0px;">
										<span class="star">
											{{#
												var tmpScore = parseFloat(d[i].star);
												if(tmpScore>0){
													for(var tmpI=0;tmpI<5;tmpI++){ if(tmpScore >= tmpI+1){ }}<i class="full"></i>{{# }else if(tmpScore > tmpI){ }}<i class="half"></i>{{# }else{ }}<i></i>{{# } }
												}else{
											}}
												<i class="full"></i><i class="full"></i><i class="full"></i><i class="half"></i><i></i>
											{{#
												}
											}}
										</span>
										{{# if(d[i].month_sale_count>0){ }}<span>月售{{ d[i].month_sale_count }}单</span>{{# } }}
										{{# if(d[i].delivery){ }}
											<em class="location-right">{{ d[i].delivery_time }} {{ d[i].delivery_time_type }}</em>
										{{# }else{ }}
											<em class="location-right ziti">门店自提</em>
										{{# } }}
									</div>
									{{# if(d[i].delivery){ }}
										<div class="price">
											<span>起送价 ￥{{ d[i].delivery_price }}</span><span class="delivery">{{# if(d[i].delivery_money==0){ }}免配送费 {{# }else{ }} 配送费 ￥{{ d[i].delivery_money }} {{# } }}</span>
											{{# if(d[i].delivery_system){ }}
												<em class="location-right">{{ deliverName }}</em>
											{{# }else{ }}
												<em class="location-right merchant_send" >商家配送</em>
											{{# } }}
										</div>
									{{# } }}

								</div>
								{{# if(d[i].coupon_count > 0){ }}
										<div class="coupon {{# if(d[i].coupon_count > 2){ }}hasMore{{# } }}">
											<ul>
												{{# var tmpCouponList = parseCoupon(d[i].coupon_list,'array');  }}
												{{# if(tmpCouponList['invoice']){ }}
													<li><em class="merchant_invoice"></em>{{ tmpCouponList['invoice'] }}</li>
												{{# } }}
												{{# if(tmpCouponList['discount']){ }}
													<li><em class="merchant_discount"></em>{{ tmpCouponList['discount'] }}</li>
												{{# } }}
												{{# if(tmpCouponList['minus']){ }}
													<li><em class="merchant_minus"></em>{{ tmpCouponList['minus'] }}</li>
												{{# } }}
												{{# if(tmpCouponList['newuser']){ }}
													<li><em class="newuser"></em>{{ tmpCouponList['newuser'] }}</li>
												{{# } }}
												{{# if(tmpCouponList['delivery']){ }}
													<li><em class="delivery"></em>{{ tmpCouponList['delivery'] }}</li>
												{{# } }}
												{{# if(tmpCouponList['system_minus']){ }}
												<li><em class="system_minus"></em>{{ tmpCouponList['system_minus'] }}</li>
												{{# } }}
												{{# if(tmpCouponList['system_newuser']){ }}
													<li><em class="system_newuser"></em>{{ tmpCouponList['system_newuser'] }}</li>
												{{# } }}
							                    {{# if(tmpCouponList['isDiscountGoods']){ }}
								                    <li><em class="isDiscountGoods"></em>{{ tmpCouponList['isDiscountGoods'] }}</li>
							                    {{# } }}
							                    {{# if(tmpCouponList['isdiscountsort']){ }}
								                    <li><em class="merchant_discount"></em>{{ tmpCouponList['isdiscountsort'] }}</li>
							                    {{# } }}
											</ul>
											{{# if(d[i].coupon_count > 2){ }}
												<div class="more">{{ d[i].coupon_count }}个活动</div>
											{{# } }}
										</div>
									{{# } }}
							</dd>
						{{# } }}
					<?php elseif($config["guess_content_type"] == 'meal'): ?>
						{{# for(var i = 0, len = d.store_list.length; i < len; i++){ }}
							{{# if(d.store_list[i].state == 0){ }}
							<dl class="on">
								<dt>
									<div class="navLtop clr recommend-link-url" data-url="{{ d.store_list[i].url }}">
										{{# if(d.store_list[i].isverify == 1){ }}
											<img src="./static/images/rec_2.png" style="width:18px; height:20px;margin-top:1px; margin-right:5px;float:left">
										{{# } }}
										<h2 class="fl1">{{ d.store_list[i].name }}</h2>
										<div class="navLtop_right fr">
											{{# if(d.store_list[i].is_book == 1){ }}
											<span class="ln">订</span>
											{{# } }}
											{{# if(d.store_list[i].is_queue == 1){ }}
											<span class="zi">排</span>
											{{# } }}
											{{# if(d.store_list[i].is_takeout == 1){ }}
											<span class="lv">外</span>
											{{# } }}
										</div>
									</div>
									<div class="navLBt clr">
										<ul class="navLBt_ul fl show_number clr">
											<li>
												<div class="atar_Show">
													<p tip="{{ d.store_list[i].score_mean }}" ></p>
												</div>
											</li>
										</ul>
										<div class="Notopen fl">未营业</div>
										<div class="distance fr">{{ d.store_list[i].range }}</div>
									</div>
								</dt>
							</dl>
							{{# } else { }}
							<dl>
								<dt>
									<div class="navLtop clr recommend-link-url" data-url="{{ d.store_list[i].url }}">
										{{# if(d.store_list[i].isverify == 1){ }}
											<img src="./static/images/rec_2.png" style="width:18px; height:20px;margin-top:1px; margin-right:5px;float:left">
										{{# } }}
										<h2 class="fl1">{{ d.store_list[i].name }}</h2>
										<div class="navLtop_right fr">
											{{# if(d.store_list[i].is_book == 1){ }}
											<span class="ln">订</span>
											{{# } }}
											{{# if(d.store_list[i].is_queue == 1){ }}
											<span class="zi">排</span>
											{{# } }}
											{{# if(d.store_list[i].is_takeout == 1){ }}
											<span class="lv">外</span>
											{{# } }}
										</div>
									</div>
									<div class="navLBt clr">
										<ul class="navLBt_ul fl show_number clr">
											<li>
												<div class="atar_Show fl">
													<p tip="{{ d.store_list[i].score_mean }}" ></p>
												</div>
											</li>
										</ul>
										<div class="distance fr">{{ d.store_list[i].range }}</div>
									</div>
								</dt>
								{{# if(d.store_list[i].pay_in_store == 1){ }}
								<dd class="navlink clr">
									<a href="{{ d.store_list[i].store_pay }}">
										<span class="link_Pay">到店付</span>
                                        {{# if(d.store_list[i].discount_txt != ''){ }}
										{{# if(d.store_list[i].discount_txt.discount_type == 1){ }}
											<span>{{ d.store_list[i].discount_txt.discount_percent }}折</span>
										{{# } else { }}
											<span>每满{{ d.store_list[i].discount_txt.condition_price }}减{{ d.store_list[i].discount_txt.minus_price }}元</span>
										{{# } }}
										{{# } }}
										<span class="link_jt fr"></span>
									</a>
								</dd>
								{{# } }}
								{{# for(var j = 0, jlen = d.store_list[i].group_list.length; j < jlen; j++){ }}
								<dd class="Menulink clr">
									<a href="{{ d.store_list[i].group_list[j].url }}">
										<div class="Menulink_img fl">
											<img class="on" src="{{ d.store_list[i].group_list[j].list_pic }}">
											<span class="MenuGroup"></span>
										</div>
										<div class="Menulink_right">
											<h2>{{ d.store_list[i].group_list[j].name }}</h2>
											<div class="MenuPrice">
												<span class="PriceF"><i>￥</i><em>{{ d.store_list[i].group_list[j].price }}</em></span>
												<span class="PriceT">门市价:￥{{ d.store_list[i].group_list[j].old_price }}</span>
												<span class="PriceS">{{ d.store_list[i].group_list[j].sale_txt }}</span>
											</div>
										</div>
									</a>
								</dd>
								{{# } }}
							</dl>
							{{# } }}
						{{# } }}
                        <?php elseif($config["guess_content_type"] == 'store'): ?>
                        {{# for(var i = 0, len = d.store_list.length; i < len; i++){ }}
						<dd class="link-url" data-url="{{ d.store_list[i].url }}">
							<div class="dealcard-img imgbox">
								{{# if(d.store_list[i].isverify == 1){ }}
									<img src="./static/images/kd_rec.png" style="width:41px;height:15px;position: absolute;z-index: 15;margin:2px 0 0 0">
								{{# } }}
								<img src="<?php echo ($config["site_url"]); ?>/index.php?c=Image&a=thumb&width=276&height=168&url={{ encodeURIComponent(d.store_list[i].list_pic) }}" style="margin-left:0px;" alt="{{ d.store_list[i].store_name }}"/>
							</div>
							<div class="dealcard-block-right" style="font-family:'Microsoft YaHei' !important;">
								<div class="brand" style="padding:0px 8px 0px 1px;font-weight:bolder;float:left;">{{ d.store_list[i].store_name }}</div>
								<div class="brand" style="float:right;">
									{{# if(d.store_list[i].have_shop == 1){ }}
										<i class="text-icon order-jiudian order-icon" style="background-color:#ea0d2c;width:20px;height:22px;font-size:14px;"><?php echo ($config["shop_alias_name"]); ?></i>
									{{# } }}
									{{# if(d.store_list[i].have_group == 1){ }}
										<i class="text-icon order-jiudian order-icon" style="background-color:#EAAD0D;width:20px;height:22px;font-size:14px;"><?php echo ($config["group_alias_name"]); ?></i>
									{{# } }}
									{{# if(d.store_list[i].have_meal == 1){ }}
										<i class="text-icon order-jiudian order-icon" style="width:20px;height:22px;font-size:14px;"><?php echo ($config["meal_alias_name"]); ?></i>
									{{# } }}
									{{# if(d.store_list[i].now_appoint){ }}
										<i class="text-icon order-jiudian order-icon" style="background-color:#0092DE;width:20px;height:22px;font-size:14px;"><?php echo ($config["appoint_alias_name"]); ?></i>
									{{# } }}
								</div>
								<div style="clear:both"></div>
								<div class="price" style="margin-bottom:5px;font-size:14px;">商家有<span style="color: #fe5842;font-size: 18px;">{{ d.store_list[i].fans_count }}</span>个粉丝</div>
								<div class="rateInfo" style="margin-top:3px;float:left;">
								{{# if(d.store_list[i].pingjun){ }}
									<div class="starIconBg">
										<div class="starIcon" style="width:{{ d.store_list[i].xing }}%"></div>
									</div>
									<div class="starText">{{ d.store_list[i].pingjun }}</div>
								{{# }else{ }}
									<span style="color:#999">暂无评分</span>
								{{# } }}
								</div>
								<div class="rateInfo" style="float:right;margin-top:4px;">
									<span style="color:#909090;float:right;font-size:12px">{{ d.store_list[i].range_txt }}</span>
								</div>
							</div>
						</dd>
		{{# if(d.store_list[i].pay_in_store == 1 && d.store_list[i].discount_txt != ''){ }}
		<dd class="navlink clr">
			<a href="{{ d.store_list[i].store_pay }}">
				<span class="link_Pay">到店付</span>
				{{# if(d.store_list[i].discount_txt.discount_type == 1){ }}
					<span>{{ d.store_list[i].discount_txt.discount_percent }}折</span>
				{{# } else { }}
					<span>每满{{ d.store_list[i].discount_txt.condition_price }}减{{ d.store_list[i].discount_txt.minus_price }}元</span>
				{{# } }}
				<span class="link_jt fr"></span>
			</a>
		</dd>
		{{# } }}
					{{# } }}<?php endif; ?>
				</script>
				
            <script id="indexMallTpl" type="text/html">
            {{# for (var i in d) { }}
            <div class="hrash">
                <div class="left_scroll" id="cat_{{ i }}">
                    {{# console.log(i); }}
                    <ul class="clear">
                        {{# for (var ii in d[i]) { }}
                        {{# if (ii == 0) { }}
                            {{# var cateId = d[i][ii].id; }}
                            {{# mallGoods(cateId, true); }}
                            <li class="ft active" data-id="{{  d[i][ii].id }}">{{ d[i][ii].name }}</li>
                        {{# } else { }}
                            <li class="ft" data-id="{{ d[i][ii].id }}">{{ d[i][ii].name }}</li>
                        {{# } }}
                        {{# } }}
                    </ul>
                </div>
                <!--p class="moreClass">更多</p-->
            </div>
            <div class="picadds" > 
                <div class="picContent" id="mall_{{ i }}">
                    <ul class="clear">
                    </ul>
                </div>
            </div>
            {{# } }}
            </script>
            <script id="indexMallGoodsTpl" type="text/html">
            {{# for (var i in d) { }}
            <li class="ft">
                <a href="{{ d[i].url }}">
                <dl>
                    <dt><img src="{{ d[i].image }}"/></dt>
                    <dd class="sizes">{{ d[i].name }}</dd>
                    <dd class="high"><i></i>好评率{{ d[i].score_mean }}</dd>
                    <dd class="flexss"><span><span>￥</span>{{ d[i].price }}</span><p>{{ d[i].sell_count }}件已售</p></dd>
                </dl>
                </a>
            </li>
            {{# } }}
            </script>
				<div id="moress" style="text-align:center;padding:10px;">正在加载...</div>
				<div id="enddate" style="text-align:center;padding:10px;display:none;">没有更多数据了</div>
				<div id="pullUp" style="bottom:-60px;">
					<img src="<?php echo ($config["site_logo"]); ?>" style="width:130px;height:40px;margin-top:10px"/>
				</div>

			</div>
		</div>

		

		<?php if(empty($no_footer)): ?><footer class="footerMenu <?php if(!$is_wexin_browser || $home_menu_list): ?>wap<?php endif; ?>">
    <?php if($home_menu_list): ?><ul>
            <?php if(is_array($home_menu_list)): $i = 0; $__LIST__ = $home_menu_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                    <a href="<?php echo ($vo['url']); ?>" <?php if(stripos($vo["url"],"c=".MODULE_NAME)): ?>class="active"<?php endif; ?>><em></em><p><?php echo ($vo["name"]); ?></p></a>
                </li>
                 <style type="text/css">
                    .footerMenu ul li:nth-of-type(<?php echo ($key+1); ?>) a em{background:url(<?php echo ($config["site_url"]); ?>/upload/slider/<?php echo ($vo["pic_path"]); ?>) no-repeat center left; background-size:22px 20px}
                    .footerMenu ul li:nth-of-type(<?php echo ($key+1); ?>) a.active em{ background:url(<?php echo ($config["site_url"]); ?>/upload/slider/<?php echo ($vo["hover_pic_path"]); ?>) no-repeat center left; background-size:22px 20px}
                </style><?php endforeach; endif; else: echo "" ;endif; ?>
		</ul>
    <?php else: ?>
        <ul>
			<li>
				<a <?php if(MODULE_NAME == 'Home'){ echo 'class="active"'; }?> href="<?php echo U('Home/index');?>"><em class="home"></em><p>首页</p></a>
			</li>
			<li>
				<a <?php if(MODULE_NAME == 'Group'){ echo 'class="hover"'; }?> href="<?php echo U('Group/index');?>"><em class="group"></em><p><?php echo ($config["group_alias_name"]); ?></p></a>
			</li>
			<li class="voiceBox">
				<a href="<?php echo U('Search/voice');?>" class="voiceBtn" data-nobtn="true"></a>
			</li>
			<li>
				<a <?php if(in_array(MODULE_NAME,array('Shop'))){ echo 'class="hover"';}?> href="<?php echo U('Shop/index');?>"><em class="store"></em><p><?php echo ($config["shop_alias_name"]); ?></p></a>
			</li>
			<li>
				<a <?php if(in_array(MODULE_NAME,array('My','Login'))){ echo 'class="active"'; }?> href="<?php echo U('My/index');?>"><em class="my"></em><p>我的</p></a>
			</li>
		</ul><?php endif; ?>
	</footer>
<?php elseif(!$is_app_browser && empty($no_small_footer) && $merchant_link_showOther): ?>
	<div class="wx_aside more_active" id="quckArea">
		<a id="quckIco2" class="btn_more"><img style="width:40px;height:40px;" src="tpl/Wap/pure/static/img/more.png" />更多</a>
		<div class="wx_aside_item" id="quckMenu" style="display:none">
			<div id="footer_home" class="item_gwq"><img src="tpl/Wap/pure/static/img/footer_home.png" /><a> 首页</a></div>
			<div id="footer_group" class="item_gwq"><img src="tpl/Wap/pure/static/img/footer_group.png" /><a> <?php echo ($config["group_alias_name"]); ?></a></div>
			<div id="footer_store" class="item_gwq"><img src="tpl/Wap/pure/static/img/footer_foodshop.png" /><a> <?php echo ($config["meal_alias_name"]); ?></a></div>
			<div id="footer_shop" class="item_gwq"><img src="tpl/Wap/pure/static/img/footer_shop.png" /><a> <?php echo ($config["merchant_alias_name"]); ?></a></div>
			<div id="footer_my" class="item_gwq"><img src="tpl/Wap/pure/static/img/footer_my.png" /><a> 我的</a></div>
			<div id="footer_refresh" class="item_gwq"><img src="tpl/Wap/pure/static/img/footer_refresh.png" /><a> 刷新</a></div>
		</div>
	</div>
	<script>
		$("#quckIco2").on('click',function(){
			$("#quckMenu").toggle();
		});
		$("#footer_home").on('click',function(){
			location.href = "<?php echo U('wap/Home/index');?>";
		});
		$("#footer_group").on('click',function(){
			location.href = "<?php echo U('wap/Group/index');?>";
		});
		$("#footer_store").on('click',function(){
			location.href = "<?php echo U('wap/Meal_list/index');?>";
		});
		$("#footer_shop").on('click',function(){
			location.href = "<?php echo U('wap/Merchant/store_list');?>";
		});
		$("#footer_my").on('click',function(){
			location.href = "<?php echo U('wap/My/index');?>";
		});
		$("#footer_refresh").on('click',function(){
			location.reload();
		});
	</script><?php endif; ?>
<div style="display:none;"><?php echo ($config["wap_site_footer"]); ?></div>
		<script type="text/javascript">
			window.shareData = {
				"moduleName":"Home",
				"moduleID":"0",
				"imgUrl": "<?php if($config['wechat_share_img']): echo ($config["wechat_share_img"]); else: echo ($config["site_logo"]); endif; ?>",
				"sendFriendLink": "<?php echo ($config["site_url"]); echo U('Home/index');?>",
				"tTitle": "<?php echo ($config["site_name"]); ?>",
				"tContent": "<?php echo ($config["seo_description"]); ?>"
			};
		</script>
        <script type="text/javascript">
            var leftWidth=$('.left_scroll ul li').length;
            var picWidth=$('.picContent ul li').length;
            $('.picContent ul').width(picWidth*160+10);
            $('.left_scroll ul').width(leftWidth*81+60);
            $('.left_scroll ul li').click(function(e){
                $(this).addClass('active').siblings('li').removeClass('active');
                var distance=$(this).offset().left;
                $('.left_scroll').animate({"scrollLeft":distance},300);
            });
            $('.moreClass').click(function(e){
                $('.bg_mask').show();
                var text=$('.left_scroll li.active').text();
                $.each($('.bg_mask ul li'), function(i,val) {
                    if(text==$(this).text()){
                        $(this).addClass('active').siblings('li').removeClass('active');
                    }
                });
                $('.bg_mask .close').click(function(e){
                    $('.bg_mask').hide();
                });
                
                
                $('.bg_mask ul li').click(function(e){
                    var this_text=$(this).text();
                    $(this).addClass('active').siblings('li').removeClass('active');
                    $.each($('.left_scroll ul li'), function() {
                        if($(this).text()==this_text){
                            var distance=$(this).offset().left;
                            $('.left_scroll').animate({"scrollLeft":distance},300);
                            $(this).addClass('active').siblings('li').removeClass('active');
                        }
                    });
                    $('.bg_mask').hide();
                });
            });
            
        </script>
		<?php echo ($shareScript); ?>
		<?php echo ($coupon_html); ?>
		<script type="text/javascript">
		if(typeof wx != "undefined"){
			wx.ready(function(){
				if(window.__wxjs_environment === 'miniprogram'){
					wx.miniProgram.switchTab({url: '/pages/index/index'});
				}
			});
		}
		</script>
	</body>
</html>