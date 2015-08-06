<?php
header('Content-Type: application/json');
$result = ['success' => true];

function addErrorMessage($msg) {
    global $result;
    if ($msg) {
        if (isset($result['errorMessage'])) $result['errorMessage'] .= '\n'; else $result['errorMessage'] = '';
        $result['errorMessage'] .= $msg;
        $result['success'] = false;
        return true;
    } else return false;
}

function printResult() {
    global $result;
    echo json_encode($result);
}
