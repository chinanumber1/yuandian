<!DOCTYPE html>
<html lang="en">
<head>
    <title>信息修改</title>
<include file="Public:classify_header" />
<script>
var liIndexNum = "{pigcms{:count($classify_userinput_info['imgs'])}";
if(!liIndexNum){
	liIndexNum = 0;
}
</script>
<form id="mpostForm" name="mpostForm" action="{pigcms{:U('Classify/classify_modify',array('cid'=>$cid))}" method="post" onsubmit="return checkForm();"> 
	
		<div class="picNote">
			<php>$img_count = count($classify_userinput_info["imgs"]);</php>
			还可上传<em><if condition="$img_count  gt 0"><php>echo  8-$img_count ;</php><else />8</if></em>张图片，已上传<em><if condition="$img_count gt 0">{pigcms{$img_count}<else />0</if></em>张(非必填)
		</div>
		<div class="imgGroup content-padded uploadNum" id="uploadNum">
			<ul class="row upload_list" id="upload_list">
				<li class="col-25 JSimgUpbtn">
					<div class="rect filePut pr">
						<input type="file" accept="image/jpg,image/jpeg,image/png,image/gif" id="fileImage" name="">
						<label class="uploadCoverBtn" for="coverPhotoPut"></label>
					</div>
				</li>
				
				<if condition='$classify_userinput_info["imgs"]'>
					<volist name='classify_userinput_info["imgs"]' id='img_info'>
						<li class="col-25 JSimg upload_item" id="imgShow{pigcms{$i}"><div class="rect pr"><img src="{pigcms{$img_info}" url="{pigcms{$img_info}"><i class="pa fa  fa-close imgDelete upload_delete"></i></div><input type="hidden" name="inputimg[]" value="{pigcms{$img_info}"></li>
					</volist>
				</if>
			</ul>
		</div>
		
		
		<div class="picNote1">
			清晰、实拍的照片更有利完成交易哟~
		</div>

	<div class=" formRow">
		<div class="list-block">
			<ul>
				<li>
					<div class="item-content">
						<div class="item-inner">
							<div class="item-title label">标        题</div>
							<div class="item-input">
								<input type="text" name="tname" value="{pigcms{$classify_userinput_info['title']}" placeholder="请输入标题">
							</div>
						</div>
					</div>
				</li>
				<li>
					<div class="item-content">
						<div class="item-inner">
							<div class="item-title label">联  系  人</div>
							<div class="item-input">
								<input type="text" name="lxname" value="{pigcms{$classify_userinput_info['lxname']}" placeholder="请输入联系人">
							</div>
						</div>
					</div>
				</li>
				<li>
					<div class="item-content">
						<div class="item-inner">
							<div class="item-title label">电        话</div>
							<div class="item-input">
								<input type="text" name="lxtel" value="{pigcms{$classify_userinput_info['lxtel']}" placeholder="只许填写固定电话和手机号">
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>


	<div class=" formRow">
		
	<if condition="!empty($catfield)">
		<div class="list-block">
			<ul>
			<volist name="catfield" id="vv" key="kk">
				<if condition="$vv['type'] eq 1" >
					<li>
						<div class="item-content">
							<div class="item-inner">
								<div class="item-title label"><php>if($vv['iswrite']>0)echo '<strong style="color:red;">*</strong>';</php>{pigcms{$vv['name']}</div>
								<div class="item-input">
									<input name="input[{pigcms{$kk}][vv]" 
									
									<volist name='classify_userinput_info["content"]' id="userinput_info">
										<if condition='($userinput_info["type"] eq $vv["type"]) AND ($userinput_info["tn"] eq $vv["name"])'>
											value="{pigcms{$userinput_info['vv']}"
											type="text" <php>if($userinput_info['inarr']==1)echo 'onkeyup="value=value.replace(/[^1234567890]+/g,'."''".')" placeholder="请填数字"';</php> <php>if(($userinput_info['inarr']==1) && !empty($userinput_info['inunit'])){echo 'class="inputtext01"';}else{echo 'class="inputtext02"';}</php>/> <php>if(($userinput_info['inarr']==1) && !empty($userinput_info['inunit']))echo "&nbsp;".$userinput_info['inunit'];</php>
										
										
										 <input name="input[{pigcms{$kk}][tn]"  value="{pigcms{$userinput_info['tn']}"  type="hidden" />
										 <input name="input[{pigcms{$kk}][unit]"  value="{pigcms{$userinput_info['unit']}"  type="hidden" />
										 <input name="input[{pigcms{$kk}][inarr]"  value="{pigcms{$userinput_info['inarr']}"  type="hidden" />
										 <input name="input[{pigcms{$kk}][input]"  value="{pigcms{$userinput_info['input']}"  type="hidden" />
										 <input name="input[{pigcms{$kk}][iswrite]"  value="{pigcms{$userinput_info['iswrite']}"  type="hidden" />
										 <input name="input[{pigcms{$kk}][isfilter]"  value="{pigcms{$userinput_info['isfilter']}"  type="hidden" />
										 <input name="input[{pigcms{$kk}][type]"  value="1"  type="hidden" />
										</if>
									</volist>
								</div>
							</div>
						</div>
					</li>
				<elseif condition="$vv['type'] eq 2" />
					<li class="align-top">
						<div class="item-content">
							<div class="item-inner">
								<div class="item-title label">
									<php>if($vv['iswrite']>0)echo '<strong style="color:red;">*</strong>';</php>{pigcms{$vv['name']}
								</div>
								<div class="item-input radioStyle">
									<div class="radioStyle-wrap">
										<volist name="vv['opt']" id="opt">
											<volist name='classify_userinput_info["content"]' id="userinput_info">
												<if condition='($userinput_info["type"] eq $vv["type"]) AND ($userinput_info["tn"] eq $vv["name"])'>
													<label><input type="radio" name="input[{pigcms{$kk}][vv]" <if condition="trim($userinput_info['vv']) eq trim($opt)">checked="checked"</if>value="{pigcms{$opt}">{pigcms{$opt}</label>
													<input name="input[{pigcms{$kk}][tn]"  value="{pigcms{$userinput_info['tn']}"  type="hidden" />
													<input name="input[{pigcms{$kk}][input]"  value="{pigcms{$userinput_info['input']}"  type="hidden" />
													<input name="input[{pigcms{$kk}][iswrite]"  value="{pigcms{$userinput_info['iswrite']}"  type="hidden" />
													<input name="input[{pigcms{$kk}][isfilter]"  value="{pigcms{$userinput_info['isfilter']}"  type="hidden" />
													<input name="input[{pigcms{$kk}][type]"  value="2"  type="hidden" />
												</if>
											</volist>
										</volist>
									</div>
								</div>
							</div>
						</div>
					</li>
				<elseif condition="$vv['type'] eq 3" />
					<li class="align-top">
						<div class="item-content">
							<div class="item-inner">
								<div class="item-title label fuliTitle">
									{pigcms{$vv['name']}<br />
									<label for="selectAll">
										<input id="selectAll" type="checkbox"/>全选
									</label>
								</div>
								<div class="item-input">
									<div class="row">
									
									
										<volist name="vv['opt']" id="opt">
											<i class="col-33
											
											<volist name='classify_userinput_info["content"]' id="userinput_info">
												<if condition='($userinput_info["type"] eq $vv["type"]) AND (in_array($opt , $userinput_info["vv"]))'>
														on
												</if>
											</volist>
											
											">{pigcms{$opt}</i>
											<input name="input[{pigcms{$kk}][vv][]" type="checkbox"

											<volist name='classify_userinput_info["content"]' id="userinput_info">
												<if condition='($userinput_info["type"] eq $vv["type"]) AND (in_array($opt , $userinput_info["vv"]))'>
													checked="checked"
												</if>
											</volist>
											
											value="{pigcms{$opt}"/>
										</volist>
									</div>
									
									<input name="input[{pigcms{$kk}][tn]"  value="{pigcms{$vv['name']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][input]"  value="{pigcms{$vv['input']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][iswrite]"  value="{pigcms{$vv['iswrite']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][isfilter]"  value="{pigcms{$vv['isfilter']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][type]"  value="3"  type="hidden" />
								</div>
							</div>
						</div>
					</li>
				 <elseif condition="$vv['type'] eq 4" />
					<li>
						<div class="item-content">
							<div class="item-inner">
								<div class="item-title label">{pigcms{$vv['name']}</div>
								<div class="item-input">
									<select name="input[{pigcms{$kk}][vv]">
										<option>==请选择==</option>
										<volist name="vv['opt']" id="opt">
												<option value="{pigcms{$opt}"
												<volist name='classify_userinput_info["content"]' id="userinput_info">
													<if condition='($userinput_info["type"] eq $vv["type"]) AND ($userinput_info["tn"] eq $vv["name"]) AND ($userinput_info["vv"] eq trim($opt))'>
														selected="selected"
													</if>
												</volist>
												>{pigcms{$opt}</option>
										</volist>
									</select>
									
									<input name="input[{pigcms{$kk}][tn]"  value="{pigcms{$vv['name']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][input]"  value="{pigcms{$vv['input']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][iswrite]"  value="{pigcms{$vv['iswrite']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][isfilter]"  value="{pigcms{$vv['isfilter']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][type]"  value="4"  type="hidden" />
								</div>
							</div>
						</div>
					</li>
				  <elseif condition="$vv['type'] eq 5" />
					<li class="align-top">
						<div class="item-content">
							<div class="item-inner">
								<volist name='classify_userinput_info["content"]' id="userinput_info">
									<if condition='($userinput_info["type"] eq $vv["type"]) AND ($userinput_info["tn"] eq $vv["name"])'>
										<div class="item-title label">{pigcms{$vv['name']}<br /><php>if($userinput_info['iswrite']>0)echo '（选填）';</php></div>
										<div class="item-input">
											<textarea name="input[{pigcms{$kk}][vv]">{pigcms{$userinput_info['vv']}</textarea>
											<input name="input[{pigcms{$kk}][tn]"  value="{pigcms{$userinput_info['tn']}"  type="hidden" />
											<input name="input[{pigcms{$kk}][input]"  value="{pigcms{$userinput_info['input']}"  type="hidden" />
											<input name="input[{pigcms{$kk}][iswrite]"  value="{pigcms{$userinput_info['iswrite']}"  type="hidden" />
											<input name="input[{pigcms{$kk}][isfilter]"  value="{pigcms{$userinput_info['isfilter']}"  type="hidden" />
											<input name="input[{pigcms{$kk}][type]"  value="5"  type="hidden" />
										</div>
									</if>
								</volist>
							</div>
						</div>
					</li>
				  </if>
			</volist>
			
			
			</ul>
		</div>
	</if>
	</div>
	
	<div class="formRow">
		<div class="list-block">
			<ul>
			<li class="align-top">
							<div class="item-content">
								<div class="item-inner">
									<div class="item-title label">
										担保支付
									</div>
									<div class="item-input radioStyle">
										<div class="radioStyle-wrap">
											<label><input type="radio" name="is_assure" value="1" <if condition='$classify_userinput_info["is_assure"] eq 1'>checked="checked"</if>>是</label>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<label><input type="radio" name="is_assure" value="0" <if condition='$classify_userinput_info["is_assure"] eq 0'>checked="checked"</if>>否</label>
										</div>
									</div>
								</div>
							</div>
						</li>
						
						<li <if condition='$classify_userinput_info["is_assure"] eq 0'>style="display:none"</if>>
							<div class="item-content">
								<div class="item-inner">
									<div class="item-title label">担保金额</div>
									<div class="item-input">
										<input name="assure_money" value="{pigcms{$classify_userinput_info['assure_money']}" type="text" onkeyup="value=value.replace(/[^1234567890]+/g,'')" placeholder="请填数字" class="inputtext01"> 
									</div>
								</div>
							</div>
						</li>
			</ul>
		</div>
	</div>

	<div class=" formRow">
		<div class="list-block">
			<ul>
				<li class="align-top">
					<div class="item-content">
						<div class="item-inner">
							<div class="item-title label">说明描述：<br />（选填）</div>
							<div class="item-input" id="cmt_txt" style="width: 90%; height:150px; overflow:auto; -webkit-overflow-scrolling:touch;-webkit-user-select:auto;" contenteditable="true">{pigcms{$classify_userinput_info['description']|htmlspecialchars_decode}</div>
							<input id="Content" type="hidden" name="description" value=""/>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<div class="release">
		<input type="hidden" id="Pic" name="" /> 
		<input type="hidden" name="cid" value="{pigcms{$cid}" /> 
		<input type="hidden" name="fcid" value="{pigcms{$fcid}" /> 
		<input type="hidden" name="id" value="{pigcms{$_GET['id']}" /> 
		<input type="hidden" name="status" value="1" /> 
		<button type="submit">确认修改</button>
	</div>
	</form>
<include file="Public:classify_footer" />
<script>
  function checkForm(){
    $("#Content").val($("#cmt_txt").html());
  }
</script>
<script>
  $(function(){
     $(".imgGroup ul li.JSimg").find(".imgDelete").tap(function(){
       $(this).parent().parent().remove();
     });

      $('.formRow .item-input .row i').tap(function(){
          if($(this).hasClass("on")){
              $(this).removeClass("on");
			  $(this).next('input').removeAttr('checked');
          }else{
              $(this).addClass("on");
			  $(this).next('input').attr('checked',true);
          }
      });
      $('#selectAll').on('change',function (e) {
          e.preventDefault();
          if ($('#selectAll').is(":checked")){
              $('.formRow .item-input .row i').addClass("on");
			  $('.formRow .item-input .row input').attr('checked',true);
          } else {
              $('.formRow .item-input .row i').removeClass("on");
			  $('.formRow .item-input .row input').removeAttr('checked');
          }
      });

      /* $("#areaSelect").cityPicker({
          value: ['天津', '河东区']
          //value: ['四川', '内江', '东兴区']
      }); */
	  
	  if ($(".upload_list").length) {
			var imgUpload = new ImgUpload({
				fileInput: "#fileImage",
				container: "#upload_list",
				countNum: "#uploadNum",
				url:location.protocol+"//" + location.hostname + "/wap.php?g=Wap&c=Classify&a=ajaxImgUpload"
			})
		}
		
		$('input[name="is_assure"]').change(function(){
			var val = $(this).val();
			
			var obj = $('input[name="assure_money"]').parents('li')
			if(val==1){
				obj.show();
			}else{
				obj.hide();
			}
		});
  });
</script>
</body>
</html>