<?php

function check_verify($code, $id = '') {
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}

/**
 * 取验证码hash值
 *
 * @param
 * @return string 字符串类型的返回结果
 */
function getHash() {
    return substr(md5(__SELF__), 0, 8);
}

/* 生成哈希链接 */

function U_hash($link) {
    return U($link, array('nchash' => getHash()));
}

function random($length, $numeric = 0) {
    $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
    $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
    $hash = '';
    $max = strlen($seed) - 1;
    for ($i = 0; $i < $length; $i++) {
        $hash .= $seed{mt_rand(0, $max)};
    }
    return $hash;
}

function makeSeccode($nchash) {
    $seccode = random(6, 1);
    $seccodeunits = '';

    $s = sprintf('%04s', base_convert($seccode, 10, 23));
    $seccodeunits = 'ABCEFGHJKMPRTVXY2346789';
    if ($seccodeunits) {
        $seccode = '';
        for ($i = 0; $i < 4; $i++) {
            $unit = ord($s{$i});
            $seccode .= ($unit >= 0x30 && $unit <= 0x39) ? $seccodeunits[$unit - 0x30] : $seccodeunits[$unit - 0x57];
        }
    }
    session('seccode', $nchash);
    //cookie('seccode'.$nchash, encrypt(strtoupper($seccode)."\t".(time())."\t".$nchash,MD5_KEY),3600);
    return $seccode;
}

//上传文件
function upload_file($savepath, $field, $short_name = '') {
    $upload = new \Think\Upload(C('UPLOAD_CONFIG'));
    $upload->savePath = $savepath;
    $upload->subName = $field;
    $upload->saveName = array('uniqid', $short_name);
    $upload_info = $upload->upload();
    if (!$upload_info) {
        return $upload->getError();
    } else {
        $file_path = '/Uploads' . ltrim($upload_info[$field]['savepath'], '.') . $upload_info[$field]['savename'];
        $upload_info[$field]['file_path'] = $file_path;
        return $upload_info[$field];
    }
}

function debt_tr_class($status, $endtime) {
    $class = '';
    if ($status == 0) {
        $class = 'active';
    } else {
        $now = time();
        $diff = $endtime - $now;
        if ($diff > 0) {
            $days = $diff / 86400;
            if ($days <= 30) {
                $class = 'danger';
            } elseif ($days <= 60) {
                $class = 'warning';
            } elseif ($days <= 90) {
                $class = 'info';
            }
        } else {
            $class = 'active';
        }
    }
    return $class;
}

//判断是否为超级管理员
function isSupper() {
    $admin = session('admin');
    if ($admin['is_supper']) {
        return true;
    }
    return false;
}

/**
 * 返回项目债权状态
 * @param string $status 状态值
 * @return string
 */
function debtStatusStr($status) {
    return Admin\Model\ProjectDebtModel::debtStatusStr($status);
}
