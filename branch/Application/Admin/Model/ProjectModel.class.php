<?php

namespace Admin\Model;

use Admin\Model\BaseModel;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class ProjectModel extends BaseModel {
    
    protected $_validate = array(
        array('pro_title', 'require', '请输入项目标题'),
        array('pro_account', 'require', '请输入项目标题'),
        array('pro_real_money', 'require', '请选择国投的跟进人'),
        array('gt_uid', 'require', '请输入项目标题'),
//        array('admin_name', '', '管理员已存在', 0, 'unique', 1),
    );
    
    protected $_auto = array(
        array('addtime', 'time', 1, 'function'),
        array('pro_no', 'getProNo', 1, 'callback')
    );
    
    public function getProNo() {
        $data = date('ymd');
        $today_midnight = strtotime('midnight');
        $map = 'addtime >= ' . $today_midnight . ' AND addtime < ' . strtotime('+1 days', $today_midnight);
        $count = $this->where($map)->count();
        return $data . '-' . ($count + 1);
    }
    
    protected $_link = array(
        'admin' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'admin',
            'mapping_name' => 'admin',
            'foreign_key' => 'admin_id',
//            'as_fields' => 'user_name',
        ),
        'risk_admin' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'admin',
            'mapping_name' => 'risk_admin',
            'foreign_key' => 'risk_admin_id',
//            'as_fields' => 'user_name',
        ),
        'company' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'company',
            'mapping_name' => 'company',
            'foreign_key' => 'company_id',
//            'as_fields' => 'user_name',
        ),
    );
}

