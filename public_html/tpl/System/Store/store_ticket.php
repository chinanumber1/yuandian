<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('buy_order')}">{pigcms{$config.cash_alias_name}订单列表</a>
					<a href="{pigcms{:U('Config/index',array('galias'=>'quickpay','header'=>'Store/buy_order_header'))}">{pigcms{$config.cash_alias_name}配置</a>
					<a href="{pigcms{:U('store_ticket')}" class="on">票务插件</a>
				</ul>
			</div>
			<form method="post" action="" refresh="true" enctype="multipart/form-data" >
				<table cellpadding="0" cellspacing="0" class="table_form" width="100%">
					<tr>
						<th colspan="2" style="text-align:left;">票务插件作为{pigcms{$config.cash_alias_name}针对线下票务市场（可用于景点售票、公交车票、长途客车票等）的补充。<!--支付手续费目前也纳入到商家订单金额，进行统一的抽成。--></th>
					</tr>
					<tr>
						<th width="160">开启票务插件</th>
						<td>
							<span class="cb-enable">
								<label class="cb-enable  <php>if(C('config.store_ticket_have')==1){</php>selected<php>}</php>">
									<span>开启</span>
									<input type="radio" name="store_ticket_have" value="1" <php>if(C('config.store_ticket_have')==1){</php>checked="checked"<php>}</php>/>
								</label>
							</span>			     
							<span class="cb-disable">
								<label class="cb-disable  <php>if(C('config.store_ticket_have') == 0){</php>selected<php>}</php>">
									<span>关闭</span>
									<input type="radio" name="store_ticket_have" value="0" <php>if(C('config.store_ticket_have') == 0){</php>checked="checked"<php>}</php>/>
								</label>
							</span>
						</td>
					</tr>
					<tr>
						<th width="160">商家独立销售保险</th>
						<td>
							<span class="cb-enable">
								<label class="cb-enable  <php>if(C('config.store_ticket_have_insure')==1){</php>selected<php>}</php>">
									<span>允许</span>
									<input type="radio" name="store_ticket_have_insure" value="1" <php>if(C('config.store_ticket_have_insure')==1){</php>checked="checked"<php>}</php>/>
								</label>
							</span>			     
							<span class="cb-disable">
								<label class="cb-disable  <php>if(C('config.store_ticket_have_insure') == 0){</php>selected<php>}</php>">
									<span>不允许</span>
									<input type="radio" name="store_ticket_have_insure" value="0" <php>if(C('config.store_ticket_have_insure') == 0){</php>checked="checked"<php>}</php>/>
								</label>
							</span>
						</td>
					</tr>
					<!--tr>
						<th width="160">额外收取支付手续费</th>
						<td>
							<span class="cb-enable">
								<label class="cb-enable  <php>if(C('config.store_ticket_have_charge')==1){</php>selected<php>}</php>">
									<span>收取</span>
									<input type="radio" name="store_ticket_have_charge" value="1" <php>if(C('config.store_ticket_have_charge')==1){</php>checked="checked"<php>}</php>/>
								</label>
							</span>			     
							<span class="cb-disable">
								<label class="cb-disable  <php>if(C('config.store_ticket_have_charge') == 0){</php>selected<php>}</php>">
									<span>不收取</span>
									<input type="radio" name="store_ticket_have_charge" value="0" <php>if(C('config.store_ticket_have_charge') == 0){</php>checked="checked"<php>}</php>/>
								</label>
							</span>
						</td>
					</tr>
					<tr>
						<th width="160">单张票满免支付手续费金额</th>
						<td>
							<input  class="input-text valid" size="10" type="text"  validate="required:true,min:0" name="store_ticket_no_charge" value="{pigcms{$config.store_ticket_no_charge}" /> 元
						</td>
					</tr>
					<tr>
						<th width="160">支付手续费（第一档）</th>
						<td>
							满 <input  class="input-text valid" size="10" type="text"  validate="required:true,min:0" name="store_ticket_charge_money_1" value="{pigcms{$config.store_ticket_charge_money_1}" /> 元收取 <input  class="input-text valid" size="10" type="text"  validate="required:true,min:0" name="store_ticket_charge_1" value="{pigcms{$config.store_ticket_charge_1}"/> 手续费
						</td>
					</tr>
					<tr>
						<th width="160">支付手续费（第二档）</th>
						<td>
							满 <input  class="input-text valid" size="10" type="text"  validate="required:true,min:0" name="store_ticket_charge_money_2" value="{pigcms{$config.store_ticket_charge_money_2}" /> 元收取 <input  class="input-text valid" size="10" type="text"  validate="required:true,min:0" name="store_ticket_charge_2" value="{pigcms{$config.store_ticket_charge_2}" /> 手续费
						</td>
					</tr>
					<tr>
						<th width="160">支付手续费（第三档）</th>
						<td>
							满 <input  class="input-text valid" size="10" type="text"  validate="required:true,min:0" name="store_ticket_charge_money_3" value="{pigcms{$config.store_ticket_charge_money_3}" /> 元收取 <input  class="input-text valid" size="10" type="text"  validate="required:true,min:0" name="store_ticket_charge_3" value="{pigcms{$config.store_ticket_charge_3}" /> 手续费
						</td>
					</tr-->
				</table>
				<div class="btn">
					<input type="submit"  name="dosubmit" value="提交" class="button" />
					<input type="reset"  value="取消" class="button" />
				</div>
			</form>
		</div>
		<style>
			.table_form{border:1px solid #ddd;}
			.tab_ul{margin-top:20px;border-color:#C5D0DC;margin-bottom:0!important;margin-left:0;position:relative;top:1px;border-bottom:1px solid #ddd;padding-left:0;list-style:none;}
			.tab_ul>li{position:relative;display:block;float:left;margin-bottom:-1px;}
			.tab_ul>li>a {
				position: relative;
				display: block;
				padding: 10px 15px;
				margin-right: 2px;
				line-height: 1.42857143;
				border: 1px solid transparent;
				border-radius: 4px 4px 0 0;
				padding: 7px 12px 8px;
				min-width: 100px;
				text-align: center;
				}
				.tab_ul>li>a, .tab_ul>li>a:focus {
				border-radius: 0!important;
				border-color: #c5d0dc;
				background-color: #F9F9F9;
				color: #999;
				margin-right: -1px;
				line-height: 18px;
				position: relative;
				}
				.tab_ul>li>a:focus, .tab_ul>li>a:hover {
				text-decoration: none;
				background-color: #eee;
				}
				.tab_ul>li>a:hover {
				border-color: #eee #eee #ddd;
				}
				.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
				color: #555;
				background-color: #fff;
				border: 1px solid #ddd;
				border-bottom-color: transparent;
				cursor: default;
				}
				.tab_ul>li>a:hover {
				background-color: #FFF;
				color: #4c8fbd;
				border-color: #c5d0dc;
				}
				.tab_ul>li:first-child>a {
				margin-left: 0;
				}
				.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
				color: #576373;
				border-color: #c5d0dc #c5d0dc transparent;
				border-top: 2px solid #4c8fbd;
				background-color: #FFF;
				z-index: 1;
				line-height: 18px;
				margin-top: -1px;
				box-shadow: 0 -2px 3px 0 rgba(0,0,0,.15);
				}
				.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
				color: #555;
				background-color: #fff;
				border: 1px solid #ddd;
				border-bottom-color: transparent;
				cursor: default;
				}
				.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
				color: #576373;
				border-color: #c5d0dc #c5d0dc transparent;
				border-top: 2px solid #4c8fbd;
				background-color: #FFF;
				z-index: 1;
				line-height: 18px;
				margin-top: -1px;
				box-shadow: 0 -2px 3px 0 rgba(0,0,0,.15);
				}
				.tab_ul:before,.tab_ul:after{
				content: " ";
				display: table;
				}
				.tab_ul:after{
				clear: both;
				}
		</style>
<include file="Public:footer"/>