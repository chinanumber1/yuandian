<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>技能管理</title>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}yuedan/css/my_fabu.css"/>
    <script src="{pigcms{$static_path}yuedan/js/jquery-1.9.1.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
</head>
<body>
	<header>
		<a href="JavaScript:history.back(-1)" class="ft"><i></i></a>
		<span>技能管理</span>
	</header>
	
	<div class="personality fuwu">
		<volist name="service_list" id="vo">
			<div class="sevice after">
				
					<div class="top">
						<a href="{pigcms{:U('Yuedan/service_detail',array('rid'=>$vo['rid']))}">
							<img src="{pigcms{$vo.listimg}"/>
							<ul>
								<li>{pigcms{$vo.title}</li>
								<li>￥{pigcms{$vo.price}/{pigcms{$vo.unit}</li>
							</ul>
						</a>
						<if condition="$vo['status'] eq 1">
							<span class="dai">待审核 </span>
							
						<elseif condition="$vo['status'] eq 3"/>
							<span class="fail" onclick="reasons_tip('{pigcms{$vo.reasons}')">审核不通过 <i class="tishi"></i></span>

						<elseif condition="$vo['status'] eq 4"/>
							<span class="fail">技能关闭中</span>
						</if>
					</div>
				
				<div class="bottom rg" style="width: 90%;">
					<button type="button" onclick='serviceEdit("{pigcms{$vo.rid}")' style="margin-right:0;">编辑</button>
					<button type="button" onclick='serviceDelete("{pigcms{$vo.rid}")'>删除</button>
					
					

					<if condition="$vo['status'] eq 2">
						<button type="button" onclick='serviceClose("{pigcms{$vo.rid}",4)'>关闭</button>
					<elseif condition="$vo['status'] eq 4"/>
						<button type="button" onclick='serviceClose("{pigcms{$vo.rid}",2)'>开启</button>
					</if>

				</div>
			</div>
		</volist>
	</div>

	<script>

		function reasons_tip(content){
			//自定义标题风格
			layer.open({
				title: [
					'拒绝理由',
					'background-color: red; color:#fff;'
				]
				,content: content
			});
			return false;
		}

		function serviceEdit(rid){
			location.href = "{pigcms{:U('Yuedan/my_service_save')}&rid="+rid;
		}
		
		function serviceDelete(rid){
			//询问框
		    layer.open({
		        content: '确定要删除此技能吗？'
		        ,btn: ['确定', '取消']
		        ,yes: function(index){
		            var my_service_del_url = "{pigcms{:U('Yuedan/my_service_del')}";
		            $.post(my_service_del_url,{'rid':rid},function(data){
		                if(data.error == 1){
		                    layer.open({
		                        content: data.msg
		                        ,btn: ['确定']
		                        ,yes: function(index){
		                            location.href = location.href;
		                            layer.close(index);
		                        }
		                    });
		                }else{
		                    layer.open({
		                        content: data.msg
		                        ,btn: ['确定']
		                    });
		                }
		            },'json')
		        }
		    });
		}

		function serviceClose(rid,status){
			if(status == 4){
				var content = "确定要关闭此技能吗？";
			}else{
				var content = "确定要开启此技能吗？";
			}
			//询问框
		    layer.open({
		        content: content
		        ,btn: ['确定', '取消']
		        ,yes: function(index){
		            var my_service_close_url = "{pigcms{:U('Yuedan/my_service_close')}";
		            $.post(my_service_close_url,{'rid':rid,'status':status},function(data){
		                if(data.error == 1){
		                    layer.open({
		                        content: data.msg
		                        ,btn: ['确定']
		                        ,yes: function(index){
		                            location.href = location.href;
		                            layer.close(index);
		                        }
		                    });
		                }else{
		                    layer.open({
		                        content: data.msg
		                        ,btn: ['确定']
		                    });
		                }
		            },'json')
		        }
		    });
		}
	</script>
</body>
</html>