<include file="Public:header"/>

	<form id="myform"  frame="true" refresh="true">
		  <ul class="tab_ul">
		 
			<li <if condition="$_GET['type'] eq 'merchant_store'">class="active"</if>><a data-toggle="tab" href="{pigcms{:U('Merchant/store_edit_percent',array('type'=>'merchant_store','store_id'=>$store_id))}" >店铺抽成设置</a></li>
			<li <if condition="$_GET['type'] eq 'group'">class="active"</if>><a data-toggle="tab" href="{pigcms{:U('Merchant/store_edit_percent',array('type'=>'group','store_id'=>$store_id))}" >{pigcms{$config.group_alias_name}</a></li>
			<li <if condition="$_GET['type'] eq 'shop'">class="active"</if>><a data-toggle="tab" href="{pigcms{:U('Merchant/store_edit_percent',array('type'=>'shop','store_id'=>$store_id))}" >{pigcms{$config.shop_alias_name}</a></li>
			<if condition="$config['pay_in_store']">
			<li <if condition="$_GET['type'] eq 'shop_offline'">class="active"</if>><a data-toggle="tab" href="{pigcms{:U('Merchant/store_edit_percent',array('type'=>'shop_offline','store_id'=>$store_id))}" >{pigcms{$config.shop_alias_name}线下零售</a></li>
			</if>
			<li <if condition="$_GET['type'] eq 'meal'">class="active"</if>><a data-toggle="tab" href="{pigcms{:U('Merchant/store_edit_percent',array('type'=>'meal','store_id'=>$store_id))}" >{pigcms{$config.meal_alias_name}</a></li>
			<if condition="C('config.appoint_page_row')">
			<li <if condition="$_GET['type'] eq 'appoint'">class="active"</if>><a data-toggle="tab" href="{pigcms{:U('Merchant/store_edit_percent',array('type'=>'appoint','store_id'=>$store_id))}" >{pigcms{$config.appoint_alias_name}</a></li>
			</if>
			
			
			<if condition="$config['is_cashier'] OR $config['pay_in_store']">
				<li <if condition="$_GET['type'] eq 'store'">class="active"</if>><a data-toggle="tab" href="{pigcms{:U('Merchant/store_edit_percent',array('type'=>'store','store_id'=>$store_id))}" >{pigcms{$config.cash_alias_name}</a></li>
			
			<li <if condition="$_GET['type'] eq 'cash'">class="active"</if>><a data-toggle="tab" href="{pigcms{:U('Merchant/store_edit_percent',array('type'=>'cash','store_id'=>$store_id))}" >到店消费</a></li>
			</if>
		
		  </ul>
		  <table cellpadding="0" cellspacing="0" class="table_form" width="100%" id="tab_mer_pr" style="display: table;">
		   <tbody>
			<tr>
			 <th width="160">店铺抽成比例：</th>
			 <td>
			 <span class="cb-enable">
				<label class="cb-enable  <php>if($mer_pr[$_GET['type'].'_percent']>=0&&$mer_pr[$_GET['type'].'_percent']!=''){</php>selected<php>}</php>">
					<span>设置</span>
					<input type="radio" name="open_percent" value="1" <php>if($mer_pr[$_GET['type'].'_percent']>=0&&$mer_pr[$_GET['type'].'_percent']!=''){</php>checked="checked"<php>}</php>/>
				</label>
			</span>			     
			<span class="cb-disable">
				<label class="cb-disable  <php>if($mer_pr[$_GET['type'].'_percent']<0||$mer_pr[$_GET['type'].'_percent']==''){</php>selected<php>}</php>">
					<span>跳过</span>
					<input type="radio" name="open_percent" value="0"   <php>if($mer_pr[$_GET['type'].'_percent']<0||$mer_pr[$_GET['type'].'_percent']==''){</php>checked="checked"<php>}</php>/>
				</label>
			</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			
			 <input type="text" id="percent" class="input-text" name="<php>echo $_GET['type'].'_percent';</php>"  value="<php>echo $mer_pr[$_GET['type'].'_percent'];</php>" size="10" validate="number:true,max:100" tips="店铺独立抽成比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前业务设置，向上查找<php>if($_GET['type']=='merchant'){echo '系统的相关业务';}else{echo '店铺抽成设置';}</php>的抽成比例）" <php>if($mer_pr[$_GET['type'].'_percent']<0||$mer_pr[$_GET['type'].'_percent']==''){</php>style="display:none"<php>}</php> /></td>
			</tr>
			
		
		   </tbody>
		  </table>
		 <p class="line_m detail">抽成细则（抽成金额范围按实际支付的金额计算）</p>
		 <table cellpadding="0" cellspacing="0" class="table_form detail" width="100%" id="tab_mer_pr_detail" style="display: table;overflow-y:scroll">
			<thead>
				<tr>
				<th>ID</th>
				<td>抽成范围</td>
				<td>抽成比例</td>
				</tr>
			</thead>
		   <tbody>
			<input type="hidden" name="type" value="<if condition="$_GET['type'] eq 'merchant'">merchant<else />{pigcms{$_GET['type']}</if>">
			<input type="hidden" name="store_id" value="{pigcms{$_GET['store_id']}">
	
			<volist name="percent_detail" id="vo">
				<tr>
					<th width="160">{pigcms{$i}</th>
					<td>{pigcms{$vo.money_start}---{pigcms{$vo.money_end}元</td>
					<td><input class="input-text valid" type="text" name="money_percent[]" value="<if condition="$detail[$i-1]==''">{pigcms{$vo.percent}<else />{pigcms{$detail[$i-1]}</if>"></td>
					<td class="textcenter" ></td>
				</tr>
			</volist>
		   </tbody>
		  </table>
		<div class="btn ">
			<input  type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
		</div>
	</form>

	<script>
		$(function(){
		
			$('#dosubmit').click(function(){
				$.post('{pigcms{:U('Merchant/store_edit_percent')}', $('#myform').serialize(), function(data, textStatus, xhr) {
					if(data.status==1){
						window.top.msg(2,data.info,true,2);
					}else{
						window.top.msg(0,data.info,true,2);
					}
					window.top.art.dialog({id:'store_edit_percent'}).close();
				});
			});
			
			$('.table_form:eq(0)').show();
			var open_percent=$("#percent").val();
			
			$('#percent').blur(function(){
				open_percent = $(this).val();
			})
			
			if(open_percent<0||open_percent==''){
				$('.detail').hide();
			}
			var percent = $('#percent');
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
			
			$('#im_key').click(function(){
				window.top.msg(2,'正在请求中,请稍等...',true,100);
				$.get("{pigcms{:U('Config/im')}",function(data){
					if(data.error_code){
						window.top.msg(0,data.msg,true,3);
					}else{
						window.top.msg(1,data.msg,true,3);
					}
				},'json');
			});
			$('#live_service_key').click(function(){
				window.top.msg(2,'正在请求中,请稍等...',true,100);
				$.get("{pigcms{:U('Config/live_service')}",function(data){
					if(data.error_code){
						window.top.msg(0,data.msg,true,3);
					}else{
						window.top.msg(1,data.msg,true,3);
					}
				},'json');
			});
			
		});
	</script>
	<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
	<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
	<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
	<script type="text/javascript">
		KindEditor.ready(function(K){
			var site_url = "{pigcms{$config.site_url}";
			var editor = K.editor({
				allowFileManager : true
			});
			$('.config_upload_image_btn').click(function(){
				var upload_file_btn = $(this);
				editor.uploadJson = "{pigcms{:U('Config/ajax_upload_pic')}";
				editor.loadPlugin('image', function(){
					editor.plugin.imageDialog({
						showRemote : false,
						clickFn : function(url, title, width, height, border, align) {
							upload_file_btn.siblings('.input-image').val(site_url+url);
							editor.hideDialog();
						}
					});
				});
			});
			$('.config_upload_file_btn').click(function(){
				var upload_file_btn = $(this);
				editor.uploadJson = "{pigcms{:U('Config/ajax_upload_file')}&name="+upload_file_btn.siblings('.input-file').attr('name');
				editor.loadPlugin('insertfile', function(){
					editor.plugin.fileDialog({
						showRemote : false,
						clickFn : function(url, title, width, height, border, align) {
							upload_file_btn.siblings('.input-file').val(url);
							editor.hideDialog();
						}
					});
				});
			});
		});
	</script>
	
	<style>
	.line_m{/*text-decoration:line-through*/text-align:center} 
		.table_form{border:1px solid #ddd;}
		.tab_ul{margin-top:10px;border-color:#C5D0DC;margin-bottom:0!important;margin-left:0;position:relative;top:1px;border-bottom:1px solid #ddd;padding-left:0;list-style:none;}
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
			min-width: 50px;
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