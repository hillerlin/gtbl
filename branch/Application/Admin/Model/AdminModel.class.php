<?php

namespace Admin\Model;

use Think\Model\RelationModel;

class AdminModel extends RelationModel {

    protected $_validate = array(
        array('admin_name', 'require', '请输入管理员账号'),
        array('admin_name', '', '管理员已存在', 0, 'unique', 1),
    );
    protected $_auto = array(
        array('admin_password', 'check_passowrd', 3, 'callback'),
        array('add_time', 'time', 1, 'function'),
        array('admin_password', '', 2, 'ignore'),
    );
    protected $_link = array(
        'role' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'role',
            'mapping_name' => 'role',
            'foreign_key' => 'role_id',
            'as_fields' => 'role_name',
        ),
        'admin_role' => array(
            'mapping_type' => self::MANY_TO_MANY,
            'class_name' => 'admin_role',
            'foreign_key' => 'admin_id',
            'relation_foreign_key' => 'role_id',
            'relation_table' => 'gt_admin_role',
        ),
//        'admin_role' => array(
//            'mapping_type' => self::HAS_MANY,
//            'class_name' => 'admin_role',
//            'foreign_key' => 'admin_id',
//            'mapping_name' => 'admin_role',
//            'mapping_fields' => 'role_id'
//        )
    );

    public function check_passowrd($value) {
        $value = $value ? md5($value) : $value;
        return $value;
    }

    //检测操作
    public function check_exist($condition) {
        return $this->where($condition)->find();
    }

    public function after_login($admin_id) {
        if (!$admin_id) {
            return false;
        }
        $this->where(array('admin_id' => $admin_id))->save(array('last_login_time' => time(), 'last_login_ip' => get_client_ip()));
        $this->where(array('admin_id' => $admin_id))->setInc('login_times', 1);
    }
    
    public function getList($page = 1, $pageSize = 30) {
        $admin = session('admin');
        $role_id = $admin['role_id'];
        $select = 'SELECT * ';
        $count = 'SELECT COUNT(*) as total';
        $from = ' FROM gt_admin AS a,gt_role as b';
        $where = ' WHERE b.role_id=a.role_id';
        if ($admin['is_supper'] == 0) {
            $where .= ' AND b.pid='.$role_id;
        }
        $re = $this->query($count.$from.$where);
        $result['total'] = empty($re[0]['total']) ? 0 : $re[0]['total'];
        $limit = ( $page - 1 ) * $pageSize;
        $limit = " LIMIT $limit,$pageSize";
        $result['list'] = $this->query($select.$from.$where.$limit);
        return $result;
    }
    
    public function setLinkCondition($relation, $param) {
        $this->_link[$relation]['condition'] = $param;
    }
    
}
