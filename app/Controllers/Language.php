<?php

namespace App\Controllers;

/**
 * Controlador responsável pela definição do idioma da aplicação.
 *
 * Permite alterar o idioma da sessão do usuário.
 */
class Language extends BaseController
{
    /**
     * Define o idioma da sessão do usuário.
     *
     * @param string $locale Código do idioma a ser definido.
     * @return mixed Redireciona de volta à página anterior.
     */
    public function set(string $locale)
    {
        if (! in_array($locale, config('App')->supportedLocales)) {
            return redirect()->back()->with('error', lang('language_not_supported'));
        }

        session()->set('locale', $locale);
        return redirect()->back();
    }
}