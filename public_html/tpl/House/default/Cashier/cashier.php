<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-tablet"></i>
                <a href="javascript:void(0)">收费管理</a>
            </li>
            <li class="active">收银台</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <style type="text/css">
        .form_list {
            width: 60%;
            float: left;    
            margin-left: 24%;
            margin-top: 5%;
            text-align: center;
        }

        .form_list select {
            margin-right: 10px;
            height: 42px;
        }

        .col-sm-1 {
            width: 23%
        }
        .caret.down {
            content: "";
            border-top: 0;
            border-bottom: 4px dashed
        }
        #dropdown-menu.dropdown-menu{
          border:none !important;

        }
        #dropdown-menu{
            font-family: Monospaced Number,Chinese Quote,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,PingFang SC,Hiragino Sans GB,Microsoft YaHei,Helvetica Neue,Helvetica,Arial,sans-serif;
            line-height: 1.5;
            color: rgba(0,0,0,.65);
            margin: 0;
            padding: 0;
            list-style: none;
            -webkit-box-shadow: 0 2px 8px rgba(0,0,0,.15) !important;
            box-shadow: 0 2px 8px rgba(0,0,0,.15) !important;
            border-radius: 4px;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            outline: none;
            font-size: 14px;
            min-width: auto;right:0px;left:12px;
            top:48px
        }
        #dropdown-menu .ant-select-dropdown-menu-item-active {
            background-color: #e6f7ff;
        }
        #dropdown-menu .ant-select-dropdown-menu-item {
            position: relative;
            display: block;
            padding: 5px 12px;
            line-height: 22px;
            font-weight: 400;
            color: rgba(0,0,0,.65);
            white-space: nowrap;
            cursor: pointer;
            overflow: hidden;
            text-overflow: ellipsis;
            -webkit-transition: background .3s ease;
            transition: background .3s ease;
        }
        #dropdown-menu .ant-select-dropdown-menu-item{line-height:35px;list-style: none;border:none !important;}
        #dropdown-menu  .ant-select-dropdown-menu-item a{text-decoration: none}
        #dropdown-menu  .ant-select-dropdown-menu-item:hover {
            background-color: #e6f7ff
        }

        #dropdown-menu .ant-select-dropdown-menu-item:first-child {
            border-radius: 4px 4px 0 0;
        }

        #dropdown-menu .ant-select-dropdown-menu-item:last-child {
            border-radius: 0 0 4px 4px
        }

        #showIndex{
            display:none;
            position:absolute;right:12px;left:2px;top:42px;background:#fff
        }

        #wuye li{
            height:40px;line-height:40px;cursor:pointer;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden
        }
        #showIndex .more p{
            height:40px;line-height:40px;font-size:12px;padding-left:10px
        }
        .nomore{
            height: 42px;
            line-height: 42px;
            color: rgba(0,0,0,.65);
            font-family: Monospaced Number,Chinese Quote,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,PingFang SC,Hiragino Sans GB,Microsoft YaHei,Helvetica Neue,Helvetica,Arial,sans-serif;
            margin: 0;
            /* background-color: #fff; */
            -webkit-box-shadow: 0 2px 8px rgba(0,0,0,.15) !important;
             box-shadow: 0 2px 8px rgba(0,0,0,.15) !important;
            border-radius: 4px;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            font-size: 14px;
        }
        .col-xs-12{
            margin-top: 3%;   
        }
        .form-group{  height: 400px; }

      .more{
          -webkit-box-shadow: 0 2px 8px rgba(0,0,0,.15) !important;
          box-shadow: 0 2px 8px rgba(0,0,0,.15) !important;
      }
        input::-webkit-input-placeholder {
            color: #b5b5b5;
            font-size: 15px;
            text-align: left;    
        }




    </style>

    <div class="page-content">
        <div class="page-content-area">
            <div class="row">
                <div class="col-xs-12" style="    margin-top: 1%;">
                    <div class="form-group" style="border:1px solid #c5d0dc;padding:10px;">
                        <form method="post" id="find-form" onSubmit="return check_user_submit()" class="form_list">
                          <!--  <div style="text-align: center;">
                                <div>
                                    <span class="ant-input-group ant-input-group-compact">
                                        <div class="ant-select-lg ant-select ant-select-enabled" style="width: 100px;">
                                            <div class="ant-select-selection  ant-select-selection&#45;&#45;single"  tabindex="0">
                                                <div class="ant-select-selection__rendered">
                                                    <div class="ant-select-selection-selected-value" title="房屋" style="display: block; opacity: 1;">房屋</div>
                                                </div>
                                                <span class="ant-select-arrow"  style="user-select: none;">
                                                    <b></b>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ant-select-lg ant-select ant-select-combobox ant-select-enabled" style="width: 500px;">
                                            <div class="ant-select-selection ant-select-selection&#45;&#45;single" >
                                                <div class="ant-select-selection__rendered">
                                                    <div  class="ant-select-selection__placeholder" style="display: block; user-select: none;">搜索说明：输入房号等即可快速搜索</div>
                                                    <ul>
                                                        <li class="ant-select-search ant-select-search&#45;&#45;inline">
                                                        <div class="ant-select-search__field__wrap">
                                                            <input autocomplete="off" value="" class="ant-select-search__field">
                                                            <span class="ant-select-search__field__mirror">&nbsp;</span>
                                                        </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                            </div>-->

                            <div style="text-align: center;margin-bottom: 90px;">
                                <p style="font-size: 40px;margin-bottom: 8px;">收银台</p>
                                <p style="color: #b5b5b5;margin-bottom:5px; ">可搜索房主姓名、手机号码、物业编号，完成账单收款。</p>
                            </div>
                            <div class="dropdown col-sm-2" style="padding-right:0px;    margin-left: 20%;">
                                 <div id="find_type" data-id="1" style=" cursor:pointer;   border: 1px solid #d5d5d5;line-height: 42px; text-align: center;height: 42px;"><span class="content" style="display:inline-block;text-align:left;width:80%">房主姓名</span><span class="caret" style="margin-bottom:5px"></span></div>
                               <div id="dropdown-menu" class="dropdown-menu ">
                                  <div>
                                      <ul class="findtype-menu" aria-labelledby="dropdownMenu1" >
                                          <li data-value="1" class="ant-select-dropdown-menu-item ant-select-dropdown-menu-item-active"><a href="#">房主姓名</a></li>
                                          <li data-value="2" class="ant-select-dropdown-menu-item"><a href="#">手机号码</a></li>
                                          <li data-value="3" class="ant-select-dropdown-menu-item"><a href="#">物业编号</a></li>
                                      </ul>
                                  </div>
                               </div>

                            </div>
                            <div class="col-sm-5" style="padding-left:0px">
                                <input class="col-sm-12" name="find_value" id="find_value" type="text" style="margin-right:10px;font-size:18px;height:42px;" autocomplete="off" placeholder="输入房主姓名可快速搜索"/>
                                <div id="showIndex">
                                    <div class="more">
                                        <!-- <p >物业信息:</p> -->
                                        <ul style="padding-left:20px" id="wuye">

                                        </ul>
                                    </div>
                                    <div class="ant-select-dropdown nomore ant-select-dropdown--single ant-select-dropdown-placement-bottomLeft  ant-select-dropdown-hidden uu" >
                                        <div style="overflow: auto;">
                                            <ul class="ant-select-dropdown-menu  ant-select-dropdown-menu-root ant-select-dropdown-menu-vertical" >
                                                <li  class="ant-select-dropdown-menu-item ant-select-dropdown-menu-item-disabled" >暂无相关数据</li>
                                            </ul>
                                        </div>
                                    </div>
                                   <!-- <div class="nomore" style="display:none">暂无相关数据</div>-->
                                </div>

                            </div>


                            <!-- <input class="btn btn-success" type="submit" id="find_submit" value="查找业主" style="margin-right:10px;"/> -->
                        </form>
                        <div class="clearfix"></div>
                    </div>
                    <!--<div class="form-group user_list" style="border:1px solid #c5d0dc;padding:10px; display:none">
                        <span>物业信息:</span>
                        <div class="user_list_content">
                        </div>
                    </div>-->


                </div>
            </div>

        </div>
    </div>
</div>


<input type="hidden" id="pay_type" name="pay_type" value=""/>
<input type="hidden" id="pay_money" name="pay_money" value=""/>
<input type="hidden" id="paid_cycle" name="paid_cycle" value=""/>
<input type="hidden" id="cycle_sum" name="cycle_sum" value=""/>
<input type="hidden" id="metering_mode_val" name="metering_mode_val" value=""/>
<input type="hidden" id="cycle_type" name="cycle_type" value=""/>
<input type="hidden" id="price" name="price" value=""/>
<input type="hidden" id="metering_mode" name="metering_mode" value="">
<input type="hidden" id="start_time" name="start_time" value="">
<input type="hidden" id="end_time" name="end_time" value="">


<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
    $(document).bind("click",function(e){
        var target=$(e.target);
        console.log(target)
        if(target.closest("#find_type").length!=0){

        }else{
            $("#dropdown-menu ").hide();
            $("#find_type .caret").removeClass("down");
        }
        if(target.closest("#find_value").length==0){
            $("#showIndex").hide().find(".nomore").hide().siblings().hide().find("#wuye").html("")
        }

    })
    $('#search_find').live('click',function () {
        var owner_id = $('select[name="owner_id"]').val()
        var search_url = "{pigcms{:U('search_owner_info')}&owner_id=" + owner_id;
        art.dialog.open(search_url,{
            init: function () {
                var iframe = this.iframe.contentWindow;
                window.top.art.dialog.data('iframe_handle',iframe);
            },
            id: 'handle',
            title: '业主信息',
            padding: 0,
            width: 720,
            height: 400,
            lock: true,
            resize: false,
            background: 'black',
            button: null,
            fixed: false,
            close: null,
            left: '50%',
            top: '38.2%',
            opacity: '0.4'
        });
        return false;
    });


    $(function () {
        /*$('body').on("click",function(){
            $(".dropdown-menu").hide()
        })*/
        $("#find_type").on("click",function(){
              if($("#find_type .caret").hasClass("down")){
                  $("#find_type .caret").removeClass("down");
                  $("#dropdown-menu").hide();
              }else{
                  $("#find_type .caret").addClass("down");
                  $("#dropdown-menu").show();
              }

          })
        $(".findtype-menu li").on("click",function(){
            $("#find_type .content").html($(this).find('a').html());
            $("#find_type").attr("data-id",$(this).attr("data-value"));
            $("#dropdown-menu ").hide();
            $("#find_type .caret").removeClass("down");
            if ($(this).attr("data-value")==1) {
                $('#find_value').prop('placeholder','输入房主姓名可快速搜索');
            } else if ($(this).attr("data-value")==2) {
                $('#find_value').prop('placeholder','输入手机号码可快速搜索');                
            }else if ($(this).attr("data-value")==3) {
                $('#find_value').prop('placeholder','输入物业编号可快速搜索');
            }
        })
        $('#find_value').keyup(function () {
            var find_type = $('#find_type').attr("data-id");
            console.log(find_type)
            var find_value = $(this).val();
            var ajax_user_list_url = "{pigcms{:U('ajax_user_list')}";

            var personal_order_list_url = '{pigcms{:U('personal_order_list')}';
            if (find_value) {
                $.post(ajax_user_list_url,{find_type: find_type,find_value: find_value},function (data) {
                    var shtml = '';

                    if (data.status) {
                        $("#showIndex").show().find(".nomore").hide().siblings().show();
                        $('.user_list').show();
                        for (var i in data['user_list']) {
                            var data_ = data['user_list'][i];
                            // console.log(data_)
                            // shtml += '<li><a href="'+personal_order_list_url+'&bind_id=' + data['user_list'][i]['pigcms_id'] + '">编号：' + data['user_list'][i]['usernum'] + '&nbsp;&nbsp;|&nbsp;&nbsp;业主姓名：' + data['user_list'][i]['name'] + '&nbsp;&nbsp;|&nbsp;&nbsp;地址：' + data['user_list'][i]['address'] + '</a></li>';
                            shtml += '<li><a href="'+personal_order_list_url+'&bind_id=' + data['user_list'][i]['pigcms_id'] + '" title="物业编号：'+data['user_list'][i]['usernum']+'&nbsp;&nbsp;|&nbsp;&nbsp;姓名：' + data['user_list'][i]['name'] + '&nbsp;&nbsp;|&nbsp;&nbsp;地址：' + data['user_list'][i]['address']+ '">'+data['user_list'][i]['usernum']+'&nbsp;&nbsp;|&nbsp;&nbsp;' + data['user_list'][i]['name'] + '&nbsp;&nbsp;|&nbsp;&nbsp;' + data['user_list'][i]['address'] + '</a></li>';
                        }
                        $("#wuye").html(shtml)
                        $('.user_list_content').html(shtml);
                    } else {
                        $("#showIndex").show().find("#wuye").html('').closest('.more').hide().siblings().show();
                        shtml += '暂无相关数据';

                        $('.user_list_content').html(shtml);
                    }
                },'json')
            }
        });

        $('#find_value').focus(function () {
            var find_type = $('#find_type').attr("data-id");
            console.log(find_type)
            var find_value = $(this).val();
            var ajax_user_list_url = "{pigcms{:U('ajax_user_list')}";
            if (find_value) {
                $.post(ajax_user_list_url,{find_type: find_type,find_value: find_value},function (data) {
                    var shtml = '';

                    if (data.status) {
                        $("#showIndex").show().find(".nomore").hide().siblings().show();
                        $('.user_list').show();
                        for (var i in data['user_list']) {
                            var data_ = data['user_list'][i];
                            console.log(data_)
                            // shtml += '<li><a href="'+personal_order_list_url+'&bind_id=' + data['user_list'][i]['pigcms_id'] + '">编号：' + data['user_list'][i]['usernum'] + '&nbsp;&nbsp;|&nbsp;&nbsp;业主姓名：' + data['user_list'][i]['name'] + '&nbsp;&nbsp;|&nbsp;&nbsp;地址：' + data['user_list'][i]['address'] + '</a></li>';
                            shtml += '<li><a href="'+personal_order_list_url+'&bind_id=' + data['user_list'][i]['pigcms_id'] + '">姓名：' + data['user_list'][i]['name'] + '&nbsp;&nbsp;|&nbsp;&nbsp;地址：' + data['user_list'][i]['address'] + '</a></li>';
                        }
                        $("#wuye").html(shtml)
                        $('.user_list_content').html(shtml);
                    } else {
                        $("#showIndex").show().find("#wuye").html('').closest('.more').hide().siblings().show();
                        shtml += '暂无相关数据';
                       // $(".nomore").html(shtml)
                        $('.user_list_content').html(shtml);
                    }
                },'json')
            }
        });
    })

    function check_user_submit() {
        var ajax_user_list_url = "{pigcms{:U('ajax_user_list')}";
        $.post(ajax_user_list_url,$('#find-form').serialize(),function (data) {
            var shtml = '';
            if (data.status) {
                $('.user_list').show();
                for (var i in data['user_list']) {
                    var data_ = data['user_list'][i];
                    console.log(data_)
                    shtml += '<span class="red">编号：' + data['user_list'][i]['usernum'] + '&nbsp;&nbsp;|&nbsp;&nbsp;业主姓名：' + data['user_list'][i]['name'] + '&nbsp;&nbsp;|&nbsp;&nbsp;地址：' + data['user_list'][i]['address'] + '</span><br />';
                    $('input[name="electric_price"]').val(data_.electric_price)
                    $('input[name="water_price"]').val(data_.water_price)
                    $('input[name="gas_price"]').val(data_.gas_price)
                    $('input[name="park_price"]').val(data_.park_price)
                }
                $('.user_list_content').html(shtml);
            } else {
                shtml += '暂无';
                $('.user_list_content').html(shtml);
            }
        },'json')
        return false;
    }


    var choose_province = "{pigcms{:U('ajax_unit')}",choose_floor = "{pigcms{:U('ajax_floor')}",
        choose_layer = "{pigcms{:U('ajax_layer')}",choose_owner = "{pigcms{:U('ajax_owner')}";

    if (document.getElementById('choose_cityarea')) {
        show_unit();
    }

    function show_unit() {
        $.post(choose_province,function (result) {
            result = $.parseJSON(result);
            if (result.error == 0) {
                var area_dom = '<select class="col-sm-2" id="choose_province" name="unit_id">';
                $.each(result.list,function (i,item) {
                    area_dom += '<option value="' + item.floor_id + '" ' + (item.floor_id == $('#choose_cityarea').attr('province_id') ? 'selected="selected"' : '') + '>' + item.floor_name + '</option>';
                });
                area_dom += '</select>';
                $('#choose_cityarea').prepend(area_dom);
                show_city($('#choose_province').find('option:selected').attr('value'),$('#choose_province').find('option:selected').html(),1);
                $('#choose_province').change(function () {
                    show_city($(this).find('option:selected').attr('value'),$(this).find('option:selected').html(),1);
                });
            } else if (result.error == 2) {
                var area_dom = '<select class="col-sm-2" id="choose_province_hide" name="unit_id" style="display:none;">';
                area_dom += '<option value="' + result.floor_id + '">' + result.floor_name + '</option>';
                area_dom += '</select>';
                $('#choose_cityarea').prepend(area_dom);
                show_city(result.id,result.name,0);
            } else {
                $('input[name="usernum"]').val('');
                //alert(result.info);
                location.href = "{pigcms{:U('owner_arrival')}";
            }
        });
    }

    function show_city(id,name,type) {
        $.post(choose_floor,{id: id,name: name,type: type},function (result) {
            result = $.parseJSON(result);
            if (result.error == 0) {
                var area_dom = '<select class="col-sm-2" id="choose_city" name="floor_id">';
                $.each(result.list,function (i,item) {
                    area_dom += '<option value="' + item.floor_id + '" ' + (item.id == $('#choose_cityarea').attr('city_id') ? 'selected="selected"' : '') + '>' + item.floor_layer + '</option>';
                });
                area_dom += '</select>';
                if (document.getElementById('choose_city')) {
                    $('#choose_city').replaceWith(area_dom);
                } else if (document.getElementById('choose_province')) {
                    $('#choose_province').after(area_dom);
                } else {
                    $('#choose_cityarea').prepend(area_dom);
                }
                if ($('#choose_cityarea').attr('area_id') != '-1') {
                    show_area($('#choose_city').find('option:selected').attr('value'),$('#choose_city').find('option:selected').html(),1);

                    $('#choose_city').change(function () {
                        show_area($(this).find('option:selected').attr('value'),$(this).find('option:selected').html(),1);
                    });
                }
            } else if (result.error == 2) {
                var area_dom = '<select class="col-sm-2" id="choose_city_hide" name="floor_id" style="display:none;">';
                area_dom += '<option value="' + result.floor_id + '">' + result.floor_name + '</option>';
                area_dom += '</select>';
                $('#choose_cityarea').prepend(area_dom);
                if ($('#choose_cityarea').attr('area_id') != '-1') {
                    show_area(result.id,result.name,0);
                }
            } else {
                $('input[name="usernum"]').val('');
                //alert(result.info);
                location.href = "{pigcms{:U('owner_arrival')}";
            }
        });
    }

    function show_area(id,name,type) {
        $.post(choose_layer,{id: id,name: name,type: type},function (result) {
            result = $.parseJSON(result);
            if (result.error == 0) {
                var area_dom = '<select class="col-sm-3" id="choose_area" name="pigcms_id">';
                $.each(result.list,function (i,item) {
                    area_dom += '<option value="' + item.pigcms_id + '" ' + (item.id == $('#choose_cityarea').attr('area_id') ? 'selected="selected"' : '') + '>' + item.address + '</option>';
                });
                area_dom += '</select>';
                if (document.getElementById('choose_area')) {
                    $('#choose_area').replaceWith(area_dom);
                } else if (document.getElementById('choose_city')) {
                    $('#choose_city').after(area_dom);
                } else {
                    $('#choose_cityarea').prepend(area_dom);
                }
                if ($('#choose_cityarea').attr('circle_id') != '-1') {
                    show_circle($('#choose_area').find('option:selected').attr('value'),$('#choose_area').find('option:selected').html(),1);
                    $('#choose_area').change(function () {
                        show_circle($(this).find('option:selected').attr('value'),$(this).find('option:selected').html(),1);
                    });
                }
            } else {
                $('input[name="usernum"]').val('');
                //alert(result.info);
            }
        });
    }

    function show_circle(id,name,type) {
        $.post(choose_owner,{id: id,name: name,type: type},function (result) {
            result = $.parseJSON(result);
            if (result.error == 0) {
                var area_dom = '<select id="choose_circle" name="owner_id" class="col-sm-2" style="margin-right:10px;">';
                $.each(result.list,function (i,item) {
                    area_dom += '<option value="' + item.pigcms_id + '" ' + (item.id == $('#choose_cityarea').attr('circle_id') ? 'selected="selected"' : '') + '>' + item.name + '</option>';
                    $('input[name="usernum"]').val(item['usernum']);
                });
                area_dom += '</select>';
                if (document.getElementById('choose_circle')) {
                    $('#choose_circle').replaceWith(area_dom);
                } else if (document.getElementById('choose_area')) {
                    $('#choose_area').after(area_dom);
                } else {
                    $('#choose_cityarea').prepend(area_dom);
                }


                //show_market($('#choose_circle').find('option:selected').attr('value'),$('#choose_circle').find('option:selected').html(),1);
                $('#choose_circle').change(function () {
                    //show_market($(this).find('option:selected').attr('value'),$(this).find('option:selected').html(),1);
                });
            } else {
                $('input[name="usernum"]').val('');
                alert(result.info);
            }
        });
    }

    var ajax_property_info_url = "{pigcms{:U('ajax_property_info')}"
    $('select[name="property_id"]').change(function () {
        var property_id = $(this).val();
        $.post(ajax_property_info_url,{'property_id': property_id},function (data) {
            if (data.status) {
                if (data['detail']['diy_type'] > 0) {
                    $('.property_desc').html(data['detail']['diy_content']);
                } else {
                    if (data['detail']['presented_property_month_num'] > 0) {
                        $('.property_desc').html('赠送' + data['detail']['presented_property_month_num'] + '个月');
                    } else {
                        $('.property_desc').html('');
                    }
                }
            } else {
                $('.property_desc').html('');
            }
        },'json')

    });
</script>

<include file="Public:footer"/>