<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta http-equiv="Expires" content="-1">
	<meta http-equiv="Cache-Control" content="no-cache">
	<meta http-equiv="Pragma" content="no-cache">
	<meta charset="utf-8">
	<title>认证页面</title>
	<link href="{pigcms{$static_path}yuedan/css/css_whir.css" rel="stylesheet"/>
	<link href="{pigcms{$static_path}yuedan/css/wap_uploadimg.css" rel="stylesheet"/>
	<script src="{pigcms{$static_path}yuedan/js/jquery-1.9.1.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="{pigcms{$static_path}js/iscroll.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
	<script type="text/javascript" src="{pigcms{$static_public}js/ajaxfileupload.js"></script>
	<!--[if lte IE 9]>
	<script src="scripts/html5shiv.min.js"></script>
	<![endif]-->
	<style>
		.ft {
		    float: left;
		}
		header {
		    width: 100%;
		    height: 44px;
		    line-height: 44px;
		    border-bottom: 1px solid #F1F1F1;
		    background: #06C1AE;
		}
		header a {
		    margin-left: 3%;
		    color: #fff;
		    font-size: 16px;
		}
		a {
		    text-decoration: none;
		}
		header span {
		    display: block;
		    width: 40%;
		    margin: 0 30%;
		    text-align: center;
		    color: #fff;
		    font-size: 16px;
		}
	</style>
</head>
<body>
	<header>
		<a href="{pigcms{:U('my_index')}" class="ft"><i></i></a>
		<span>个人认证</span>
	</header>

	<if condition="$authentication_info['authentication_status'] eq 1">
		<div class="Guide_top Guide_shz">
			<h5>&nbsp;<span>审核中</span></h5>
		</div>
	<elseif condition="$authentication_info['authentication_status'] eq 3"/>
		<div class="Guide_top" >
			<h5>&nbsp;<span style="width: 90px;">审核未通过</span></h5>
		</div>
	<elseif condition="$authentication_info['authentication_status'] eq 2"/>
		<div class="Guide_top Guide_ytg">
			<h5>&nbsp;<span>已通过</span></h5>
		</div>
	</if>

	<form id="myform" method="post" action="{pigcms{:U('Yuedan/authentication_data')}" frame="true" refresh="true">
		<input type="hidden" name="authentication_id" value="{pigcms{$authentication_info['authentication_id']}" />
		<div class="Secondary">
			<ul>

				<volist name="authentication_wenben" id="vo">
					<li>
						<span>{pigcms{$vo.title}</span>
						<input id="{pigcms{$vo.key}" name="{pigcms{$vo.key}[value]" value="{pigcms{$authentication_info['authentication_field'][$vo['key']]['value']}" type="text" placeholder="请输入{pigcms{$vo.title}">
						<input type="hidden" name="{pigcms{$vo.key}[type]" value="{pigcms{$vo.type}"/>
						<input type="hidden" name="{pigcms{$vo.key}[title]" value="{pigcms{$vo.title}"/>
					</li>
				</volist>
			</ul>
		</div>
		<div class="Secondary">
			<!-- 新增 -->
			<volist name="authentication_tupian" id="vo">
				<div class="increaseid">
					<dl class="clr">
						<dd class="fl">
							<div class="increaseid_end">
								<h2>{pigcms{$vo.title}上传</h2>
							</div>
						</dd>
						<dd class="p165">
							<ul class="upload_list3 clearfix increaseid_top" id="upload_list3">
								<li class="upload_action">
									<img src="{pigcms{$authentication_info['authentication_field'][$vo['key']]['value']}" id="image_src_{pigcms{$vo.key}" onclick="upimgFileBtn('{pigcms{$vo.key}')" style="width: 229px; height: 144px;" />
									<input type="hidden" name="{pigcms{$vo.key}[value]" value="{pigcms{$authentication_info['authentication_field'][$vo['key']]['value']}" id="image_value_{pigcms{$vo.key}" />
									
									<input type="hidden" name="{pigcms{$vo.key}[type]" value="{pigcms{$vo.type}"/>
									<input type="hidden" name="{pigcms{$vo.key}[title]" value="{pigcms{$vo.title}"/>
								</li>
							</ul>
						</dd>
					</dl>
				</div>
			</volist>
		</div>

		<div class="dysb_d">
			<input type="file" id="imgUploadFile" onchange="imgUpload()" style="display: none;" name="imgFile" accept="image/*" value="选择文件上传"/>
      		<input id="submit" type="submit" value="重新提交" class="dysub">
	    </div>
	</form>
    


    <script>
    	$('header a').click(function(e){
    		location.href="{pigcms{:U('my_index')}";
    	});
        var imgKey='';

        function upimgFileBtn(key){
        	imgKey = key;
        	$("#imgUploadFile").click();
        }

        function imgUpload(){
            $.ajaxFileUpload({
                url:"{pigcms{:U('Yuedan/ajax_upload_file')}",
                secureuri:false,
                fileElementId:'imgUploadFile',
                dataType: 'json',
                success: function (data) {
                    if(data.error == 2){
                    	$("#image_src_"+imgKey).attr("src",data.url);
                    	$("#image_value_"+imgKey).val(data.url);
                    	imgKey='';
                    }else{
                        layer.open({
                            content: data.msg
                            ,btn: ['确定']
                        });
                    }
                }
            }); 
        }
    </script>


</body>
</html>