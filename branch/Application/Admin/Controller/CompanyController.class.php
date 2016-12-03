<?php

namespace Admin\Controller;

class CompanyController extends CommonController {

    public function __construct() {
        $this->mainModel = D('Company');
        parent::__construct();
    }

    /* 管理员列表 */
    public function index() {
        $pageSize = I('post.pageSize', 30);
        $page = I('post.pageCurrent', 1);
        $company_name = I('post.company_name');
        $company_linker = I('post.company_linker');
        $isSearch = I('post.isSearch');
        $this->mainModel = D('Company');
        if (!empty($isSearch)) {
            if (!empty($company_name)) {
                $map['company_name'] = array('LIKE', $company_name);
            }
            if (!empty($company_linker)) {
                $map['company_linker'] = array('LIKE', $company_linker);
            }
        }
        $map['status'] = 1;
        $total = $this->mainModel->where($map)->count();
        $list = $this->mainModel->where($map)->order('addtime desc')->page($page, $pageSize)->select();

        $this->assign(array('total'=>$total, 'pageCurrent'=>$page, 'list'=>$list));
        $this->display();
    }

    /* 添加管理员 */
    public function add() {
        $this->display();
    }

    /* 编辑管理员 */
    public function edit() {
        $company_id = I('get.company_id');
        $data = $this->mainModel->where(array('company_id' => $company_id))->find();
        $this->assign($data);
        $this->display();
    }
    
    /* 保存 */
    public function save() {
        if (false === $data = $this->mainModel->create()) {
            $e = $this->mainModel->getError();
            $this->json_error($e);
        }
//        var_dump($model->role_id);exit;
        if ($data['company_id']) {
            $this->mainModel->addtime = $_SERVER['REQUEST_TIME'];
            $result = $this->mainModel->save();
        } else {
            $result = $this->mainModel->add();
        }

        if ($result === false) {
            $this->json_error('保存失败');
        } else {
            $this->json_success('保存成功');
        }
    }

    /* 删除管理员 */

    public function del() {
        $company_id = I('get.company_id');
        $state = $this->mainModel->where('company_id=' . $company_id)->save(array('status' => 0));
        if ($state !== false) {
            $this->json_success('删除成功');
        } else {
            $this->json_error('操作失败');
        }
    }
    
    public function lookup() {
        $pageSize = I('post.pageSize', 30);
        $page = I('post.pageCurrent', 1);
        $company_name = I('post.company_name');
        $company_linker = I('post.company_linker');
        $isSearch = I('post.isSearch');
        if (!empty($isSearch)) {
            if (!empty($company_name)) {
                $map['company_name'] = array('LIKE', $company_name);
            }
            if (!empty($company_linker)) {
                $map['company_linker'] = array('LIKE', $company_linker);
            }
        }
        $map['status'] = 1;
        $total = $this->mainModel->where($map)->count();
        $list = $this->mainModel->where($map)->order('addtime desc')->page($page, $pageSize)->select();

        $this->assign(array('total'=>$total, 'pageCurrent'=>$page, 'list'=>$list));
        $this->display();
    }

}
