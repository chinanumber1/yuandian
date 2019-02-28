<include file="Public:header"/>
		
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Shop/main_page')}" class="on">{pigcms{$config['shop_alias_name']}首页配置</a>
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/add_shop_center_type_img')}','添加中部导航',600,400,true,false,false,addbtn,'edit',true);">添加中部导航</a>
				
				</ul>
			</div>
		
			 <form id="myform" method="post" action="/admin.php?g=System&c=Config&a=amend" refresh="true"> 
			   <table cellpadding="0" cellspacing="0" class="table_form" width="100%"  id="tab_user_score">
				<tbody>
			
				 <tr>
				  <th width="160">是否开启{pigcms{$config['shop_alias_name']}首页中部广告显示</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable <php>if(C('config.shop_main_page_show_ad')==1){</php>selected<php>}</php>"><span>开启</span>
						<input type="radio" name="shop_main_page_show_ad" value="1" <php>if(C('config.shop_main_page_show_ad')==1){</php>checked="checked"<php>}</php>/>
						</label>
					</span>
					<span class="cb-disable">
						<label class="cb-disable <php>if(C('config.shop_main_page_show_ad')==0){</php>selected<php>}</php>"><span>关闭</span>
						<input type="radio" name="shop_main_page_show_ad" value="0" <php>if(C('config.shop_main_page_show_ad')==0){</php>checked="checked"<php>}</php>/>
						</label>
					</span>
					<em tips="是否开启{pigcms{$config['shop_alias_name']}首页中部广告显示" class="notice_tips"></em></td>
				 </tr>
		
				 <tr>
				  <th width="160">前台广告显示模式</th>
				  <td>
				
					<select name="shop_main_page_center_type" id="shop_main_page_center_type" class="valid">
						<option value="3" <if condition="($config['shop_main_page_center_type'] eq 3 AND $_GET['type'] eq '') OR $_GET['type'] eq 3">selected="selected"</if>>3图 纯图片模式</option>
						<option value="4" <if condition="($config['shop_main_page_center_type'] eq 4 AND $_GET['type'] eq '') OR $_GET['type'] eq 4">selected="selected"</if>>4图 纯图片模式</option>
						<option value="5" <if condition="($config['shop_main_page_center_type'] eq 5 AND $_GET['type'] eq '') OR $_GET['type'] eq 5">selected="selected"</if>>5图 纯图片模式</option>
						<!--option value="-3" <if condition="($config['shop_main_page_center_type'] eq -3 AND $_GET['type'] eq '') OR $_GET['type'] eq -3">selected</if>>3图 图文模式</option>
						<option value="-4" <if condition="($config['shop_main_page_center_type'] eq -4 AND $_GET['type'] eq '') OR $_GET['type'] eq -4">selected</if>>4图 图文模式</option>
						<option value="-5" <if condition="($config['shop_main_page_center_type'] eq -5 AND $_GET['type'] eq '') OR $_GET['type'] eq -5">selected</if>>5图 图文模式</option-->
						
					</select>
					<a href="javascript:void(0)" onclick="window.top.artiframe('{pigcms{:U('image_show')}','示例图',1320,825,true,false,false,'','show',true);" style="color:blue">示例图,用户前台广告位显示顺序按照排序值由高到低，从上往下，从左往右原则排序。</a>
				  </td>
				 </tr>
				 <input type="hidden" name="shop_main_page_show_type" id="shop_main_page_show_type" value="<if condition="isset($_GET['type'])"><if condition="$_GET['type'] gt 0">0<else />1</if><else />{pigcms{$config.shop_main_page_show_type}</if>">
				  <!--tr>
				  <th width="160">展示类型</th>
				  <td>
					<select name="shop_main_page_show_type" class="valid">
						<option value="0" <if condition="$config.shop_main_page_show_type eq 0">selected</if>>纯图片形式</option>
						<option value="1" <if condition="$config.shop_main_page_show_type eq 1">selected</if>>图片加文字形式</option>
						
					</select>
				  </td>
				 </tr-->
				 
				 
				  
				</tbody>
			   </table> 
			   <div class="btn" style="margin-top:20px;"> 
				<input type="submit" name="dosubmit" value="提交" class="button" /> 
				
			   </div> 
			</form> 
			
			<div class="table-list">
					<style>
					.table-list td{line-height:22px;padding-top:5px;padding-bottom:5px;}
					</style>
					<table width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>顺序</th>
								<th>标题</th>
								<th <if condition="$_GET['type'] gt 0 OR  $_GET['type'] eq ''">style="display:none"</if>>副标题</th>
								<th>链接地址</th>
								<th>图片</th>
								<th>最后更新时间</th>
								<th>操作</th>
								
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($slider_list)">
								<volist name="slider_list" id="vo">
									<tr>
										<td>{pigcms{$i}</td>
										<td>{pigcms{$vo.name}</td>
										<td <if condition="$_GET['type'] gt 0 OR $_GET['type'] eq ''">style="display:none"</if>>{pigcms{$vo.sub_name}</td>
										<td><a href="{pigcms{$vo.url}" target="_blank">访问链接</a></td>
										<td><img class="slider_pic" src="./upload/slider/{pigcms{$vo.pic}"></td>
										<td>{pigcms{$vo['last_time']|date='Y-m-d H:i:s',###}</td>
										<td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/add_shop_center_type_img',array('id'=>$vo['id']))}','查看图文详情',800,560,true,false,false,editbtn,'edit',true);">查看详情</a></td>
										
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="11">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="11">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
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
			.slider_pic{
				width:100px;
				height:60px;
				
			}
		</style>
		
		<script>
		$(function(){
			
			$("#shop_main_page_center_type").change(function(){
				window.location.href = "{pigcms{:U('main_page')}&type="+$(this).val()
			})
		});
		</script>
<include file="Public:footer"/>