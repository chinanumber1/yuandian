<include file="Public:header"/>
<if condition="$_GET['type']==1">
    <form id="myform" method="post" action="{pigcms{:U('Useraction/amend',array('type'=>1))}" frame="true" refresh="true">
        <input type="hidden" name="action_id" value="{pigcms{$now_area['action_id']}"/>
        <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
            <tr>
                <th width="80">分组名</th>
                <td><input type="text" class="input fl" name="action_name" value="{pigcms{$now_area.action_name}" size="20" placeholder="请输入名称" validate="maxlength:30,required:true"/></td>
            </tr>
            <tr>
                <th width="80">排序</th>
                <td><input type="text" class="input fl" name="action_sort" value="{pigcms{$now_area.action_sort}" size="10" value="0" validate="required:true,number:true,maxlength:6" tips="数值越大，排序越前"/></td>
            </tr>
        </table>
        <div class="btn hidden">
            <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
            <input type="reset" value="取消" class="button" />
        </div>
    </form>
<elseif condition="$_GET['type']==2"/>
    <form id="myform" method="post" action="{pigcms{:U('Useraction/amend',array('type'=>2))}" frame="true" refresh="true">
        <input type="hidden" name="rela_id" value="{pigcms{$now_area['rela_id']}"/>
        <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
            <tr>
                <th width="80">分类名</th>
                <td>
                    <select name="cat_type">
                        <option value="group" <if condition="$now_area['cat_type'] eq 'group'">selected="selected"</if>>团购</option>
                        <option value="meal" <if condition="$now_area['cat_type'] eq 'meal'">selected="selected"</if>>快店</option>
                        <option value="appoint" <if condition="$now_area['cat_type'] eq 'appoint'">selected="selected"</if>>预约</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th width="80">分类ID</th>
                <td><input type="text" class="input fl" name="cat_id" value="{pigcms{$now_area.cat_id}" size="20" placeholder="" validate="maxlength:20,required:true" tips="一般为地区名称的首字母！输入名称后，若此字段为空，会自动填写（仅作为示例）"/></td>
            </tr>
        </table>
        <div class="btn hidden">
            <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
            <input type="reset" value="取消" class="button" />
        </div>
    </form>
</if>
<include file="Public:footer"/>