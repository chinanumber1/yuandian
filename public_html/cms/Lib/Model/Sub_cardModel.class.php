<?php
    /*
     * 免单套餐类
     *
     * */
class Sub_cardModel extends Model{
    //根据商家获取会员卡用户列表
    public function get_sub_card($id){
        $where['id']=$id;
        $sub_card =$this->field(true)->where($where)->find();
        if($sub_card['buy_time_type']==1&& $sub_card['end_time']<time()){
            $sub_card['status'] = 3;//超时foreach ($sub_card_list as &$item) {
            M('Sub_card')->where(array(id=>$id))->setField('status',3);
        }
        $condition['sub_card_id'] = $id;
        $condition['status'] = 1;
        $condition['_string'] = 'sku>0';
        $store_list = $this->get_card_list($condition);

        $sub_card['join_num'] = count($store_list);
        $sku_less = 0;

        $sub_card['pic_lists'] = empty($sub_card['pic_list'])?'':explode(';',$sub_card['pic_list']);
        $error_arr = $this->error_info();
        $sub_card['status_txt'] = $error_arr[$sub_card['status']];

        return $sub_card;
    }


    public  function get_card_list($condition){
       return  M('Sub_card_mer_apply')->where($condition)->select();
    }

    public function error_info(){
        return array(
          0=>'免单没有开启',
          1=>'免单状态正常',
          2=>'免单正在审核',
          3=>'本卡已过期',
          4=>'参加免单套餐的店铺库存不足',
        );
    }

    //可以购买的免单套餐
    public function get_can_buy_sub_card($order='id DESC',$limit='0,10'){
        $now_time = time();
        $where['status']= 1;
        $sub_card_by_area = M('Sub_card_area')->join('AS sa LEFT JOIN '.C('DB_PREFIX').'sub_card sc ON sc.id = sa.sub_cardid')->where(array('aid'=>C('config.now_city'),'use_area'=>1))->select();
        foreach ($sub_card_by_area as $item) {
            $sub_card_in_arr[]  = $item['sub_cardid'];
        }
        $sub_card_in_arr && $where['id'] = array('in',$sub_card_in_arr);
        $where['_string']= "mer_pass_num*mer_free_num>=free_total_num AND ((end_time >={$now_time} AND start_time <={$now_time}) OR buy_time_type=0) ";
        $count = $this->where($where)->count();
        $list = $this->where($where)->order($order)->limit($limit)->select();
        return array('list'=>$list ,'count'=>$count);
    }
    public function sub_card_store_info($sub_card_id,$store_id){
       $store =  M('Sub_card_mer_apply')->field('sa.*,ms.name,ms.adress,ms.have_meal,ms.have_group,ms.pic_info,ms.lat,ms.long')->join('as sa LEFT JOIN '.C('DB_PREFIX').'merchant_store as ms ON ms.store_id= sa.store_id')->where(array('sub_card_id'=>$sub_card_id,'ms.store_id'=>$store_id))->find();
        $store_image_class = new store_image();
        $all_pic = $store_image_class->get_allImage_by_path($store['pic_info']);
        $store['pic_info'] = $all_pic[0];
        return $store;
    }

    //获取参与的店铺列表
    public function get_sub_card_store($id,$mer_id=0,$status=''){
        if($mer_id){
            $where['ms.mer_id'] =$mer_id;
        }
        $where['sub_card_id'] =$id;
        $status && $where['ms.status'] = $status;
        $store_list = M('Sub_card_mer_apply')
            ->join('AS ms LEFT JOIN '.C('DB_PREFIX').'merchant_store AS s ON ms.store_id = s.store_id')
            ->where($where)
            ->getField('ms.store_id,ms.id,ms.mer_id,ms.pic_list,ms.appoint,ms.desc,ms.desc_txt,ms.sub_card_id,ms.apply_time,ms.status,ms.sku,ms.start_time,ms.end_time,s.name,s.long,s.lat,s.adress');
        foreach ($store_list as &$item) {
            $item['pic_lists'] = empty($item['pic_list'])?'':explode(';',$item['pic_list']);
        }
        return $store_list;
    }

    //更新 参与记录的数量
    public function sub_card_change_num($id,$field,$is_add=true){
        if($is_add){
            $this->where(array('id'=>$id))->setInc($field,1);
        }else{
            $this->where(array('id'=>$id))->setDec($field,1);
        }
    }

    //获取卡的信息
    public function  get_card_info($id){
        $sub_card = $this->where(array('id'=>$id))->find();
        return $sub_card;
    }

    //删除商家参加的店铺
    public function delete_mer_join($sub_card_id,$mer_id,$store_id){
        $where['sub_card_id'] = $sub_card_id;
        $where['mer_id'] = $mer_id;
        $store_id && $where['store_id'] = $store_id;
        $join_list = M('Sub_card_mer_apply')->where($where)->select();
        $store_join_num = 0;
        $join_num = 0;
        foreach ($join_list as $v) {
            $store_join_num++;
            if($v['status']==1){
                $join_num++;
            }
        }
        M('Sub_card_mer_apply')->where($where)->delete();
        $store_join_num && $this->where(array('id'=>$sub_card_id))->setDec('store_join_num',$store_join_num);
        $join_num && $this->where(array('id'=>$sub_card_id))->setDec('join_num',$join_num);
        return true;
    }





}

