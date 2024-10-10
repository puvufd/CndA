<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function login(Request $request)
    {
        if($request->method() == "POST"){
            $data = $request->only(['email', 'password']);

            if(Auth::guard('admin')->attempt($data)){
                return redirect()->route('admin.dashboard');
            }else{
                return redirect()->back()->with("alert", "Пожалуйста, введите действительные учетные данные");
            }
        }

        return view('admin.login');
    }

    public function index()
    {
        return view('admin/dashboard');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login')->with('alert', '👮‍♂️ Logged OUT 🥱');

    }
    
    /**
     * Отобразение формы для создания нового ресурса.
     */
    public function create()
    {
        //
    }

    /**
     * Сохранение вновь созданого ресурса в хранилище.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Отображение указанного ресурса.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Отображение формы для редактирования указанного ресурса.
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Обновление указаного ресурса в хранилище.
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Удаление указанного ресурса из хранилища.
     */
    public function destroy(Admin $admin)
    {
        //
    }
}
