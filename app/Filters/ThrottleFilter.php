<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class ThrottleFilter implements FilterInterface
{
    /**
     * This is called before the controller is executed.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $throttler = Services::throttler();

        if ($throttler->check($request->getIPAddress(), config('Throttler')->maxRequests, config('Throttler')->timeInterval) === false) {
            return Services::response()->setStatusCode(429);
        }
    }

    /**
     * This is called after the controller is executed.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nada a fazer após a requisição.
    }
}
