<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    /**
     * Отображение списка ресурсов.
     */

    public function allDrafts()
    {
        $allDrafts = Post::where('status' , 0)->paginate(50);
        return view('admin.post.drafts', compact('allDrafts'));
    }
    public function allPosts()
    {
        $allposts = Post::where('status', 1)->paginate(100);
        return view('admin.post.allPost', compact('allposts'));
    }
    public function makePublic($postId)
    {   $post = Post::findOrFail($postId);
        $post->update(['status' => 1]);
        return redirect()->back()->with('alert', 'Запись успешно опубликовано 🎉');
    }
    public function makeDraft($postId)
    {   $post = Post::findOrFail($postId);
        $post->update(['status' => 0]);
        return redirect()->back()->with('alert', 'Запись успешно добавлена в черновики 🎁');
    }
    public function tempDelete($postId)
    {   $post = Post::findOrFail($postId);
        $post->update(['status' => 2]);
        return redirect()->back()->with('alert', '🚮 Запись отправлено в корзину 🚮');
    }
    public function permanentDelete($postId)
    {   $post = Post::findOrFail($postId);
        $post->delete();
        return redirect()->back()->with('alert', '🗑 Запись удалена безвозвратно 🗑');
    }

    // recycle post page

    public function openRecyclePostPage (Request $request)
    {
        $allposts = Post::where('status', 2)->paginate(20);
        return view('admin.recycle.post', compact('allposts'));
    } 
    /**
     * Show the form for creating a new resource.
     */

    public function createPost()
    {
        $data = Category::all();
        return view('admin.post.createPost', compact('data'));
    }

    public function createNewPost(Request $req)
    {
        $data = $req->validate([
            "title"=>"nullable",
            "date"=>"nullable",
            "video_type"=>"nullable",
            'category_id' => 'nullable|exists:categories,id',
            'movie_info' => 'nullable', // Загрузочная информацию о записе
            'screenshots' => 'nullable', // Загрузочное фото о записе
            'download_description' => 'required',
            "thumbnail"=>"nullable|image|mimes:jpeg,png,gif,svg",
            "meta_title"=>"nullable",
            "meta_description"=>"nullable",
            "meta_keywords"=>"nullable",
        ]);

        // for thumbnail 
        if($req->hasFile('thumbnail')){
            $imageName = time() . '.' . $req->thumbnail->getClientOriginalExtension();
            $req->thumbnail->move(public_path('/thumbnails'), $imageName);
            $data['thumbnail']=$imageName;
        }

        // if($data['screenshots']){
        //     $description = $data['screenshots'];

        //     $dom = new \DomDocument();
        //     libxml_use_internal_errors(true); // Suppress any potential HTML parsing errors
        //     $dom->loadHtml($description, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    
        //     $images = $dom->getElementsByTagName('img');
    
        //     foreach ($images as $key => $img) {
        //         $imageData = base64_decode(explode(',', explode(';', $img->getAttribute('src'))[1])[1]);
        //         $image_name = "/upload/" . time() . $key . '.png';
        //         file_put_contents(public_path() . $image_name, $imageData);
    
        //         $img->removeAttribute('src');
        //         $img->setAttribute('src', $image_name);
        //     }
    
        //     $description = $dom->saveHTML();
        //     $data['screenshots'] = $description;
        // }
        // Обрабатываемая загрузка изображения в поле описания

        $screenshots = $data['screenshots'];
        $dom = new \DomDocument();
        libxml_use_internal_errors(true); // Подавляемая любыми потенциальные ошибками синтаксического анализа HTML
        $dom->loadHtml($screenshots, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $images = $dom->getElementsByTagName('img');

        foreach ($images as $index => $image) {
            $imageSrc  = $image->getAttribute('src');
            list($type, $imageSrc ) = explode(';', $imageSrc );
            list(, $imageSrc ) = explode(',', $imageSrc );
            $imageData = base64_decode($imageSrc );

            $image_name = "/upload/" . time() . Str::random(10) . '.png';
            Storage::disk('public')->put($image_name, $imageData);

            $image->removeAttribute('src');
            $image->setAttribute('src', asset('storage' . $image_name));
        }

        $screenshots = $dom->saveHTML();
        $data['screenshots'] = $screenshots;
        Post::create($data);
        return redirect()->route('post.all')->with("success","Запись успешно создана");

    }

    /**
     * Сохранение вновь созданного ресурса в хранилище.
     */
    public function editPost($id)
    {   
        
        $data = Post::where('id', $id)->first();
        $cats = Category::all();
        return view('admin.post.editPost', compact('data', 'cats'));
    }

    /**
     * Отображение указанного ресурса.
     */
    public function updatePost(Request $request, $id)
    {
        $data = $request->validate([
            "title" => "nullable",
            "date" => "nullable",
            "video_type" => "nullable",
            'category_id' => 'nullable|exists:categories,id',
            'movie_info' => 'nullable',
            'screenshots' => 'nullable', // Ensure screenshots field is nullable
            'download_description' => 'required',
            "thumbnail" => "nullable|image|mimes:jpeg,png,gif,svg",
            "meta_title" => "nullable",
            "meta_description" => "nullable",
            "meta_keywords" => "nullable",
        ]);
    
        $post = Post::findOrFail($id);
    
        // Обрабатываемое обновление миниатюр
        if ($request->hasFile('thumbnail')) {
            $existingImage = $post->thumbnail;
            $imagePath = public_path('/thumbnails' . '/' . $existingImage);
    
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
    
            $imageName = time() . '.' . $request->thumbnail->getClientOriginalExtension();
            $request->thumbnail->move(public_path('/thumbnails'), $imageName);
            $data['thumbnail'] = $imageName;
        }
    
        // Обрабатывание обновления фото
        if (isset($data['screenshots'])) {
            // Проверка, имеет ли значение поле screenshots (скриншоты/фото)
            if ($data['screenshots']) {
                $screenshots = $data['screenshots'];

                $dom = new \DomDocument();
                libxml_use_internal_errors(true); // Подавляемые любые потенциальные ошибки синтаксического анализа HTML
                $dom->loadHtml($screenshots, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
                $images = $dom->getElementsByTagName('img');
    
                foreach ($images as $index => $image) {
                    if(strpos($image->getAttribute('src'),'data:image/') ===0){
                        $imageSrc = $image->getAttribute('src');
                        list($type, $imageSrc) = explode(';', $imageSrc);
                        list(, $imageSrc) = explode(',', $imageSrc);
                        $imageData = base64_decode($imageSrc);
        
                        $image_name = 'upload/' . time() . Str::random(10) . '.png';
                        Storage::disk('public')->put($image_name, $imageData);
        
                        $image->removeAttribute('src');
                        $image->setAttribute('src', asset('storage/' . $image_name));
                    }
                    
                }
    
                $screenshots = $dom->saveHTML();
                $data['screenshots'] = $screenshots;
            } else {
                // Если поле скриншоты пусто, устанавливается значение null или любое другое значение по умолчанию
                $data['screenshots'] = null; // Или устанавливается любое значение по умолчанию в соответствии с логикой сайта
            }
        } else {
            // Если поле фото в форме не задано, устанавливается значение null или любое другое значение по умолчанию
            $data['screenshots'] = null; // Или устанавливается любое значение по умолчанию в соответствии с логикой сайта
        }
    
        // Обновление публикации с помощью проверенных данных
        $post->update($data);
    
        return redirect()->back()->with('alert', 'Post Updated Successfully');
    }
    

    /**
     * Отображение формы для редактирования указанного ресурса.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Обновление указанного ресурса в хранилище.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Удаление указанного ресурса из хранилища.
     */
    public function destroy(Post $post)
    {
        //
    }
}