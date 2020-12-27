<?php
    include_once ('../global.php');

    //login logic
//    if ($this->isLogin()) {
//
//    } else {
//          header('location: /dashboard.php');
//    }


    if (strpos($_SERVER['REQUEST_URI'], 'test/test') !== false) {
        $requestParams = explode('?', $_SERVER['REQUEST_URI'], 2)[1];
        header('location: /test/testX.php'. (!empty($requestParams)? '?'.$requestParams: ''));
        exit;
    }