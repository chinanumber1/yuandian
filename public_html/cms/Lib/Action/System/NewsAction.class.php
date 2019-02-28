<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2016/8/13
 * Time: 13:52
 */
  class NewsAction extends BaseAction {

      /**
       * 资讯列表
       */
      public function index()
      {
          $newsList = D('Fc_news_main')->select();

          foreach ($newsList as &$list){
              $oneCategory = D('Fc_news_category')->field('name')->where(array('category_id'=>$list['category_pid']))->find();
              $twoCategory = D('Fc_news_category')->field('name')->where(array('category_id'=>$list['category_child_id']))->find();
              $threeCategory = D('Fc_news_category')->field('name')->where(array('category_id'=>$list['category_grandson_id']))->find();
              $list['onename'] = $oneCategory['name'];
              $list['twoname'] = $twoCategory['name'];
              $list['threename'] = $threeCategory['name'];
          }
          $this->assign('newsList',$newsList);
          $this->display();
      }

      /**
       * 资讯添加
       */
      public function news_add()
      {
          $categoryList = D('Fc_news_category')->where(array('category_pid'=>0))->select();

          $this->assign('categoryList',$categoryList);
          $this->display();
      }

      /**
       * 资讯添加
       */
      public function create_data()
      {
          if(IS_POST){
              $database_news_category = D('Fc_news_main');
              $data['category_pid'] = !empty($_POST['category_pid']) ? $_POST['category_pid'] : 0;
              $data['category_child_id'] = !empty($_POST['category_child_id']) ? $_POST['category_child_id'] : 0;
              $data['category_grandson_id'] = !empty($_POST['category_grandson_id']) ? $_POST['category_grandson_id'] : 0;
              $data['title'] = !empty($_POST['title']) ? $_POST['title'] : '';
              $data['title_short'] = !empty($_POST['title_short']) ? $_POST['title_short'] : '';
              $data['tag_name'] = !empty($_POST['tag_name']) ? $_POST['tag_name'] : '';
              $data['province_id'] = !empty($_POST['province_id']) ? $_POST['province_id'] : '';
              $data['city_id'] = !empty($_POST['city_id']) ? $_POST['city_id'] : '';
              $data['abstract'] = !empty($_POST['abstract']) ? $_POST['abstract'] : 0;
              $data['content'] = !empty($_POST['content']) ? htmlspecialchars_decode($_POST['content']) : '';
              $data['is_display'] = !empty($_POST['is_display']) ? $_POST['is_display'] : '';
              $data['order'] = !empty($_POST['order']) ? $_POST['order'] : 0;
              $data['add_time'] = time();
              $data['update_time'] = time();

              $image = D('Image')->handle($this->system_session['id'], 'system', 0, array('size' => 10));

              if (!$image['error']) {
                  $data['cover_img'] = $image['url']['cover_img'];
              }
              $database_news_category->data($data)->add();

              if($database_news_category->data($data)->add()){
                  $this->frame_submit_tips(1,'添加成功');
              }else{
                  $this->frame_submit_tips(0,'添加失败！请重试~');
              }
          }else{
              $this->frame_submit_tips(0,'非法提交！请重试~');
          }
      }


      /**
       * 获取分类 (ajax)
       */
      public function getCategoryList()
      {
          $category_pid = !empty($_POST['category_id']) ? $_POST['category_id'] : 0;

          if(!empty($category_pid)) {
              $categoryList = D('Fc_news_category')->where(array('category_pid'=>$category_pid))->select();
              $level = D('Fc_news_category')->field('level')->where(array('category_pid'=>$category_pid))->find();
          }
          $level = $level['level'];
          exit(json_encode(array('level'=>$level,'err_code'=>1,'err_msg'=>$categoryList)));
      }

      /**
       * 资讯修改
       */
      public function news_save(){
          $news_id = !empty($_GET['news_id']) ? $_GET['news_id'] : 0;

          if(empty($news_id)){
              $this->error('缺少参数,请检查~');
          }

          $newsMain = D('Fc_news_main')->where(array('news_id'=>$news_id))->find();

          $categoryList = D('Fc_news_category')->where(array('category_pid'=>0))->select();

          $twoCategory = D('Fc_news_category')->where(array('level'=>2))->select();
          $threeCategory = D('Fc_news_category')->where(array('level'=>3))->select();

          $this->assign('newsMain',$newsMain);
          $this->assign('categoryList',$categoryList);
          $this->assign('twoCategory',$twoCategory);
          $this->assign('threeCategory',$threeCategory);
          $this->display();
      }

      /**
       * 资讯修改
       */
      public function news_save_data(){
          if(IS_POST){
              $database_news_category = D('Fc_news_main');
              $news_id = !empty($_POST['news_id']) ? $_POST['news_id'] : 0;

              if(empty($news_id)){
                  $this->error('缺少参数,请检查~');
              }

              $data['category_pid'] = !empty($_POST['category_pid']) ? $_POST['category_pid'] : 0;
              $data['category_child_id'] = !empty($_POST['category_child_id']) ? $_POST['category_child_id'] : 0;
              $data['category_grandson_id'] = !empty($_POST['category_grandson_id']) ? $_POST['category_grandson_id'] : 0;
              $data['title'] = !empty($_POST['title']) ? $_POST['title'] : '';
              $data['title_short'] = !empty($_POST['title_short']) ? $_POST['title_short'] : '';
              $data['tag_name'] = !empty($_POST['tag_name']) ? $_POST['tag_name'] : '';
              $data['province_id'] = !empty($_POST['province_id']) ? $_POST['province_id'] : '';
              $data['city_id'] = !empty($_POST['city_id']) ? $_POST['city_id'] : '';
              $data['abstract'] = !empty($_POST['abstract']) ? $_POST['abstract'] : 0;
              $data['content'] = !empty($_POST['content']) ? htmlspecialchars_decode($_POST['content']) : '';
              $data['is_display'] = !empty($_POST['is_display']) ? $_POST['is_display'] : '';
              $data['order'] = !empty($_POST['order']) ? $_POST['order'] : 0;
              $data['update_time'] = time();

              $image = D('Image')->handle($this->system_session['id'], 'system', 0, array('size' => 10));

              if (!$image['error']) {
                  $data['cover_img'] = $image['url']['cover_img'];
              }

              if($database_news_category->where(array('news_id'=>$news_id))->data($data)->save()){
                  $this->frame_submit_tips(1,'修改成功');
              } else {
                  $this->frame_submit_tips(0,'修改失败！请重试~');
              }
          }else{
              $this->error('非法提交,请重新提交~');
          }
      }

      /**
       * 资讯删除
       */
      public function news_del()
      {
          $news_id = !empty($_POST['news_id']) ? $_POST['news_id'] : 0;
          if(empty($news_id)) {
              $this->error('缺少参数,请检查~');
          }

          if(D('Fc_news_main')->where(array('news_id'=>$news_id))->delete()){
              $this->frame_submit_tips(1,'删除成功');
          }else{
              $this->frame_submit_tips(0,'删除失败！请重试~');
          }
      }
  }