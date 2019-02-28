<!DOCTYPE html>
<html lang="en">
<head>
    <title>信息发布</title>
    <script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
<include file="Public:classify_header" />
<style>
.round { border-radius: 4px;}
.visualSelect {
 width: 100%;
 padding: .45rem .5rem .25rem .75rem;
 margin-bottom: .875rem;
 border: 1px solid #ddd;
 border-radius: 4px;
 color: #bbb;
}

.title_info {
    text-align: center;
    font-weight: bolder;
    font-size: 18px;
    padding: 20px 0 0;
}

.title_msg {
    text-align: center;
    font-size: 18px;
    padding: 20px 0;
}


.title_tip {
    text-align: center;
    font-size: 16px;
    padding: 10px 0;
}

.layermcont {
    padding: 0;
}

.layermchild {
    width: 66%;
}

.tip_info_list {
    border-top: 1px solid #f0f0f0;
    padding: 10px 20px;
}

.money_info {
    float: right;
}
</style>

<form id="mpostForm" name="mpostForm" method="post"> 
	<if condition="$fabuset['isupimg'] eq 1">
		<div class="picNote">
			还可上传<em class="upload_count">8</em>张图片，已上传<em class="upload_num">0</em>张(非必填)
		</div>
		<div class="imgGroup content-padded uploadNum" id="uploadNum">
			<ul class="row upload_list" id="upload_list">
				<li class="col-25 JSimgUpbtn">
					<div class="rect filePut pr">
						<!--input class="pa" id="coverPhotoPut" type="file" accept="image/*"-->
						<input type="file" accept="image/jpg,image/jpeg,image/png,image/gif" id="fileImage" name="">
						<label class="uploadCoverBtn" for="coverPhotoPut"></label>
					</div>
				</li>
			</ul>
		</div>
		
		
		<div class="picNote1">
			清晰、实拍的照片更有利完成交易哟~
		</div>
	</if>
	<div class=" formRow">
		<div class="list-block">
			<ul>
				<li>
					<div class="item-content">
						<div class="item-inner">
							<div class="item-title label">标        题</div>
							<div class="item-input">
								<input type="text" name="tname" placeholder="请输入标题">
							</div>
						</div>
					</div>
				</li>
				<li>
					<div class="item-content">
						<div class="item-inner">
							<div class="item-title label">联  系  人</div>
							<div class="item-input">
								<input type="text" name="lxname" placeholder="请输入联系人">
							</div>
						</div>
					</div>
				</li>
				<li>
					<div class="item-content">
						<div class="item-inner">
							<div class="item-title label">电        话</div>
							<div class="item-input">
								<input type="text" name="lxtel" placeholder="只许填写固定电话和手机号">
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
									<input name="input[{pigcms{$kk}][vv]"  value="" type="text" <php>if($vv['inarr']==1)echo 'onkeyup="value=clearNoNum(this.value)" placeholder="请填数字"';</php> <php>if(($vv['inarr']==1) && !empty($vv['inunit'])){echo 'class="inputtext01"';}else{echo 'class="inputtext02"';}</php>/> <php>if(($vv['inarr']==1) && !empty($vv['inunit']))echo "&nbsp;".$vv['inunit'];</php>
									 <input name="input[{pigcms{$kk}][tn]"  value="{pigcms{$vv['name']}"  type="hidden" />
									 <input name="input[{pigcms{$kk}][unit]"  value="{pigcms{$vv['inunit']}"  type="hidden" />
									 <input name="input[{pigcms{$kk}][inarr]"  value="{pigcms{$vv['inarr']}"  type="hidden" />
									 <input name="input[{pigcms{$kk}][input]"  value="{pigcms{$vv['input']}"  type="hidden" />
									 <input name="input[{pigcms{$kk}][iswrite]"  value="{pigcms{$vv['iswrite']}"  type="hidden" />
									 <input name="input[{pigcms{$kk}][isfilter]"  value="{pigcms{$vv['isfilter']}"  type="hidden" />
									 <input name="input[{pigcms{$kk}][type]"  value="1"  type="hidden" />
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
											<label><input type="radio" name="input[{pigcms{$kk}][vv]" value="{pigcms{$opt}">{pigcms{$opt}</label>
										</volist>
										<input name="input[{pigcms{$kk}][tn]"  value="{pigcms{$vv['name']}"  type="hidden" />
										<input name="input[{pigcms{$kk}][input]"  value="{pigcms{$vv['input']}"  type="hidden" />
										<input name="input[{pigcms{$kk}][iswrite]"  value="{pigcms{$vv['iswrite']}"  type="hidden" />
										<input name="input[{pigcms{$kk}][isfilter]"  value="{pigcms{$vv['isfilter']}"  type="hidden" />
										<input name="input[{pigcms{$kk}][type]"  value="2"  type="hidden" />
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
											<i class="col-33">{pigcms{$opt}</i>
											<input name="input[{pigcms{$kk}][vv][]" type="checkbox"  value="{pigcms{$opt}"/>
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
					<li class="align-top" style="height:4rem;">
						<div class="item-content">
							<div class="item-inner" style="postition:fixed">
								<div class="item-title label">{pigcms{$vv['name']}</div>
								<div class="item-input">
									<select name="input[{pigcms{$kk}][vv]" style="height:2.2rem; width:100%; display:block;" autofocus="true">
										<option>请选择</option>
										<volist name="vv['opt']" id="opt">
										 <option value="{pigcms{$opt}">{pigcms{$opt}</option>
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
								<div class="item-title label">{pigcms{$vv['name']}<br /><php>if($vv['iswrite']>0)echo '（选填）';</php></div>
								<div class="item-input">
									<textarea name="input[{pigcms{$kk}][vv]"></textarea>
									<input name="input[{pigcms{$kk}][tn]"  value="{pigcms{$vv['name']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][input]"  value="{pigcms{$vv['input']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][iswrite]"  value="{pigcms{$vv['iswrite']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][isfilter]"  value="{pigcms{$vv['isfilter']}"  type="hidden" />
									<input name="input[{pigcms{$kk}][type]"  value="5"  type="hidden" />
								</div>
							</div>
						</div>
					</li>
				  </if>
			</volist>
			
				
			</ul>
		</div>
	</if>
	
	
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
										<label><input type="radio" name="is_assure" value="1">是</label>
										<label><input type="radio" name="is_assure" value="0" checked="checked">否</label>
									</div>
								</div>
							</div>
						</div>
					</li>
					
					<li style="display:none">
						<div class="item-content">
							<div class="item-inner">
								<div class="item-title label">担保金额</div>
								<div class="item-input">
									<input name="assure_money" value="" type="text" onkeyup="value=value.replace(/[^1234567890]+/g,'')" placeholder="请填数字" class="inputtext01"> 
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
							<div class="item-title label">说明描述<br />（选填）</div>
							<div class="item-input">
								<textarea id="Content" name="description" class="myborder"  placeholder="写上一些想要说明的内容" style="width: 90%;"></textarea>
							</div>
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
        <input type="hidden" name="pfcid" value="{pigcms{$_GET.pfcid}" />

        <if condition="$fabuTmp['reward_type'] eq 2 || $fabuTmp['reward_type'] eq 4">
            <button onclick='article_reward_pay("{pigcms{$_GET.cid}", "{pigcms{$_GET.fcid}")'
                    class="btn btn-larger btn-block" style="background-color: #06c1ae;font-size: 16px;">
                确认发布（￥{pigcms{$fabuTmp.reward_publish}）
            </button>
            <else/>
            <button type="submit">确认发布</button>
        </if>
	</div>
	</form>
<include file="Public:classify_footer" />
<script>
    var load = false;
    var click = false;
  $(function(){
     $(".imgGroup ul li.JSimg").find(".imgDelete").tap(function(){
       $(this).parent().parent().remove();
     });

      $('.formRow .item-input .row i').tap(function(){
          if($(this).hasClass("on")){
              $(this).removeClass("on");
			  $(this).next('input').attr('checked',false);
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
		
		$('#uploadNum').click(function(){
			if($(".upload_list li").length > 8){
				alert('最多只能上传8张图片！')
				return false;
			}
		});
		
		
		$(".upload_list li").change(function(){
			var len = $('.upload_item').length;
			$('.upload_num').html(len);
			$('.upload_count').html(8 - len);
		})
		
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
  
  function clearNoNum(value){
    //清除"数字"和"."以外的字符

    value = value.replace(/[^\d.]/g,"");

    //验证第一个字符是数字而不是
    value = value.replace(/^\./g,"");

    //只保留第一个. 清除多余的
    value = value.replace(/\.{2,}/g,".");
    value = value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");

    //只能输入两个小数
    value = value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3'); 
	return value;
}
  

  $('#mpostForm').submit(function(){
      if (load) {
          load = false;
          return false;
      }
      console.log('提交------load----');
	  var url = "{pigcms{:U('Classify/fabuTosave',array('cid'=>$cid))}";
	  $.post(url,$(this).serialize(),function(data){
	      if (data['status']==0) {
              layer.open({content: '<div  class="title_tip">' + data.msg + '</div>',skin: 'msg',time: 1});
          } else if(data['status']==-1){
			  location.href="{pigcms{:U('Login/index')}";
		  }else if(data['status']==1){
			  var redirect_url = location.protocol+"//" + location.hostname + "/wap.php?g=Wap&c=Classify&a=myfabu";
			  redirect_url +='&uid={pigcms{$uid}';
			  location.href = redirect_url;
		  } else if (data['status'] == 2) {
              layer.open({
                  content: '<div  class="title_info">' + data.msg + '</div><br>' +
                  '<div class="tip_info_list">账户余额（元）：<div class="money_info">￥' + data.info.now_money + '</div></div>' +
                  '<div class="tip_info_list" style="color: #06c1ae;">打赏金额（元）：<div class="money_info">￥' + data.info.reward_money + '</div></div>' +
                  '<div class="tip_info_list" style="color: #FF658E;">还需充值（元）：<div class="money_info">￥' + data.info.difference + '</div></div>'
                  , btn: ['确定', '取消']
                  , yes: function (index) {
                      location.href = "{pigcms{:U('My/recharge',array('label'=>'wap_portal_article_'))}{pigcms{$article['aid']}";
                  }
              });
          }else if (data['status'] == 3) {
              layer.open({
                  content: '<div  class="title_info">' + data.msg + '</div><br>' +
                  '<div class="tip_info_list">账户余额（元）：<div class="money_info">￥' + data.info.now_money + '</div></div>' +
                  '<div class="tip_info_list" style="color: #06c1ae;">打赏金额（元）：<div class="money_info">￥' + data.info.reward_money + '</div></div>' +
                  '<div class="tip_info_list" style="color: #FF658E;">还需充值（元）：<div class="money_info">￥' + data.info.difference + '</div></div>'
                  , btn: ['确定', '取消']
                  , yes: function (index) {
                      location.href = "{pigcms{:U('My/recharge',array('label'=>'wap_portal_article_'))}{pigcms{$article['aid']}";
                  }
              });
          }
      },'json')
	  return false;
  });

  function article_reward_pay(cid, fcid) {
      if (click) return false;
      click = true;
      load = true;
      setTimeout(function () {
          if (click) {
              console.log('change')
              click = false;
              load = false;
          }
      }, 2000);
      console.log('分类id----------', cid);
      console.log('分类父id----------', fcid);
      console.log('shuju---------', $('#mpostForm').serialize());
      var fabu_reward_pay_order = "{pigcms{:U('Classify/fabu_reward_pay_order')}";
      $.post(fabu_reward_pay_order, $('#mpostForm').serialize(), function (data) {
          console.log('支付信息-》  ', data)
          load = false;
          if (data.error == 3) {
              layer.open({
                  content: '<div  class="title_info">' + data.msg + '</div><br>' +
                  '<div class="tip_info_list">账户余额（元）：<div class="money_info">￥' + data.info.now_money + '</div></div>' +
                  '<div class="tip_info_list" style="color: #06c1ae;">打赏金额（元）：<div class="money_info">￥' + data.info.reward_money + '</div></div>' +
                  '<div class="tip_info_list" style="color: #FF658E;">还需充值（元）：<div class="money_info">￥' + data.info.difference + '</div></div>'
                  , btn: ['确定', '取消']
                  , yes: function (index) {
                      location.href = "{pigcms{:U('My/recharge',array('label'=>'wap_portal_article_'))}{pigcms{$article['aid']}";
                  }
              });
          } else if (data.error == 1 || data.error == 2) {
              layer.open({
                  content: '<div  class="title_info">' + data.msg + '</div>'
                  , btn: ['确定']
                  , yes: function (index) {
                      window.location.href = window.location.href;
                  }
              });
          } else if (data.error == 5) {
              layer.open({
                  content: '<div  class="title_info">打赏</div><br>' +
                  '<div class="tip_info_list">账户余额（元）：<div class="money_info">￥' + data.info.now_money + '</div></div>' +
                  '<div class="tip_info_list" style="color: #06c1ae;">打赏金额（元）：<div class="money_info">￥' + data.info.reward_money + '</div></div>'
                  , btn: ['立即支付', '取消']
                  , yes: function (index) {
                      $('#mpostForm').submit();
                  }
              });
          } else {
              if (data.code == 2) {
                  layer.open({
                      content: '<div  class="title_msg">请先登录</div>'
                      , btn: ['去登录']
                      , yes: function (index) {
                          location.href = "{pigcms{:U('Login/index')}";
                      }
                  });
              } else {
                  layer.open({
                      content: '<div  class="title_msg">' + data.msg + '</div>'
                      , btn: ['确定']
                  });
              }
          }
      }, 'json');
  }
</script>
</body>
</html>