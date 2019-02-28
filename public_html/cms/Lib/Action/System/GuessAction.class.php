<?php
  class GuessAction extends BaseAction {
      /**
       * 猜你喜欢
       */
      public function index()
      {
          if(IS_POST){
              if($_POST['guess_num']<=0){
                  $this->error('显示的数量要大于0');
              }
              if(M('Config')->where(array('name'=>'guess_content_type'))->setField('value',$_POST['content_type'])||M('Config')->where(array('name'=>'guess_num'))->setField('value',$_POST['guess_num'])){
                  M('Config')->where(array('name'=>'guess_num'))->setField('value',$_POST['guess_num']);
                  $this->success('编辑成功');
              }else{
                  $this->error('编辑失败');
              }
          }else{
              $this->display();
          }
      }

  }