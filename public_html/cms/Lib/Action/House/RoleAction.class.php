<?php
/*
    小区角色管理
 */
class RoleAction extends BaseAction{
    protected $village_id;

    public function _initialize(){
        parent::_initialize();
        $this->village_id = $this->house_session['village_id'];
    }
  

    //角色列表
    public function role_list(){
        //角色管理-查看 权限
        if (!in_array(8, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_house_admin = D('House_admin');
        $where = array();
        $where['village_id'] = $this->village_id;
        $list = $database_house_admin->get_limit_list_page($where);
        $this->assign('list',$list);
        $this->display();
    }

    //添加角色
    public function role_add(){
        $this->assign('bg_color', '#F3F3F3');

        //角色管理-添加 权限
        if (!in_array(9, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $database_house_admin = D('House_admin');
        if (IS_POST) {
            //验证账号是否存在
            if (!$_POST['account']) {
                $this->error('请输入账号');
            }
            if (!$_POST['pwd']) {
                $this->error('请输入密码');
            }
            $role_info = $database_house_admin->where(array('account'=>$_POST['account']))->find();
            $village_info = D('House_village')->where(array('account'=>$_POST['account']))->find();
            if ($role_info || $village_info) {
                $this->error('该账号已存在，请重新输入账号');
            }
            $data = array();
            $data['village_id'] = $this->village_id;
            $data['account'] = $_POST['account'];
            $data['pwd'] = md5($_POST['pwd']);
            $data['realname'] = $_POST['realname'];
            $data['phone'] = $_POST['phone'];
            // $data['email'] = $_POST['email'];
            $data['remarks'] = $_POST['remarks'];
            $data['time'] = $_SERVER['REQUEST_TIME'];
            $data['menus'] = $_POST['menus'] ? implode(',',$_POST['menus']) : '';
            $res = $database_house_admin->data($data)->add();
            if ($res) {
                $this->success('添加成功',U('role_list'));
            }else{
                $this->error('添加失败');
            }
        }else{
            $where = array('status' => 1,'show'=>1,'id' => array('in',$this->house_session['menus']));
            if (C('config.PC_write_card')!=1) { // 没有写卡权限
               $where['id'] = array('not in',array(95,112));
            }
            $menus = D('House_menu')->where($where)->order('sort asc,id asc')->select();
            $list = $this->getTree($menus);
            $this->assign('menus', $list);
            $this->display();
        }

        
    } 

    //收银台 打印模板配置
    public function role_edit(){
        //角色管理-查看 权限
        if (!in_array(8, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $id = $_GET['id'] + 0;
        if (!$id) {
            $this->error('参数不对');
        }
        $this->assign('id',$id);
        $this->assign('bg_color', '#F3F3F3');

        $database_house_admin = D('House_admin');
        //角色信息
        $condition['id'] = $id;
        $condition['village_id'] = $this->village_id;
        $role = $database_house_admin->field(true)->where($condition)->find();
        if (empty($role)) {
           $this->error('数据库中没有查询到该管理员的信息！');
        }
        $role['menus'] = explode(',', $role['menus']);
        $this->assign('role', $role);

        $where = array('status' => 1,'show'=>1);
        if (C('config.PC_write_card')!=1) { // 没有写卡权限
           $where['id'] = array('not in',array(95,112));
        }
        // 判断角色权限是否小于当前管理员权限
        $diff_menu = array_diff($role['menus'], $this->house_session['menus']);
        if ( (!isset($diff_menu[0]) || $diff_menu[0]) && $diff_menu ) { // 大于当前管理员权限
            //权限信息
        }else{
            $where['id'] = array('in',$this->house_session['menus']);
        }
        $menus = D('House_menu')->where($where)->order('sort asc,id asc')->select();
        $list = $this->getTree($menus);
        $this->assign('menus', $list);

        if (IS_POST) {
            //角色管理-编辑 权限
            if (!in_array(10, $this->house_session['menus'])) {
                $this->error('对不起，您没有权限执行此操作');
            }
            if ( (!isset($diff_menu[0]) || $diff_menu[0]) && $diff_menu ) { // 大于当前管理员权限
                $this->error('对不起，您没有权限编辑此账号');
            }
            //验证账号是否存在
            if (!$_POST['account']) {
                $this->error('请输入账号');
            }
            $is_exist = $database_house_admin->where(array('account'=>$_POST['account']))->find();
            if ($is_exist&&$is_exist['id']!=$id) {
                $this->error('该账号已存在，请重新输入账号');
            }
            $data = array();
            $data['account'] = $_POST['account'];
            if ($_POST['pwd']) {
                $data['pwd'] = md5($_POST['pwd']);
            }
            $data['realname'] = $_POST['realname'];
            $data['phone'] = $_POST['phone'];
            // $data['email'] = $_POST['email'];
            $data['remarks'] = $_POST['remarks'];
            $data['menus'] = $_POST['menus'] ? implode(',',$_POST['menus']) : '';
            $res = $database_house_admin->where($condition)->data($data)->save();
            if ($res) {
                $this->success('编辑成功',U('role_list'));
            }else{
                $this->error('编辑失败');
            }
        }else{
            $this->display();
        }
    } 

    //角色删除
    public function role_del(){
        //角色管理-删除 权限
        if (!in_array(11, $this->house_session['menus'])) {
            $this->error('对不起，您没有权限执行此操作');
        }

        $id = $_GET['id'] + 0;
        if ($id) {
            $database_house_admin = D('House_admin');
            //角色信息
            $condition['id'] = $id;
            $condition['village_id'] = $this->village_id;
            $role = $database_house_admin->field(true)->where($condition)->find();
            if (empty($role)) {
               $this->error('数据库中没有查询到该账号的信息！');
            }
            $role['menus'] = explode(',', $role['menus']);

            // 判断角色权限是否小于当前管理员权限
            $diff_menu = array_diff($role['menus'], $this->house_session['menus']);
            if ( (!isset($diff_menu[0]) || $diff_menu[0]) && $diff_menu ) { // 大于当前管理员权限
               $this->error('对不起，您没有权限删除此账号');
            }

            // 不能删除自己
            if ( $id ==  $this->house_session['role_id'] ) { 
               $this->error('不能删除自己的账号');
            }

            $result = D('House_admin')->where(array('id'=>$id))->delete();
            if($result){
                $this->success('删除成功');
            }else{
                $this->error('删除失败');
            }
        }
    }

    /**
     * 实现无限极分类
    */
    function getTree($array){

        //第一步 构造数据
        $items = array();
        foreach($array as $value){
            $items[$value['id']] = $value;
        }
        //第二部 遍历数据 生成树状结构
        $tree = array();
        foreach($items as $key => $value){
            if(isset($items[$value['fid']])){
                $items[$value['fid']]['child'][] = &$items[$key];
                if (isset($items[$value['fid']]['count'])) {
                    $items[$value['fid']]['count']++;
                }else{
                    $items[$value['fid']]['count'] = 1;
                }
            }else{
                $tree[] = &$items[$key];
            }
        }
        return $tree;
    }

}
?>

