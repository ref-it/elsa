<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class LanguageController extends Controller
{
    public function languageSwitch(Request $request)
    {
        $language = $request->input('language');
        Log::info('Language set to (1): ' . $language);
        session()->put('language', $language);
        $lang = Session::get('language');
        $request->session()->save();
        Log::info('Language set to (2): ' . $lang);
        return Redirect::back();
    }
}
