<?php

require_once 'vendor/autoload.php';

$dataSource = new \App\DataSource();
$report = new \App\PaymentReport($dataSource);
$report->initData();
$report->createReport();
$report->printResult('csv');
