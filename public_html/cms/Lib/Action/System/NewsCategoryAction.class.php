<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2016/8/13
 * Time: 9:16
 */
  class NewsCategoryAction extends BaseAction {

      /**
       * 分类列表
       */
      public function index()
      {
          $parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
          $database_news_category = D('Fc_news_category');
          $category = $database_news_category->field(true)->where(array('category_id' => $parentid))->find();
          $category_list = $database_news_category->field(true)->where(array('category_pid' => $parentid))->order('`order` DESC,`category_id` ASC')->select();
          $level = $database_news_category->field('level')->where(array('category_id' => $parentid))->find();
          $level = !empty($parentid) ? $level['level'] + 1 : 0;
          $this->assign('category', $category);
          $this->assign('category_list', $category_list);
          $this->assign('level', $level);
          $this->assign('parentid', $parentid);
          $this->display();;
      }

      public function cat_add()
      {
          $parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
          $level = isset($_GET['level']) ? intval($_GET['level']) : 0;
          $this->assign('level', $level);
          $this->assign('parentid', $parentid);
          $this->display();
      }

      /**
       * 添加分类
       */
      public function create_data()
      {
          if(IS_POST){
              $database_news_category = D('Fc_news_category');
              $data['category_pid'] = !empty($_POST['category_pid']) ? $_POST['category_pid'] : 0;
              $data['name'] = !empty($_POST['name']) ? $_POST['name'] : '';
              $data['flag'] = !empty($_POST['flag']) ? $_POST['flag'] : '';
              $data['is_display'] = !empty($_POST['is_display']) ? $_POST['is_display'] : 0;
              $data['order'] = !empty($_POST['order']) ? $_POST['order'] : 0;
              $data['level'] = !empty($_POST['level']) ? $_POST['level'] : 1;
              if($data['level'] > 3){
                  $data['level'] = 3;
              }
              $data['update_time'] = time();
              $data['add_time'] = time();

              if($database_news_category->data($data)->add()){
                  $this->success('添加成功！');
              }else{
                  $this->error('添加失败！请重试~');
              }
          }else{
              $this->error('非法提交,请重新提交~');
          }
      }

      public function cat_edit()
      {
          $parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
          $level = isset($_GET['level']) ? intval($_GET['level']) : 0;
          $database_shop_category = D('Fc_news_category');
          $condition_now_shop_category['category_id'] = intval($_GET['category_id']);
          $now_category = $database_shop_category->field(true)->where($condition_now_shop_category)->find();
          if (empty($now_category)) {
              $this->frame_error_tips('没有找到该分类信息！');
          }
          $this->assign('parentid', $parentid);
          $this->assign('level', $level);
          $this->assign('now_category', $now_category);
          $this->display();
      }

      /**
       * 编辑分类
       */
      public function cat_amend()
      {
          if (IS_POST) {
              $database_shop_category = D('Fc_news_category');
              $where = array('category_id' => $_POST['category_id']);
              $data['name'] = !empty($_POST['name']) ? $_POST['name'] : '';
              $data['flag'] = !empty($_POST['flag']) ? $_POST['flag'] : '';
              $data['is_display'] = !empty($_POST['is_display']) ? $_POST['is_display'] : 0;
              $data['order'] = !empty($_POST['order']) ? $_POST['order'] : 0;
              $data['level'] = !empty($_POST['level']) ? $_POST['level'] : 0;
              $data['update_time'] = time();

              if ($database_shop_category->where($where)->save($data)) {
                  $this->success('编辑成功！');
              } else {
                  $this->error('编辑失败！请重试~');
              }
          } else {
              $this->error('非法提交,请重新提交~');
          }
      }

      /**
       * 删除分类
       */
      public function cat_del()
      {
          if (IS_POST) {
              $database_shop_category = D('Fc_news_category');
              $condition_now_shop_category['category_id'] = intval($_POST['category_id']);
              if ($obj = $database_shop_category->field(true)->where($condition_now_shop_category)->find()) {
                  $t_list = $database_shop_category->field(true)->where(array('category_id' => $obj['category_id']))->select();
                  if ($t_list) {
                      $this->error('该分类下有子分类，先清空子分类，再删除该分类');
                  }
              }
              if ($database_shop_category->where($condition_now_shop_category)->delete()) {
                  $database_shop_category_relation = D('Shop_category_relation');
                  $condition_shop_category_relation['category_id'] = intval($_POST['category_id']);
                  $database_shop_category_relation->where($condition_shop_category_relation)->delete();
                  $this->success('删除成功！');
              } else {
                  $this->error('删除失败！请重试~');
              }
          } else {
              $this->error('非法提交,请重新提交~');
          }
      }
  }
