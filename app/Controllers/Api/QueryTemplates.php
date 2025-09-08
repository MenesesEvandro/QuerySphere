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
        $map = directory_map($this->basePath, 2);
        $templates = [];

        if (!$map) {
            return $this->respond([]);
        }

        ksort($map);

        foreach ($map as $category => $scripts) {
            if (
                is_array($scripts) &&
                substr($category, -1) === DIRECTORY_SEPARATOR
            ) {
                $categoryName = rtrim($category, DIRECTORY_SEPARATOR);
                $templateCategory = [
                    'category' => $categoryName,
                    'scripts' => [],
                ];
                sort($scripts);
                foreach ($scripts as $scriptFile) {
                    if (pathinfo($scriptFile, PATHINFO_EXTENSION) === 'sql') {
                        $templateCategory['scripts'][] = [
                            'filename' => $scriptFile,
                            'name' => str_replace(
                                ['.sql', '_'],
                                ['', ' '],
                                pathinfo($scriptFile, PATHINFO_FILENAME),
                            ),
                        ];
                    }
                }
                $templates[] = $templateCategory;
            }
        }
        return $this->respond($templates);
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
