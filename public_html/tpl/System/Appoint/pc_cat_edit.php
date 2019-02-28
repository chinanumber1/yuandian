<include file="Public:header"/>
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="./static/js/upyun.js"></script>
<script type="text/javascript">
var diyTool = "{pigcms{:U('Home/diytool')}";
var editor;
function get_KindEditor(id){
	KindEditor.ready(function(K) {
		editor = K.create('#'+id, {
			height:'350px',
			width:'750px',
			resizeType : 1,
			allowPreviewEmoticons : false,
			allowImageUpload : true,
			uploadJson : '/admin.php?g=System&c=Upyun&a=kindedtiropic',
			items : ['formatblock', 'fontsize','subscript','superscript','indent','outdent','|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline','hr',
			 '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist','link', 'unlink', 'table','image','diyTool']
		});
	});
}

$(document).ready(function() {
			if($('.odd').length<=1){
				$('.delete').children('a').hide();
			}
		});
		
		function plus(){
			var item = $('.plus:last');
			var No = item.data('num');
			No++;
			$('.delete').children().show();
			if(No>10){
				alert('不能超过10条信息');
			}else{
				var shtml ='<tr class="pc_title_'+No+'"><td width="60">标题：</td><td><input type="text" style="width:200px;" class="input" name="pc_title[]"  value="" /></td></td><tr/><tr class="plus" data-num="'+No+'"><td width="40">内容</label></td><td><table style="width:100%;border:#d5dfe8 1px solid;padding:2px;"><tr><td><textarea  style="width:200px;height:60px" class="input" name="pc_content[]" id="pc_content'+No+'" ></textarea></td><td rowspan="2" class="delete"><a href="javascript:void(0)" onclick="del(this)"><img style="width:30px;height:30px;" src="{pigcms{$static_path}images/del.jpg"/></a></td></tr></table></td></tr>';
				
				item.after(shtml);
				get_KindEditor('pc_content'+No);
			}
		}
		function del(obj){
			if($('.plus').length<=1){
				$('.delete').children().hide();
			}else{
				if($('.plus').length==2){
					$('.delete').children().hide();
				}
				$(obj).parents('.plus').remove();
				var num = $(obj).parents('.plus').data('num');
				$('.pc_title_'+num).remove();
				$.each($('.plus'), function(index, val) {
					var No =index+1;
					$(val).find('label').html(No);
				});
			}
		}
</script>

	<style>.frame_form td{vertical-align:middle;}</style>
	<form id="myform" method="post" action="__SELF__" frame="true" refresh="true">
    
    <if condition='!$detail["pc_content"]'>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
        	<tr>
                <td width="60">标题：</td>
                <td><input type="text" style="width:200px;" class="input" name="pc_title[]"  value="" /></td>
                </td>
			<tr/>
        
			<tr class="plus" data-num='0'>
				<td width="40">内容</td>
				<td>
					<table style="width:100%;border:#d5dfe8 1px solid;padding:2px;">
						<tr>
							<td><textarea  style="width:200px;height:60px" class="input" name="pc_content[]" id="pc_content0" ></textarea></td>
                            <td rowspan="2" class="delete">
								<a href="javascript:void(0)" onclick="del(this)"><img style="width:30px;height:30px;" src="{pigcms{$static_path}images/del.jpg"/></a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><a href="javascript:void(0)" onclick="plus()"><img style="width:30px;height:30px;" src="{pigcms{$static_path}images/plus.jpg"/></a></td>
			</tr>
		</table>
       <else/>
       
       <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
       
       		<volist name='detail["pc_content"]' id='vo'>
            	<tr class="pc_title_{pigcms{$key+1}">
                    <td width="60">标题：</td>
                    <td><input type="text" style="width:200px;" class="input" name="pc_title[]"  value="{pigcms{$detail['pc_title'][$key]}" /></td>
                    </td>
				<tr/>
            
                <tr class="plus" data-num="{pigcms{$key+1}">
                    <td width="40">内容</td>
                    <td>
                        <table style="width:100%;border:#d5dfe8 1px solid;padding:2px;">
                            <tr>
                                <td><textarea  style="width:200px;height:60px" class="input" name="pc_content[]" id="pc_content{pigcms{$key+1}" >{pigcms{$vo}</textarea></td>
                                <td rowspan="2" class="delete">
                                    <a href="javascript:void(0)" onclick="del(this)"><img style="width:30px;height:30px;" src="{pigcms{$static_path}images/del.jpg"/></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <script type="text/javascript">
				var id = "{pigcms{$key+1}";
                get_KindEditor('pc_content' + id);
                </script>
            </volist>
			<tr>
				<td></td>
				<td><a href="javascript:void(0)" onclick="plus()"><img style="width:30px;height:30px;" src="{pigcms{$static_path}images/plus.jpg"/></a></td>
			</tr>
		</table>
       </if>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
		
	</form>
<script type="text/javascript">	
	get_KindEditor('pc_content0');
</script>
	<include file="Public:footer"/>

