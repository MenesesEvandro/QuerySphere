<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

/**
 * Manages user session settings via the API.
 *
 * This controller provides endpoints to modify session-specific data,
 * such as changing the active database context for the current user.
 *
 * @package App\Controllers\Api
 */
class SessionManager extends BaseController
{
    use ResponseTrait;

    /**
     * Sets the active database for the current user session.
     *
     * This method receives a database name via a POST request and updates
     * the 'db_database' value in the session. This allows the user to switch
     * the database context for all subsequent operations within the application.
     * It performs validation to ensure the request is a POST and that a database
     * name is provided.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface The JSON response indicating success or failure.
     */
    public function setActiveDatabase()
    {
        // 1. Security Check: Ensures the request method is POST.
        if (!$this->request->is('post')) {
            return $this->fail('Method not allowed.', 405); // 405 Method Not Allowed
        }

        // 2. Robust Data Reading: Uses getVar() which is more flexible.
        $dbName = $this->request->getVar('database');

        // 3. Final Check: Ensures the name is not empty.
        if (empty($dbName)) {
            // Provides a more descriptive error message for debugging.
            return $this->fail('Database name not provided. Please check the submitted data.', 400);
        }

        // 4. Business Logic: Saves to the session.
        session()->set('db_database', $dbName);

        return $this->respond([
            'status' => 'success',
            'message' => 'Database context changed to ' . $dbName
        ]);
    }
}