<?php
use CodeIgniter\Router\RouteCollection;

/**
 * @var
 * RouteCollection $routes
 */
// --- ROTAS PÚBLICAS --- //
$routes->get('/', 'Connection::index');
$routes->post('connect', 'Connection::connect');
$routes->get('logout', 'Connection::logout');
$routes->get('lang/(:any)', 'Language::set/$1');
$routes->get('check', 'ServerCheck::index');

// --- GRUPO DE ROTAS PROTEGIDAS --- //
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('main', 'Main::index');

    // Rotas da API
    $routes->group('api', static function ($routes) {
        // Object Explorer
        $routes->get('objects/databases', 'Api\ObjectExplorer::databases');
        $routes->get('objects/children', 'Api\ObjectExplorer::children');

        // Query Execution
        $routes->post('query/execute', 'Api\Query::execute');
        $routes->post('query/explain', 'Api\Query::explain');

        // Export
        $routes->get('export/csv', 'Api\Export::csv');
        $routes->get('export/json', 'Api\Export::json');

        // History
        $routes->get('history/get', 'Api\History::get');

        // Intellisense
        $routes->get('intellisense', 'Api\Intellisense::index');

        // Object Search
        $routes->get('objects/search', 'Api\ObjectExplorer::search');

        //Set Active Database
        $routes->post(
            'session/database',
            'Api\SessionManager::setActiveDatabase',
        );

        // Shared Queries
        $routes->get('shared-queries', 'Api\SharedQueries::index');
        $routes->post('shared-queries', 'Api\SharedQueries::create');
        $routes->delete(
            'shared-queries/(:segment)',
            'Api\SharedQueries::delete/$1',
        );

        // Object Source
        $routes->get('objects/source', 'Api\ObjectExplorer::getObjectSource');

        // Query Templates
        $routes->get('templates', 'Api\QueryTemplates::index');
        $routes->get(
            'templates/get/(:segment)/(:segment)',
            'Api\QueryTemplates::get/$1/$2',
        );

        // Columns for Scripting
        $routes->get(
            'objects/columns',
            'Api\ObjectExplorer::getColumnsForScripting',
        );

        // SQL Server Agent
        $routes->get('agent/jobs', 'Api\Agent::jobs');
        $routes->post('agent/start', 'Api\Agent::startJob');
        $routes->post('agent/stop', 'Api\Agent::stopJob');
        $routes->get('agent/history/(:any)', 'Api\Agent::jobHistory/$1');

        // MySQL Events
        $routes->get('mysql/events', 'Api\MySqlEvents::index');
        $routes->post('mysql/events/toggle', 'Api\MySqlEvents::toggleStatus');
        $routes->get(
            'mysql/events/definition/(:any)',
            'Api\MySqlEvents::definition/$1',
        );
    });
});
