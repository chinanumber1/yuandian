<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-qrcode"></i>
				<a href="{pigcms{:U('Promote/index')}">商家推广</a>
			</li>
			<li>商家推广用户</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<form name="frmselect" action="{pigcms{:U('Merchant_spread_user/index')}" method="get" >
					<input type="hidden" name="c" value="Merchant_spread_user"/>
					<input type="hidden" name="a" value="index"/>
					
					搜索: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
					<input type="submit" value="查询" class="button"/>　<font color="red">*</font>只能搜索已注册用户
					
					<select id="reg" name="reg" onchange="change(this)">
						
						<option value="-1" <if condition="$reg_type eq 0" >selected="selected"</if>>所有</option>
						<option value="1" <if condition="$reg_type eq 1" >selected="selected"</if>>已注册</option>
						<option value="0" <if condition="isset($_GET['reg']) AND $_GET['reg'] eq 0" >selected="selected"</if>>未注册</option>
				
				</select>
					
					<a class="btn btn-success" href="{pigcms{:U('Merchant_spread_user/index')}">商家推广记录</a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a class="btn btn-success" href="{pigcms{:U('Merchant_spread_user/store_spread')}">店铺推广记录</a>
					<div style="display:inline-block">商家推广佣金总额：<font color="blue">{pigcms{$all_spread_money}元</font></div>
				</form>
				<div class="col-xs-12">
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>推广用户</th>
									<th>总推广佣金</th>
									<th>推广时间</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$spread_user">
									<volist name="spread_user" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td><if condition="empty($vo['uid'])"><font color="red">未注册用户</font><elseif condition="$vo.phone" />{pigcms{$vo.phone}<else />{pigcms{$vo.nickname}</if></td>
											<td><font color="orange"><if condition="$vo.spread_money gt 0">{pigcms{$vo.spread_money}<else />0.00</if></font></td>
											<td>{pigcms{$vo.spread_time|date='Y-m-d H:i:s',###}</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="4" >无内容</td></tr>
								</if>
							</tbody>
						</table>
						{pigcms{$pagebar}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
input.ke-input-text {
background-color: #FFFFFF;
background-color: #FFFFFF!important;
font-family: "sans serif",tahoma,verdana,helvetica;
font-size: 12px;
line-height: 24px;
height: 24px;
padding: 2px 4px;
border-color: #848484 #E0E0E0 #E0E0E0 #848484;
border-style: solid;
border-width: 1px;
display: -moz-inline-stack;
display: inline-block;
vertical-align: middle;
zoom: 1;
}
.form-group>label{font-size:12px;line-height:24px;}
#upload_pic_box{margin-top:20px;height:150px;}
#upload_pic_box .upload_pic_li{width:130px;float:left;list-style:none;}
#upload_pic_box img{width:100px;height:70px;}

.small_btn{
margin-left: 10px;
padding: 6px 8px;
cursor: pointer;
display: inline-block;
text-align: center;
line-height: 1;
letter-spacing: 2px;
font-family: Tahoma, Arial/9!important;
width: auto;
overflow: visible;
color: #333;
border: solid 1px #999;
-moz-border-radius: 5px;
-webkit-border-radius: 5px;
border-radius: 5px;
background: #DDD;
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFFFFF', endColorstr='#DDDDDD');
background: linear-gradient(top, #FFF, #DDD);
background: -moz-linear-gradient(top, #FFF, #DDD);
background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#FFF), to(#DDD));
text-shadow: 0px 1px 1px rgba(255, 255, 255, 1);
box-shadow: 0 1px 0 rgba(255, 255, 255, .7), 0 -1px 0 rgba(0, 0, 0, .09);
-moz-transition: -moz-box-shadow linear .2s;
-webkit-transition: -webkit-box-shadow linear .2s;
transition: box-shadow linear .2s;
outline: 0;
}
.small_btn:active{
border-color: #1c6a9e;
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#33bbee', endColorstr='#2288cc');
background: linear-gradient(top, #33bbee, #2288cc);
background: -moz-linear-gradient(top, #33bbee, #2288cc);
background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#33bbee), to(#2288cc));
}
</style>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
	$(function(){
		$('.see_qrcode').click(function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'查看渠道二维码',
				padding: 0,
				width: 430,
				height: 433,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: null,
				left: '50%',
				top: '38.2%',
				opacity:'0.4'
			});
			return false;
		});
		
		
	});
		
	function change(val){
		window.location.href="{pigcms{:U('Merchant_spread_user/index')}&reg="+$(val).val();
	}
</script>
<include file="Public:footer"/>
