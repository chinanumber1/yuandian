<include file="Public:header"/>		<div class="mainbox">			<div id="nav" class="mainnav_title">				<ul>                    <a href="{pigcms{:U('Community/index')}">社群列表</a>|                    <a href="{pigcms{:U('Community/folder_list',array('community_id'=>$_GET['community_id']))}">文件夹列表</a>|                    <a href="{pigcms{:U('Community/file_list',array('community_id'=>$_GET['community_id'], 'folder_id'=>$_GET['folder_id']))}"  class="on">{pigcms{$now_file.folder_name}-文件列表</a>                </ul>			</div>            <table class="search_table" width="100%">                <tr>                    <td style="width:60%;">                        <form action="{pigcms{:U('Community/file_list')}" method="get">                            <input type="hidden" name="c" value="Community"/>                            <input type="hidden" name="a" value="file_list"/>                            <input type="hidden" name="community_id" value="{pigcms{$_GET['community_id']}"/>                            <input type="hidden" name="folder_id" value="{pigcms{$_GET['folder_id']}"/>                            文件名称:                            <input type="text" name="file_remark" class="input-text" value="{pigcms{$_GET['file_remark']}"/>                            <input type="submit" value="查询" class="button"/>                        </form>                    </td>                </tr>            </table>						<form name="myform" id="myform" action="" method="post">				<div class="table-list">					<style>					.table-list td{line-height:22px;padding-top:5px;padding-bottom:5px;}					</style>					<table width="100%" cellspacing="0">						<colgroup>							<col/>							<col/>							<col/>							<col/>							<col/>							<col/>							<col width="100" align="center"/>						</colgroup>						<thead>							<tr>								<th>编号</th>                                <th>文件名</th>                                <th>创建者</th>                                <th>状态</th>                                <th>添加时间</th>                                <th>开启/禁止</th>                                <th class="textcenter">操作</th>							</tr>						</thead>						<tbody>							<if condition="$community_file">								<volist name="community_file" id="vo">									<tr>										<td>{pigcms{$vo.file_id}</td>                                        <td>{pigcms{$vo.file_remark}</td>                                        <td>{pigcms{$vo.nickname}</td>                                        <td>                                            <if condition="$vo['file_status'] eq 1">                                                开启                                                <else/>                                                禁止                                            </if>                                        </td>                                        <td>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</td>                                        <td>                                            <span class="cb-enable"><label class="cb-enable <if condition="$vo['file_status'] eq 1">selected</if>"><span onclick="fileSwitch('{pigcms{$vo.file_id}', 1)" >开启</span><input type="radio" value="1" <if condition="$vo['file_status'] eq 1">checked="checked"</if> /></label></span>                                            <span class="cb-disable"><label class="cb-disable <if condition="$vo['file_status'] eq 2">selected</if>"><span onclick="fileSwitch('{pigcms{$vo.file_id}', 2)">禁止</span><input type="radio" value="2" <if condition="$vo['file_status'] eq 2">checked="checked"</if> /></label></span>                                        </td>                                        <td class="textcenter">                                            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Community/look_or_download',array('file_id'=>$vo['file_id']))}','查看信息',700,500,true,false,false,false,'detail',true);" >查看/下载</a>                                        </td>                                    </tr>								</volist>								<tr><td class="textcenter pagebar" colspan="11">{pigcms{$page}</td></tr>							<else/>								<tr><td class="textcenter red" colspan="11">列表为空！</td></tr>							</if>						</tbody>					</table>				</div>			</form>		</div>		<script type="text/javascript" src="{pigcms{$static_path}js/area_pca.js"></script>        <script>            var lockInfo = false;            function fileSwitch(file_id, status) {                if (lockInfo) return;                lockInfo = true;                var url = '';                var close_file = "{pigcms{:U('Community/close_file')}";                var open_file = "{pigcms{:U('Community/open_file')}";                if (1 == status) {                    url = open_file;                } else if(2 == status) {                    url = close_file;                } else {                    return;                }                $.post(url,{'file_id': file_id},function(data){                    console.log(data)                    if (data && (data.status === 1 || data.status === '1')) {                        location.reload();                    } else {                        alert(data.info)                    }                },'json')                console.log('数据信息', type_id, status)                setTimeout(function(){                    lockInfo = false;                },1000);            }        </script><include file="Public:footer"/>