<?php

$logFile = './tmp/sys.log';
$logFileTime = './tmp/last_fatal_error_time.log';
$monitorFile = '/logs/shi/error_log';

system("echo '' > $logFile");
system("tail -n1000 $monitorFile |grep 'fatal error' -i > $logFile");

$lastFatalErrorTime = file_get_contents($logFileTime);

//分析log数据
$logList = file_get_contents($logFile);
preg_match_all('#.*Fatal error.*#i', $logList, $fullMatch);

if (empty($fullMatch[0])) {
    echo "No Fatal Error\n";
    exit;
}

$newFatalError = 0;
foreach($fullMatch[0] as $errInfo) {
    preg_match('#^\[([^\]]*)\]#', $errInfo, $errTime);

    $logTime = date('Y-m-d H:i:s', strtotime($errTime[1]));
    if ($logTime > $lastFatalErrorTime) {
        $lastFatalErrorTime = $logTime;
        $newFatalError++;
    }
}

if ($newFatalError > 0) {
    file_put_contents($logFileTime, $lastFatalErrorTime);
    echo "Has New Fatal Error: $newFatalError\n";
} else {
    echo "No Fatal Error\n";
}

