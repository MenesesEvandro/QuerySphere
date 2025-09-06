<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Filter responsible for setting the application's locale for the current request.
 *
 * This 'before' filter checks the user's session for a stored locale preference.
 * If found, it applies this locale to both the incoming Request object and the
 * global Language service, ensuring that all subsequent operations in the
 * request lifecycle (controllers, views, helpers) use the correct language.
 *
 * @package App\Filters
 */
class LocaleFilter implements FilterInterface
{
    /**
     * Runs before the main controller is executed.
     *
     * Checks the session for the 'locale' variable. If it's set, this method
     * updates the locale for the current Request and synchronizes the Language
     * service to the same locale. This ensures consistency for all lang() calls,
     * whether they are in controllers or views.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if ($session->has('locale')) {
            $locale = $session->get('locale');

            // Set the locale for the current request
            $request->setLocale($locale);

            // Also explicitly set the locale for the Language service
            service('language')->setLocale($locale);
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
    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null,
    ) {
        // No action needed here.
    }
}
