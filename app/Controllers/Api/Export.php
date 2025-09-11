<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Factories\DatabaseModelFactory;

/**
 * Controller responsible for exporting data in different formats via the API.
 *
 * Allows exporting the result of the last successful SQL query into CSV or JSON formats.
 * It retrieves the query from the user's session, re-executes it, and streams
 * the formatted file back to the user as a download.
 * * @package App\Controllers\Api
 */
class Export extends BaseController
{
    /**
     * Exports the result of the last successful SQL query as a CSV file.
     *
     * Retrieves the query from the session, executes it, formats the data into CSV,
     * and sends the appropriate headers to trigger a file download in the user's browser.
     * Includes a BOM (Byte Order Mark) for better Excel compatibility with UTF-8 characters.
     *
     * @return void
     */
    public function csv()
    {
        $sql = session()->get('last_successful_query');
        if (empty($sql)) {
            exit(lang('App.feedback.noquery_to_export'));
        }

        $model = DatabaseModelFactory::create();
        $result = $model->executeQuery($sql);

        if ($result['status'] === 'success' && !empty($result['data'])) {
            $filename = 'export_' . date('Y-m-d_H-i-s') . '.csv';
            header('Content-Description: File Transfer');
            header("Content-Disposition: attachment; filename={$filename}");
            header('Content-Type: text/csv; charset=UTF-8');

            $output = fopen('php://output', 'w');
            fputs($output, "\xEF\xBB\xBF"); // BOM for UTF-8
            fputcsv($output, $result['headers']);
            foreach ($result['data'] as $row) {
                fputcsv($output, $row);
            }
            fclose($output);
            exit();
        }
    }

    /**
     * Exports the result of the last successful SQL query as a JSON file.
     *
     * Retrieves the query from the session, executes it, formats the data into
     * a human-readable (pretty-printed) JSON string, and sends the appropriate headers
     * to trigger a file download in the user's browser.
     *
     * @return void
     */
    public function json()
    {
        $sql = session()->get('last_successful_query');
        if (empty($sql)) {
            exit(lang('App.feedback.noquery_to_export'));
        }

        $model = DatabaseModelFactory::create();
        $result = $model->executeQuery($sql);

        if ($result['status'] === 'success' && !empty($result['data'])) {
            $filename = 'export_' . date('Y-m-d_H-i-s') . '.json';
            header('Content-Type: application/json');
            header("Content-Disposition: attachment; filename={$filename}");

            echo json_encode(
                $result['data'],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE,
            );
            exit();
        }
    }
}
