<?php

namespace Admin\Controller;

class ProjectController extends CommonController {

    public function __construct() {
        parent::__construct();
    }
    
    //列表
    public function index() {
        $pageSize = I('post.pageSize', 30);
        $page = I('post.pageCurrent', 1);
        $pro_no = I('post.pro_no');
        if (!empty($pro_no)) {
            $map['pro_no'] = $pro_no;
        }
//        if ($submit_type > -1) {
//            $map['submit_status'] = $submit_status;
//        }
        $map['submit_status'] = 1;
        $model = D('Project');
        $total = $model->where($map)->count();
        $list = $model->where($map)->order('addtime desc')->relation(true)->page($page, $pageSize)->select();
        $workflow = D('Workflow')->getWorkFlow();
        $this->assign('workflow', $workflow);
        $this->assign(array('total'=>$total, 'pageCurrent'=>$page, 'list'=>$list));
        $this->display();
    }
    
    //项目立项
    public function start() {
        $pageSize = I('post.pageSize', 30);
        $page = I('post.pageCurrent', 1);
        $pro_no = I('post.pro_no');
        $submit_status = I('post.submit_status', -1);
        if (!empty($pro_no)) {
            $map['pro_no'] = $pro_no;
        }
        if ($submit_type > -1) {
            $map['submit_status'] = $submit_status;
        }
        $admin = session('admin');
        $map['pro_linker'] = $admin['admin_id'];
        $model = D('Project');
        $total = $model->where($map)->count();
        $list = $model->where($map)->order('addtime desc')->relation(true)->page($page, $pageSize)->select();
        $workflow = D('Workflow')->getWorkFlow();
        $this->assign('workflow', $workflow);
        $this->assign(array('total'=>$total, 'pageCurrent'=>$page, 'list'=>$list));
        $this->display('index');
    }
    
    //项目审核
    public function auditList() {
        $pageSize = I('post.pageSize', 30);
        $page = I('post.pageCurrent', 1);
        $model = D('Project');
        $pro_no = I('post.pro_no');
        $submit_status = I('post.submit_status', -1);
        if (!empty($pro_no)) {
            $map['pro_no'] = $pro_no;
        }
        if ($submit_status > -1) {
            $map['submit_status'] = $submit_status;
        }
        
        $admin = session('admin');
        $role_arr = D('Role')->where('pid=' . $admin['role_id'])->field('role_id')->select();
        if (!isSupper()) {
            if (is_array($role_arr) && !empty($role_arr)) {
                $map['role_id'] = array('in', array_column($role_arr, 'role_id'));
            } else {
                $map['admin_id'] = $admin['admin_id'];
            }
        }
        $map['pro_step'] = array('LT', 9);
        $total = $model->where($map)->count();
        $list = $model->where($map)->order('addtime desc')->relation(true)->page($page, $pageSize)->select();
        $workflow = D('Workflow')->getWorkFlow();

        $this->assign('workflow', $workflow);
        $this->assign(array('total'=>$total, 'pageCurrent'=>$page, 'list'=>$list));
        $this->display();
    }

    /* 项目立项 */
    public function add() {
        $this->display();
    }

    /* 编辑管理员 */
    public function edit() {
        $p_model = D('Project');
        $pro_id = I('get.pro_id');
        $admin = session('admin');
        $data = $p_model->where(array('pro_linker' => $admin['admin_id'], 'pro_id' => $pro_id))->relation(true)->find();
        if ($data['submit_status'] == 1) {
            $this->json_error('此项目已提交，不能修改');
        }
        $this->assign($data);
        $this->display();
    }
    
    public function auditEdit() {
        $p_model = D('Project');
        $pro_id = I('get.pro_id');
        $admin = session('admin');
        $map['pro_id'] = $pro_id;
        $process_list = D('ProcessLog')->where($map)->relation('admin')->select();
        $data = $p_model->where(array('pro_id' => $pro_id))->relation(true)->find();
        $workflow = D('Workflow')->getWorkFlow();   //工作流

        $this->assign('workflow', $workflow);
        $this->assign('process_list', $process_list);
        $this->assign($data);
        $this->display('audit_edit');
    }
    
    public function detail() {
        $p_model = D('Project');
        $pro_id = I('get.pro_id');
        $admin = session('admin');
        $map['pro_id'] = $pro_id;
        $process_list = D('ProcessLog')->where($map)->relation('admin')->select();
        $data = $p_model->where(array('pro_id' => $pro_id))->relation(true)->find();
        $this->assign('process_list', $process_list);
        $this->assign($data);
        $this->display();
    }
    
    //放款
    public function loan() {
        $p_model = D('Project');
        $pro_id = I('get.pro_id');
        $admin = session('admin');
        $map['pro_id'] = $pro_id;
        $data = $p_model->where($map)->relation(true)->find();
        $debt_list = D('ProjectDebt')->where($map)->select();   //债权列表
        $this->assign('debt_list', $debt_list);
        $this->assign($data);
        $this->display(); 
    }
    
    public function submit() {
        $p_model = D('Project');
        $pro_id = I('request.pro_id');
        $admin = session('admin');
        $data = $p_model->where(array('pro_linker' => $admin['admin_id'], 'pro_id' => $pro_id))->relation(true)->find();
        if ($data['submit_status'] == 1) {
            $this->json_error('此项目已提交，不能重复提交');
        }
        if (IS_POST) {
            $opinion = '--';
            $this->process($pro_id, 1, 1, 0, $opinion);
        }
        $this->assign($data);
        $this->display();
    }
    
    //项目审核
    public function audit() {
        $p_model = D('Project');
        $pro_id = I('request.pro_id');
        $status = I('request.status');
        $pro_step = I('request.pro_step');
        $opinion = I('request.opinion');
        
        $data = $p_model->where(array('pro_id' => $pro_id))->find();
        $this->process($pro_id, $pro_step, $status, $data['submit_status'], $opinion);
        $this->assign($data);
    }
    
    /**
     * 
     * @param type $pro_step 现在状态值
     * @param type $status 现在状态通过与否
     * @param type $opinion 意见
     */
    protected function process($pro_id, $pro_step, $status, $submit_status, $opinion) {
        if ($pro_step == 0) {
            $this->json_error('项目已终结');
        }
        $workflow = D('Workflow')->getWorkFlow();
        $now_step = $workflow[$pro_step];
        $admin = session('admin');
//        if ($now_step['step_role_id'] != 0 && $admin['role_id'] != $now_step['step_role_id']) {   //登录人的角色不是本环节的角色，拒绝操作
//            $this->json_error('您没有权限操作');
//        }
        $pro_detail = array('pro_id'=>$pro_id,'admin_id'=>$admin['admin_id'],'status'=>$status,'opinion'=>$opinion,'addtime'=> time(), 'pro_step' => $pro_step);
        $pro_model = D('ProcessLog');
        $pro_model->startTrans();
        if (!$pro_model->add($pro_detail)) {     //更新意见表
            $pro_model->rollback();
            $this->json_error('审核失败。失败原因：内部错误。');
        }
        //获取下一步id
        $next_step_id = $now_step['step_next'][$status]['next_id'];
        //更新项目表，下一步
        $data = array('pro_step'=>$next_step_id,'role_id'=>$workflow[$next_step_id]['step_role_id']);
        if ($submit_status == 0) {
            $data['submit_status'] = 1;
        }
        if (!D('Project')->where('pro_id='.$pro_id)->save($data)) {
            $pro_model->rollback();
            $this->json_error('失败');
        }
        $pro_model->commit();
        $this->json_success('成功', '','',true, array('tabid'=>'bjui-hnav-tree0_2'));
    }
    
    /* 保存项目 */
    public function save_project() {
        $model = D('Project');
        $request_json = html_entity_decode(I('post.json'));
//        var_dump($request_json);
        $request_data = json_decode($request_json, true);
        $admin = session('admin');
        if (false === $data = $model->create($request_data)) {
            $e = $model->getError();
            $this->json_error($e);
        }
        
        $model->pro_linker = $admin['admin_id'];
        if ($data['pro_id']) {
            $result = $model->save();
        } else {
            $result = $model->add();
        }

        if ($result === false) {
            $this->json_error('保存失败');
        } else {
            $this->json_success('保存成功');
        }
    }
    
    /* 删除项目 */
    public function del() {
        $pro_id = I('get.pro_id');
        $model = D('Project');
        $state = $model->delete($pro_id);
        if ($state !== false) {
            $this->json_success('删除成功');
        } else {
            $this->json_error('操作失败');
        }
    }
    
    //文件树
    public function file() {
        $map['pro_id'] = I('get.pro_id');
        $file_tree = D('ProjectFile')->select();
        foreach ($file_tree as $v) {
            $array[$v['file_id']] = $v;
        }
        $tree = new \Admin\Lib\Tree;
        $tree->init($array);
        $file_tree = $tree->get_array(0);
//        var_dump($file_tree[1]['sub']);exit;
        $this->assign('file_tree', $file_tree);
        $this->assign($map);
        $this->display();
    }
    
    //上传页面
    public function upload() {
        $map['pro_id'] = I('get.pro_id');
        $map['file_id'] = I('get.file_id');
        $list = D('ProjectAttachment')->where($map)->select();
        $this->assign('list', $list);
        $this->assign($map);
        $this->display();
    }
    
    //上传附件
    public function upload_attachment() {
        $pro_id = I('request.pro_id');
        $file_id = I('request.file_id');
        $admin = session('admin');
        $role_id = $admin['role_id'];
        if (!$this->checkAuthUpload($pro_id, $file_id, $role_id)) {
            $this->json_error('您没有上传的权限');
        }
        $field = 'pro-'.$pro_id;
        $short_name = D('ProjectFile')->where('file_id='.$file_id)->getField('short_name');
        $upload_info = upload_file('/project/attachment/', $field, $short_name.'-');
//        $this->ajaxReturn(array('status' => 1, 'data' => array('file_path' => $upload_info['file_path'], 'file_id' => date('YmdHis'))));
        if (isset($upload_info['file_path'])) {
            $save_data['file_id'] = $file_id;
            $save_data['pro_id'] = $pro_id;
            $save_data['path'] = $upload_info['file_path'];
            $save_data['doc_name'] = $upload_info['name'];
            $save_data['addtime'] = time();
            
            $save_data['admin_id'] = $admin['admin_id'];
            if (!($aid = D('ProjectAttachment')->add($save_data))) {
                $this->json_error('上传失败');
            }
            $content = array('file_path' => $upload_info['file_path'],'file_id' => date('YmdHis'), 'file_name'=>$upload_info['name'], 'addtime'=> date("Y-m-d H:i:s", $save_data['addtime']), 'aid' => $aid);
            $this->ajaxReturn(array('statusCode' => 200, 'content'=>$content, 'message'=>'上传成功'));
        }
        $this->json_error('上传失败');
    }
    
    /**
     * 查询上传的权限
     * @param type $pro_id
     * @param type $file_id
     * @param type $role_id
     * @return boolean
     */
    protected function checkAuthUpload($pro_id, $file_id, $role_id) {
        if (isSupper()) {
            return true;
        }
        $file_auth_flag = D('ProjectFile')->where('file_id=' . $file_id)->getField('is_auth');
        if ($file_auth_flag == 0) {     //不用鉴权
            return true;
        }
        $pro_step = D('Project')->where('pro_id=' . $pro_id)->getField('pro_step');
        //现在项目提交以后项目经理不能提交文档；判断权限是否在可上传的权限中；暂时不能灵活配置
        if ($pro_step > 1) {
            if (!in_array($role_id, array(14, 2))) {
                return false;
            }
        }
    }
    
    //删除附件
    public function remove_attachment() {
        $file_path = I('request.file_path');
        $aid = I('request.aid');
        $pro_id = I('request.pro_id');
        $file_id = I('request.file_id');
        $admin = session('admin');
        $role_id = $admin['role_id'];
        //只删数据库
        if (!$this->checkAuthUpload($pro_id, $file_id, $role_id)) {
            $this->json_error('您没有删除的权限');
        }
        $model = D('ProjectAttachment');
        $res1 = $model->where(array('id' => $aid, 'pro_id' => $pro_id))->delete();
//        $res2 = unlink('.'.$file_path);
        if ($res1) {
            $this->json_success('删除成功','', '','', array('divid'=>'layout-01'));
        } else {
            $this->json_error('删除失败');
        }
    }
    
    //项目跟踪
    public function follow() {
        $pageSize = I('post.pageSize', 30);
        $page = I('post.pageCurrent', 1);
        $model = D('Project');
        $admin = session('admin');
//        $map['pro_step'] = array('GT', 4);
        $map['admin_id'] = array('EQ', $admin['admin_id']);
        $total = $model->where($map)->count();
        $list = $model->where($map)->order('addtime desc')->relation(true)->page($page, $pageSize)->select();
        $workflow = D('Workflow')->getWorkFlow();
        
        $this->assign('workflow', $workflow);
        $this->assign(array('total'=>$total, 'pageCurrent'=>$page, 'list'=>$list));
        $this->display('index');
    }
    
    //分控审核
    public function riskCheck() {
        $pageSize = I('post.pageSize', 30);
        $page = I('post.pageCurrent', 1);
        $model = D('Project');
        $admin = session('admin');
        $role_arr = D('Role')->where('pid=' . $admin['role_id'])->field('role_id')->select();
        if (!isSupper()) {
            if (is_array($role_arr) && !empty($role_arr)) {
                $map['role_id'] = array('in', array_column($role_arr, 'role_id'));
            } else {
                $map['risk_admin_id'] = $admin['admin_id'];
            }
        }
        $map['pro_step'] = 3;
        $total = $model->where($map)->count();
        $list = $model->where($map)->order('addtime desc')->relation(true)->page($page, $pageSize)->select();
        $workflow = D('Workflow')->getWorkFlow();
        
        $this->assign('workflow', $workflow);
        $this->assign(array('total'=>$total, 'pageCurrent'=>$page, 'list'=>$list));
        $this->display('auditList');
    }
    
    //完结项目
    public function finish() {
        $pageSize = I('post.pageSize', 30);
        $page = I('post.pageCurrent', 1);
        $model = D('Project');
        $admin = session('admin');
        $map['role_id'] = array('in', $admin['role_id']);
        $map['pro_step'] = array('EQ', 999);
        $total = $model->where($map)->count();
        $list = $model->where($map)->order('addtime desc')->page($page, $pageSize)->select();
        $workflow = D('Workflow')->getWorkFlow();

        $this->assign('workflow', $workflow);
        $this->assign(array('total'=>$total, 'pageCurrent'=>$page, 'list'=>$list));
        $this->display('index');
    }
    
    public function lookUp() {
        $pageSize = I('post.pageSize', 30);
        $page = I('post.pageCurrent', 1);
        $pro_no = I('post.pro_no');
        $is_loan = I('request.is_loan');
        if (!empty($pro_no)) {
            $map['pro_no'] = $pro_no;
        }
        if (!empty($is_loan)) {
            $map['is_loan'] = $is_loan;
        }
        $model = D('Project');
        $total = $model->where($map)->count();
        $list = $model->where($map)->order('addtime desc')->relation(true)->page($page, $pageSize)->select();
        $workflow = D('Workflow')->getWorkFlow();
        $this->assign('workflow', $workflow);
        $this->assign(array('total'=>$total, 'pageCurrent'=>$page, 'list'=>$list));
        $this->display('look-up');
    }
    
    //等待分配的项目
    public function undistributed() {
        $pageSize = I('post.pageSize', 30);
        $page = I('post.pageCurrent', 1);
        $pro_no = I('post.pro_no');
        if (!empty($pro_no)) {
            $map['pro_no'] = $pro_no;
        }
//        if ($submit_type > -1) {
//            $map['submit_status'] = $submit_status;
//        }
        $map['submit_status'] = 1;
        $map['_query'] = " admin_id=''&risk_admin_id= '&_logic=or";
//        $map['admin_id'] = '';
//        $map['risk_admin_id'] = array('', 'OR');
        $model = D('Project');
        $total = $model->where($map)->count();
        $list = $model->where($map)->order('addtime desc')->relation(true)->page($page, $pageSize)->select();
        $workflow = D('Workflow')->getWorkFlow();
        $this->assign('workflow', $workflow);
        $this->assign(array('total'=>$total, 'pageCurrent'=>$page, 'list'=>$list));
        $this->display();
    }
    
    public function distribute() {
        $pro_id = I('get.pro_id');
        if (empty($pro_id)) {
            $this->json_error('参数错误');
        }
        $model = D('Project');
        $data = $model->where('pro_id=' . $pro_id)->relation(true)->find();
        $this->assign($data);
        $this->assign('pro_id', $pro_id);
        $this->display();
    }
    
    //风控审核
    public function riskauditList() {
        $pageSize = I('post.pageSize', 30);
        $page = I('post.pageCurrent', 1);
        $model = D('Project');
        $pro_no = I('post.pro_no');
        $submit_status = I('post.submit_status', -1);
        if (!empty($pro_no)) {
            $map['pro_no'] = $pro_no;
        }
        if ($submit_status > -1) {
            $map['submit_status'] = $submit_status;
        }
        
        $admin = session('admin');
        $map['role_id'] = array('in', $admin['role_id']);
//        $map['pro_step'] = array('LT', 4);
        $total = $model->where($map)->count();
        $list = $model->where($map)->order('addtime desc')->relation(true)->page($page, $pageSize)->select();
        $workflow = D('Workflow')->getWorkFlow();

        $this->assign('workflow', $workflow);
        $this->assign(array('total'=>$total, 'pageCurrent'=>$page, 'list'=>$list));
        $this->display();
    }
    
    //已放款项目和放款管理
    public function loanIndex() {
        $pageSize = I('post.pageSize', 30);
        $page = I('post.pageCurrent', 1);
        $model = D('Project');
        $pro_no = I('post.pro_no');
        $submit_status = I('post.submit_status', -1);
        if (!empty($pro_no)) {
            $map['pro_no'] = $pro_no;
        }
        if ($submit_status > -1) {
            $map['submit_status'] = $submit_status;
        }
        
        $admin = session('admin');
//        $map['role_id'] = array('in', $admin['role_id']);
        $map['is_loan'] = array('EQ', 1);
        $total = $model->where($map)->count();
        $list = $model->where($map)->order('addtime desc')->relation(true)->page($page, $pageSize)->select();
        $workflow = D('Workflow')->getWorkFlow();

        $this->assign('workflow', $workflow);
        $this->assign(array('total'=>$total, 'pageCurrent'=>$page, 'list'=>$list));
        $this->display('loan_index');
    }
    
    //查找待放款项目
    public function unloan() {
        $pageSize = I('post.pageSize', 30);
        $page = I('post.pageCurrent', 1);
        $model = D('Project');
        $pro_no = I('post.pro_no');
        $submit_status = I('post.submit_status', -1);
        if (!empty($pro_no)) {
            $map['pro_no'] = $pro_no;
        }
        if ($submit_status > -1) {
            $map['submit_status'] = $submit_status;
        }
        
        $admin = session('admin');
//        $map['role_id'] = array('in', $admin['role_id']);
        $map['pro_step'] = array('EQ', 9);
        $total = $model->where($map)->count();
        $list = $model->where($map)->order('addtime desc')->relation(true)->page($page, $pageSize)->select();
        $workflow = D('Workflow')->getWorkFlow();

        $this->assign('workflow', $workflow);
        $this->assign(array('total'=>$total, 'pageCurrent'=>$page, 'list'=>$list));
        $this->display();
    }
    
    //放款申请接口
    public function loanApply() {
        $pro_id = I('post.pro_id');
        $pro_real_money = I('post.pro_real_money');
        if (empty($pro_id)) {
            $this->json_error('非法请求');
        }
        $model = D('Project');
        $pro_info = $model->where('pro_id=' . $pro_id)->find();
        if ($pro_info['pro_step'] != 9) {
            $this->json_error('该项目还不能放款');
        }
        if (!is_numeric($pro_real_money) || $pro_real_money <= 0) {
            $this->json_error('放款金额不正确');
        }
        $data = array('pro_id' => $pro_id, 'pro_real_money' => $pro_real_money, 'is_loan' => 1);
        if (false === $data = $model->create($data)) {
            $e = $model->getError();
            $this->json_error($e);
        }
        if (!$model->save()) {
            $this->json_error('修改失败');
        }
        $this->json_success('保存成功');
    }
}
