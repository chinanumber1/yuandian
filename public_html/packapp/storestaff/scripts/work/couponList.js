var myScroll = null;
var order_id = 0;
var isSearch = false;
var hasMore = true;
var nowPage = 1;
var indexData = common.getCache('indexData',true);
 var tab1LoadEnd = false;
 var tab2LoadEnd = false;
// 页数
var page = 0;
window.onload=function(){
    var AppView = {
        init: function() {
          this.initView();
            this.pick()


        },
        initView:function(type){
        	var _self=this;
            var dropload1=$('.order_list').dropload({
                scrollArea : window,
                loadUpFn : function(me){
                    var datas={
                        'id':0,
                        'keyword':$('#find_value').val(),
                        'find_type':$('#find_type').val(),
                        'noTip':true
                    }
                    var url='Storestaff&a=coupon_find';
                    if($.trim($('#find_value').val())==''){
                        url='Storestaff&a=coupon_list';
                           
                    }
                    $("#order_list ul").html("");
                    common.http(url,datas, function(data){
                        if(data.order_list.length > 0){
                            setTimeout(function(){
                                order_id = data.order_list[data.order_list.length-1].pigcms_id;
                                laytpl($('#listTpl').html()).render(data.order_list, function(html){
                                    $('#order_list ul').html(html);

                                });
                                // 每次数据加载完，必须重置
                                me.resetload();
                                // 重置页数，重新获取loadDownFn的数据
                                page = 0;
                                // 解锁loadDownFn里锁定的情况
                                me.unlock();
                                me.noData(false);
                            },500);


                        }else{
                            // 如果没有数据
                            // 锁定
                            me.lock();
                            // 无数据
                            me.noData();
                        }



                    },function(){
                        // 即使加载出错，也得重置
                        me.resetload();
                    });

                },
                loadDownFn : function(me){
                	console.log(order_id)
                    var datas={
                        'id':order_id,
                        'keyword':$('#find_value').val(),
                        'find_type':$('#find_type').val(),
                        'noTip':true
                    }
                    var url='Storestaff&a=coupon_find';
                    if($.trim($('#find_value').val())==''){
                        url='Storestaff&a=coupon_list';
                        
                    }
                        common.http(url,datas, function(data){
                            if(data.order_list.length > 0){
                                order_id = data.order_list[data.order_list.length-1].pigcms_id;
                                laytpl($('#listTpl').html()).render(data.order_list, function(html){
                                 
                                        $('#order_list ul').append(html);
                                    

                                });
                                tab1LoadEnd=true;
                            }else{
                                // 如果没有数据
                                // 锁定
                                me.lock();
                                // 无数据
                                me.noData();
                            }

                            // 每次数据插入，必须重置
                            me.resetload();
                        },function(){
                            // 即使加载出错，也得重置
                            me.resetload();
                        });

                },
                threshold : 50
            });
            var bind_name = 'input';
            if (navigator.userAgent.indexOf("MSIE") != -1){ bind_name = 'propertychange' }
            	 $('#find_value').bind(bind_name, function(){
                     coupon_find()
                          
                   }) 

            	//
            	$("#find_type").on("change",function(e){
                   var checkText=$("#find_type").find("option:selected").val();
                   coupon_find()
            	});
            	var  coupon_find=function(){
                   var content=$.trim($('#find_value').val());
                      
                        $("#order_list ul").html(" ");
                      
                      setTimeout(function(){
                            	order_id=0;
                                 // 解锁
                              dropload1.unlock();
                              dropload1.noData(false); 
                             // 重置
                             dropload1.resetload();
                      },100) 
            	}

        },
		pick:function(){
			$("#order_list ").on("click",'.consum',function(e){
				e.stopPropagation();
				var pass = $(this).data('pass');
				common.http('Storestaff&a=coupon_verify', {'pass':pass, 'noTip':false}, function(data){
			      motify.log('验证消费成功！');
			   setTimeout(location.reload(), 1000);
		      });
			})
		}


    };
    window.AppView=AppView;
    AppView.init();
}
/*
$(document).ready(function(){
	var indexData = common.getCache('indexData',true);
    
	
	//$('#order_list').css({height:$(window).height()- 104});
	/!*$('#order_list ul').after('<div class="jroll-infinite-tip">正在加载中...</div>');
	common.scroll($('#order_list'),function(scrollIndex){
		showList(scrollIndex);
	});*!/
	
	//showList();

	$('#searchForm').submit(function(){
		$('#order_list ul').empty();
		if($('#keyword').val() == ''){
			isSearch = false;
		}else{
			isSearch = true;
		}
		hasMore = true;
		order_id = 0;
		nowPage = 1;
		showList();
		
		return false;
	});

	$(document).on('click', 'a.consum', function(){
		var pass = $(this).data('pass');
		console.log(pass)
		common.http('Storestaff&a=coupon_verify', {'pass':pass, 'noTip':false}, function(data){
			motify.log('验证消费成功！');
			setTimeout(location.reload(), 5000);
		});
		
	});
	
});



function showList(scrollIndex){
	if(hasMore == false){
		return false;
	}
	if(isSearch == false){
		common.http('Storestaff&a=coupon_list',{'id':order_id,noTip:true}, function(data){

			if(data.order_list.length > 0){
				order_id = data.order_list[data.order_list.length-1].pigcms_id;
				if(nowPage >= data.pagenum){
					hasMore = false;
					$('.jroll-infinite-tip').addClass('hideText');
				}
				laytpl($('#listTpl').html()).render(data.order_list, function(html){
					$('#order_list ul').append(html);
					common.scrollEnd(scrollIndex);
				});
				nowPage++;
			}else{
					$('.jroll-infinite-tip').html('暂无数据');
			
			}
			
		});
	}else{
		common.http('Storestaff&a=coupon_find',{'id':order_id,keyword:$('#find_value').val(),find_type:$('#find_type').val(),noTip:true}, function(data){
			if(data.order_list.length > 0){
				order_id = data.order_list[data.order_list.length-1].id;
			
			// if(nowPage >= data.pagenum){
				hasMore = false;
				$('.jroll-infinite-tip').addClass('hideText');
			// }
				laytpl($('#listTpl').html()).render(data.order_list, function(html){
					$('#order_list ul').append(html);
					common.scrollEnd(scrollIndex);
				});
				nowPage++;
			}else{
				$('.jroll-infinite-tip').html('暂无数据');
			}
			
		});
	}
}*/
