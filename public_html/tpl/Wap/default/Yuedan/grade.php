<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<style>
			*{margin: 0;padding: 0;}
			ul,ol,li{list-style:none;}
			body{background: #f4f4f4;}	
			header {
			    height: 50px;
			    background-color: #06c1ae;
			    color: white;
			    line-height: 50px;
			    text-align: center;
			    position: relative;
			    font-size: 16px;
			}
			header #backBtn {
			    position: absolute;
			    width: 50px;
			    height: 100%;
			    top: 0;
			    left: 0;
			}
			header #backBtn:after {
			    display: block;
			    content: "";
			    border-top: 2px solid white;
			    border-left: 2px solid white;
			    width: 12px;
			    height: 12px;
			    -webkit-transform: rotate(315deg);
			    background-color: transparent;
			    position: absolute;
			    top: 19px;
			    left: 19px;
			}
			.content>p{
				padding: 10px 35px;
				background: #06C1AE;
				color: white;
				font-size: 14px;
				
			}
			.content .no {padding-bottom: 0;padding-top: 0;}
			.content>p>span{margin-left: 15px;}
			.content .no span{font-size: 16px;font-weight:bold;}
			.content .take span{
				border: 1px solid #54D1C5;
				padding: 4px 10px;
				border-radius:3px;
				font-size: 13px;
			}
			.classLists{
				margin:10px 0;
				width: 100%;
				
			}
			.list{
				width: 100%;
				background: white;
				display: -webkit-flex;
			    display: flex;
			    -webkit-box-pack: justify;
			    -webkit-justify-content: space-between;
			    justify-content: space-between;
			    -webkit-box-align: center;
			    -webkit-align-items: center;
			    align-items: center;
			    margin-bottom: 10px;
			}
			.left_style{
				padding: 15px 4%;
				font-size: 16px;font-weight: 700;
			}
			.list ul{width: 80%;padding: 10px 0;}
			.list ul li:first-child i.active{
				background: url({pigcms{$static_path}yuedan/images/d1.png) center no-repeat;
				background-size: contain;
			}
			.list ul li:first-child i{
				display: inline-block;
				width: 40px;height: 40px;
				background: url({pigcms{$static_path}yuedan/images/d2.png) center no-repeat;
				background-size: contain;
				margin-right: 15px;
			}
			.list ul li span{font-size: 14px;}
			.list ul li:nth-of-type(2){
				display: -webkit-flex;
			    display: flex;
			    -webkit-box-pack: justify;
			    -webkit-justify-content: space-between;
			    justify-content: space-between;
			    -webkit-box-align: center;
			    -webkit-align-items: center;
			    align-items: center;
			}
			.list ul li:nth-of-type(2) b{
				display: inline-block;
				width: 30px;height: 30px;
				background: url({pigcms{$static_path}yuedan/images/che2.png) center no-repeat;
				background-size: 20px 20px;
				margin-right: 5%;
			}
			.list ul li:nth-of-type(2) b.active{
				background: url({pigcms{$static_path}yuedan/images/che1.png) center no-repeat;
				background-size: 20px 20px;
			}
			.pay{
				text-decoration: none;
				display: inline-block;
				width: 80%;height: 40px;
				text-align: center;
				margin: 20px 10%;
				line-height: 40px;
				background: #06C1AE;
				color: white;
				font-size: 14px;
				border-radius: 5px;
			}
		</style>
	</head>
	<body>
	<header class="pageSliderHide"><div id="backBtn"></div></header>
	<div class="content">
		<p class="no">当前等级 : <span ><if condition="is_array($userGradeInfo)">{pigcms{$userGradeInfo.grade}级<else/>暂无等级</if></span></p>
		<p class="take">当前享受 : <span><if condition="is_array($userGradeInfo)">{pigcms{$userGradeInfo.precent}<else/>暂无</if></span></p>
		<div class="classLists">
			<volist name="gradeList" id="vo">
				<div class="list" onclick="gradeclick(this,'{pigcms{$vo.money}','{pigcms{$vo.grade_id}')">
					<span class="left_style">{pigcms{$vo.grade}级</span>
					<ul>
						<li><i class="<if condition='$vo.grade egt 1'>active</if>"></i> <i class="<if condition='$vo.grade egt 2'>active</if>"></i> <i class="<if condition='$vo.grade egt 3'>active</if>"></i></li>
						<li><span>所需金额 : {pigcms{$vo['money']}元</span> <b class="bb <if condition='$key eq 0'>active</if>"></b></li>
						<li><span>可享优惠:每发布一条服务,享受{pigcms{$vo.precent}%的抽成</span></li>
						<input type="hidden" name="money" value="">
					</ul> 
				</div>
			</volist>
		</div>

		<input type="hidden" id="money" value="{pigcms{$gradeList[0]['money']}">
		<input type="hidden" id="grade_id" value="{pigcms{$gradeList[0]['grade_id']}">

		<if condition="is_array($user_grade)">
			<input type="hidden" name="pay_money" id="pay_money" value="{$user_grade.pay_money}">
		<else/>
			<input type="hidden" name="pay_money" id="pay_money" value="0">
		</if>
		
		<a href="javascript:void(0);" onclick="buyGrade()" class="pay">购买 ( ￥<span id="paymoneyhtml">{pigcms{$gradeList[0]['money']}</span> )</a>
		<input type="hidden" name="buyMoney" id="buyMoney" value="" />
	</div>
	<script src="{pigcms{$static_path}layer/layer.m.js"></script>
  	<script src="{pigcms{$static_path}yuedan/js/jquery-1.9.1.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">

		function gradeclick(obj,money,grade_id){
			$(".bb").removeClass('active');
			$(obj).find('b').addClass('active');
			$("#paymoneyhtml").html(money);
			$("#money").val(money);
			$("#grade_id").val(grade_id);
		}

		function buyGrade(){
			var money = parseFloat($("#money").val()); //等级金额
			var pay_money = parseFloat($("#pay_money").val()); //已支付金额
			var buy_grade_url = "{pigcms{:U('Yuedan/buy_grade')}";
			var grade_id = $("#grade_id").val();

			//询问框
			layer.open({
				content: '您确定要购买此等级吗？'
				,btn: ['确定', '取消']
				,yes: function(index){
					layer.closeAll();
					layer.open({type: 2 ,content: '提交中...'});
					$.post(buy_grade_url,{'money':money,pay_money:pay_money,grade_id:grade_id},function(data){
						layer.closeAll();
						if(data.error == 1){
							alert(data.msg);
							location.href = location.href;
						}else{
							layer.open({content: data.msg,skin: 'msg',time: 2 });
						}
					},'json');
				}
			});
		}
	</script>
	
</html>