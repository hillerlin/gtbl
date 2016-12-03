<?php
namespace Admin\Model;

use Think\Model\RelationModel;

class BaseModel extends RelationModel {
    
    public function setLinkCondition($relation, $param) {
        $this->_link[$relation]['condition'] = $param;
    }
}

