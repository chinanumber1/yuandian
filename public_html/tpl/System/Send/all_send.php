<include file="Public:header"/>		<div class="mainbox">			<div id="nav" class="mainnav_title">				<a href="{pigcms{:U('Send/all_send')}" class="on">群发列表</a>				<a href="{pigcms{:U('Send/add_all_send',array('edit'=>1))}" >创建群发</a>			</div>					<div class="alert alert-info" style="margin:10px 0;background-color: #d9edf7;    border-color: #bce8f1;    color: #31708f;    padding: 15px;    margin-bottom: 20px;    border: 1px solid transparent;    border-radius: 4px;    font-size: 16px;">			1.由于群发任务彻底完成需要较长时间,送达成功人数和送达失败人数统计需要一段时间，群发状态中的“成功/失败”也需要时间才能显示</br>			2. 群发消息已经发送则不允许编辑图文</br>			3. 在48小时内未与公众号互动的粉丝则会通过微信模板消息收到相关群发消息</br>		</div>			<form name="myform" id="myform" action="" method="post">				<div class="table-list">					<table width="100%" cellspacing="0">						<colgroup><col> <col> <col><col>  <col width="180" align="center"> </colgroup>						<thead>							<tr>								<th>编号</th>								<th>群发标题</th>								<th>发送对象</th>								<th>送达成功人数</th>								<th>送达失败人数</th>								<th>发布时间</th>								<th>发送状态</th>								<th class="textcenter">操作</th>							</tr>						</thead>						<tbody>							<if condition="$list">								<volist name="list" id="vo">									<tr>										<td>{pigcms{$vo.pigcms_id}</td>										<td>{pigcms{$vo.title}</td>																			<td>											{pigcms{$send_type[$vo['send_type']]}										</td>										<td>											{pigcms{$vo.success_num|floatval}										</td>										<td>											{pigcms{$vo.fail_num|floatval}										</td>										<td>											<if condition="$vo.dateline gt 0">{pigcms{$vo.dateline|date="Y-m-d H:i:s",###}<else />及时发送</if>										</td>										<td>											<if condition="$vo['dateline'] gt time()">												<a class="send_type">未到发送时刻</a>											<elseif condition="$vo['sended'] eq 1" />																							<a class="send_type" style="background-color:green;">已发送</a>												<else />												<a class="send_type" >未发送</a>											</if>										</td>										<td class="textcenter">											<if condition="$vo.sended eq 0"><a href="{pigcms{:U('Send/add_all_send',array('id'=>$vo['pigcms_id'],'edit'=>1))}" >编辑</a> |											<a href="javascript:void(0);" onclick="preview_send('{pigcms{$vo.pigcms_id}')">预览</a> |  </if>											<a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.pigcms_id}" url="{pigcms{:U('Send/send_all_del')}">删除</a> | 											<a href="{pigcms{:U('Send/add_all_send',array('id'=>$vo['pigcms_id'],'edit'=>0))}" >查看群发</a> 																				</td>									</tr>								</volist>								<tr><td class="textcenter pagebar" colspan="9">{pigcms{$pagebar}</td></tr>							<else/>								<tr><td class="textcenter red" colspan="5">列表为空！</td></tr>							</if>						</tbody>					</table>				</div>			</form>		</div>				<style>		.send_type{					    color: #fff;			padding: .2em .4em .3em;			font-size: 11px;			line-height: 1;			height: 18px;			background-color: #f89406;			border-radius: 0;		}		</style>		<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">		<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>		<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>		<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>		<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>		<script type="text/javascript" src="./static/js/upyun.js"></script>		<script>			function preview_send(id){				art.dialog.open('?g=System&c=Send&a=preview_send&id='+id,{lock:true,title:'预览',width:600,height:400,yesText:'关闭',background: '#000',opacity: 0.45});			}		</script><include file="Public:footer"/>