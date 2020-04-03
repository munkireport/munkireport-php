<?php
$status_code = $status_code ?? 200;
header('Content-Type: application/json;charset=utf-8');
header( "HTTP/1.1 $status_code" );

echo json_encode($msg ?? []);