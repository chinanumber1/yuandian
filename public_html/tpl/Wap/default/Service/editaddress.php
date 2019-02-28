<html lang="en">
<head>
<meta charset="UTF-8" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="yes" name="apple-touch-fullscreen" />
<meta content="telephone=no" name="format-detection" />
<meta content="black" name="apple-mobile-web-app-status-bar-style" />
<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, minimum-scale=1.0,user-scalable=no"/>
<meta name="baidu-site-verification" content="Rp99zZhcYy" />
<meta name="keywords" content=""/>
<meta name="description" content=""/>

<link href='{pigcms{$static_path}service/css/basic.css?t=58da05f1' rel='stylesheet' type='text/css' />
<script src='{pigcms{$static_path}service/js/jquery-2.1.4.js?t=58a16a34'></script>
<script src='{pigcms{$static_path}service/js/json2.js?t=58a16a34'></script>
<script src='{pigcms{$static_path}service/js/basic.js?t=58d24290'></script>
<script src='{pigcms{$static_path}service/js/jquery.validate.min.js?t=58a16a34'></script>
<script src='{pigcms{$static_path}service/js/md5.min.js?t=58a16a34'></script>
<script src='{pigcms{$static_path}service/js/newcode-src.js?t=58a16a34'></script>
<title>修改地址</title>
<link href='{pigcms{$static_path}service/css/demand.css?t=58d8bf20' rel='stylesheet' type='text/css' /></head>
<body>
    <div class="pagewrap" id="mainpage">
        <div class="clear"></div>
        <div class="show-top-bar-wrap show-top-bar-s2">
            <div class="show-top-bar js_topfixed js_map_topfixed">
                <div class="show-bar">
                    完善服务商资料，提高报价采纳率 
                </div>
            </div>
        </div>
        <div class="main">
            <div class="service-form" id="demand_form">
                <form action="{pigcms{:U('Service/editaddress')}" id="publish_demand_form" method="post">

                    <div class="service-edit-box1 demand-form-list form-list-show">
                        <div class="form-list1">
                            <div class="li coordinate-ele js_coordinate_ele" id="js_sel_service_address" >
                                <label class="lab-title"> <span class="validate-title">服务商地址：</span> </label>
                                <div class="ele-wrap ">
                                    <input class="form-control js_coordinate_address coordinate_address" onfocus="addresshref()" name="address" placeholder="请输入您的服务商地址"  type="text" value="{pigcms{$providerInfo.sname}">
                                    <input type="hidden" name="pid" value="{pigcms{$providerInfo.pid}">
                                    <input type="hidden" name="sname" value="{pigcms{$providerInfo.sname}">
                                    <input type="hidden" name="lng" value="{pigcms{$providerInfo.lng}">
                                    <input type="hidden" name="lat" value="{pigcms{$providerInfo.lat}">
                                <div class="clear"></div>
                                <div class="js_coordinate_map coordinate_map hidden" ></div>
                            </div>
                        </div>
                    </div>

                    <div class="pop_action pop_action_s2 js_bottomfixed">
                        <a actioncolse="false" href="{pigcms{:U('Service/provider_home')}"class="btn btn-blue btn-s2"> <i class="ico ico-goback hidden"></i> 取消 </a>
                        <button type="submit"  id="js_publish_demand_submit" class="btn btn-orange btn-s2"> <i class="ico ico-sure"></i> 确定 </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    <script>
        function addresshref(){
            location.href="{pigcms{:U('Service/editaddressdata')}";
        }
    </script>


</body>
</html>


