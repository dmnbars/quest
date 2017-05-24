<?php

require_once 'vendor/autoload.php';

$report = new \App\PaymentReport();
$report->initData();
$report->createReport();
$report->printResult('csv');
