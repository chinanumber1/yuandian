<?php
  class MapConfigAction extends BaseAction {
      /**
       * 地图配置
       */
      public function index()
      {
          if(IS_POST){
              if($_POST['map_type']){
                  if(M('Config')->where(array('name'=>'map_config'))->setField('value',$_POST['map_type'])|| M('Config')->where(array('name'=>'baidu_map_ak'))->setField('value',$_POST['baidu_map_ak'])||M('Config')->where(array('name'=>'google_map_ak'))->setField('value',$_POST['google_map_ak'])){
                          M('Config')->where(array('name'=>'baidu_map_ak'))->setField('value',$_POST['baidu_map_ak']);
                          M('Config')->where(array('name'=>'google_map_ak'))->setField('value',$_POST['google_map_ak']);
                      $this->success('编辑成功');
                  }else{
                      $this->error('编辑失败');
                  }
              }else{
                  $this->error('操作失败');
              }
          }else{
              $this->display();
          }
      }

  }