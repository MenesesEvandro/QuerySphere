<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\ConnectionManager;
use App\Models\SqlServerModel;

/**
 * Controller responsible for managing database connections.
 *
 * This class handles the connection screen, the connection attempt logic,
 * and the user logout process.
 *
 * @package App\Controllers
 */
class Connection extends BaseController
{
    /**
     * Displays the connection screen and cleans up any previous connection data from the session.
     *
     * Instead of destroying the entire session (which would clear language preferences),
     * it surgically removes only the keys related to an active database connection,
     * ensuring a clean state for a new login attempt.
     *
     * @return string Returns the rendered connection view.
     */
    public function index()
    {
        // Surgically remove connection-specific keys, preserving language preference.
        session()->remove([
            'is_connected',
            'db_host',
            'db_database',
            'db_user',
            'db_password',
            'last_successful_query',
            'query_history',
        ]);

        return view('connection/index');
    }

    /**
     * Attempts to connect to the database with the provided credentials.
     *
     * It validates the submitted form data. On success, it stores the credentials
     * securely in the session and redirects the user to the main application interface.
     * On failure, it redirects back to the login form with an error message.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function connect()
    {
        $rules = [
            'host' => 'required',
            'user' => 'required',
            'password' => 'permit_empty',
            'port' =>
                'required|integer|greater_than_equal_to[1]|less_than_equal_to[65535]',
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $credentials = [
            'host' => $this->request->getPost('host'),
            'database' => $this->request->getPost('database'),
            'user' => $this->request->getPost('user'),
            'password' => $this->request->getPost('password'),
            'port' => $this->request->getPost('port'),
            'trust_cert' => $this->request->getPost('trust_cert')
                ? true
                : false,
        ];

        $sqlServerModel = new SqlServerModel();
        $connectionResult = $sqlServerModel->tryConnect($credentials);

        if ($connectionResult['status'] === true) {
            $connManager = new ConnectionManager();
            $connManager->storeCredentials($credentials);
            return redirect()
                ->to('/main')
                ->with('success', lang('App.connection_success'));
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $connectionResult['message']);
        }
    }

    /**
     * Logs the user out by destroying their entire session.
     *
     * This action completely clears all session data and redirects the user
     * back to the initial connection screen.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', lang('App.logout_success'));
    }
}
