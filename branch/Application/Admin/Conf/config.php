<?php
return array(
	//'配置项'=>'配置值'
    'default_m_layer' => 'Model',
    'Page_Title'=>'国投保理信息系统',
    'UPLOAD_CONFIG' => array(
        'maxSize' => 20971520,
        'saveName' => array('uniqid', ''),
        'exts' => array('jpg', 'gif', 'png', 'jpeg', 'pdf', 'doc', 'docx'),
        'autoSub' => true,
    ),
    'page_sizes' => array(30, 60, 90, 150), //页面分页选项
    'page_default_size' => 30,  //默认分页数
    'LOAD_EXT_CONFIG' => 'process',
    
    'symbiosis_levels' => array(    //合作关系
        0 => '无合作关系',
        1 => '合作伙伴',
        2 => '全面合作伙伴',
        3 => '战略伙伴',
        4 => '全面战略伙伴',
    )
);