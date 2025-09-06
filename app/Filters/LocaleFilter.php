<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Filtro responsável por definir o idioma (locale) da sessão do usuário.
 *
 * Aplica o locale armazenado na sessão à requisição.
 */
class LocaleFilter implements FilterInterface
{
    /**
     * Executa antes da requisição ser processada.
     *
     * Define o locale da requisição com base na sessão do usuário.
     *
     * @param RequestInterface $request
     * @param array|null $arguments
     * @return void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if ($session->has('locale')) {
            $locale = $session->get('locale');
            $request->setLocale($locale);
            service('language')->setLocale($locale);
        }
    }
    
    /**
     * Executa após a resposta ser gerada (não utilizado neste filtro).
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array|null $arguments
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}