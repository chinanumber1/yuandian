<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('FilterWord/index')}" class="on">敏感词列表</a>
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('FilterWord/add_word')}','添加敏感词',400,120,true,false,false,addbtn,'edit',true);">添加敏感词</a>
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('FilterWord/mutil_add')}','批量添加敏感词',680,560,true,false,false,addbtn,'edit',true);">批量添加敏感词</a>
				
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('FilterWord/index')}" method="get">
							<input type="hidden" name="c" value="FilterWord"/>
							<input type="hidden" name="a" value="index"/>
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
					
							<input type="submit" value="查询" class="button"/> 
						</form>
					</td>
				</tr>
			</table>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup>
							<col/>
			
							<col/>
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>ID</th>
								<th>敏感词</th>
				
					
								<th class="textcenter">编辑</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($word_list)">
								<volist name="word_list" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{$vo.word}</td>
									
										<td class="textcenter">
										
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('FilterWord/edit_word',array('id'=>$vo['id']))}','编辑敏感词信息',400,120,true,false,false,editbtn,'add',true);">编辑</a>
										<a href="javascript:void(0);" class="delete_row" parameter="id=10" url="{pigcms{:U('FilterWord/del_word',array('id'=>$vo['id']))}">删除</a>

									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="9">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="8">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>