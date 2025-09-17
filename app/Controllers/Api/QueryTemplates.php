<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class QueryTemplates extends BaseController
{
    use ResponseTrait;

    private $basePath;
    private $dbType;

    public function __construct()
    {
        $this->dbType = session()->get('db_type') ?? 'sqlsrv';
        $this->basePath = WRITEPATH . 'query_templates/' . $this->dbType;
    }

    /**
     * Lists all available query templates for the current DBMS, organized by category.
     */
    public function index()
    {
        helper('filesystem');
        $templateTranslations = lang('App.query_templates.' . $this->dbType);

        if (
            !is_dir($this->basePath) ||
            empty($templateTranslations) ||
            !is_array($templateTranslations)
        ) {
            return $this->respond([]);
        }

        $directoryMap = directory_map($this->basePath, 2);
        $response = [];

        foreach ($templateTranslations as $categoryKey => $categoryData) {
            if (
                is_array($categoryData) &&
                isset($categoryData['title'], $categoryData['scripts']) &&
                is_array($categoryData['scripts']) &&
                isset($directoryMap[$categoryKey . DIRECTORY_SEPARATOR])
            ) {
                $templateCategory = [
                    'category' => $categoryData['title'],
                    'scripts' => [],
                ];

                foreach (
                    $categoryData['scripts'] as $scriptFileKey => $scriptData
                ) {
                    if (
                        is_array($scriptData) &&
                        isset($scriptData['title']) &&
                        in_array(
                            $scriptFileKey,
                            $directoryMap[$categoryKey . DIRECTORY_SEPARATOR],
                        )
                    ) {
                        $templateCategory['scripts'][] = [
                            'filename' => $scriptFileKey,
                            'category_key' => $categoryKey,
                            'name' => $scriptData['title'],
                            'description' => $scriptData['description'] ?? '',
                        ];
                    }
                }

                if (!empty($templateCategory['scripts'])) {
                    $response[] = $templateCategory;
                }
            }
        }

        return $this->respond($response);
    }

    /**
     * Gets the content of a specific query template file for the current DBMS.
     */
    public function get($category, $filename)
    {
        $category = basename($category);
        $filename = basename($filename);

        $fullPath =
            $this->basePath .
            DIRECTORY_SEPARATOR .
            $category .
            DIRECTORY_SEPARATOR .
            $filename;

        if (!str_starts_with(realpath($fullPath), realpath($this->basePath))) {
            return $this->failForbidden('Acesso não permitido.');
        }

        if (
            !file_exists($fullPath) ||
            pathinfo($fullPath, PATHINFO_EXTENSION) !== 'sql'
        ) {
            return $this->failNotFound('Template não encontrado.');
        }

        return $this->respond(['sql' => file_get_contents($fullPath)]);
    }
}
