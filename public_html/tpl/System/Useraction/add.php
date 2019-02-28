<include file="Public:header"/>
<if condition="$type eq 1">
    <form id="myform" method="post" action="{pigcms{:U('Useraction/modify',array('status'=>1))}" frame="true" refresh="true">
        <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
            <tr>
                <th width="80">分组名</th>
                <td><input type="text" class="input fl" name="action_name" id="action_name" size="20" placeholder="请输入名称" validate="maxlength:30,required:true"/></td>
            </tr>
            <tr>
                <th width="80">排序</th>
                <td><input type="text" class="input fl" name="action_sort" size="10" value="0" validate="required:true,number:true,maxlength:6" tips="数值越大，排序越前"/></td>
            </tr>
        </table>
        <div class="btn hidden">
            <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
            <input type="reset" value="取消" class="button" />
        </div>
    </form>
<else/>
    <form id="myform" method="post" action="{pigcms{:U('Useraction/modify',array('status'=>2))}" frame="true" refresh="true">
        <input type="hidden" name="action_id" value="{pigcms{$action_id}"/>
        <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
            <tr>
                <th width="80">分类名</th>
                <td>
                <select name="cat_type">
                    <option value="group" selected="selected">团购</option>
                    <option value="meal">快店</option>
                    <option value="appoint">预约</option>
                </select>
                </td>
            </tr>
            <tr>
                <th width="80">分类ID</th>
                <td><input type="text" class="input fl" name="cat_id" id="cat_id" size="20" placeholder="请输入分类ID" validate="maxlength:30,required:true" tips="分类ID找到对应的团购、预约、快店，分类ID"/></td>
            </tr>
        </table>
        <div class="btn hidden">
            <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
            <input type="reset" value="取消" class="button" />
        </div>
    </form>
</if>
<include file="Public:footer"/>