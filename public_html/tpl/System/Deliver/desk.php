<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/desk/style_google.css">
<style type="text/css">
.amap-indoor-map .label-canvas {
    position: absolute;
    top: 0;
    left: 0
}

.amap-indoor-map .highlight-image-con * {
    pointer-events: none
}

.amap-indoormap-floorbar-control {
    position: absolute;
    margin: auto 0;
    bottom: 165px;
    right: 12px;
    width: 35px;
    text-align: center;
    line-height: 1.3em;
    overflow: hidden;
    padding: 0 2px
}

.amap-indoormap-floorbar-control .panel-box {
    background-color: rgba(255, 255, 255, .9);
    border-radius: 3px;
    border: 1px solid #ccc
}

.amap-indoormap-floorbar-control .select-dock {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    box-sizing: border-box;
    height: 30px;
    border: solid #4196ff;
    border-width: 0 2px;
    border-radius: 2px;
    pointer-events: none;
    background: linear-gradient(to bottom, #f6f8f9 0, #e5ebee 50%, #d7dee3 51%, #f5f7f9
        100%)
}

.amap-indoor-map .transition {
    transition: opacity .2s
}

,
.amap-indoormap-floorbar-control .transition {
    transition: top .2s, margin-top .2s
}

.amap-indoormap-floorbar-control .select-dock:after,
    .amap-indoormap-floorbar-control .select-dock:before {
    content: "";
    position: absolute;
    width: 0;
    height: 0;
    left: 0;
    top: 10px;
    border: solid transparent;
    border-width: 4px;
    border-left-color: #4196ff
}

.amap-indoormap-floorbar-control .select-dock:after {
    right: 0;
    left: auto;
    border-left-color: transparent;
    border-right-color: #4196ff
}

.amap-indoormap-floorbar-control.is-mobile {
    width: 37px
}

.amap-indoormap-floorbar-control.is-mobile .floor-btn {
    height: 35px;
    line-height: 35px
}

.amap-indoormap-floorbar-control.is-mobile .select-dock {
    height: 35px;
    top: 36px
}

.amap-indoormap-floorbar-control.is-mobile .select-dock:after,
    .amap-indoormap-floorbar-control.is-mobile .select-dock:before {
    top: 13px
}

.amap-indoormap-floorbar-control.is-mobile .floor-list-box {
    height: 105px
}

.amap-indoormap-floorbar-control .floor-list-item .floor-btn {
    color: #555;
    font-family: "Times New Roman", sans-serif, "Microsoft Yahei";
    font-size: 16px
}

.amap-indoormap-floorbar-control .floor-list-item.selected .floor-btn {
    color: #000
}

.amap-indoormap-floorbar-control .floor-btn {
    height: 28px;
    line-height: 28px;
    overflow: hidden;
    cursor: pointer;
    position: relative;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none
}

.amap-indoormap-floorbar-control .floor-btn:hover {
    background-color: rgba(221, 221, 221, .4)
}

.amap-indoormap-floorbar-control .floor-minus,
    .amap-indoormap-floorbar-control .floor-plus {
    position: relative;
    text-indent: -1000em
}

.amap-indoormap-floorbar-control .floor-minus:after,
    .amap-indoormap-floorbar-control .floor-plus:after {
    content: "";
    position: absolute;
    margin: auto;
    top: 9px;
    left: 0;
    right: 0;
    width: 0;
    height: 0;
    border: solid transparent;
    border-width: 10px 8px;
    border-top-color: #777
}

.amap-indoormap-floorbar-control .floor-minus.disabled,
    .amap-indoormap-floorbar-control .floor-plus.disabled {
    opacity: .3
}

.amap-indoormap-floorbar-control .floor-plus:after {
    border-bottom-color: #777;
    border-top-color: transparent;
    top: -3px
}

.amap-indoormap-floorbar-control .floor-list-box {
    max-height: 153px;
    position: relative;
    overflow-y: hidden
}

.amap-indoormap-floorbar-control .floor-list {
    margin: 0;
    padding: 0;
    list-style: none
}

.amap-indoormap-floorbar-control .floor-list-item {
    position: relative
}

.amap-indoormap-floorbar-control .floor-btn.disabled,
    .amap-indoormap-floorbar-control .floor-btn.disabled *,
    .amap-indoormap-floorbar-control.with-indrm-loader * {
    -webkit-pointer-events: none !important;
    pointer-events: none !important
}

.amap-indoormap-floorbar-control .with-indrm-loader .floor-nonas {
    opacity: .5
}

.amap-indoor-map-moverf-marker {
    color: #555;
    background-color: #FFFEEF;
    border: 1px solid #7E7E7E;
    padding: 3px 6px;
    font-size: 12px;
    white-space: nowrap;
    display: inline-block;
    position: absolute;
    top: 1em;
    left: 1.2em
}

.amap-indoormap-floorbar-control .amap-indrm-loader {
    -moz-animation: amap-indrm-loader 1.25s infinite linear;
    -webkit-animation: amap-indrm-loader 1.25s infinite linear;
    animation: amap-indrm-loader 1.25s infinite linear;
    border: 2px solid #91A3D8;
    border-right-color: transparent;
    box-sizing: border-box;
    display: inline-block;
    overflow: hidden;
    text-indent: -9999px;
    width: 13px;
    height: 13px;
    border-radius: 7px;
    position: absolute;
    margin: auto;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0
}

@
-moz-keyframes amap-indrm-loader { 0%{
    -moz-transform: rotate(0);
    transform: rotate(0)
}

100%{
-moz-transform
:rotate(360deg)
;transform
:rotate(360deg)
}
}
@
-webkit-keyframes amap-indrm-loader { 0%{
    -webkit-transform: rotate(0);
    transform: rotate(0)
}

100%{
-webkit-transform
:rotate(360deg)
;transform
:rotate(360deg)
}
}
@
keyframes amap-indrm-loader { 0%{
    -moz-transform: rotate(0);
    -ms-transform: rotate(0);
    -webkit-transform: rotate(0);
    transform: rotate(0)
}
100%{
-moz-transform
:rotate(360deg)
;-ms-transform
:rotate(360deg)
;-webkit-transform
:rotate(360deg)
;transform
:rotate(360deg)
}
}
</style>
<style type="text/css">
@charset "UTF-8"; 

[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak,
    .x-ng-cloak, .ng-hide:not (.ng-hide-animate ){
    display: none !important;
}

ng\:form {
    display: block;
}

.ng-animate-shim {
    visibility: hidden;
}

.ng-anchor {
    position: absolute;
}
.ng-hide {
display:none !important;
}
.BMapLabel{
    border: 0px !important;
}
.tips{
    background-color: #1073f0;
    margin-top: -6px;
    width: 50px;
    text-align: center;
    color: #ffffff;
}
    .gm-style-iw{
        position:unset;
    }
</style>
<title>配送员|调度控制台</title>
<base href=".">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<link href="{pigcms{$static_path}css/desk/quill.snow.css" rel="stylesheet">
<link href="{pigcms{$static_path}css/desk/commoncss.css" rel="stylesheet">
<link href="{pigcms{$static_path}css/desk/application.css?t=<?php echo time();?>" rel="stylesheet">
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE')}"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
</head>
<body class="embed-theme ng-scope">
    <header class="l-header ng-scope">
        <nav class="navbar l-header__navbar ng-scope">
            <div class="container-fluid">
                <div class="collapse navbar-collapse l-header__navbar-collapse">
                    <form class="navbar-form navbar-right ng-pristine ng-valid ng-valid-pattern">
                        <div class="form-group">
                            <input type="text" class="form-control l-header__navbar-search ng-pristine ng-untouched ng-valid ng-empty ng-valid-pattern" placeholder="请输入完整订单号/运单号查询" name="keyword">
                        </div>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <article class="core-view ng-scope">
        <div class="hb-fit-page ng-scope">
            <div id="order-fresh-new-page" class="ng-scope">
                <div class="off-work-tip ng-hide">
                    <p>
                        <span class="glyphicon glyphicon-info-sign"></span>
                        <span>您已下班，营业时间 :
                            <span class="ng-binding">06:00:00</span>－
                            <span class="ng-binding">23:00:00</span>
                        </span>
                        <span class="pull-right off-work-closeBtn"> x </span>
                    </p>
                </div>
                <div class="dispatch-nav-wrapper ng-scope ng-isolate-scope">
                    <nav class="dispatch-nav">
                        <a href="javascript:;" id="unGetCount">待指派 ( 0 )</a>　
                        <a data-href="{pigcms{:U('Deliver/order')}" href="javascript:void(0);" id="sendCount">配送中 ( 0 )</a>
                        <!--a href=""> 异常订单 ( 9 ) </a-->
                        <!--a href=""> 完成订单 ( 532 ) </a-->
                    </nav>

                    <!--form class="search-form ng-pristine ng-valid ng-valid-pattern">
                        <div class="form-group">
                            <input type="text" class="form-control ng-pristine ng-untouched ng-valid ng-empty ng-valid-pattern" placeholder="请输入完整订单号/运单号查询" name="keyword">
                        </div>
                    </form-->

                    <span class="voice-control">
                        <i class="fa fa-volume fa-volume-up hb-text-medium ng-scope" ></i>
                    </span>
                    <span class="team-info">城市选择：<if condition="$area_type eq 3">【{pigcms{$area_name}】</if>
                        <div class="ui-select-extend ui-select-container select2 select2-container" id="choose_pca" province_id="{pigcms{$province_id}" city_id="{pigcms{$city_id}" area_id="{pigcms{$area_id}" is_province="{pigcms{$is_province}" is_city="{pigcms{$is_city}" is_area="{pigcms{$is_area}">
                        </div>
                    </span>
                </div>
                <header>
                    <div class="lag-emergency-mode ng-hide">
                        <label>
                            <input type="checkbox" class="ng-pristine ng-untouched ng-valid ng-empty">手动刷新模式</label>
                    </div>
                    <div class="dispatch-mode ng-scope">
                        <span>调度模式</span>
                        <div class="btn-group btn-mode dropdown">
                            <span class="btn btn-default dropdown-toggle">
                                <span class="ng-binding"><if condition="$config['deliver_model'] eq 1">手动调度<else />配送员抢单</if></span>
                                <i class="fa fa-angle-down"></i>
                            </span>
                            <ul class="dropdown-menu">
                                <li class="ng-binding ng-scope" data-model="1">手动调度</li>
                                <li class="ng-binding ng-scope" data-model="0">配送员抢单</li>
                            </ul>
                        </div>
                        <i class="fa fa-question-circle question pointer ng-hide"></i>
                    </div>
                </header>
                <section>
                    <div class="order-list-wrapper">
                        <!--div class="search-wrapper hb-margin-bottom dropdown">
                            <input class="form-control ng-pristine ng-untouched ng-valid dropdown-toggle ng-empty" type="text" placeholder="输入商家名称筛选">
                        </div-->
                        <div class="order-tab ng-isolate-scope">
                            <ul class="nav nav-tabs nav-stacked nav-justified">
                                <li class="ng-scope ng-isolate-scope <if condition="$type eq 0">active</if>">
                                    <a href="javascript:void(0);" class="ng-binding" data-type="0">
                                        <tab-heading class="ng-scope"> 正在指派 <span class="assigning-count ng-binding">(0)</span> </tab-heading>
                                    </a>
                                </li>
                                <li class="ng-scope ng-isolate-scope <if condition="$type eq 1">active</if>">
                                    <a href="javascript:void(0);" class="ng-binding" data-type="1">
                                        <tab-heading class="ng-scope"> 预指派 <span class="ng-binding other-count">(0)</span> </tab-heading>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane ng-scope active"></div>
                                <div class="tab-pane ng-scope"></div>
                            </div>
                        </div>
                        <div class="order-panel-header">
                            <!--section class="force-refresh-block ng-binding">点击刷新订单</section-->
                            <section class="smart-dispatch-label">
                                <span class="ng-scope">智能调度开启，订单将会最佳时机分配，请耐心等待</span>
                            </section>
                        </div>
                        <div class="order-list ng-scope">
                        <p class="no-data ng-scope">暂无订单</p>
                        </div>
                    </div>
                    <div class="map improve amap-container" id="_mapNew" style="background: rgb(252, 249, 242); cursor: pointer;">

                    </div>
                    <div class="postman-list improve" >
                        <div class="wish-toastr ng-hide">
                            <span class="triangle"></span>
                            <span class="ng-binding">0人要单</span>
                        </div>
                        <div class="assign-count-toastr ng-hide">
                            <span class="triangle"></span>
                            <span class="ng-binding">新分配给0位骑手</span>
                        </div>
                        <a class="aside-toggle">
                            <i class="fa fa-angle-right"></i>
                        </a>
                        <div class="search-wrapper">
                            <input type="text" placeholder="配送员姓名/手机号" class="form-control ng-pristine ng-untouched ng-valid ng-empty" id="searchUser">
                            <i class="fa fa-search"></i>
                        </div>
                        <div class="advice-wrapper disabled">
                            <a href="javascript:void(0)">推荐骑手不合理？</a>
                        </div>
                        <div class="empty-list-label ng-binding">可选骑手(0)</div>
                    </div>
                    <div class="assigned-orders-modal ng-hide assigned-orders-modal--hide" id="orderDetail">
                        <div class="modal-inner">
                            <div class="fixed-header">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>店铺名称</th>
                                            <th>送餐地</th>
                                            <th>订单状态</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="table-wrapper" style="top: 26px;">
                                <table>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <i class="assigned-orders-modal-angle"></i>
                    </div>
                    <div class="legend-block ng-hide">
                        <span class="open-legend"><i class="icon prompt-icon"></i>路线图例</span>
                        <div class="legend-list ng-hide">
                            <i class="icon close-icon"></i>
                            <ul>
                                <li><i class="icon order-take-icon"></i>所选订单取餐位置</li>
                                <li><i class="icon order-user-icon"></i>所选订单送餐位置</li>
                                <li><i class="icon delivery-has-taken-icon"></i>骑手背负订单取餐位置（已取餐）</li>
                                <li><i class="icon delivery-take-icon"></i>骑手背负订单取餐位置</li>
                                <li><i class="icon delivery-arrived-icon"></i>骑手背负订单送餐位置</li>
                                <li><i class="icon overtime-line-icon"></i>超时订单路线</li>
                                <li><i class="icon line-icon"></i>订单路线</li>
                                <li><i class="icon new-line-icon"></i>最新指派的订单路线</li>
                            </ul>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </article>

    <!-- crayfish 信息引用 -->

    <section>
        <audio id="fresh_order_hint" preload="auto" src="{pigcms{$static_path}css/desk/media/new_order_hint.mp3">
            <source src="{pigcms{$static_path}css/desk/media/new_order_hint.mp3" type="audio/mpeg">
        </audio>


        <audio id="cancel_order_hint" preload="auto" src="{pigcms{$static_path}css/desk/media/cancel_order_hint.mp3">
            <source src="{pigcms{$static_path}css/desk/media/cancel_order_hint.mp3" type="audio/mpeg">
        </audio>


        <audio id="overtime_order_hint" preload="auto" src="{pigcms{$static_path}css/desk/media/overtime_order_hint.mp3">
            <source src="{pigcms{$static_path}css/desk/media/overtime_order_hint.mp3" type="audio/mpeg">
        </audio>


        <audio id="wish_order_hint" preload="auto" src="{pigcms{$static_path}css/desk/media/wish_order_hint.mp3">
            <source src="{pigcms{$static_path}css/desk/media/wish_order_hint.mp3" type="audio/mpeg">
        </audio>


        <audio id="wait_manual_order_hint" preload="auto" loop="loop" src="{pigcms{$static_path}css/desk/media/wait_manual_order.mp3">
            <source src="{pigcms{$static_path}css/desk/media/wait_manual_order.mp3" type="audio/mpeg">
        </audio>
    </section>
<div class="modal-backdrop fade in" style="z-index: 1040;display:none"></div>
<div class="modal fade ng-isolate-scope reassign-window in" style="z-index: 1050; display: none;">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <div id="order-reassign-modal" class="ng-scope">
                <header class="modal-header">
                    <h4 class="modal-title">改派订单</h4>
                    <i class="fa fa-close"></i>
                </header>

                <section class="modal-body">
                    <div class="ranking ng-scope">
                        <div class="courier-list">
                        </div>
                    </div>
                </section>
                <footer class="modal-footer">
                    <button class="btn btn-primary" disabled="disabled">确认分配</button>
                </footer>
            </div>
        </div>
    </div>
</div>
    
</body>
<script>
    var site_url = '{pigcms{$static_path}';
</script>
<if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key={pigcms{$config.google_map_ak}"></script>
    <script type="text/javascript"> var url = '/admin.php?g=System&c=Deliver&a=', areaUrl = '/admin.php?g=System&c=Area&a=', type = {pigcms{$_GET['type']|intval}, lat = {pigcms{$lat}, lng = {pigcms{$lng};</script>
    <script type="text/javascript" src="{pigcms{$static_path}js/desk_google_map.js?t=11"></script>
    <else />
<script type="text/javascript" src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1" charset="utf-8"></script>
<script type="text/javascript"> var url = '/admin.php?g=System&c=Deliver&a=', areaUrl = '/admin.php?g=System&c=Area&a=', type = {pigcms{$_GET['type']|intval}, lat = {pigcms{$lat}, lng = {pigcms{$lng};</script>
<script type="text/javascript" src="{pigcms{$static_path}js/desk.js?t=<?php echo time();?>"></script>
</if>
</html>