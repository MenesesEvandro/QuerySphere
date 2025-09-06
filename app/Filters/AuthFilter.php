<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Authentication filter to protect routes that require a user connection.
 *
 * This 'before' filter checks for an active session flag ('is_connected').
 * If the user is not authenticated, it either redirects them to the login page
 * (for standard requests) or returns a 401 Unauthorized error (for AJAX requests).
 *
 * @package App\Filters
 */
class AuthFilter implements FilterInterface
{
    /**
     * Runs before the controller is executed to verify user authentication.
     *
     * It checks if the 'is_connected' flag is present in the session. If not, it
     * inspects the request type. For AJAX requests, it aborts execution and returns
     * a 401 Unauthorized response. For all other requests, it returns a redirect
     * response to the main login page, preventing access to the intended route.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\HTTP\Response|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (! $session->get('is_connected')) {
            // For AJAX requests, return a 401 error instead of redirecting
            if ($request->isAJAX()) {
                return service('response')->setStatusCode(401)->setBody('Session expired or invalid.');
            }
            // For standard page loads, redirect to the login page
            return redirect()->to(site_url('/'))->with('error', 'Please connect to access this page.');
        }
    }

    /**
     * Runs after the controller has executed and the response is generated.
     *
     * No action is performed in this filter after the response is created.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed here.
    }
}