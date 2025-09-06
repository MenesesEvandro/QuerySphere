<?php

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * The main application controller.
 *
 * This controller is responsible for displaying the primary user interface
 * after a successful database connection. It gathers session and database
 * information to bootstrap the main view.
 *
 * @package App.Controllers
 */
class Main extends BaseController
{
    /**
     * Displays the main application interface with current connection data.
     *
     * It fetches the current connection details (host, user, active database)
     * from the session and queries the model to get a list of all available databases.
     * This data is then passed to the 'main/index' view to render the UI components,
     * such as the navigation bar and the database switcher dropdown.
     *
     * @return string Renders the 'main/index' view.
     */
    public function index()
    {
        $model = new \App\Models\SqlServerModel();

        $data = [
            'db_host' => $this->session->get('db_host'),
            'db_user' => $this->session->get('db_user'),
            'db_database' => $this->session->get('db_database'),
            'databases' => $model->getDatabases(),
        ];

        return view('main/index', $data);
    }
}
