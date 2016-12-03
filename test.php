<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/4
 * Time: 8:57
 */
//pay_time,begin_interest_time,customer_name,fund_title,money,fund_rate,term,fmanager_id,branch_name
//$xml=new DOMDocument();
//$contents=file_get_contents('gtbl.xml');
//$xml->load('gtbl.xml');
//$xml->loadXML($contents);
//$root=$xml->documentElement;
//$parents=$root->getElementsByTagName('process');
//var_dump($parents);die;
/*foreach ($parents as $k=> $parent)
{
    $nodes=$parent->getElementsByTagName('bpmn2userTask');
    foreach ($nodes as $kk=>$vv)
    {
       // var_dump($nodes->item($k)->nodeValue);
        //var_dump($vv->firstChild);
    }

    //var_dump($parent->getAttribute('id'));
}*/



//1111111111111111111111111111

$xmlstring = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<bpmn2:definitions xmlns:bpmn2="http://www.omg.org/spec/BPMN/20100524/MODEL"
                   xmlns:bpmndi="http://www.omg.org/spec/BPMN/20100524/DI"
                   xmlns:di="http://www.omg.org/spec/DD/20100524/DI" xmlns:dc="http://www.omg.org/spec/DD/20100524/DC"
                   id="BPMNProcessmaker" targetNamespace="http://bpmn.io/schema/bpmn">
    <bpmn2:process id="pmui-366534151583fbfd2a99ca3095734705">
        <bpmn2:userTask id="el_308715820583f9d39322489074895638" name="项目经理立项">
            <bpmn2:incoming>flo_pmui-826586314583fdbb5ac84d9091143068</bpmn2:incoming>
            <bpmn2:outgoing>flo_pmui-257124943583fc153a9eb15063458647</bpmn2:outgoing>
        </bpmn2:userTask>
        <bpmn2:userTask id="el_pmui-749796691583fbfe3a99cd6045215095" name="项目总监">
            <bpmn2:incoming>flo_pmui-257124943583fc153a9eb15063458647</bpmn2:incoming>
            <bpmn2:outgoing>flo_pmui-952433172583fc729aa3c27010064349</bpmn2:outgoing>
        </bpmn2:userTask>
        <bpmn2:subProcess id="el_pmui-350530735583fc032a9a917007603289" name="分配人员跟进">
            <bpmn2:incoming>flo_pmui-577909305583fc7c0aa98b7011716591</bpmn2:incoming>
            <bpmn2:outgoing>flo_pmui-235804509583fc85dab4882050095298</bpmn2:outgoing>
        </bpmn2:subProcess>
        <bpmn2:userTask id="el_pmui-524945296583fc09fa9aec8038484790" name="项目专员">
            <bpmn2:incoming>flo_pmui-235804509583fc85dab4882050095298</bpmn2:incoming>
            <bpmn2:outgoing>flo_pmui-802995043583fc86aab5032098614808</bpmn2:outgoing>
        </bpmn2:userTask>
        <bpmn2:sequenceFlow id="flo_pmui-257124943583fc153a9eb15063458647" name="提交立项"
                            sourceRef="el_308715820583f9d39322489074895638"
                            targetRef="el_pmui-749796691583fbfe3a99cd6045215095"/>
        <bpmn2:inclusiveGateway id="el_pmui-333326121583fc6a5aa30c6029771876" name="是否通过">
            <bpmn2:incoming>flo_pmui-952433172583fc729aa3c27010064349</bpmn2:incoming>
            <bpmn2:outgoing>flo_pmui-577909305583fc7c0aa98b7011716591</bpmn2:outgoing>
            <bpmn2:outgoing>flo_pmui-826586314583fdbb5ac84d9091143068</bpmn2:outgoing>
        </bpmn2:inclusiveGateway>
        <bpmn2:sequenceFlow id="flo_pmui-952433172583fc729aa3c27010064349" name="审核"
                            sourceRef="el_pmui-749796691583fbfe3a99cd6045215095"
                            targetRef="el_pmui-333326121583fc6a5aa30c6029771876"/>
        <bpmn2:sequenceFlow id="flo_pmui-577909305583fc7c0aa98b7011716591" name="通过"
                            sourceRef="el_pmui-333326121583fc6a5aa30c6029771876"
                            targetRef="el_pmui-350530735583fc032a9a917007603289"/>
        <bpmn2:sequenceFlow id="flo_pmui-235804509583fc85dab4882050095298" name="分配完成"
                            sourceRef="el_pmui-350530735583fc032a9a917007603289"
                            targetRef="el_pmui-524945296583fc09fa9aec8038484790"/>
        <bpmn2:endEvent id="el_pmui-284647945583fc867ab4935027552481" name="结束">
            <bpmn2:incoming>flo_pmui-802995043583fc86aab5032098614808</bpmn2:incoming>
        </bpmn2:endEvent>
        <bpmn2:sequenceFlow id="flo_pmui-802995043583fc86aab5032098614808" name="执行任务"
                            sourceRef="el_pmui-524945296583fc09fa9aec8038484790"
                            targetRef="el_pmui-284647945583fc867ab4935027552481"/>
        <bpmn2:sequenceFlow id="flo_pmui-826586314583fdbb5ac84d9091143068" name="驳回"
                            sourceRef="el_pmui-333326121583fc6a5aa30c6029771876"
                            targetRef="el_308715820583f9d39322489074895638"/>
    </bpmn2:process>
    <bpmndi:BPMNDiagram id="dia_426664915583fbfd2a99c82051295804">
        <bpmndi:BPMNPlane id="plane_471431950583fbfd2a99c96040358885"
                          bpmnElement="pmui-366534151583fbfd2a99ca3095734705">
            <bpmndi:BPMNShape id="di_el_308715820583f9d39322489074895638"
                              bpmnElement="el_308715820583f9d39322489074895638">
                <dc:Bounds x="599" y="55" width="150" height="75"/>
            </bpmndi:BPMNShape>
            <bpmndi:BPMNShape id="di_el_pmui-749796691583fbfe3a99cd6045215095"
                              bpmnElement="el_pmui-749796691583fbfe3a99cd6045215095">
                <dc:Bounds x="599" y="260" width="150" height="75"/>
            </bpmndi:BPMNShape>
            <bpmndi:BPMNShape id="di_el_pmui-350530735583fc032a9a917007603289"
                              bpmnElement="el_pmui-350530735583fc032a9a917007603289">
                <dc:Bounds x="675" y="566" width="94" height="63"/>
            </bpmndi:BPMNShape>
            <bpmndi:BPMNShape id="di_el_292541503583fc0a60758b7036469204"
                              bpmnElement="el_pmui-524945296583fc09fa9aec8038484790">
                <dc:Bounds x="699" y="777" width="150" height="75"/>
            </bpmndi:BPMNShape>
            <bpmndi:BPMNEdge id="flo_pmui-257124943583fc153a9eb15063458647_di"
                             bpmnElement="flo_pmui-257124943583fc153a9eb15063458647">
                <di:waypoint x="675" y="130"/>
                <di:waypoint x="675" y="260"/>
            </bpmndi:BPMNEdge>
            <bpmndi:BPMNShape id="di_el_pmui-333326121583fc6a5aa30c6029771876"
                              bpmnElement="el_pmui-333326121583fc6a5aa30c6029771876">
                <dc:Bounds x="829" y="418" width="41" height="41"/>
            </bpmndi:BPMNShape>
            <bpmndi:BPMNEdge id="flo_pmui-952433172583fc729aa3c27010064349_di"
                             bpmnElement="flo_pmui-952433172583fc729aa3c27010064349">
                <di:waypoint x="675" y="335"/>
                <di:waypoint x="675" y="377"/>
                <di:waypoint x="850" y="377"/>
                <di:waypoint x="850" y="418"/>
            </bpmndi:BPMNEdge>
            <bpmndi:BPMNEdge id="flo_pmui-577909305583fc7c0aa98b7011716591_di"
                             bpmnElement="flo_pmui-577909305583fc7c0aa98b7011716591">
                <di:waypoint x="850" y="459"/>
                <di:waypoint x="850" y="512"/>
                <di:waypoint x="723" y="512"/>
                <di:waypoint x="723" y="566"/>
            </bpmndi:BPMNEdge>
            <bpmndi:BPMNEdge id="flo_pmui-235804509583fc85dab4882050095298_di"
                             bpmnElement="flo_pmui-235804509583fc85dab4882050095298">
                <di:waypoint x="675" y="598"/>
                <di:waypoint x="655" y="598"/>
                <di:waypoint x="655" y="687"/>
                <di:waypoint x="775" y="687"/>
                <di:waypoint x="775" y="777"/>
            </bpmndi:BPMNEdge>
            <bpmndi:BPMNShape id="di_el_pmui-284647945583fc867ab4935027552481"
                              bpmnElement="el_pmui-284647945583fc867ab4935027552481">
                <dc:Bounds x="759" y="1029" width="33" height="33"/>
            </bpmndi:BPMNShape>
            <bpmndi:BPMNEdge id="flo_pmui-802995043583fc86aab5032098614808_di"
                             bpmnElement="flo_pmui-802995043583fc86aab5032098614808">
                <di:waypoint x="775" y="852"/>
                <di:waypoint x="775" y="940"/>
                <di:waypoint x="776" y="940"/>
                <di:waypoint x="776" y="1029"/>
            </bpmndi:BPMNEdge>
            <bpmndi:BPMNEdge id="flo_pmui-826586314583fdbb5ac84d9091143068_di"
                             bpmnElement="flo_pmui-826586314583fdbb5ac84d9091143068">
                <di:waypoint x="870" y="439"/>
                <di:waypoint x="890" y="439"/>
                <di:waypoint x="890" y="93"/>
                <di:waypoint x="750" y="93"/>
            </bpmndi:BPMNEdge>
        </bpmndi:BPMNPlane>
    </bpmndi:BPMNDiagram>
</bpmn2:definitions>
XML;

$dom = new DOMDocument();
$dom->loadXML($xmlstring);
print_r(getArray($dom->documentElement));



function getArray($node) {
    $array = false;

    if ($node->hasAttributes()) {
        foreach ($node->attributes as $attr) {
            $array[$attr->nodeName] = $attr->nodeValue;
           // var_dump($attr->nodeName.'-----'.$attr->nodeValue);
        }
    }

    if ($node->hasChildNodes()) {
        if ($node->childNodes->length == 1) {
            $array[$node->firstChild->nodeName] = getArray($node->firstChild);
                    // var_dump($node->firstChild->nodeName);
        } else {
            foreach ($node->childNodes as $childNode) {
                if ($childNode->nodeType != XML_TEXT_NODE) {
                    $array[$childNode->nodeName][] = getArray($childNode);
                    //var_dump($childNode->nodeName);
                }
            }
        }
    } else {
        return $node->nodeValue;
    }
   return $array;
}


/*header("Content-type:text/html; Charset=utf-8");
$url = "123.xml";

//  加载XML内容
echo "<pre/>";
$content = file_get_contents('gtbl.xml');
$p = xml_parser_create();
xml_parse_into_struct($p, $content, $vals, $index);
xml_parser_free($p);
$tags=array('BPMN2:DEFINITIONS', 'BPMN2:DATASTORE', 'BPMN2:GROUP', 'BPMNDI:BPMNDIAGRAM', 'BPMNDI:BPMNPLANE', 'BPMNDI:BPMNEDGE', 'DI:WAYPOINT', 'BPMNDI:BPMNSHAPE', 'DC:BOUNDS', 'BPMN2:PROCESS');
foreach($vals as $k => $v){
    if(strcasecmp($v['type'],'cdata')===0 || in_array($v['tag'],$tags))unset($vals[$k]);
}
$data=[];$children=[];
foreach($vals as $kk=>$vv){
    if($vv['level']==3 && $vv['type']=='complete'){
        array_push($data,$vv);
        continue;
    }
    if($vv['type']=='open'){
        array_push($data,$vv);
        continue;
    }
    if($vv['type']=='close'){
        $data[count($data)-1]['value']=$children;
        $children=[];
        continue;
    }
    array_push($children,$vv);
}
foreach ($data as $k =>$v){
    $result[$v['attributes']['ID']]=$v;
}
print_r($result);*/






















$arr=array(
    'edit'=>array(
        array('field'=>'pay_time','name'=>'打款时间'),
        array('field'=>'begin_interest_time','name'=>'起息时间'),
        array('field'=>'customer_name','name'=>'客户姓名'),
        array('field'=>'fund_title','name'=>'认购项目'),
        array('field'=>'money','name'=>'金额'),
        array('field'=>'fund_rate','name'=>'客户收益率'),
        array('field'=>'term','name'=>'期限'),
        array('field'=>'fmanager_id','name'=>'客户经理'),
        array('field'=>'branch_name','name'=>'地区'),
        array('field'=>'remark','name'=>'备注')
    ),
    'rateInfo'=>'none'
);

$bbb=array(
    '同意'=>array('roleId'=>'','name'=>'end'),
    '反对'=>array('roleId'=>'24','name'=>'资金管理专员')
    );

$ccc=array(
    'edit'=>'all',
    'rateInfo'=>array(
        array('field'=>'interestDue','name'=>'到期本息'),
        array('field'=>'remark','name'=>'备注'),
        array('field'=>'upload','name'=>'上传图片')
    )
);

//print_r(serialize($ccc));
//删除文件
//$file_path='/Uploads/project/review/pro-/582d64ddb35f2.png';
/*$file_path='test.html';
if (file_exists($file_path)) {
    //$res2 = unlink('.' . $file_path);
    $aa=11;
} else {
    $res2 = true;
}*/
/*$aa=array('1',);
$arr="Array([0] => '小麦理财' [1] => '大麦理财' [2] => '财富中心')";
$reg1="/>\s(\'[\x{4e00}-\x{9fa5}A-Za-z0-9_]+\')/u";
var_dump(preg_replace($reg1,'${1},',$arr));*/

/*$fruits = array("d" => "lemon", "a" => "orange", "b" => "banana", "c" => "apple");

function test_alter(&$item1, $key, $prefix)
{
    $item1 = "$prefix: $item1";
}

function test_print($item2, $key)
{
    echo "$key. $item2<br />\n";
}

echo "Before ...:\n";
array_walk($fruits, 'test_print');

array_walk($fruits, 'test_alter', 'fruit');
var_dump($fruits);
echo "... and after:\n";

array_walk($fruits, 'test_print');*/

//var_dump();

/*$a=3;
echo ($a==1)?'等于1':($a==2)?'等于2':'等于3';;*/
//echo strtotime('2017-05-15');
//echo date('Y-m-d h:i:s','1494799200');






/*$arr = array(
    array('aeare' => '北京', 'id' => 1, 'parentId' => 0),
    array('aeare' => '海锭', 'id' => 2, 'parentId' => 1),
    array('aeare' => '上地', 'id' => 3, 'parentId' => 2)
);
$numb = array(
    array(10, 15, 30), array(10, 15, 30), array(10, 15, 30)
);
$x=array(1,2,3);
$xx=array(4,5,6);
$xxxx=array(41,51,61);
$xxxxx=array(42,52,62);
$cxxxcvx=array(43,53,63);
array(0=>'xx',1=>'xxxx');
array(0=>'xx1',1=>'xxxx1');

$data=array(
    array(0=>'xx',1=>'xxxx'),
    array(0=>'xx1',1=>'xxxx1')
);
array_unshift($data,null);
$bb=call_user_func_array('array_map',$data);
print_r($bb);*/
//var_dump(count($numb));
/*function recure($arr,$id)
{
    static $rearr=array();
    foreach($arr as $k=>$v)
    {
        if($id==$v['id'])
        {

            if($v['parentId']>0)
            {
                //array_merge($rearr,recure($arr,$v['parentId']));
                recure($arr,$v['parentId']);
            }
            $rearr[]=$v;

        }

    }
    return $rearr;
}*/

/*var_dump(
    array_walk($arr,'_sum',0)
);*/
/*$c = array_map("show_Spanish", $a, $b);
print_r($c);

print_r($d);
$d = array_map("map_Spanish", $a , $b);*/

/*function show_Spanish($n, $m)
{
    return("The number $n is called $m in Spanish");
}

function map_Spanish($n, $m)
{
    return(array($n => $m));
}

$a = array(1, 2, 3, 4, 5);
$b = array("uno", "dos", "tres", "cuatro", "cinco");

$c = array_map("show_Spanish", $a, $b);
print_r($c);

$d = array_map("map_Spanish", $a , $b);
print_r($d);*/





/*CREATE TABLE `gt_fund_order_log` (
    `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fund_id` int(10) unsigned NOT NULL,
  `fmanager_id` mediumint(5) unsigned NOT NULL DEFAULT '0' COMMENT '客户经理id',
  `money` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `customer_name` varchar(16) NOT NULL DEFAULT '' COMMENT '客户姓名',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '客户打款时间',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `contract_no` varchar(32) NOT NULL DEFAULT '' COMMENT '合同号',
  `partnership` varchar(32) NOT NULL DEFAULT '' COMMENT '合伙企业',
  `begin_interest_time` int(10) NOT NULL DEFAULT '0' COMMENT '起息时间',
  `done_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '成立时间',
  `fund_title` varchar(32) NOT NULL DEFAULT '' COMMENT '项目',
  `fund_rate` float(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '客户收益率',
  `term` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '期限',
  `deadline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '到期时间',
  `id_no` varchar(20) NOT NULL DEFAULT '' COMMENT '身份证号',
  `bank_no` varchar(32) NOT NULL DEFAULT '' COMMENT '银行账号',
  `link_type` varchar(32) NOT NULL DEFAULT '' COMMENT '联系方式',
  `performance_rate` float(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '业绩提成率',
  `manage_rate` float(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '管理津贴率',
  `changer_id` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '修改人的id',
  `remark` text  COMMENT '备注',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;*/


/*$aa='[{"time":"2015-06-15","rete":"4.4301"},{"time":"2015-09-15","rete":"4.1589"},{"time":"2015-12-15","rete":"4.1137"}]';
//print_r(json_decode($aa,true));
$bb=array('第一','第二');
array_push($bb,'第三');
var_dump($bb);*/


$aa='a:2:{s:4:"edit";a:10:{i:0;a:2:{s:5:"field";s:8:"pay_time";s:4:"name";s:12:"打款时间";}i:1;a:2:{s:5:"field";s:19:"begin_interest_time";s:4:"name";s:12:"起息时间";}i:2;a:2:{s:5:"field";s:13:"customer_name";s:4:"name";s:12:"客户姓名";}i:3;a:2:{s:5:"field";s:10:"fund_title";s:4:"name";s:12:"认购项目";}i:4;a:2:{s:5:"field";s:5:"money";s:4:"name";s:6:"金额";}i:5;a:2:{s:5:"field";s:9:"fund_rate";s:4:"name";s:15:"客户收益率";}i:6;a:2:{s:5:"field";s:4:"term";s:4:"name";s:6:"期限";}i:7;a:2:{s:5:"field";s:11:"fmanager_id";s:4:"name";s:12:"客户经理";}i:8;a:2:{s:5:"field";s:11:"branch_name";s:4:"name";s:6:"地区";}i:9;a:2:{s:5:"field";s:6:"remark";s:4:"name";s:6:"备注";}}s:8:"rateInfo";s:4:"none";}';
//var_dump(unserialize($aa));











