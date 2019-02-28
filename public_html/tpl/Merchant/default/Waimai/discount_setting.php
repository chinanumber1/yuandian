<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-gear gear-icon"></i>
                <a href="{pigcms{:U('Waimai/index')}">外卖管理</a>
            </li>
            <li class="active">优惠方式</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <div class="row">
                <div class="col-xs-12">
                    <div class="tabbable">
                        <ul class="nav nav-tabs" id="myTab">                
                            <li class="active">
                                <a data-toggle="tab" href="#basicinfo">列表</a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#txtstore">添加</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">               
                        <div id="basicinfo" class="tab-pane active">
                            <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">编号</th>
                                    <th width="5%">图标</th>
                                    <th width="10%">优惠类型</th>
                                    <th width="10%">优惠描述</th>
                                    <th width="8%">金额</th>
                                    <th width="8%">新用户</th>
                                    <th width="8%">创建时间</th>
                                    <th width="8%">使用类型</th>
                                    <th class="button-column" width="10%">设置</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$store_discount_list">
                                    <volist name="store_discount_list" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.discount_id}</div></td>
                                            <td><div class="tagDiv"><img src="{pigcms{$config.site_url}/upload/waimai/{pigcms{$vo.icon}"/></div></td>
                                            <td><div class="shopNameDiv">{pigcms{$vo.type_name}</div></td>
                                            <td>{pigcms{$vo.desc}</td>
                                            <td>{pigcms{$vo.money_term}</td>
                                            <td>{pigcms{$vo.newuser_term}</td>
                                            <td>{pigcms{$vo.create_time|date='Y-m-d H:i:s',###}</td>
                                            <td>
											<if condition="$vo['type_id'] eq 101">
											<if condition="$vo['pay_method_term'] eq 1">在线支付<else />线下支付</if>
											<else />
											不区分
											</if>
											</td>
                                            <td class="button-column">
                                                <a style="width:80px;" class="label label-sm label-info" title="{pigcms{$config.meal_alias_name}优惠设置" href="{pigcms{:U('Waimai/discount_store_del',array('discount_id'=>$vo['discount_id'], 'store_id'=>$vo['store_id']))}">删除</a>
                                            </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="9" >您没有添加优惠方式，或优惠方式没开启{pigcms{$config.waimai_alias_name}功能，或优惠方式正在审核中。</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$pagebar}
                        </div>
                        <div id="txtstore" class="tab-pane">
                            <form enctype="multipart/form-data" class="form-horizontal" method="post" id="add_form">
                            <input name="store_id" type="hidden" value="{pigcms{$store_id}" />
                            <div class="form-group">
                                <label class="col-sm-2">优惠类型:</label>
                                <select name="type_id" class="col-sm-2">
                                    <volist name="discount_type_data" id="vo">
                                        <option value="{pigcms{$vo.type_id}"><img src="{pigcms{$vo.icon}" />{pigcms{$vo.name}--{pigcms{$vo.desc}</option>
                                    </volist>
                                </select>
                                <span class="form_tips">优惠类型选取！</span>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2">简述:</label>
                                <input class="col-sm-3" maxlength="10" name="desc" type="text" value="" />
                                <span class="form_tips">简要描述该优惠！</span>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2">优惠金额:</label>
                                <input class="col-sm-3" maxlength="10" name="discount_money" type="text" value="0" />
                                <span class="form_tips">该优惠可优惠金额！</span>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2">订单金额:</label>
                                <input class="col-sm-3" maxlength="10" name="money_term" type="text" value="0" />
                                <span class="form_tips">订单金额需满足的条件，不限制为0！</span>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2">是否新用户:</label>
                                <div class="col-sm-2"><label style="margin-right:8px;">普通用户</label><input name="newuser_term" type="radio" value="0" checked /></div>
                                <div class="col-sm-2"><label style="margin-right:8px;">需新用户</label><input name="newuser_term" type="radio" value="1" /></div>
                                <span class="form_tips">该优惠是否只对新用户开放！</span>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2">在线支付:</label>
                                <div class="col-sm-2"><label style="margin-right:8px;">是</label><input name="pay_term" type="radio" value="1" checked /></div>
                                <div class="col-sm-2"><label style="margin-right:8px;">否</label><input name="pay_term" type="radio" value="0" /></div>
                                <span class="form_tips">该优惠是否只对在线支付！</span>
                            </div>
                            <div class="clearfix form-actions">
                                <div class="col-md-offset-3 col-md-9">
                                    <button class="btn btn-info" type="submit" id="save_btn">
                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                            保存
                                    </button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<include file="Public:footer"/>