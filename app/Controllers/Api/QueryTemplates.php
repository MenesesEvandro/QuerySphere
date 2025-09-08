<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class QueryTemplates extends BaseController
{
    use ResponseTrait;

    private $basePath;

    public function __construct()
    {
        $this->basePath = WRITEPATH . 'query_templates';
    }

    /**
     * Lists all available query templates, organized by category (subdirectories).
     */
    public function index()
    {
        helper('filesystem');

        $templateTranslations = lang('App.query_templates');

        $directoryMap = directory_map($this->basePath, 2);
        $response = [];

        if (!$directoryMap || !$templateTranslations) {
            return $this->respond([]);
        }

        foreach ($templateTranslations as $categoryKey => $categoryData) {
            if (isset($directoryMap[$categoryKey . DIRECTORY_SEPARATOR])) {
                $templateCategory = [
                    'category' => $categoryData['title'],
                    'scripts' => [],
                ];

                foreach (
                    $categoryData['scripts']
                    as $scriptFileKey => $scriptData
                ) {
                    if (
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
     * Gets the content of a specific query template file.
     * Includes security checks to prevent directory traversal.
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
