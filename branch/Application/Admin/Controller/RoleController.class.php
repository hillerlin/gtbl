<?php

namespace Admin\Controller;

class RoleController extends CommonController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
//        if (IS_POST) {
//            $pageSize = I('post.pageSize', 10);
//            $page = I('post.pageCurrent', 1);
//            $model = new \Admin\Model\AdminRoleModel();
//            $total = $model->count();
//            $list = $model->order('add_time desc')->relation('role')->page($page, $pageSize)->select();
////            $this->ajaxReturn($list);
//            $this->ajaxReturn(array('total'=>$total, 'pageCurrent'=>$page+1, 'list'=>$list));
//        }
        $pageSize = I('post.pageSize', 30);
        $page = I('post.pageCurrent', 1);
        $model = D('Role');
        $admin = session('admin');
        if ($admin['is_supper'] == 0) {
            $where['pid'] = $admin['role_id'];
        }
        $total = $model->where($where)->count();
        $list = $model->order('add_time desc')->where($where)->page($page, $pageSize)->select();
        $this->assign('list', $list);
        $this->assign('total', $total);
        $this->display();
    }

    public function add() {
//        $menu_model = D('Menu');
//        $menu_list = $menu_model->where(array('type' => 1))->select();
        $admin = session('admin');
        if ($admin['is_supper'] == 0) {
            $model = D('Role');
            $menu = $model->get_auth_menu(0, -1);
        } else {
            $Menu= new \Admin\Model\MenuModel();
            $menu = $Menu->get_all_menu(0);
        }
        $model = D('Role');
        $role_list = $model->where(array('type' => 1))->select();
        foreach ($role_list as $v) {
            $role[$v['role_id']] = $v;
        }
        $str = "<option value='\$role_id' \$selected>\$spacer \$role_name</option>";
        $tree = new \Admin\Lib\Tree;
        $tree->init($role, 'role_id', 'pid');
        $select_role = $tree->get_tree(0, $str);
        
        $this->assign('menu', $menu);
        $this->assign('select_role', $select_role);
        $this->assign('back', 1);
        $this->display('add');
    }

    public function edit() {
        $role_id = I('role_id');
        $model = D('Role');
        $menu_model = D('Menu');
        if (!$role_id || !$model->check_exist(array('role_id' => $role_id))) {
            $this->error('参数错误！');
        }
        $data = $model->relation('auth')->where(array('role_id' => $role_id))->find();
//        var_dump($data);
        foreach ($data['auth'] as $v) {
            $select_menu_ids[] = $v['menu_id'];
        }

        $menu_list = $menu_model->where(array('type' => 1))->select();
        foreach ($menu_list as $v) {
            $array[$v['menu_id']] = $v;
        }
        $tree = new \Admin\Lib\Tree;
        $tree->init($array);
        $menu = $tree->get_array(0);

        $role_list = $model->where(array('type' => 1))->select();
        foreach ($role_list as $v) {
            $v['selected'] = $v['role_id'] == $data['pid'] ? 'selected' : '';
            $role[$v['role_id']] = $v;
        }
        $str = "<option value='\$role_id' \$selected>\$spacer \$role_name</option>";
//        $tree = new \Admin\Lib\Tree;
        $tree->init($role, 'role_id', 'pid');
        $select_role = $tree->get_tree(0, $str, $role_id);

        $this->assign('menu', $menu);
        $this->assign('select_menu_ids', json_encode($select_menu_ids));
        $this->assign($data);
        $this->assign('select_role', $select_role);
        $this->assign('back', 1);
        $this->display();
    }

    /* 检测权限组是否存在 */

    public function check_role_exist() {
        $role_name = I('role_name');
        $role_id = I('role_id');
        $where['role_name'] = $role_name;
        if ($role_id) {
            $where['role_id'] = array('neq', I('role_id'));
        }
        $state = D('Role')->check_exist($where) ? false : true;
        if (IS_AJAX) {
            $this->ajaxReturn($state);
        } else {
            return $state;
        }
    }

    /* 删除权限组 */

    public function del_role() {
        $model = D('Role');
        $role_id = I('get.role_id');
        if (!$role_id)
            $this->json_error('参数错误'.$role_id);
        $status = $model->relation(true)->delete($role_id);
        if ($status !== false) {
            $this->json_success('删除成功', U('role/index'));
        } else {
            $this->json_error('操作失败');
        }
    }

    /* 保存权限组 */

    public function save_role() {
        $model = D('Role');
        $auth = I('auth');
        if (false === $data = $model->create()) {
            $e = $model->getError();
            $this->error($e);
        }

        if ($auth) {
            foreach ($auth as $v) {
                $auth_d[] = array('menu_id' => $v);
            }
            $model->auth = $auth_d;
        }
        if ($data['role_id']) {
            $result = $model->relation('auth')->save();
        } else {
            $result = $model->relation('auth')->add();
        }

        if ($result === false) {
            $this->json_error('保存失败');
        } else {
            $this->json_success('保存成功', U('role/index'));
        }
    }
    
    public function lookup() {
        $pageSize = I('post.pageSize', 30);
        $page = I('post.pageCurrent', 1);
        $model = D('Role');
        $admin = session('admin');
        if ($admin['is_supper'] == 0) {
            $where['pid'] = $admin['role_id'];
        }
        $total = $model->where($where)->count();
        $list = $model->order('add_time desc')->where($where)->page($page, $pageSize)->select();
        $this->assign('list', $list);
        $this->assign('total', $total);
        $this->display();
    }

}
