<?php
namespace Admin\Controller;

class TestController extends CommonController {
    
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        echo date('Y-m-d H:i:s',strtotime('midnight'));
        $today_midnight = strtotime('midnight');
        echo date('Y-m-d H:i:s', strtotime('+1 days', $today_midnight));
        var_dump(C('wex'));
        $proxy = new \Admin\Lib\wex\WexAproxy(C('wex'));
        $para_temp = array(
            'service' => 'goods_info_list',
//            'goods_id' => '1003272|1003287|1003268'
        );
//        $para_temp = array(
//            "service"=>"trade_invest_get",
//            "partner_id"=>"2088002007018916",
//            "input_charset"=>"utf-8",
//            "return_url"=>"http://www.test.com/qijian/return_url.asp",
//            "out_trade_no"=>"709651609727679",
//            "subject"=>"nokia n8",
//            "price"=>"300",
//            "quantity"=>"1",
//            "seller_email"=>"zhoubo_seller@qjtest.com"
//        );
        var_dump($proxy->request($para_temp));
    }
    
}