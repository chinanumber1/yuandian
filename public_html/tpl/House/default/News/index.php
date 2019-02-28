<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-tablet"></i>
                <a href="{pigcms{:U('News/index')}">新闻列表</a>
            </li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <button class="btn btn-success" onclick="CreateCategory()" <if condition="!in_array(159,$house_session['menus'])">disabled="disabled"</if>>新增新闻</button>
            <style>
                .ace-file-input a {display:none;}
            </style>
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th width="40%">新闻title</th>
                                <th width="10%">发布时间</th>
                                <th width="10%">热门</th>
                                <th width="10%">所属分类</th>
                                <th width="10%">已微信通知</th>
                                <th class="button-column" width="15%">操作</th>
                            </tr>
                            </thead>
                            <tbody>
								<if condition="$news_list">
									<volist name="news_list['news_list']" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td><div class="tagDiv">{pigcms{$vo.title}</div></td>
											<td><div class="tagDiv">{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</div></td>
											<td><div class="shopNameDiv"><if condition="$vo.is_hot eq '1' ">是<else />否</if></div></td>
											<td><div class="tagDiv">{pigcms{$vo.cat_name}</div></td>
											<td><div class="shopNameDiv"><if condition="$vo.is_notice eq '1' ">是<else />否</if></div></td>

											<td class="button-column">
												<a style="width: 60px;" class="label label-sm label-info" title="编辑" href="{pigcms{:U('News/news_edit',array('news_id'=>$vo['news_id']))}">编辑</a>

												<if condition="$vo['is_notice'] eq 0">
                                                    <if condition=" $vo['status'] eq 0 ">
													<a style="width: 100px;" class="label label-sm label-info" href="javascript:;">此新闻已被关闭</a>
                                                    <else/>
                                                    <if condition="in_array(162,$house_session['menus'])">　
                                                        <a style="width: 100px;" class="label label-sm label-info handle_btn" href="{pigcms{:U('News/send',array('news_id'=>$vo['news_id']))}">微信群发业主</a>
                                                    </if>
                                                    </if>
												</if>
                                                <if condition="in_array(161,$house_session['menus'])">　
												<a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onclick="if(confirm('确认进行删除')){location.href='{pigcms{:U('News/news_del',array('news_id'=>$vo['news_id']))}'}">删除</a>
                                                </if>
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="6" >您没有添加任何新闻。</td></tr>
								</if>
                            </tbody>
                        </table>
						{pigcms{$pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script>
    function CreateCategory(){
        window.location.href = "{pigcms{:U('News/news_edit')}";
    }

    $(function(){
        $('.handle_btn').live('click',function(){
            art.dialog.open($(this).attr('href'),{
                init: function(){
                    var iframe = this.iframe.contentWindow;
                    window.top.art.dialog.data('iframe_handle',iframe);
                },
                id: 'handle',
                title:'提示',
                padding: 0,
                width: 720,
                height: 420,
                lock: true,
                resize: false,
                background:'black',
                button: null,
                fixed: false,
                close: null,
                left: '50%',
                top: '38.2%',
                opacity:'0.4'
            });
            return false;
        });

    });
</script>
<include file="Public:footer"/>