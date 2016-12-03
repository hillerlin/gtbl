<?php

namespace Admin\Model;

class WorkflowModel extends BaseModel {

    protected $_validate = array(
//        array('role_name', 'require', '请输入权限组'),
//        array('role_name', '', '权限组已存在', 0, 'unique', 1),
    );
    protected $_link = array(
        'role' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Role',
            'foreign_key' => 'step_role_id',
            'mapping_name' => 'role',
            'as_fields' => 'role_name'
        ),
        
    );
    
    public function getWorkFlow() {
        $wokflow = session('workflow');
        if (empty($wokflow)) {
            $workflow = $this->relation('role')->order('step_id')->select();
            foreach ($workflow as & $val) {
                $val['step_next'] = json_decode($val['step_next'], true);
            }
            session('workflow', $wokflow);
        }   
        return $workflow;
    }

}
