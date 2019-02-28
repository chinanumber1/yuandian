<include file="Public:header"/>
		<div class="mainbox">
			<if condition="empty($_GET['galias'])">
				<div id="nav" class="mainnav_title">
					<ul>
						<volist name="group_list" id="vo">
							<a href="{pigcms{:U('Config/index',array('gid'=>$vo['gid']))}" <if condition="$gid eq $vo['gid']">class="on"</if>>{pigcms{$vo.gname}</a>|
						</volist>
					</ul>
				</div>
			<else/>
				<if condition="$header_file">
					<include file="$header_file"/>
				</if>
			</if>
			<if condition="$system_session['level'] eq 2 && in_array($gid,array(7,8,22,34,43))">
				<div class="page_tips">
					<ol>
						<if condition="$gid eq 7">
							<li>1.微信支付 对接文档&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E5%BE%AE%E4%BF%A1%E6%94%AF%E4%BB%98.pdf" target="_blank">（PDF版）</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E5%BE%AE%E4%BF%A1%E6%94%AF%E4%BB%98.doc" target="_blank">（WORD版）</a></li>
							<li>2.支付宝H5 对接文档&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E6%94%AF%E4%BB%98%E5%AE%9DH5.pdf" target="_blank">（PDF版）</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E6%94%AF%E4%BB%98%E5%AE%9DH5.doc" target="_blank">（WORD版）</a></li>
						<elseif condition="$gid eq 43"/>
							<li>1.支付宝H5 对接文档&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E6%94%AF%E4%BB%98%E5%AE%9DH5.pdf" target="_blank">（PDF版）</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E6%94%AF%E4%BB%98%E5%AE%9DH5.doc" target="_blank">（WORD版）</a></li>
						<elseif condition="$gid eq 8"/>
							<li>1.微信公众号 对接文档&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E5%BE%AE%E4%BF%A1%E5%85%AC%E4%BC%97%E5%8F%B7%E5%AF%B9%E6%8E%A5.pdf" target="_blank">（PDF版）</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E5%BE%AE%E4%BF%A1%E5%85%AC%E4%BC%97%E5%8F%B7%E5%AF%B9%E6%8E%A5.doc" target="_blank">（WORD版）</a></li>
						<elseif condition="$gid eq 22"/>
							<li>1.车牌识别配置 对接文档&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E8%BD%A6%E7%89%8C%E8%AF%86%E5%88%AB%E9%85%8D%E7%BD%AE.pdf" target="_blank">（PDF版）</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E8%BD%A6%E7%89%8C%E8%AF%86%E5%88%AB%E9%85%8D%E7%BD%AE.doc" target="_blank">（WORD版）</a></li>
						<elseif condition="$gid eq 34"/>
							<li>1.支付宝H5 对接文档&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E6%94%AF%E4%BB%98%E5%AE%9DH5.pdf" target="_blank">（PDF版）</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E6%94%AF%E4%BB%98%E5%AE%9DH5.doc" target="_blank">（WORD版）</a></li>
						</if>
					</ol>
				</div>
			</if>
			<form id="myform" method="post" action="{pigcms{:U('Config/amend')}" refresh="true">
				{pigcms{$config_tab_html}
				{pigcms{$config_html}
				<div class="btn" style="margin-top:20px;">
					<input TYPE="submit"  name="dosubmit" value="提交" class="button" />
					<input type="reset"  value="取消" class="button" />
					<if condition="empty($_GET['galias'])">
						<input type="button"  value="获取及时聊天的key" class="button" id="im_key"/>
						<input type="button"  value="微信API接口填写信息" class="button" onclick="window.top.artiframe('{pigcms{:U('Config/show',array('id'=>$vo['id']))}','API接口信息',560,100,true,false,false,'','add',true);"/>
						<input type="button"  value="获取生活服务充值的key" class="button" id="live_service_key"/>
					</if>
				</div>
			</form>
		</div>
		<script>
			$(function(){
				$('.table_form:eq(0)').show();
				
				$('.tab_ul li a').click(function(){
					$(this).closest('li').addClass('active').siblings('li').removeClass('active');
					$($(this).attr('href')).show().siblings('.table_form').hide();
					return false;
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
					setTimeout(function(){
						$('.ke-dialog').css('top',$('#Main_content',parent.document).scrollTop()+((screen.height-$('.ke-dialog').height())/2)+'px');
					},200);
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
					setTimeout(function(){
						$('.ke-dialog').css('top',$('#Main_content',parent.document).scrollTop()+((screen.height-$('.ke-dialog').height())/2)+'px');
					},200);
				});
				
				window.editor = K.create('#config_register_agreement',{pasteType : 1});
				window.editor = K.create('#config_store_register_agreement',{pasteType : 1});
			});
		</script>
		<style>
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