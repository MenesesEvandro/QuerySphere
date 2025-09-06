<?php

namespace App\Controllers;

/**
 * Controller responsible for setting the application's language.
 *
 * This controller provides the backend logic for the user-facing language switcher.
 * It validates the selected language against a list of supported locales and
 * persists the user's choice in the session.
 *
 * @package App\Controllers
 */
class Language extends BaseController
{
    /**
     * Sets the user's language preference in the session.
     *
     * This method is typically called from a URL link. It receives a locale string,
     * validates it against the `supportedLocales` array in the `App` configuration file,
     * and if valid, stores it in the session. It then redirects the user back to
     * the page they came from.
     *
     * @param string $locale The locale code to set (e.g., 'en-US', 'pt-BR').
     * @return \CodeIgniter\HTTP\RedirectResponse It always returns a redirect response.
     */
    public function set(string $locale)
    {
        // Access the 'supportedLocales' config from the App.php file in a safe way
        if (!in_array($locale, config('App')->supportedLocales)) {
            return redirect()->back()->with('error', lang('App.language_not_supported'));
        }

        session()->set('locale', $locale);
        return redirect()->back();
    }
}