<?php
$result = ['success' => true];

function api_add_error_msg($msg) {
    global $result;
    if (is_string($msg)) {
        if (isset($result['errorMessage'])) $result['errorMessage'] .= '\n'; else $result['errorMessage'] = '';
        $result['errorMessage'] .= $msg;
        $result['success'] = false;
        return true;
    } else return false;
}

function api_print() {
    global $result;
    header('Content-Type: application/json');
    echo json_encode($result);
}

function csv_init($filename) {
    global $fout;
    header('Content-Type: text/csv');
    header("Content-Disposition: attachment;filename=$filename.csv");
    $fout = fopen('php://output', 'w');
}

function fatal_alert($msg) {
    die('<html><head><meta charset="utf-8" /></head><body><script>alert(' . json_encode($msg) .
        ');history.go(-1);</script></body></html>');
}
