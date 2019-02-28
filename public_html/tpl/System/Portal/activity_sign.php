<include file="Public:header"/>
<style>
    .alert {
        font-size: 14px;
        border-radius: 0;
    }

    .alert-info {
        background-color: #d9edf7;
        /* border-color: #bce8f1; */
        color: #31708f;
    }
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }
</style>
<div class="mainbox">
    <div id="nav" class="mainnav_title">
        <ul>
            <a href="{pigcms{:U('Portal/activity')}">活动列表</a>|
            <a href="javascript:void(0);" class="on">报名列表</a>
        </ul>
    </div>
    <table class="search_table" width="100%">
        <tr>
            <td>
                <a href="{pigcms{:U('Portal/export', array( 'a_id' => $peopleList[0]['a_id']))}" class="button" style="float:right;margin-right: 10px;">导出报名信息</a>
            </td>
        </tr>
        <tr>

            <td>
                <div class="alert alert-info" style="margin:10px 0;">&nbsp;&nbsp;&nbsp;报名总人数：{pigcms{$people}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;报名总金额：{pigcms{$moneyTotal}</div>
            </td>
        </tr>
    </table>
    <form name="myform" id="myform" action="" method="post">
        <div class="table-list">
            <table width="100%" cellspacing="0">
                <colgroup>
                    <col/>
                    <col/>
                    <col/>
                    <col/>
                    <col/>
                    <col/>
                    <col/>
                    <col/>

                </colgroup>
                <thead>
                <tr>
                    <th class="textcenter">序号</th>
                    <th class="textcenter">姓名</th>
                    <th class="textcenter">电话</th>
                    <th class="textcenter">QQ</th>
                    <th class="textcenter">备注</th>
                    <th class="textcenter">活动费用</th>
                    <th class="textcenter">报名时间</th>
                    <th class="textcenter">操作</th>
                </tr>
                </thead>
                <tbody>
                <if condition="is_array($peopleList)">
                    <volist name="peopleList" id="vo">
                        <tr>
                            <td class="textcenter">{pigcms{$vo.sid}</td>
                            <td class="textcenter">{pigcms{:msubstr($vo['truename'],0,5)}</td>
                            <td class="textcenter">{pigcms{$vo.phone}</td>
                            <td class="textcenter">{pigcms{$vo.qq}</td>
                            <td class="textcenter">{pigcms{:msubstr($vo['message'],0,10)}</td>
                            <td class="textcenter">{pigcms{$money}</td>

                            <td class="textcenter">{pigcms{$vo.create_time|date="Y-m-d H:i:s",###}</td>

                           <td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Portal/activity_sign_detail',array('sid'=>$vo['sid'],'frame_show'=>true))}','查看信息',580,350,true,false,false,false,'detail',true);">查看</a> </td>
                        </tr>
                    </volist>
                    <tr><td class="textcenter pagebar" colspan="12">{pigcms{$pagebar}</td></tr>
                    <else/>
                    <tr><td class="textcenter red" colspan="12">列表为空！</td></tr>
                </if>
                </tbody>
            </table>
        </div>
    </form>
</div>
<include file="Public:footer"/>