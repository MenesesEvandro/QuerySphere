<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\SqlServerModel;

/**
 * Controlador responsável pela exportação de dados em diferentes formatos via API.
 *
 * Permite exportar o resultado da última consulta SQL bem-sucedida em CSV ou JSON.
 */
class Export extends BaseController
{
    /**
     * Exporta o resultado da última consulta SQL bem-sucedida em formato CSV.
     *
     * O arquivo é enviado como download para o usuário.
     *
     * @return void
     */
    public function csv()
    {
        $sql = session()->get('last_successful_query');
        if (empty($sql)) exit(lang('App.noquery'));

        $model = new SqlServerModel();
        $result = $model->executeQuery($sql);

        if ($result['status'] === 'success' && !empty($result['data'])) {
            $filename = "export_" . date("Y-m-d_H-i-s") . ".csv";
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename={$filename}");
            header("Content-Type: text/csv; charset=UTF-8");

            $output = fopen("php://output", "w");
            fputs($output, "\xEF\xBB\xBF");
            fputcsv($output, $result['headers']);
            foreach ($result['data'] as $row) {
                fputcsv($output, $row);
            }
            fclose($output);
            exit();
        }
    }
    
    /**
     * Exporta o resultado da última consulta SQL bem-sucedida em formato JSON.
     *
     * O arquivo é enviado como download para o usuário.
     *
     * @return void
     */
    public function json()
    {
        $sql = session()->get('last_successful_query');
        if (empty($sql)) exit(lang('App.noquery'));

        $model = new SqlServerModel();
        $result = $model->executeQuery($sql);

        if ($result['status'] === 'success' && !empty($result['data'])) {
            $filename = "export_" . date("Y-m-d_H-i-s") . ".json";
            header('Content-Type: application/json');
            header("Content-Disposition: attachment; filename={$filename}");
            
            echo json_encode($result['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            exit();
        }
    }
}