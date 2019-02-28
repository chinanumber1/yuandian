<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" name="viewport"/>
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<link href="{pigcms{$static_path}css/base.css" rel="stylesheet">
<link href="{pigcms{$static_path}css/cate_list.css" rel="stylesheet">
<link rel="stylesheet" href="{pigcms{$static_path}css/swiper.min.css" type="text/css">
<title>技师-评价</title>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script src="{pigcms{$static_path}js/swiper.min.js"></script>
<script src="{pigcms{$static_path}js/rem.js"></script>
<script src="{pigcms{$static_path}js/index.js"></script>
</head>
<body>
<article class="comment">
    <section>
        <ul class="comment_list clearfix activity_title">
            <li class="active">
                <p>全部</p>
                <span>({pigcms{$appoint_comment_list|count})</span></li>
            <li>
                <p>超出期待</p>
                <span>({pigcms{$perfect_appoint_comment_list|count})</span></li>
            <li>
                <p>很满意</p>
                <span>({pigcms{$great_appoint_comment_list|count})</span></li>
            <li>
                <p>基本满意</p>
                <span>({pigcms{$general_appoint_comment_list|count})</span></li>
            <li>
                <p>不满意</p>
                <span>({pigcms{$bad_appoint_comment_list|count})</span></li>
        </ul>
        
        <ul class="comment_table acticity_list">
            
                <li><if condition='$appoint_comment_list'>
                    <ul>
                        <volist name='appoint_comment_list' id='vo'>
                        <li class="clearfix">
                            <div class="comment_img"><img src="{pigcms{$vo.avatar}"></div>
                            <div class="comment_txt">
                                <p>{pigcms{$vo.content}</p>
                                <if condition="$vo['comment_img']">
                                <ul>
                                <volist name='vo["comment_img"]' id='val'>
                                    <li><img src="{pigcms{$config.site_url}/{pigcms{$val}">
                                    </li>
                                </volist>
                                </ul>
                                </if>
                            </div>
                        </li>
                        </volist>
                    </ul>
                   </if>
                </li>
                
                
                            
                <li><if condition='$perfect_appoint_comment_list'>
                    <ul>
                        <volist name='perfect_appoint_comment_list' id='vo'>
                        <li class="clearfix">
                            <div class="comment_img"><img src="{pigcms{$vo.avatar}"></div>
                            <div class="comment_txt">
                                <p>{pigcms{$vo.content}</p>
                                <if condition="$vo['comment_img']">
                                <ul>
                                <volist name='vo["comment_img"]' id='val'>
                                    <li><img src="{pigcms{$config.site_url}/{pigcms{$val}">
                                    </li>
                                </volist>
                                </ul>
                                </if>
                            </div>
                        </li>
                        </volist>
                    </ul>
                    </if>
                </li>
               


           
                <li> <if condition='$great_appoint_comment_list'>
                    <ul>
                        <volist name='great_appoint_comment_list' id='vo'>
                        <li class="clearfix">
                            <div class="comment_img"><img src="{pigcms{$vo.avatar}"></div>
                            <div class="comment_txt">
                                <p>{pigcms{$vo.content}</p>
                                <if condition="$vo['comment_img']">
                                <ul>
                                <volist name='vo["comment_img"]' id='val'>
                                    <li><img src="{pigcms{$config.site_url}/{pigcms{$val}">
                                    </li>
                                </volist>
                                </ul>
                                </if>
                            </div>
                        </li>
                        </volist>
                    </ul>
                   </if>
                </li>
                


           
                <li> <if condition='$general_appoint_comment_list'>
                    <ul>
                        <volist name='general_appoint_comment_list' id='vo'>
                        <li class="clearfix">
                            <div class="comment_img"><img src="{pigcms{$vo.avatar}"></div>
                            <div class="comment_txt">
                                <p>{pigcms{$vo.content}</p>
                                <if condition="$vo['comment_img']">
                                <ul>
                                <volist name='vo["comment_img"]' id='val'>
                                    <li><img src="{pigcms{$config.site_url}/{pigcms{$val}">
                                    </li>
                                </volist>
                                </ul>
                                </if>
                            </div>
                        </li>
                        </volist>
                    </ul>
                    </if>
                </li>
               


            
                <li><if condition='$bad_appoint_comment_list'>
                    <ul>
                        <volist name='bad_appoint_comment_list' id='vo'>
                        <li class="clearfix">
                            <div class="comment_img"><img src="{pigcms{$vo.avatar}"></div>
                            <div class="comment_txt">
                                <p>{pigcms{$vo.content}</p>
                                <if condition="$vo['comment_img']">
                                <ul>
                                <volist name='vo["comment_img"]' id='val'>
                                    <li><img src="{pigcms{$config.site_url}/{pigcms{$val}">
                                    </li>
                                </volist>
                                </ul>
                                </if>
                            </div>
                        </li>
                        </volist>
                    </ul>
                   </if>
                </li>
                

        </ul>
    </section>
</article>
</body>
</html>