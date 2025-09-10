<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

/**
 * Controller responsible for the user's SQL query history via the API.
 *
 * Allows retrieving the history of queries that have been successfully
 * executed within the current user session.
 *
 * @package App\Controllers\Api
 */
class History extends BaseController
{
    use ResponseTrait;

    /**
     * Retrieves the SQL query history from the user's session.
     *
     * This method requires an active user session (i.e., the user must be connected).
     * If the user is not authenticated, it returns a 401 Unauthorized response.
     * Otherwise, it returns the query history array as a JSON response.
     * If no history exists, it returns an empty array.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface The JSON response containing the history or an error.
     */
    public function get()
    {
        if (!session()->get('is_connected')) {
            return $this->failUnauthorized();
        }
        return $this->respond(session()->get('query_history') ?? []);
    }
}
