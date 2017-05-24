<?php

namespace App;

class PaymentReport extends Report
{
    /**
     * @var DataSource $dataSource
     */
    private $dataSource;

    public function __construct(DataSource $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    /**
     * {@inheritdoc}
     */
    public function initData()
    {
        $this->dataSource->loadFixtures();
    }

    /**
     * {@inheritdoc}
     */
    public function createReport()
    {
        // для mysql можно использовать DATE_FORMAT(payments.finish_time, '%m.%Y')
        $sql = <<<SQL
SELECT strftime('%m.%Y', payments.finish_time) AS `month`,
       COUNT(payments.id) AS COUNT,
       SUM(payments.amount) AS SUM
FROM payments
LEFT JOIN documents ON payments.id = documents.payment_id
WHERE documents.id IS NULL
GROUP BY `month`;
SQL;
        $data = $this->dataSource->selectData($sql, []);

        $this->data = array_map(
            function ($item) {
                /*
                 * преобразование в мм.гг можно сделать и здесь, вот так:
                 * $item['month'] = \DateTime::createFromFormat('d.Y', $item['month'])->format('d.y');
                 * но на мой взгляд это логичнее делать в "представлении"
                 */
                $item['month'] = \DateTime::createFromFormat('d.Y', $item['month']);

                return $item;
            },
            $data
        );
    }

    /**
     * {@inheritdoc}
     */
    public function printResult(string $template)
    {
        switch ($template) {
            /*
             * Можно, конечно, сохранить это в физический файл и вернуть его из функции,
             * или вернуть из метода контент этого файла.
             * Но судя по заданию и предоставленным файлам, предполагается, что этот метод сразу
             * отдаст в браузер этот файл.
             */
            case 'csv':
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Cache-Control: private', false);
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="payment_report.csv";');
                header('Content-Transfer-Encoding: binary');

                $csv = fopen('php://output', 'w');
                foreach ($this->data as $row) {
                    if ($row['month'] instanceof \DateTime) {
                        $row['month'] = $row['month']->format('d.m');
                    }
                    fputcsv($csv, $row, "\t");
                }
                die();

                break;
            default:
                break;
        }
    }
}
