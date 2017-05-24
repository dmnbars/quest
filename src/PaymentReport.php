<?php

namespace App;

class PaymentReport extends Report
{
    /**
     * {@inheritdoc}
     */
    public function initData()
    {
        $dataSource = new DataSource();
        $dataSource->loadFixtures();
    }

    /**
     * {@inheritdoc}
     */
    public function createReport()
    {
        // TODO: Implement createReport() method.
    }

    /**
     * {@inheritdoc}
     */
    public function printResult(string $template)
    {
        switch ($template) {
            case 'csv':
                break;
            default:
                break;
        }
    }
}
