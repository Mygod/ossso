<?php
header('Content-Type: application/json');
$result = ['success' => false];

function printResult() {
    global $result;
    echo json_encode($result);
}
