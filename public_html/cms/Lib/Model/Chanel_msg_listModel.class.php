<?php
class Chanel_msg_listModel extends Model{

    public function get_qrcode($id){
        $condition_store['chanel_id'] = $id;
        $qrcode_id = $this->field('`chanel_id`,`qrcode_id`')->where($condition_store)->find();
        if(empty($qrcode_id)){
            return false;
        }
        return $qrcode_id;
    }

    public function save_qrcode($id,$qrcode_id){
        $chanel_where['chanel_id'] = $id;
        $data_chanel['qrcode_id'] = $qrcode_id;
        if($this->where($chanel_where)->data($data_chanel)->save()){
            return(array('error_code'=>false));
        }else{
            return(array('error_code'=>true,'msg'=>'保存二维码至渠道二维码失败！请重试。'));
        }
    }
}