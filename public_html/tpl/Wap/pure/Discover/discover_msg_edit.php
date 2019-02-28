<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>编辑信息</title>
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name='apple-touch-fullscreen' content='yes' />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="format-detection" content="address=no" />
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css" />
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css" />
    <link rel="stylesheet" href="{pigcms{$static_path}discover/css/discover.css?003" />
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/LCalendar.css" />
    <script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/jquery.cookie.js?210" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
    <style type="text/css">
        #container {
            bottom: -150px;
            top:0
        }
        .picNote {
            line-height: 1.3rem;
            font-size: 0.6rem;
            color: #808080;
            background: #e6e6e6;
            text-align: center;
        }
        .fabu-type-info textarea{
            min-height: 80px;
        }
        .relate-topic{padding:0.5rem;height: 2.5rem;}
        .relate-topic img{
            float: left;
            width: 2.5rem;
            margin-right: 0.5rem;
            height: 2.5rem;
        }
        .relate-topic p{
            word-break:break-all;
            line-height: 2.5rem;
        }
        .del_img{
            position: relative;
            height: 1.5rem;
            float: right;
            bottom: 1.8rem;
            left: 0.8rem;
            display: none;
        }
    </style>
</head>

<body>
<form method="post" action="{pigcms{:U('Discover/edit_discover_msg')}" onsubmit="return chk_submit()">
    <div id="container">
        <div id="scroller" class="discover_add_info">
            <input name="discover_id" type="hidden" value="{pigcms{$_GET['discover_id']}">
            <if condition="$_GET['type_id']">
                <input name="type_id" type="hidden" value="{pigcms{$_GET['type_id']}">
                <else/>
                <nav>
                    <section class="link-url">
                        <p>分类类型：</p>
                    </section>
                    <section class="fabu-type">
                        <select id="type_id" name="type_id" style="height:2.2rem; width:100%; display:block;" autofocus="true">
                            <option>请选择</option>
                            <volist name="discover_category" id="dc">
                                <option value="{pigcms{$dc['type_id']}">{pigcms{$dc['type_name']}</option>
                            </volist>
                        </select>
                    </section>
                </nav>
            </if>
            <div class="fabu-type-info">
                <nav>
                    <section>
                        <textarea autoHeight="true" name="discover_content" id="discover_content" onKeyDown="textdown(event)" onKeyUp="textup()" placeholder="快来晒晒你的新鲜事吧。">{pigcms{$info['discover_content']}</textarea>
                    </section>
                </nav>
            </div>

            <nav>
                <section>
                    <dd class="item">
                        <div class="picNote">
                            <php>$img_count = count($img_list);</php>
                            还可上传<b id="img_post_count"><if condition="$img_count  gt 0"><php>echo  9-$img_count ;</php><else />9</if></b>张图片，已上传<b id="img_already_count"><if condition="$img_count gt 0">{pigcms{$img_count}<else />0</if></b>张(非必填)
                        </div>
                        <div class="upload_box">
                            <ul class="upload_list clearfix" id="upload_list">
                                <if condition='$img_list'>
                                    <volist name='img_list' id='img_info'>
                                        <li class="upload_item" id="imgShow{pigcms{$i}" style="background: url('{pigcms{$img_info}') center center / cover;">
                                            <a href="javascript:void(0);" class="upload_delete" title="删除"></a>
                                            <img src="{pigcms{$img_info}" url="{pigcms{$img_info}" class="upload_image loading_img">
                                            <input type="hidden" name="inputimg[]" value="{pigcms{$img_info}">
                                            <br>
                                        </li>
                                    </volist>
                                </if>
                                <li class="upload_action">
                                    <img src="{pigcms{$static_path}discover/images/addImg.png">
                                    <input type="file" accept="image/jpg,image/jpeg,image/png,image/gif" id="fileImage">
                                </li>
                            </ul>
                        </div>
                    </dd>
                </section>
            </nav>

            <nav>
                <section>
                    <input class="input_msg" type="text" name="discover_url" id="discover_url" value="{pigcms{$info['discover_url']}" placeholder="填写图片链接（选填可用的完整链接）" />

                    <img class="del_img" src="{pigcms{$static_path}discover/images/icon/del.png?t=001" />
                </section>
            </nav>


            <nav id="url_info" style="margin-bottom: 60px;">

            </nav>

        </div>

    </div>
    <footer class="footerMenu wap house" style="height: 3rem;">
        <input type="submit" value="确认修改" class="fabu-btn" />
    </footer>
    <div id="allmap"></div>
</form>
<!--		<script type="text/javascript" src="{pigcms{$static_path}js/LCalendar.js"></script>-->
<script src="{pigcms{$static_path}js/exif.js"></script>
<script src="{pigcms{$static_path}discover/js/imgUpload.js?t=004"></script>
<script src="{pigcms{$static_path}discover/js/md5.js"></script>
<script src="{pigcms{$static_path}discover/js/previewImage.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}/layer/layer.m.js" charset="utf-8"></script>
<script type="text/javascript" language="JavaScript">
    $('.fabu-type ul li').each(function(i) {
        $(this).click(function() {
            $('.fabu-type ul li').removeClass('active');
            $('.fabu-type-info').hide().eq(i).show();
            $(this).addClass('active');
            $('input[name="type"]').val($(this).data('type'));
        });
    });

    var lock = false;

    load();
    function load() {
        $('#type_id').val("{pigcms{$info['type_id']}")
        $(function(){
            $.fn.autoHeight = function(){
                function autoHeight(elem){
                    elem.style.height = 'auto';
                    elem.scrollTop = 0; //防抖动
                    elem.style.height = elem.scrollHeight + 'px';
                    if (elem.scrollHeight > 88) {
                        var height = elem.scrollHeight - 88;
                        var container_bottom = -150 - height;
                        $('#container').css('bottom', container_bottom);
                    }
                }
                this.each(function(){
                    autoHeight(this);
                    $(this).on('keyup', function(){
                        autoHeight(this);
                    });
                });
            }
            $('textarea[autoHeight]').autoHeight();
        })

        $(function(){
            var list = $(".upload_list li")
            var height = list[0].clientWidth + 1;
            list.css('height', height)
        })


        var val = $('.input_msg').val();
        if (val) {
            lock = true;
            var check_url = "{pigcms{:U('check_url')}";
            $.post(check_url,{'url': val},function(data){
                console.log('点赞返回信息：', data);
                lock = false;
                if(data.status==0){
                    $('.input_msg').val("");
                    alert(data.msg)
                }else{
                    var url = "'" + data.info.url + "'";
                    var html = '<div class="relate-topic" onclick="location.href='+url+'">';
                    html += '<img src="'+data.info['title_pic']+'">';
                    html += '<p>'+data.info['title']+'</p>';
                    html += '</div>';
                    $('#url_info').html("");
                    $('#url_info').append(html);
                    $('.del_img').css('display', 'block');
                    $(this).val(data.info.url);
                }
            },'json')
        } else {
            lock = false;
            $('#url_info').html("");
        }

    }


    var edit_discover_msg_url = "{pigcms{:U('edit_discover_msg')}";
    var submit = false;
    function chk_submit(){
        if (submit) return;
        var type_id = "{pigcms{$_GET['type_id']}";
        submit = true;
        if (!type_id) {
            var val = $('#type_id').val();
            if(!val || val == '请选择'){
                submit = false;
                layer.open({
                    content: '请选择分类!',
                    btn: ['确定'],
                    shadeClose: false
                });
                return false;
            }
        }
        if(!$('#discover_content').val()){
            submit = false;
            layer.open({
                content: '请填写你的新鲜事吧!',
                btn: ['确定'],
                shadeClose: false
            });
            return false;
        }
        $.post(edit_discover_msg_url,$('form').serialize(),function(result){
            console.log('编辑数据-------', result);
            submit = false;
            if(result.status && result.url){
                window.location.href=result.url;
            }else{
                layer.open({
                    content: result.info,
                    btn: ['确定'],
                    shadeClose: false,
                    yes:function(){
                        window.location.reload();
                    }
                });
            }

        },'json')
        return false;
    }

    // 链接输入框焦点触发时间
    $('.input_msg').blur(function() {
        console.log('离开焦点', $(this).val(), lock);
        $('#scroller').css({'top': '0px','bottom': '-150px'});
        if (lock) return;
        var val = $(this).val();
        if (val) {
            lock = true;
            var check_url = "{pigcms{:U('check_url')}";
            $.post(check_url,{'url': val},function(data){
                console.log('点赞返回信息：', data);
                lock = false;
                if(data.status==0){
                    $('.input_msg').val("");
                    alert(data.msg)
                }else{
                    var url = "'" + data.info.url + "'";
                    var html = '<div class="relate-topic" onclick="location.href='+url+'">';
                    html += '<img src="'+data.info['title_pic']+'">';
                    html += '<p>'+data.info['title']+'</p>';
                    html += '</div>';
                    $('#url_info').html("");
                    $('#url_info').append(html);
                    $('.del_img').css('display', 'block');
                    $(this).val(data.info.url);
                }
            },'json')
        } else {
            lock = false;
            $('#url_info').html("");
        }
    }).focus(function(){
        console.log('获取焦点', $(this).val());
        var winHeight = $(window).height(); //获取当前页面高度
        var tmp_px1 = winHeight/2 - 90;
        $('#scroller').css({'bottom': tmp_px1 + 'px'});
    });

    // 清除数据
    $('.del_img').click(function () {
        lock = true;
        $('.input_msg').focus();
        $('.input_msg').val("");
        $('#url_info').html("");
        $('.del_img').css('display', 'none');
        setTimeout(function () {
            lock = false;
        }, 800);
    });



    function textdown(e) {
        textevent = e;
        if(textevent.keyCode == 8) {
            return;
        }
        // if(document.getElementById('content').value.length >= 1000) {
        // 	alert("大侠，手下留情，此处限字1000")
        // 	if(!document.all) {
        // 		textevent.preventDefault();
        // 	} else {
        // 		textevent.returnValue = false;
        // 	}
        // }
    }

    function textup() {
        // var s = document.getElementById('content').value;
        // //判断ID为text的文本区域字数是否超过100个
        // if(s.length > 100) {
        // 	document.getElementById('content').value = s.substring(0, 100);
        // }
    }

    var ajaxImgUpload_url = "{pigcms{:U('ajaxImgUpload')}&ml=discover";

    if ($(".upload_list").length) {
        console.log('上传图片', ajaxImgUpload_url);
        var imgUpload = new ImgUpload({
            fileInput: "#fileImage",
            container: "#upload_list",
            countNum: "#uploadNum",
            url:ajaxImgUpload_url,
        })
    }

    // 点击查看大图
    $(document).on('click', '.upload_item', function (e) {
        var tagNameWord = e.target.tagName;
        if (tagNameWord == 'A') return;
        var str = $(this).css('background-image')
        var current = str.substring(5, str.length-2)
        var urls = []
        var list = $(".upload_list li img")
        for (var i = 0; i < list.length-1; i++) {
            urls.push(list[i].currentSrc)
        }
        var obj = {
            urls : urls,
            current : current
        };
        previewImage.start(obj);
    })
</script>
</body>

</html>