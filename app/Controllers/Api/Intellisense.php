<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Factories\DatabaseModelFactory;

/**
 * Controller responsible for providing SQL autocompletion (Intellisense) data via the API.
 *
 * This class fetches the database schema information, including tables, views,
 * schemas, and columns, and formats it in a way that can be consumed by the
- * CodeMirror `sql-hint` addon to provide intelligent code completion in the editor.
 *
 * @package App\Controllers\Api
 */
class Intellisense extends BaseController
{
    use ResponseTrait;

    /**
     * Retrieves the database schema for SQL autocompletion.
     *
     * This method instantiates the nDatabaseModelFactory and calls the method responsible
     * for querying the database metadata. It then returns the structured schema
     * as a JSON response, which the frontend uses to power the Intellisense feature.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface The JSON response containing the autocompletion schema.
     */
    public function index()
    {
        $model = DatabaseModelFactory::create();
        $schema = $model->getAutocompletionSchema();
        return $this->respond($schema);
    }
}
