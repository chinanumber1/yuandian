<?php
class Community_user_formidModel extends Model
{
    /*获得小程序fromid信息*/
    public function get($uid, $field='uid', $field_info='formid, id')
    {
        $condition_info[$field] = $uid;
        $time_info = strtotime("-7 day"); // fromid有效期为七天
        $condition_info['add_time']  = array('gt',$time_info);
        $info = $this->field($field_info)->where($condition_info)->find();
        return $info;
    }

    /*删除信息*/
    public function del($formid,$field='formid')
    {
        $condition_info[$field] = $formid;
        $info = $this->field(true)->where($condition_info)->delete();
        return $info;
    }
}
?>