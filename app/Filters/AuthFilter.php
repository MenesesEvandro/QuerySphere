<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Filtro de autenticação para proteger rotas que exigem conexão do usuário.
 *
 * Redireciona ou retorna erro 401 caso a sessão não esteja conectada.
 */
class AuthFilter implements FilterInterface
{
    /**
     * Executa antes da requisição ser processada.
     *
     * Verifica se o usuário está conectado na sessão. Caso contrário, retorna erro ou redireciona.
     *
     * @param RequestInterface $request
     * @param array|null $arguments
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (! $session->get('is_connected')) {
            if ($request->isAJAX()) {
                return service('response')->setStatusCode(401)->setBody('Sessão expirada ou inválida.');
            }
            return redirect()->to(site_url('/'))->with('error', 'Por favor, conecte-se para acessar esta página.');
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
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}