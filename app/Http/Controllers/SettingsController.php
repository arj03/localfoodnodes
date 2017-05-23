<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Change locale.
     *
     * @param Request $request
     * @param $locale
     */
    public function changeLocale(Request $request, $locale)
    {
        if ($locale) {
            \Session::put('locale', $locale);
            \App::setLocale($locale);
        }

        return redirect()->back();
    }
}
