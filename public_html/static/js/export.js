var ii,file_date,file_url;
	function exports(){
		get_str = $('form').serialize();
		get_str = get_str.replace(/c=\w+&/,'',get_str)
		get_str = get_str.replace(/a=\w+&/,'',get_str)
		
		$.post(export_url,get_str,function(date){
			  ii= layer.msg('加载中,请耐心等待,数量越多时间越长', {
				  icon: 16
				  ,shade: [0.3, '#000']
				  ,time:0
				});
			//此处用setTimeout演示ajax的回调
				file_url = url+'/index.php?g=Index&c=ExportFile&a=download_export_file&id='+date.export_id;
				file_date = date;
				CheckStatus()
					
				
			
		},'json')
	}
		
	function CheckStatus()
	{
		date = file_date;
		url = file_url;
		$.post(url,{id:date.export_id},function(result){
			  if(result.error_code==0){
					layer.close(ii);
					layer.open({
					  type: 1,
					  title:'下载导出文件',
					  area: ['600px', '120px'],
					  content: '\<p style="height:18px"\>\<\/p\>\<\a style="font-size:18px;" href="'+url+'/index.php?g=Index&c=ExportFile&a=download_export_file&id='+date.export_id+'" download="'+date.file_name+'">点击下载导出文件\<\/a>'
					});
					$('.layui-layer-content').css('text-align','center');
					$('.layui-layer .layui-anim .layui-layer-page').css('top','200px');
					 
				}else{
					setTimeout("CheckStatus()",1000);
				}
					
				
			
		},'json')
	
	$('.group_verify_btn').click(function(){
		
	})	
	}