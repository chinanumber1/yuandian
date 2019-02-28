<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8"/>
        <title>我的小区</title>
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
        <section class="quarters">
            <ul>
            	<if condition="!$my_village['my_village_lists'] && !$my_village['my_village_vacancy']">
                    <div class="binding" style="margin-top:10px;">
                    <div class="bind_list">
                    <dl>
                        <dd>
                           <a href="javascript:viod(0);">您还没有绑定小区！</a>                        
                        </dd>
                        
                    </dl>
                    </div>
                    </div>
                <else />
                
                <volist name="my_village['my_village_lists']" id="vo">
                <li>
                    <div class="name">{pigcms{$vo.village_name} {pigcms{$vo.floor_layer}{pigcms{$vo.floor_name}{pigcms{$vo.layer}#{pigcms{$vo.room}</div>
                    <div class="owner clr">
                        <div class="fl">
                            <div class="img fl">
                                <img src="{pigcms{$static_path}village_list/images/user_avatar.jpg">
                            </div>
                            <div class="p95">
                                <h2>{pigcms{$vo.name}</h2>
                                <p class="ce6">业主</p>
                            </div>
                        </div>
                        <div class="fr">
                        	<if condition="$vo['status']==3">
                            <div class="apply clr">
                           	 	<if condition="$vo['unbind_status'] gt 0">
                                <div class="over_unbund">解绑中</div>
                                <else />
                                <div class="unbund" data-pigcms-id="<if condition='$vo.bind_pigcms_id'>{pigcms{$vo.bind_pigcms_id}</if>">申请解绑</div>
                                </if>
                                <a href="{pigcms{:U('village_select',array('village_id'=>$vo['village_id']))}"><div class="house">使用房屋</div></a>
                            </div>
                            <elseif condition="$vo['status']==2" />
                            <div class="application" style="float: left;">申请中</div>
                            <div class="delete_user_yezhu" style=" border: #eca538 1px solid; color: #eca538;float: right; padding: 0 0.16rem; line-height: 0.4rem; font-size: 0.22rem; margin-left: 0.2rem; border-radius: 0.1rem;margin-top: 0.24rem" data-pigcms-id="{pigcms{$vo.pigcms_id}">删除</div>
							<elseif condition="$vo['status']==0" />
                            <div class="join">
                                <div class="fail"><span>绑定失败</span></div>
                                <div class="again">重新绑定</div>
                            </div>
                            </if>

                        </div> 
                    </div>
                    <notempty name="vo.child_list">
                    <div class="members">
                        <div class="h2 clr">
                            <div class="fl">我的亲属/租客</div>
                            <div class="fr stop">展开</div>
                        </div>
                        <dl>
                        	<volist name="vo.child_list" id="voo">
                            <dd>
                                <div class="img fl">
                                    <img src="{pigcms{$static_path}village_list/images/user_avatar.jpg">
                                </div>
                                <div class="p75">
                                    <h2>{pigcms{$voo.name}</h2>
                                    <if condition="$voo['type']==0">
                                    <p class="ce6">房主</p>
                                    <elseif condition="$voo['type']==1" />
                                    <p class="cf8">亲属</p>
                                    <elseif condition="$voo['type']==2" />
                                    <p class="c1c">租客</p>
                                    </if>
                                </div>
                                <if condition="$voo['status']==1">
                                <div class="del" data-pigcms-id="{pigcms{$voo.pigcms_id}" data-village-id="{pigcms{$voo.village_id}"></div>
                                <elseif condition="$voo['status']==2" />
                                <div class="application" style="right: 1.02rem;">申请中</div>
                                <div class="delete_user" style="position: absolute; border: #eca538 1px solid; color: #eca538;float: right; padding: 0 0.16rem; line-height: 0.4rem; font-size: 0.22rem; margin-left: 0.2rem; border-radius: 0.1rem;top: 0rem; right: 0.02rem;" data-pigcms-id="<if condition='$voo.pigcms_id'>{pigcms{$voo.pigcms_id}</if>">删除</div>
                                </if>
                            </dd>
                            </volist>
                        </dl>
                    </div>
                    
                    </notempty>
                </li>
                </volist>
                
                
                
                <volist name="my_village['my_village_vacancy']" id="vo">
                <li>
                    <div class="name">{pigcms{$vo.village_name} {pigcms{$vo.floor_layer}{pigcms{$vo.floor_name}{pigcms{$vo.vacancy_layer}#{pigcms{$vo.vacancy_room}</div>
                    <div class="owner clr">
                        <div class="fl">
                            <div class="img fl">
                                <img src="{pigcms{$static_path}village_list/images/user_avatar.jpg">
                            </div>
                            <div class="p95">
                                <h2>{pigcms{$vo.name}</h2>
                                <if condition="$vo['type']==1">
                                <p class="cf8">亲属</p>
                                <elseif condition="$vo['type']==2" />
								<p class="c1c">租客</p>
                                <elseif condition="$vo['type']==3" />
								<p class="ce6">替换房主</p>
                                </if>
                            </div>
                        </div>
                        <div class="fr">
                        	<if condition="$vo['status']==1">
                            <div class="apply clr">
                            	<if condition="$vo['unbind_status'] gt 0">
                                <div class="over_unbund">解绑中</div>
                                <else />
                                <div class="unbund" data-pigcms-id="{pigcms{$vo.pigcms_id}">申请解绑</div>
                                </if>
                               <a href="{pigcms{:U('village_select',array('village_id'=>$vo['village_id']))}"><div class="house">使用房屋</div></a>
                            </div>
                            <elseif condition="$vo['status']==2" />
                            <div class="application" style="float: left;">申请中</div>
                            <div class="delete_user" style="border: #eca538 1px solid; color: #eca538;float: right; padding: 0 0.16rem; line-height: 0.4rem; font-size: 0.22rem; margin-left: 0.2rem; border-radius: 0.1rem;margin-top: 0.24rem" data-pigcms-id="<if condition='$vo.pigcms_id'>{pigcms{$vo.pigcms_id}</if>">删除</div>
							<elseif condition="$vo['status']==0" />
                            <div class="join">
                                <div class="fail"><span>加入失败</span></div>
                                <a href="{pigcms{:U('empty_village_room_info',array('pigcms_id'=>$vo['vacancy_id']))}"><div class="again">重新加入</div></a>
                            </div>
                            <!--<div class="join">
                                <div class="fail"><span>加入失败</span></div>
                                <div class="again">重新加入</div>
                            </div>-->
                            </if>
                        </div> 
                    </div>
                </li>
                </volist>
                </if>
            
            
            
                
            </ul> 
        </section>
        
        <section class="tie clr">
            <a href="{pigcms{:U('House/empty_village_list')}"><div class="jr" style="color:#FFF">加入房屋</div></a>
            <a href="{pigcms{:U('House/empty_bind_relatives')}"><div class="bd" style="color:#FFF">绑定亲属</div></a>
        </section>
    
        <section class="reason">
        	
            <input type="hidden" name="pigcms_id" id="pigcms_id" value="0">
            <div class="text">
                <span>请告诉我们<br>您解绑原因</span>
            </div>
            <div class="textarea">
                <textarea placeholder="请输入您解绑的原因" name="note" id="note"></textarea>
            </div>
            <div class="sub clr">
                <div class="gb">关闭</div>
                <div class="tj">提交</div>
            </div>

        </section>
        
         <section class="popup-tip">
            <div class="p400">
                <p class="tip-text"></p>
                <div class="clr button">
                    <div class="center binding">确定</div>
                </div>
            </div>
        </section> 
        <div class="mask"></div>

        <script src="{pigcms{$static_path}js/jquery-1.8.3.min.js"></script>
        <script src="{pigcms{$static_path}village_list/js/common.js"></script>
    </body>
</html>


<script>
    $(".gb").on("click" , function(){
		$("#pigcms_id").val(0);
		$("#note").val('');
	});
	
    $(".tj").on("click" , function(){
        var pigcms_id = $("#pigcms_id").val();
        var note      = $("#note").val();
        if(pigcms_id <= 0){
            $(".gb").click();
            motify.log('无法解除绑定，请联系管理员');    
            return false;
        }
        
        if(note.length <= 0){
            motify.log('请填写您的解绑原因');    
            return false;
        }
        
        $.post("{pigcms{:U('ajax_user_unbind')}" , { pigcms_id:pigcms_id , note:note } , function(data){
            $(".gb").click();
            $(".tip-text").html(data.msg);
            $(".popup-tip,.mask").show();
        },"json");
        
    });	

    // 删除申请记录
    $(".delete_user").on("click" , function(){
        var pigcms_id = $(this).attr('data-pigcms-id');
        if(confirm('您确定要删除申请吗？')){
            $.post("{pigcms{:U('ajax_delete_audit')}" , { pigcms_id:pigcms_id } , function(data){
                motify.log(data.msg);
                if (data.status==1) {
                    location.reload();  
                }
            },"json");
        }
        
    });

    // 删除申请记录 业主
    $(".delete_user_yezhu").on("click" , function(){
		var	pigcms_id = $(this).attr('data-pigcms-id');
		if(confirm('您确定要删除申请吗？')){
    		$.post("{pigcms{:U('ajax_delete_audit_yezhu')}" , { pigcms_id:pigcms_id } , function(data){
                motify.log(data.msg);
                if (data.status==1) {
                    location.reload();  
                }
    		},"json");
        }
		
	});
	
	
	$(".binding").on('click' , function(){
		location.reload();	
	});
	
	
	$(".quarters").css({"height":$(window).height()-$(".tie").height(),"overflow-y": "auto","-webkit-overflow-scrolling" : "touch" });

    //展开收起
    $(".members").each(function(){
        var index = $(this).find("dl").height();
        if(index > 260*per){
            $(this).addClass("hashide");
        }else{
            $(this).find(".stop").hide();
        }
    });

    $(".members .stop").click(function(){
        if($(this).hasClass("on")){
            $(this).removeClass("on").text("展开");
            $(this).parents(".members").removeClass("hasauto");
        }else{
            $(this).addClass("on").text("收起");
            $(this).parents(".members").addClass("hasauto");  
        }
    });

    //删除
    $(".members dd .del").click(function(){
		
		if(confirm('您确定要删除亲属/租客吗？')){

			var data_pigcms_id = $(this).attr('data-pigcms-id');
			var data_village_id = $(this).attr('data-village-id');
			if(data_pigcms_id <= 0 || data_village_id <= 0){
				motify.log('删除失败');	
				return false;	
			}
			
			var _this = $(this);
			$.post("{pigcms{:U('ajax_delete_bind')}" , { data_pigcms_id:data_pigcms_id,data_village_id:data_village_id } , function(data){
				//alert(data);
				motify.log(data.msg);
				if(data.status==1){
					if(_this.parents("dd").siblings("dd").size() < 3){
						_this.parents(".members").removeClass("hashide").find(".stop").hide();
							if(_this.parents("dd").siblings("dd").size() == 0){
								_this.parents(".members").remove();
							}
					}
					_this.parents("dd").fadeOut(function(){
						_this.remove();
					});	
				}
			},"json");
		
		}else{
			return false;	
		}
		
        
    })



    // 申请解绑
    $(".unbund").click(function(){
		var data_pigcms_id = $(this).attr('data-pigcms-id');
		
		$("#pigcms_id").val(data_pigcms_id);
        $(".reason,.mask").show();
    });
    $(".mask,.reason .gb").click(function(){
        $(".reason,.mask").hide();
    })

    $(".reason").css({"top":($(window).height()-$(".reason").height())/2});

    
</script>


