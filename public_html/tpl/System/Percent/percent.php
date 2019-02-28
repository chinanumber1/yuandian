<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Percent/percent')}" <if condition="empty($_GET['type'])">class="on"</if>>平台抽成设置</a>
					<a href="{pigcms{:U('Percent/percent',array('type'=>'group'))}" <if condition="$_GET['type'] eq 'group'">class="on"</if>>{pigcms{$config.group_alias_name}</a>
					<a href="{pigcms{:U('Percent/percent',array('type'=>'shop'))}" <if condition="$_GET['type'] eq 'shop'">class="on"</if>>{pigcms{$config.shop_alias_name}</a>
					<if condition="$config['pay_in_store']">
					<a href="{pigcms{:U('Percent/percent',array('type'=>'shop_offline'))}" <if condition="$_GET['type'] eq 'shop_offline'">class="on"</if>>{pigcms{$config.shop_alias_name}线下零售</a>
					</if>
					<a href="{pigcms{:U('Percent/percent',array('type'=>'meal'))}" <if condition="$_GET['type'] eq 'meal'">class="on"</if>>{pigcms{$config.meal_alias_name}</a>
					<if condition="C('config.appoint_page_row')">
					<a href="{pigcms{:U('Percent/percent',array('type'=>'appoint'))}" <if condition="$_GET['type'] eq 'appoint'">class="on"</if>>{pigcms{$config.appoint_alias_name}</a>
					</if>
					<if condition="$config['wxapp_url']"><a href="{pigcms{:U('Percent/percent',array('type'=>'wxapp'))}" <if condition="$_GET['type'] eq 'wxapp'">class="on"</if>>营销</a></if>
					<if condition="$config['is_open_weidian']"><a href="{pigcms{:U('Percent/percent',array('type'=>'weidian'))}" <if condition="$_GET['type'] eq 'weidian'">class="on"</if>>微店</a>
					</if>
					<if condition="$config['is_cashier'] OR $config['pay_in_store']">
					<a href="{pigcms{:U('Percent/percent',array('type'=>'store'))}" <if condition="$_GET['type'] eq 'store'">class="on"</if>>{pigcms{$config.cash_alias_name}</a>
					<a href="{pigcms{:U('Percent/percent',array('type'=>'cash'))}" <if condition="$_GET['type'] eq 'cash'">class="on"</if>>到店消费</a></if>
					<a href="{pigcms{:U('Percent/percent',array('type'=>'activity'))}" <if condition="$_GET['type'] eq 'activity'">class="on"</if>>平台活动</a>
				</ul>
			</div>
			  <form id="myform" method="post" action="{pigcms{:U('Percent/percent')}" refresh="true"> 
				<table cellpadding="0" cellspacing="0" class="table_form" width="100%">
					<php> if($_GET['type']=='meal' && $config['open_meal_scan_percent'] == 1){</php>
						<tr>
							<th width="160">{pigcms{$meal_alias_name}扫码抽成比例</th>				
							<td>		
								<span class="cb-enable">
									<label class="cb-enable  <php>if($config['meal_scan_percent']>=0&&$config['meal_scan_percent']!=''){</php>selected<php>}</php>">
										<span>设置</span>
										<input type="radio" name="open_meal_scan_percent" value="1" <php>if($config['meal_scan_percent']>=0&&$config['meal_scan_percent']!=''){</php>checked="checked"<php>}</php>/>
									</label>
								</span>			     
								<span class="cb-disable">
									<label class="cb-disable  <php>if($config['meal_scan_percent']<0||$config['meal_scan_percent']==''){</php>selected<php>}</php>">
										<span>跳过</span>
										<input type="radio" name="open_meal_scan_percent" value="0" <php>if($config['meal_scan_percent']<0||$config['meal_scan_percent']==''){</php>checked="checked"<php>}</php>/>
									</label>
								</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								 <input type="text" class="input-text" name="meal_scan_percent" id="meal_scan_percent" value="{pigcms{$config.meal_scan_percent}" size="10" validate="number:true,max:100" tips="抽成比例（按百分比，不要填写%）" <php>if($config['meal_scan_percent']<0||$config['meal_scan_percent']==''){</php>style="display:none"<php>}</php>/>
							</td>
						</tr>
					<php> }</php>
					<tr>
						<th width="160">平台抽成比例</th>				
						<td>
							<php> if(empty($_GET['type'])){</php>
							<input  class="input-text valid" size="10" type="text"   name="platform_get_merchant_percent" value="{pigcms{$config.platform_get_merchant_percent}">
							<php> }else{</php>
								<span class="cb-enable">
									<label class="cb-enable  <php>if($config[$_GET['type'].'_percent']>=0&&$config[$_GET['type'].'_percent']!=''){</php>selected<php>}</php>">
										<span>设置</span>
										<input type="radio" name="open_percent" value="1" <php>if($config[$_GET['type'].'_percent']>=0&&$config[$_GET['type'].'_percent']!=''){</php>checked="checked"<php>}</php>/>
									</label>
								</span>			     
								<span class="cb-disable">
									<label class="cb-disable  <php>if($config[$_GET['type'].'_percent']<0||$config[$_GET['type'].'_percent']==''){</php>selected<php>}</php>">
										<span>跳过</span>
										<input type="radio" name="open_percent" value="0" <php>if($config[$_GET['type'].'_percent']<0||$config[$_GET['type'].'_percent']==''){</php>checked="checked"<php>}</php>/>
									</label>
								</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							 <input type="text" class="input-text" name="<php>echo $_GET['type'].'_percent';</php>" id="percent" value="<php> echo $config[$_GET['type'].'_percent'];</php>" size="10" validate="number:true,max:100" tips="抽成比例（按百分比，不要填写%）" <php>if($config[$_GET['type'].'_percent']<0||$config[$_GET['type'].'_percent']==''){</php>style="display:none"<php>}</php>/>
						 
						 
							<php> }</php>
							
						</td>
					</tr>
					
					<input type="hidden" name="type" value="{pigcms{$_GET['type']}">
				</table>
				<div class="table-list detail">
					<table width="100%" cellspacing="0">
						<colgroup><col> <col> <col> <col width="240" align="center"> </colgroup>
						<thead>
							<tr>
								<th>ID</th>
								<th>抽成范围(<font color="red">请填写大于0的数字</font>)</th>
								<th>抽成比例(<font color="red">请填写0-100的数字</font>)</th>
								<th class="textcenter"><if condition="empty($_GET['type'])" >操作</if></th>
							</tr>
						</thead>
						<tbody>
							
							<if condition="$percent_detail AND empty($_GET['type'])">
								<volist name="percent_detail" id="vo">
									<tr class="plus">
										<td class="sort">{pigcms{$i}</td>
										<td><input class="input-text valid" type="text" name="money_start[]" value="{pigcms{$vo.money_start}" >--- <input  class="input-text valid" type="text" name="money_end[]" value="{pigcms{$vo.money_end}"></td>
										<td><input class="input-text valid" type="text" name="money_percent[]" value="{pigcms{$vo.percent}" ></td>
										<td class="textcenter" ><a href="javascript:void(0);" class="delete" parameter="id={pigcms{$vo.id}" url="" onclick="del(this)">删除</a></td>
									</tr>
								</volist>
								<tr style="display:none"><td class="textcenter pagebar" colspan="3">{pigcms{$pagebar}</td></tr>
							<elseif condition="!empty($_GET['type'])" />
								<volist name="percent_detail" id="vo">
									<tr >
										<td class="sort">{pigcms{$i}</td>
										<td>{pigcms{$vo.money_start}---{pigcms{$vo.money_end}</td>
										<td><input class="input-text valid" type="text" name="money_percent[]" value="<php> if($detail[$i-1]==''){ echo $vo['percent']; }else{echo $detail[$i-1]; }</php>" ></td>
										<td class="textcenter" ></td>
									</tr>
								</volist>
							<else/>
								<tr class="plus">
									<td class="sort">1</td>
									<td><input class="input-text valid" type="text" name="money_start[]" value="" >--- <input  class="input-text valid" type="text" name="money_end[]" value="" ></td>
									<td><input class="input-text valid" type="text" name="money_percent[]" value="" ></td>
									<td class="textcenter" ><a href="javascript:void(0);" class="delete" parameter="id={pigcms{$vo.id}" url="" onclick="del(this)">删除</a></td>
								</tr>
								
							</if>
							<tr>
								<if condition="empty($_GET['type'])" ><td colspan="3" style="text-align:center;color:blue" onclick="add();">[增加]</td></if>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="btn">
					<input type="submit"  name="dosubmit" value="提交" class="button" />
				</div>
			</form>
		</div>
		<script>
			$(function(){
				var percent = $('#percent');
				var meal_scan_percent = $('#meal_scan_percent');
				
				var open_percent = percent.val();
				var open_meal_percent = meal_scan_percent.val();
				$('#percent').blur(function(){
					open_percent = $(this).val();
				})

				$('#meal_scan_percent').blur(function(){
					open_meal_percent = $(this).val();
				})
				
				if(open_percent<0||open_percent==''){
					$('.detail').hide();
				}
				
				$('input[name="open_percent"]').click(function(){
					if($(this).val()==1){
						percent.show();
						if(open_percent<0){
							percent.val('');
						}else{
							percent.val(open_percent);
						}
						$('.detail').show();
					}else{
						percent.hide();
						percent.attr('value',-1);
						$('.detail').hide();
					}
				});

				$('input[name="open_meal_scan_percent"]').click(function(){
					if($(this).val()==1){
						meal_scan_percent.show();
						if(open_meal_percent<0){
							meal_scan_percent.val('');
						}else{
							meal_scan_percent.val(open_meal_percent);
						}
					
					}else{
						meal_scan_percent.hide();
						meal_scan_percent.attr('value',-1);
						
					}
				});
			
			
				// $('input[name="dosubmit"]').click(function(){
					// $.ajax({
						// url: '{pigcms{:U('Percent/index')}',
						// type:'POST',
						// dataType:'json',
						// data:$('#myform').serialize(),
						// beforeSend:function () {
							// window.top.msg(2,'表单提交中，请稍等...',true,360);
						// },
						// success:function (data) {
							// if(data.status == 1){
								// window.top.msg(1,data.info,true);
								// window.top.main_refresh();
								// window.top.closeiframe();
							// }else{
								// window.top.msg(0,data.info,true);
							// }
						// }
					// });
				// });
				
			})
			function add(){
				var item = $('.plus:last');
				if($('.plus').length<=1&&$('.plus').css('display')=='none'){
					$('.plus').show();
				}else{
					var newitem = $(item).clone(true);
					var No = parseInt(item.find(".sort").html())+1;
					$(item).after(newitem);
					newitem.find('input').attr('value','');
					newitem.find(".sort").html(No);
				}
				
			}
			
			function del(obj){
				if($('.plus').length<1){
					$('.plus').hide();
				}else{
					$(obj).parents('.plus').remove();
					$.each($('.plus'), function(index, val) {
						var No =index+1;
						$(val).find('.sort').html(No);
						$(val).find('input[name="url[]"]').attr('id','url'+No);
						
					});
				}
			}
		</script>
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