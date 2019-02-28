<div style="height:50px"></div>
<nav class="bar bar-tab ft" <if condition="$is_app_browser">style="display:none;"</if>>
    <a class="tab-item external" href="{pigcms{:U('Home/index')}">
        <span class="ftIcon">
           <img src="{pigcms{$static_path}classify/images/icon/ft1.png">
           <img src="{pigcms{$static_path}classify/images/icon/ft1_a.png">
        </span>
        <span class="tab-label">平台首页</span>
    </a>
    <a <if condition="ACTION_NAME EQ 'index'">class="tab-item external active"<else />class="tab-item external"</if> href="{pigcms{:U('Classify/index')}">
        <span class="ftIcon">
           <img src="{pigcms{$static_path}classify/images/icon/ft2.png">
           <img src="{pigcms{$static_path}classify/images/icon/ft2_a.png">
        </span>
        <span class="tab-label">{pigcms{$config.classify_name}</span>
    </a>
    <a <if condition="ACTION_NAME EQ 'SelectSub'">class="tab-item external active"<else />class="tab-item external"</if> href="{pigcms{:U('Classify/SelectSub',array('cid'=>0))}">
        <span class="ftIcon">
           <img src="{pigcms{$static_path}classify/images/icon/ft3.png">
           <img src="{pigcms{$static_path}classify/images/icon/ft3_a.png">
        </span>
        <span class="tab-label">发布</span>
    </a>
    <a <if condition="ACTION_NAME EQ 'myCollect'">class="tab-item external active"<else />class="tab-item external"</if>  href="{pigcms{:U('Classify/myCollect',array('uid'=>$uid))}">
        <span class="ftIcon">
           <img src="{pigcms{$static_path}classify/images/icon/ft4.png">
           <img src="{pigcms{$static_path}classify/images/icon/ft4_a.png">
        </span>
        <span class="tab-label">收藏</span>
    </a>
</nav>

<script src="{pigcms{$static_path}classify/js/zepto.min.js"></script>
<script src="{pigcms{$static_path}classify/js/sm.min.js"></script>
<script src="{pigcms{$static_path}classify/js/iscroll.js"></script>
<script src="{pigcms{$static_path}classify/js/swiper.min.js"></script>
<script src="{pigcms{$static_path}classify/js/common.js"></script>
<script src="{pigcms{$static_path}classify/js/exif.js"></script>
<script src="{pigcms{$static_path}classify/js/imgUpload.js"></script>
