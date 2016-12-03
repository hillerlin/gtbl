<?php

namespace Admin\Controller;

class ProjectDebtController extends CommonController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $pageSize = I('post.pageSize', $this->pageDefaultSize);
        $page = I('post.pageCurrent', 1);
        $isSearch = I('post.isSearch');
        $status = I('post.status');
        $pro_no = I('post.pro_no');
        $debt_no = I('post.debt_no');
        $model = D('ProjectDebt');
//        $total = $model->count();
//        $list = $model->relation(true)->order('end_time desc')->page($page, $pageSize)->select();
        if ($isSearch) {
            if ($status !== '') {
                $map['t.status'] = $status;
            }
            if (!empty($pro_no)) {
                $map['p.pro_no'] = $pro_no;
            }
            if (!empty($debt_no)) {
                $map['t.debt_no'] = $debt_no;
            }
        }
        $result = $model->getList($page, $pageSize ,$map);
//        var_dump($status);eixt;
        $this->assign(array('total'=>$result['total'], 'pageCurrent'=>$page, 'list'=>$result['list']));
        $this->assign('post', $_POST);
        $this->display();
       
    }
    
    public function specified() {
        $pageSize = I('post.pageSize', $this->pageDefaultSize);
        $page = I('post.pageCurrent', 1);
        $isSearch = I('post.isSearch');
        $status = I('post.status');
        $pro_id = I('get.pro_id');
        $model = D('ProjectDebt');
//        $total = $model->count();
//        $list = $model->relation(true)->order('end_time desc')->page($page, $pageSize)->select();
        if ($isSearch) {
            if ($status !== '') {
                $map['t.status'] = $status;
            }
            if (!empty($pro_no)) {
                $map['p.pro_no'] = $pro_no;
            }
            if (!empty($debt_no)) {
                $map['t.debt_no'] = $debt_no;
            }
        }
        $map['t.pro_id'] = $pro_id;
        $result = $model->getList($page, $pageSize ,$map);
//        var_dump($status);eixt;
        $this->assign(array('total'=>$result['total'], 'pageCurrent'=>$page, 'list'=>$result['list']));
        $this->assign('pro_id', $pro_id);
        $this->assign('post', $_POST);
        $this->display();
       
    }

    public function add() {
        $pro_id = I('get.pro_id');
        if (empty($pro_id)) {
            $this->json_error('项目id不能为空');
        }
        $this->assign('pro_id', $pro_id);
        $this->display();
    }

    public function edit() {
        
    }

    public function del() {
        
    }
    
    public function save_debt() {
        $model = D('ProjectDebt');
        $admin = session('admin');
        if (false === $data = $model->create()) {
            $e = $model->getError();
            $this->json_error($e);
        }
        if ($data['debt_id']) {
            $result = $model->save();
        } else {
            $model->admin_id = $admin['admin_id'];
            $model->start_time = strtotime($model->start_time);
            $model->end_time = strtotime($model->end_time);
            $result = $model->add();
        }

        if ($result === false) {
            $this->json_error('保存失败');
        } else {
            $this->json_success('保存成功', '', '', true, array('tabid'=>'project-fangkuan'));
        }
    }
    
    public function exchange() {
        if (IS_POST) {
            $model = D('ProjectDebt');
            $admin = session('admin');
            if (false === $data = $model->create($request_data)) {
                $e = $model->getError();
                $this->json_error($e);
            }
            $model->startTrans();
            
            $model->admin_id = $admin['admin_id'];
            $model->start_time = strtotime($data['start_time']);
            $model->end_time = strtotime($data['end_time']);
            if (!$model->add()) {
                $model->rollback();
                $this->json_error('内部错误');
            }
            if (!$model->where('debt_id='.$data['parent_id'])->save(array('status'=>0))) {
                $model->rollback();
                $this->json_error('内部错误');
            }
            $model->commit();
            $this->json_success('换质成功');
        }
        $pro_id = I('get.pro_id');
        $debt_id = I('get.debt_id');
        if (empty($pro_id)) {
            $this->json_error('项目id不能为空');
        }
        $this->assign('pro_id', $pro_id);
        $this->assign('debt_id', $debt_id);
        $this->display();
    }


}
