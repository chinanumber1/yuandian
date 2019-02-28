<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8"/>
        <title>{pigcms{$find_info.vacancy_layer}#{pigcms{$find_info.vacancy_room}-{pigcms{$find_info.floor_layer}{pigcms{$find_info.floor_name}-{pigcms{$find_info.village_name}</title>
        <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no,width=device-width"/>
        <meta http-equiv="pragma" content="no-cache"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name='apple-touch-fullscreen' content='yes'/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
        <meta name="format-detection" content="telephone=no"/>
        <meta name="format-detection" content="address=no"/>
        <link href="{pigcms{$static_path}village_list/css/pigcms.css" rel="stylesheet"/>
    </head>
    <body>
    <style>
		.binding .app_list { display:block}
	</style>
    	<form method="post" id="submitFrom" action="__SELF__">
        <section class="binding">
            <div class="bind_list">
                <div class="bind_top">
                    <div class="p25 link-url" data-url="{pigcms{:U('empty_village_room_list',array('floor_id'=>$find_info['vacancy_floor_id']))}">
                        <h2>{pigcms{$find_info.village_name}-{pigcms{$find_info.floor_layer}{pigcms{$find_info.floor_name}-{pigcms{$find_info.vacancy_layer}#{pigcms{$find_info.vacancy_room}</h2>
                        <p>{pigcms{$find_info.village_address}</p>
                    </div>
                </div>
            </div>

            <div class="role">
                 <div class="name">我的角色</div>
                 <ul>
                 	 <empty name="room_true_find">
                     <input type="hidden" name="type" class="on-data-val" value="0">
                     <li class="on" data-val="0">房主</li>
                     <else />
                     <input type="hidden" name="type" class="on-data-val" value="1">
                     <li class="on" data-val="1">家属</li>
                     <li data-val="2">租客</li>
                     <li data-val="3">替换房主</li>
                     </empty>
                    
                 </ul>   
            </div>
            <empty name="room_true_find">
            <div class="app_list">
                <div class="applicant">
                    <div class="name">申请人信息</div>
                    <ul>
                        <li class="clr">
                            <div class="fl">姓名</div>
                            <div class="fr"><input type="text" placeholder="请输入姓名" name="name" id="name"/></div>
                        </li>
                        <li class="clr">
                            <div class="fl">手机号</div>
                            <div class="fr"><input type="text" placeholder="请输入手机号" name="phone" id="phone" value="{pigcms{$userArr.phone}"/></div>
                        </li>
                        <!-- <li class="clr">
                            <div class="fl">房屋面积</div>
                            <div class="fr"><input type="text" placeholder="单位：m²" name="housesize" id="housesize" value=""/></div>
                        </li> -->
                        <input type="hidden" name="housesize" id="housesize" value="{pigcms{$find_info.vacancy_housesize}">
                        <!-- <li class="clr">
                        	<input type="hidden" name="park_flag" class="on-data-val-car" value="1">
                            <div class="fl">是否有停车位</div>
                            <div class="fr clr">
                                <span class="on" data-val="1">是</span>
                                <span data-val="0">否</span>
                            </div>
                        </li> -->
                        <li class="clr">
                            <div class="fl">备注</div>
                            <div class="fr"><textarea placeholder="请输入备注内容"></textarea> </div>
                        </li>
                        <li class="clr">
                            <div style="font-size: 0.24rem;color: #808080;"><img src="{pigcms{$static_path}images/warning_grey.png" style="width: 12px;height: 12px; margin-right: 4px;"/>建议使用平台注册的手机号申请，以便正常使用开门等功能</div>
                        </li>
                    </ul>
                </div>
            </div>
            <else />
			<div class="app_list">
                <div class="applicant">
                <div class="name">请输入房东手机后四位</div>
                <ul>
                    <li class="clr">
                        <dl class="clr">
                            <dd>{pigcms{$room_true_find.phone|msubstr=###,0,1,''}</dd>
                            <dd>{pigcms{$room_true_find.phone|msubstr=###,1,1,''}</dd>
                            <dd>{pigcms{$room_true_find.phone|msubstr=###,2,1,''}</dd>
                            <dd class="xx">****</dd>
                            <dd><input type="tel" name="master_phone[]" onkeyup="keyup_back(this.value,1)" id="back_1" maxlength="1"/></dd>
                            <dd><input type="tel" name="master_phone[]" onkeyup="keyup_back(this.value,2)" id="back_2" maxlength="1"/></dd>
                            <dd><input type="tel" name="master_phone[]" onkeyup="keyup_back(this.value,3)" id="back_3" maxlength="1"/></dd>
                            <dd><input type="tel" name="master_phone[]" onkeyup="keyup_back(this.value,4)" id="back_4" maxlength="1"/></dd>
                        </dl>
                    </li>  
                </ul>
                </div>
                <div class="remind">如不知道房东电话，可主动联系房东或物业</div>
            </div>
            <div class="app_list">
                <div class="applicant">
                <div class="name">申请人信息</div>
                <ul>
                    <li class="clr">
                        <div class="fl">姓名</div>
                        <div class="fr"><input type="text" placeholder="请输入姓名" name="name" id="name" value=""/></div>
                    </li>
                    <li class="clr">
                        <div class="fl">我的手机号</div>
                        <div class="fr"><input type="text" placeholder="请输入手机号" name="phone" id="phone" value="{pigcms{$userArr.phone}"/></div>
                    </li>
                    <li class="clr">
                        <div class="fl">备注</div>
                        <div class="fr"><textarea placeholder="请输入备注内容" name="memo"></textarea> </div>
                    </li>
                    <li class="clr">
                        <div style="font-size: 0.24rem;color: #808080;"><img src="{pigcms{$static_path}images/warning_grey.png" style="width: 12px;height: 12px; margin-right: 4px;" />建议使用平台注册的手机号申请，以便正常使用缴费等附加功能</div>
                    </li>
                </ul>
                </div>
            </div>
            </empty>
            

            <div class="submit">完成</div>
        </section>
		</form>
        <script src="{pigcms{$static_path}js/jquery-1.8.3.min.js"></script>
        <script src="{pigcms{$static_path}village_list/js/common.js"></script>
    </body>
</html>
<script>
	$(".submit").on('click' , function(){
		//name phone
		<notempty name="room_true_find">
		var master_phone="";
		$("input[name='master_phone[]']").each(function(index,item){
			master_phone += $(this).val();
		 });
		if(master_phone.length != 4){
			motify.log("房东手机号不正确！");
			return false;
		}
		</notempty>
		
		var name = $("#name").val();
		if(name==''){
			motify.log("请填写姓名");
			return false;
		}
		
		var phone = $("#phone").val();
		if(phone==''){
			motify.log("请填写电话号码");
			return false;
		}
		
		
		
		$("#submitFrom").submit();	
	});
    $(".applicant li span").click(function(){
		 var data_val_car = $(this).attr('data-val');
		 $('.on-data-val-car').val(data_val_car);
        $(this).addClass("on").siblings().removeClass("on");
    });
	
	 $(".role li").click(function(){
		 var data_val = $(this).attr('data-val');
		 $('.on-data-val').val(data_val);
        $(this).addClass("on").siblings().removeClass("on");
    });
	


    $(".role li").click(function(){
       // var index=$(this).index();
       // $(".app_list").eq(index).show().siblings(".app_list").hide();
    }).eq(0).trigger("click");

    function keyup_back(val,number){
        if(val){
            if(number == 4){
                $("#name").focus();
            }else{
                var next = parseInt(number)+1;
                $("#back_"+next).focus();
            }
        }else{
            var next = parseInt(number)-1;
            $("#back_"+next).focus();
        }
    }

</script>