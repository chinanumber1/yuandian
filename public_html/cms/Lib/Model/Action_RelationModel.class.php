<?php
/*
 *     用户行为统计
 *     $order_id 订单号    $order_type 订单类型(group、appoint、meal)
 */
    class Action_relationModel extends Model{
        private $order_id;
        private $order_type;
        private $order_type_table;
        public function add_user_action($order_id,$order_type){
            $this->order_id   =   $order_id;
            $this->order_type   =   strtolower($order_type);
            $this->order_type_table	=	ucfirst($this->order_type);
            if($this->order_type_table=='Meal'){
                $sId    =   M($this->order_type_table.'_order')->where(array('order_id'=>$order_id))->field(array('uid','store_id'))->find();
                $storeId  =   M('Meal_store_category_relation')->where(array('store_id'=>$sId['store_id']))->field('cat_id')->find();
                if($storeId){
                    $sCatId =   M('Meal_store_category')->where($sCatId)->find();
                    $order_type_store =   'Meal_store';
                }else{
                    return  array('error' => 5,'msg'=>'商铺没有分类');
                }
            }else if($this->order_type_table=='Shop'){
                $sId    =   M($this->order_type_table.'_order')->where(array('order_id'=>$order_id))->field(array('uid','store_id'))->find();
                $storeId  =   M('Shop_category_relation')->where(array('store_id'=>$sId['store_id']))->field('cat_id')->find();
                if($storeId){
                    $sCatId =   M('Shop_category')->where($sCatId)->find();
                    $order_type_store =   'Shop';
                }else{
                    return  array('error' => 5,'msg'=>'商铺没有分类');
                }
            }else{
                $sId    =   M($this->order_type_table.'_order')->where(array('order_id'=>$order_id))->field(array($this->order_type.'_id','uid','store_id'))->find();
                if($sId[$this->order_type.'_id']){
                    $sCatId  =   M($this->order_type_table)->where(array($this->order_type.'_id'=>$sId[$this->order_type.'_id']))->field('cat_id')->find();
                }else{
                    return  array('error' => 4,'msg'=>'订单号不存在');
                }
            }
            if($sCatId['cat_id']){
                $aCate  =   M($order_type_store.'_category')->where($sCatId)->field(array('cat_fid','cat_name','cat_url','cat_id'))->find();
                $is_one_relation   =   $this->is_one_relation($aCate);
                if(empty($is_one_relation)){
                    return  array('error' => 10,'msg'=>'失败');
                }else{
                    foreach($is_one_relation as $v){
                        $aLogArr    =   array(
                            'rela_id'   =>  $v['rela_id'],
                            'action_id'   =>  $v['action_id'],
                            'uid'   =>  $sId['uid'],
                        );
                        $this->is_user_log($aLogArr);
                    }
                }
                if($aCate['cat_fid']){
                    $aFCate  =   M($order_type_store.'_category')->where(array('cat_id'=>$aCate['cat_fid']))->field(array('cat_name','cat_url','cat_id'))->find();
                    $is_one_Frelation   =   $this->is_one_relation($aFCate);
                    if(empty($is_one_Frelation)){
                        $add_Fcategory   =   $this->add_category($aFCate);
                    }else{
                        foreach($is_one_Frelation as $v){
                            $aLogArr    =   array(
                                'rela_id'   =>  $v['rela_id'],
                                'action_id'   =>  $v['action_id'],
                                'uid'   =>  $sId['uid'],
                            );
                            $this->is_user_log($aLogArr);
                        }
                    }
                }
            }else{
                return  array('error' => 4,'msg'=>'商铺不存在');
            }
            return  array('error' => 0,'msg'=>'添加成功');
        }
        //  增加用户行为分组    action_category表
        public  function    add_category($cate_name){
            if(empty($cate_name)){
                return  array('error' => 3,'msg'=>'数据不能为空');
            }
            $sName  =   M('Action_category')->where(array('action_name'=>$cate_name['cat_name']))->field('action_id')->find();
            if($sName['action_id']){
                return  array('error' => 2,'msg'=>'已经存在','id'=>$sName['action_id']);
            }else{
                $sAdd   =   M('Action_category')->add(array('action_name'=>$cate_name['cat_name'],'action_url'=>$cate_name['cat_url'],'create_time'=>time()));
                if($sAdd){
                    return  array('error' => 0,'msg'=>'添加成功','id'=>$sAdd);
                }else{
                    return  array('error' => 1,'msg'=>'添加失败');
                }
            }
        }
        //  查询用户分组关系是否存在    action_relation
        public  function    is_relation($cate_name){
            foreach($cate_name as $k=>$v){
                $sName  =   $this->where(array('cat_type'=>$this->order_type,'cat_id'=>$v['cat_id']))->field('*')->select();
                if($sName[0]['rela_id']){
                    $aName[$k]  =   $sName;
                }else{
                    $aName[$k]  =   null;
                }
            }
            return  $aName;
        }
        //  查询单个用户分组关系是否存在    action_relation
        public  function    is_one_relation($cate_name){
            $sName  =   $this->where(array('cat_type'=>$this->order_type,'cat_id'=>$cate_name['cat_id']))->field('*')->select();
            if(empty($sName)){
                $add_category   =   $this->add_category($cate_name);
                if($add_category['error']!=1 && $add_category['error']!=3){
                    $aName    =   array(
                        'action_id'    =>  $add_category['id'],
                        'cat_type'    =>  $this->order_type,
                        'cat_id'    =>  $cate_name['cat_id'],
                        'create_time'    =>  time()
                    );
                    $sAdd   =   $this->add($aName);
                    $asName[0]   =   $aName;
                    $asName[0]['rela_id']    =   $sAdd;
                }
            }else{
                $asName  =   $sName;
            }
            return  $asName;
        }
        //  插入用户行为记录    action_user_log
        public  function    is_user_log($cate_name){
            if($cate_name['rela_id'] && $cate_name['action_id'] && $cate_name['uid']){
                $aName  =   M('Action_user_log')->where($cate_name)->field('*')->find();
                if(!empty($aName)){
                    $arr    =   array(
                        'log_number' =>   $aName['log_number']+1,
                        'modify_time' =>   time(),
                    );
                    $sAdd  =   M('Action_user_log')->where(array('log_id'=>$aName['log_id']))->data($arr)->save();
                }else{
                    $arr    =   array(
                        'create_time' =>   time(),
                        'modify_time' =>   time(),
                        'action_id' =>   $cate_name['action_id'],
                        'rela_id' =>   $cate_name['rela_id'],
                        'uid' =>   $cate_name['uid'],
                    );
                    $sAdd  =   M('Action_user_log')->data($arr)->add();
                }
                return  $sAdd;
            }
        }
    }
?>
