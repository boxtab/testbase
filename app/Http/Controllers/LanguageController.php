<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LanguageController extends Controller
{
    /**
     * Сохранение выбранного языка для текущего пользователя.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        auth()->user()->update(['language' => $request->language]);
        return back()->withInput();
    }
}
