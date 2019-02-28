<?php
// 商家余额
class Merchant_authModel extends Model{
    //增加商家权限
    public function add_auth($mer_id,$auth_id,$type=0){
        $now_merchant = D('Merchant')->get_info($mer_id);
        $now_merchant['menus'] = explode(',',$now_merchant['menus']);
        if(!$type && in_array($auth_id,$now_merchant['menus'])){
            return array('error_code'=>1,'msg'=>'权限已经存在了');
        }elseif($type && $auth_id == $now_merchant['authority_group_id']){
            return array('error_code'=>1,'msg'=>'您已经购买了权限套餐了');
        }else{
          //  !$type && $now_merchant['menus']= array_merge($this->get_fid_menu($auth_id),$now_merchant['menus']);
            if(!$type){
                $now_merchant['menus']= array_merge($this->get_fid_menu($auth_id),$now_merchant['menus']);
                if(M('Merchant')->where(array('mer_id'=>$mer_id))->setField('menus',implode(',',$now_merchant['menus']))){
                    return array('error_code'=>0,'msg'=>'权限更新成功');
                }else{

                    return array('error_code'=>1,'msg'=>'权限更新失败');
                }
            }else{
                $menu_group  =  M('Authority_group')->where(array('id' => $auth_id))->find();

                if(M('Merchant')->where(array('mer_id'=>$mer_id))->save(array('menus'=>$menu_group['menus'],'authority_group_id'=>$auth_id) )){

                    return array('error_code'=>0,'msg'=>'权限更新成功');
                }else{
                    return array('error_code'=>1,'msg'=>'权限更新失败');
                }

            }
        }
    }

    public function get_fid_menu($fid,$tmp=array()){
        $menu = M('New_merchant_menu')->where(array('id'=>$fid))->find();
        $tmp [] = $menu['id'];
        if($menu['fid']!=0){
            $tmp  = $this->get_fid_menu($menu['fid'],$tmp);
        }
        return $tmp;
    }
}