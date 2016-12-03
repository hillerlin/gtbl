<?php
namespace Admin\Controller;

class IndexController extends CommonController {
    
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $admin = session('admin');
        if ($admin['is_supper'] == 0) {
            $model = D('Role');
            $menu = $model->get_auth_menu(0);
        } else {
            $Menu= new \Admin\Model\MenuModel();
            $menu = $Menu->get_all_menu(0);
        }
        $this->assign('auth_menu', $menu);
        $this->display('index_tree');
    }
    
    public function index_tree() {
        $model = D('Role');
        $admin = session('admin');
        if ($admin['is_supper'] == 0) {
            $menu = $model->get_auth_menu(0);
        } else {
            $admin_password = new \Admin\Model\MenuModel();
            $menu = $admin_password->get_all_menu(0);
        }
        $this->assign('auth_menu', $menu);
        $this->display();
    }
    
    /* 登录 */
    public function login() {
        if (IS_POST) {
            $admin_name = I('admin_name', 'trim');
            $admin_password = I('admin_password', 'trim');
            $verify_code = I('j_captcha');
            $admin_model = new \Admin\Model\AdminModel();
//            $role_model = D('AdminRole');
            if (!check_verify($verify_code)) {
                $this->error('验证码错误,请重新输入');
            }
            $admin = $admin_model->where(array('admin_name' => $admin_name, 'admin_password' => md5($admin_password)))->find();
            if (!$admin) {
                $this->error('账号密码错误！');
            } else {
                session('admin', array(
                    'admin_id' => $admin['admin_id'],
                    'admin_name' => $admin['admin_name'],
                    'role_id' => $admin['role_id'],
//                    'role_name' => $admin['role']['role_name'],
                    'is_supper' => $admin['is_supper'],
                ));

                $admin_model->after_login($admin['admin_id']);
                $this->success('登录成功！', U('index/index'));
            }
        } else {
            $admin = session('admin');
            if (!empty($admin)) {
                $this->redirect('Index/index');
            }
            $this->display();
        }
    }
    
    /* 退出 */
    public function logout() {
        session('admin', null);
        $this->success('退出成功！', U('index/login'));
        exit;
    }
    
    public function makecode(){
        $Verify = new \Think\Verify(array('fontSize'=>60,'length'=>4,'fontttf'=>'5.ttf', 'useAl' => true));
        $Verify->entry();
    }
    
    public function index_layout() {
        $this->display();
    }
}