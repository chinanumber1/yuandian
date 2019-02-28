<include file="Public:header"/>

<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-tablet"></i>
                <a href="{pigcms{:U('payment_item')}">收费设置</a>
            </li>
            <li class="active">添加收费标准</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <style>
                .ace-file-input a {display:none;}
            </style>
            <div class="row">
                <div class="col-xs-12">
                    <form  class="form-horizontal" method="post" onSubmit="return check_submit()" action="__SELF__">
                        <div class="tab-content">
                            <div id="basicinfo" class="tab-pane active">
                                <input type="hidden" name="payment_id" id="payment_id" value="{pigcms{$_GET['payment_id']}">
                                <input type="hidden" name="standard_id" id="standard_id" value="{pigcms{$_GET['standard_id']}">
                                <div class="form-group">
                                    <label class="col-sm-1">收费模式</label>
                                    <label onclick="pay_type_click(1)" style="padding-left:0px;padding-right:20px;"><input type="radio" <if condition="$info.pay_type eq 1">checked="checked"</if>  class="ace" value="1" name="pay_type"><span style="z-index: 1" class="lbl">固定费用</span></label>
                                    <label onclick="pay_type_click(2)" style="padding-left:0px;"><input type="radio" <if condition="$info.pay_type eq 2">checked="checked"</if> class="ace" value="2" name="pay_type"><span style="z-index: 1" class="lbl">按单价*数量</span></label>
                                </div>
                                <script>
                                    function pay_type_click(type){
                                        if(type == 1){
                                            $(".metering_mode").css('display','none');
                                        }else{
                                            $(".metering_mode").css('display','block');
                                        }
                                    }
                                </script>
                                <!-- <div class="form-group metering_mode" <if condition="$info.pay_type eq '1'"> style="display: none;"<else/>style="display: block;"</if> >
                                    <label class="col-sm-1">计量方式</label>
                                    <input class="col-sm-2" size="" name="metering_mode" placeholder="自定义计量方式  例：房间面积" id="metering_mode" type="text" value="{pigcms{$info.metering_mode}"/>
                                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="red">（必填项）</span> 
                                </div> -->


                                <div class="form-group metering_mode" <if condition="$info.pay_type eq '1'"> style="display: none;"<else/>style="display: block;"</if>>
                                    <label class="col-sm-1">计量方式</label>

                                    <label onclick="metering_mode_type_click(1)" style="padding-left:0px;padding-right:20px;">
                                        <input  type="radio" class="ace" value="1" name="metering_mode_type" <if condition="$info['metering_mode_type'] eq 1">checked="checked"</if>>
                                        <span style="z-index: 1" class="lbl">房间面积</span>
                                    </label>

                                    <label onclick="metering_mode_type_click(3)" style="padding-left:0px;padding-right:20px;">
                                        <input  type="radio" class="ace" value="3" name="metering_mode_type" <if condition="$info['metering_mode_type'] eq 3">checked="checked"</if>>
                                        <span style="z-index: 1" class="lbl">车位面积</span>
                                    </label>

                                    <label onclick="metering_mode_type_click(2)" style="padding-left:0px;">
                                        <input type="radio" class="ace" value="2" name="metering_mode_type" <if condition="$info['metering_mode_type'] eq 2">checked="checked"</if>>
                                        <span style="z-index: 1" class="lbl">自定义&nbsp;
                                            <if condition="$info['metering_mode_type'] eq '2'">
                                            <input type="text" name="metering_mode" id="metering_mode" value="{pigcms{$info.metering_mode}" placeholder="例：楼道面积"></span>
                                            <else/>
                                            <input type="text" name="metering_mode" id="metering_mode" value="" placeholder="例：楼道面积" style="display: none;"></span>
                                            </if> 
                                    </label>

                                </div>

                                <script>
                                    function metering_mode_type_click(type){
                                        if(type != 2){
                                            $("#metering_mode").css('display','none');
                                        }else{
                                            $("#metering_mode").css('display','');
                                        }
                                    }
                                </script>




                                <div class="form-group">
                                    <label class="col-sm-1"><label for="pay_money">收费金额</label></label>
                                    <input onkeyup="if(isNaN(value))execCommand('undo')" size="20" name="pay_money" id="pay_money" type="text" value="{pigcms{$info.pay_money}"/>
                                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="red">（必填项）</span>
                                </div>
                                
                                <!-- <div class="form-group">
                                    <label class="col-sm-1"><label for="cycle_type">周期类型</label></label>
                                    <select name="cycle_type" id="cycle_type">
                                        <option value=''>请选择</option>
                                        <option <if condition="$info.cycle_type eq 'Y'">selected="selected"</if> value='Y'>年</option>
                                        <option <if condition="$info.cycle_type eq 'M'">selected="selected"</if> value='M'>月</option>
                                        <option <if condition="$info.cycle_type eq 'D'">selected="selected"</if> value='D'>日</option>
                                    </select>
                                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="red">（必填项）</span>
                                </div> -->
                                
                                <div class="form-group">
                                    <label class="col-sm-1"><label for="pay_cycle">收费周期</label></label>
                                    <input onkeyup="this.value=this.value.replace(/\D/g,'')" size="6" name="pay_cycle" id="pay_cycle" type="text" value="{pigcms{$info.pay_cycle}"/>
                                    &nbsp;
                                    <select name="cycle_type" id="cycle_type">
                                        <option <if condition="$info.cycle_type eq 'Y'">selected="selected"</if> value='Y'>年</option>
                                        <option <if condition="$info.cycle_type eq 'M'">selected="selected"</if> value='M'>月</option>
                                        <option <if condition="$info.cycle_type eq 'D'">selected="selected"</if> value='D'>日</option>
                                    </select>
                                    &nbsp;/&nbsp;周期&nbsp;&nbsp;&nbsp;&nbsp;<span class="red">即每隔多长时间收取一次费用。例如卫生费用是按月收取，计费周期就填写1（单位：月）；如果是半年收取一次，就填写6（单位：月）。（必填项）</span>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-1"><label for="max_cycle">周期上限</label></label>
                                    <input size="6" onkeyup="this.value=this.value.replace(/\D/g,'')" name="max_cycle" id="max_cycle" type="text" value="{pigcms{$info.max_cycle}"/>
                                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="red">即业主缴费时，最多可选择缴费的周期数</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="red">（必填项）</span>
                                </div>
                                        
                                <div class="form-group">
                                    <label class="col-sm-1"><label for="pay_icon">收费图标</label></label>
                                    <input class="btn btn-primary" type="button" id="image3" value="选择图片" />
                                    <input type="hidden" name="pay_icon" value="{pigcms{$info.pay_icon}" id="pay_icon" />
                                    <img src="{pigcms{$info.pay_icon}" id="img_pay_icon" style="padding: 15px; width: 80px;">
                                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="red">建议尺寸 80*80</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="red">（必填项）</span>
                                </div>

                                <link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
                                <script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
                                <script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
                                <script>
                                    KindEditor.ready(function(K) {
                                        var editor = K.editor({
                                            allowFileManager : true
                                        });
                                        K('#image3').click(function() {
                                            editor.uploadJson = "{pigcms{:U('ajax_upload_pic')}";
                                            editor.loadPlugin('image', function() {
                                                editor.plugin.imageDialog({
                                                    showRemote : false,
                                                    imageUrl : K('#url3').val(),
                                                    clickFn : function(url, title, width, height, border, align) {
                                                        var img = K('#img_pay_icon');
                                                        img.attr("src",url);
                                                        K("#pay_icon").val(url);
                                                        editor.hideDialog();
                                                    }
                                                });
                                            });
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                        <div class="space"></div>
                            <div class="clearfix form-actions">
                                <div class="col-md-offset-3 col-md-9">
                                    <if condition="in_array(77,$house_session['menus'])">
                                    <button class="btn btn-info" type="submit">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        保存
                                    </button>
                                    <else/>
                                    <button class="btn btn-info" type="submit" disabled="disabled">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        保存
                                    </button>
                                    </if>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>




<script type="text/javascript">
function check_submit(){

    var radio = $('input[name="metering_mode_type"]:checked').val();
    if(radio ==2){
        if($('#metering_mode').val() == ''){
            alert('自定义计量方式不能为空');
            return false;
        }
    }
    
    if($('#pay_money').val() == ''){
        alert('收费金额不能为空！');
        return false;
    }

    if($("option:selected","#cycle_type").val() == ''){
        alert('周期类型不能为空！');
        return false;
    }

    if($('#pay_cycle').val() == ''){
        alert('收费周期不能为空！');
        return false;
    }

    if($('#max_cycle').val() == ''){
        alert('周期上限不能为空！');
        return false;
    }
    

    if($('#pay_icon').val() == ''){
        alert('收费图标不可以为空');
        return false;
    }

}

// $('select').change(function(){
//     if($(this).val() == 0){
//         $(this).next('label').show();
//     }else{
//         $(this).next('label').hide();
//     }
// });
</script>

<include file="Public:footer"/>