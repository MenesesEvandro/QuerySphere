<?php

namespace App\Controllers;

use App\Libraries\ConnectionManager;

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
     * If the user is already connected, redirects to the main page.
     * Otherwise, it displays the connection screen and cleans up any previous connection data from the session.
     *
     * Instead of destroying the entire session (which would clear language preferences),
     * it surgically removes only the keys related to an active database connection,
     * ensuring a clean state for a new login attempt.
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function index()
    {
        if (session()->get('is_connected')) {
            return redirect()->to('main');
        }

        // Surgically remove connection-specific keys, preserving language preference.
        session()->remove([
            'is_connected',
            'db_type',
            'db_host',
            'db_database',
            'db_user',
            'db_password',
            'last_successful_query',
            'query_history',
        ]);
        
        $data['logout_message'] = session()->getFlashdata('logout_message');

        return view('connection/index', $data);
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
            'db_type' => $this->request->getPost('db_type'),
            'host' => $this->request->getPost('host'),
            'database' => $this->request->getPost('database'),
            'user' => $this->request->getPost('user'),
            'password' => $this->request->getPost('password'),
            'port' => $this->request->getPost('port'),
            'trust_cert' => $this->request->getPost('trust_cert')
                ? true
                : false,
        ];

        $model = \App\Factories\DatabaseModelFactory::create(
            $credentials['db_type'],
        );
        $connectionResult = $model->tryConnect($credentials);

        if ($connectionResult['status'] === true) {
            $connManager = new ConnectionManager();
            $connManager->storeCredentials($credentials);
            return redirect()
                ->to('/main')
                ->with('success', lang('App.feedback.connection_success'));
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $connectionResult['message']);
        }
    }

    /**
     * Logs the user out by removing connection-specific session data.
     *
     * This action preserves user preferences like language while ensuring
     * the connection is terminated. It then redirects the user to the
     * initial connection screen.
     *
     * @param string|null $reason Optional reason for logout (e.g., 'inactivity').
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function logout($reason = null)
    {
        session()->remove([
            'is_connected',
            'db_type',
            'db_host',
            'db_database',
            'db_user',
            'db_password',
            'last_successful_query',
            'query_history',
        ]);

        if ($reason === 'inactivity') {
            session()->setFlashdata('logout_message', lang('App.general.session_logged_out_inactivity'));
        } else {
            session()->setFlashdata('logout_message', lang('App.feedback.logout_success'));
        }

        return redirect()->to('/');
    }
}