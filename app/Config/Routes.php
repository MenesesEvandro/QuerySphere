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
        $routes->post('session/database', 'Api\SessionManager::setActiveDatabase');

        // Shared Queries
        $routes->get('shared-queries', 'Api\SharedQueries::index');
        $routes->post('shared-queries', 'Api\SharedQueries::create');
        $routes->delete('shared-queries/(:segment)', 'Api\SharedQueries::delete/$1');

        // Object Source
        $routes->get('objects/source', 'Api\ObjectExplorer::getObjectSource');
    });
});