<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Получение пути, по которому пользователь должен быть перенаправлен, если он не прошел проверку подлинности.
     */
    protected function redirectTo(Request $request): ?string
    {
        if($request->expectsJson()){
            return null;
        }else{
            if($request->is('admin') || $request->is('admin/*')){
                return route("admin.login");
            }else{
                return route('home');
            }
        };
    }
}