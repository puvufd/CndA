<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Отображение списка ресурсов.
     */
    public function allCats(Request $req){
        $data = Category::all();

        return view('admin.category.allCat', compact('data'));
    }
    public function createCat(Request $request)
    {
        return view('admin.category.createCat');
    }

    /**
     * Отображение формы для создания нового ресурса.
     */

    /**
     * Сохранение вновь созданного ресурса в хранилище.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "cat_name" => "required"
        ]);

        Category::create($data);

        return redirect()->back()->with('alert', "категория успешно создана");
    }

    /**
     * Отображение указанного ресурс.
     */

    public function editCat(Category $category, $id)
    {
        $data = Category::where('id', $id)->first();

        return view('admin.category.editCat', compact('data'));
    }

    /**
     * Обновление указанного ресурса в хранилище.
     */
    public function updateCat(Request $request)
    {
        $data = $request->validate([
            "cat_name" => "required"
        ]);

        $id = $request->id;
        Category::where('id', $id)->update($data);

        return redirect()->route('cat.all')->with('alert', 'категория была успешно обновлена');
    }

    /**
     * Удаление указанного ресурса из хранилища.
     */
    public function delete(Request $req, $id)
    {
        Category::where('id', $id)->delete();
        
        return redirect()->route('cat.all')->with('alert' , 'категория была успешно удалена');
    }
}
