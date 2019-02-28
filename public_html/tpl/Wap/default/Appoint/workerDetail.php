<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" name="viewport"/>
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<link href="{pigcms{$static_path}css/base.css" rel="stylesheet">
<link href="{pigcms{$static_path}css/cate_list.css" rel="stylesheet">
<link rel="stylesheet" href="{pigcms{$static_path}css/swiper.min.css" type="text/css">
<title>{pigcms{$merchant_workers_info.name}技师-详情</title>
<script src="{pigcms{$static_path}js/swiper.min.js"></script>
<script src="{pigcms{$static_path}js/rem.js"></script>
<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script src="{pigcms{$static_path}js/index.js"></script>

</head>
<body>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script src="{pigcms{$static_path}js/fakeLoader.min.js"></script>
<link rel="stylesheet" href="{pigcms{$static_path}css/fakeLoader.css">
 <div class="container">
    <div class="fakeloader"></div>
</div> 
<script>
            $(".fakeloader").fakeLoader({
                timeToHide:1200,
                bgColor:"#F87EA3",
                spinner:"spinner1"
            });
    </script>
<article class="product craftsman">
    <section class=" clearfix">
        <div class="evaluate clearfix"><em class="spread"></em>
            <div class="head_img"><img src="{pigcms{$config.site_url}/upload/appoint/{pigcms{$merchant_workers_info['avatar_path']}"></div>
            <div class="cate_txt">
                <p class="name">{pigcms{$merchant_workers_info.name}</p>
                <p>
                 <if condition="$merchant_workers_info['comment_num'] neq 0">
               		<if condition="$merchant_workers_info['all_avg_score'] neq 0">
                        <for start='0' end='floor($merchant_workers_info["all_avg_score"])'>
                       		 <img src="{pigcms{$static_path}images/shoucnagdianji.png" width="15px" height="15px"/>
                        </for><for start='floor($merchant_workers_info["all_avg_score"])' end='5'>
                        	<img src="{pigcms{$static_path}images/shoucang.png" width="15px" height="15px" />
                        </for>
                    <else/>
                    	<for start='0' end='5'>
                        	<img src="{pigcms{$static_path}images/shoucang.png" width="15px" height="15px" />
                        </for>
                    </if>
                   <else/>
                   <for start='0' end='5'>
                                <img src="{pigcms{$static_path}images/shoucnagdianji.png" width="15px" height="15px" />
                            </for>
                   </if>
                    
                    <if condition="$merchant_workers_info['comment_num'] neq 0">
                        <if condition="$merchant_workers_info['all_avg_score'] egt 4.8">
                            <span>顶级</span>
                        <elseif condition="$merchant_workers_info['all_avg_score'] egt 4.5"/>
                            <span>优秀</span>
                        <elseif condition="$merchant_workers_info['all_avg_score'] egt 4" />
                            <span>卓越</span>
                        <elseif condition="$merchant_workers_info['all_avg_score'] egt 3.5"/>
                            <span>良好</span>
                        <else/>
                            <span>一般</span>
                        </if>
                    <else/>
                    	<span>顶级</span>
                    </if>
                    
                    </p>
                <p>
                <if condition="$merchant_workers_info['appoint_num'] lt 10">
                	<span>接单&nbsp;&nbsp;<b>10次以下</b></span>
                <else />
                	<span>接单<b>{pigcms{$merchant_workers_info.appoint_num}次</b></span>
                </if>
                <if condition="$merchant_workers_info['appoint_price'] neq 0.00">
                	<span>均价&nbsp;:&nbsp;<b>￥{pigcms{$merchant_workers_info.appoint_price}</b></span>
                </if>
                </p>
                <if condition="$merchant_workers_info['now_month_sales'] neq 0">
                <p>月销量&nbsp;:&nbsp;<b>{pigcms{$merchant_workers_info.now_month_sales}</b></span></p>
                </if>
            </div>
        </div>
        <ul class="assess clearfix">
            <li>专业:&nbsp;&nbsp;<i><if condition="$merchant_workers_info['comment_num'] neq 0">{pigcms{$merchant_workers_info.profession_avg_score}<else/>5.0</if></i></li>
            <li>沟通:&nbsp;&nbsp;<i><if condition="$merchant_workers_info['comment_num'] neq 0">{pigcms{$merchant_workers_info.communicate_avg_score}<else/>5.0</if></i></li>
            <li>守时:&nbsp;&nbsp;<i><if condition="$merchant_workers_info['comment_num'] neq 0">{pigcms{$merchant_workers_info.speed_avg_score}<else/>5.0</if></i></li>
        </ul>
    </section>
    <section class="craftsman_info">
    <if condition="$merchant_workers_info['comment_num'] gt 0">
        <div class="comment clearfix">
            <div class="comment_txt"><a href="{pigcms{:U('worker_comment_list',array('merchant_worker_id'=>$merchant_workers_info['merchant_worker_id']))}"><i></i>顾客评论({pigcms{$merchant_workers_info.comment_num})</a></div>
            <em class="spread"></em></div>
    </if>
         <div class="collection js-fav-btn"><span><i <if condition="$collect_num egt 1">class="faved"</if>></i>收藏(<em class="collect_num">{pigcms{$merchant_workers_info.collect_num}</em>)</span><span><a href="tel:{pigcms{$merchant_workers_info['tel']}"><i></i>电话咨询</a></span></div>
    </section>
    
    <section class="craftsman_info">
        <div class="comment clearfix">
            <div class="comment_txt" style="color:#d0d0d0">简介：</div>
            <br />
            <div class="comment_txt"><if condition='$merchant_workers_info["desc"]'>{pigcms{$merchant_workers_info['desc']|html_entity_decode}<else/>暂无简介</if></div>
            </div>
    </section>
    
    <section class="craftsman_content">
        <ul class="craftsman_top clearfix activity_title">
            <li class="active"><span>服务项目</span></li>
        </ul>
        <ul class="craftsman_list acticity_list">
            <li>
            <if condition="$appoint_list">
                <ul class="craftsman_table clearfix">
                	<volist name="appoint_list" id="vo">
                        <li>
                            <a href="{pigcms{:U('detail',array('appoint_id'=>$vo['appoint_id'],'merchant_workers_id'=>$_GET['merchant_workers_id']))}"><div class="img"><img src="{pigcms{$config.site_url}/upload/appoint/{pigcms{$vo.pic}" /></div>
                            <p>{pigcms{$vo.appoint_content}</p>
                            <p>
                             <if condition="$vo['appoint_type'] eq 0">
                        	<span class="imgLabel daodian"></span>
                        <else/>
                        	<span class="imgLabel shangmen"></span>
                        </if>
                            </p>
                            <p><span>￥{pigcms{$vo.appoint_price}</span><span>{pigcms{$vo.appoint_sum}人做过</span>
                           </p></a>
                        </li>
                    </volist> 
                </ul>
            <else/>
            	<div style="text-align:center; line-height:4rem; height:4rem">暂无相关项目</div>
            </if>
            </li>
        </ul>
    </section>
</article>

<script type="text/javascript">
$('.js-fav-btn span').first().click(function(){
	var collect_url="{pigcms{:U('Collect/collect')}";
	var id="{pigcms{$_GET['merchant_workers_id']}";
	if($(this).find('i').hasClass('faved')){
		var action="del";
	}else{
		var action="add";
	}

	$.post(collect_url,{action:action,type:'worker_detail',id:id},function(result){
		if(result['status']){
			alert(result['info']);
			var collect_num = parseInt($('.collect_num').html());
			if($('.js-fav-btn').find('i').hasClass('faved')){
				$('.js-fav-btn').find('i').removeClass('faved');
				collect_num--;
			}else{
				$('.js-fav-btn').find('i').addClass('faved');
				collect_num++;
			}
			$('.collect_num').html(collect_num)
		}else{
			alert(result['info']);
			$('.js-fav-btn').find('i').removeClass('faved');
		}
	});
});
</script>
</body>
</html>
