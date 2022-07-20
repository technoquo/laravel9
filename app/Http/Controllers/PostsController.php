<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // DB::transaction(function () {
            
        // });

       // $post = Post::orderBy('id','desc')->take(10)->get();

       // $post = Post::where('min_to_read', '!=',2)->get();


        //  Post::chunk(25, function($posts){
        //        foreach ($posts as $post) {
        //          echo $post->title .'</br>';
        //        }

        //  });

        //$post = Post::get()->count();
      //  $post = Post::sum('min_to_read');
      //  $post = Post::avg('min_to_read');
   

        return view('blog.index', [
            'posts' => Post::orderBy('updated_at', 'desc')->get()
        ]);

        // return view('blog.index', [
        //     'posts' => DB::table('posts')->get()
        // ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blog.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        
        //  $post = new Post();
        //  $post->title = $request->title;
        //  $post->excerpt = $request->excerpt;
        //  $post->body = $request->body;
        //  $post->image_path = 'temporary';
        //  $post->is_published = $request->is_published === 'on';
        //  $post->min_to_read = $request->min_to_read;
        //  $post->save();

          Post::create([
            "title" => $request->title,
            "excerpt" => $request->excerpt,
            "body" => $request->body,
            "image_path" => $this->storeImage($request),
            "is_published" => $request->is_published === 'on',
            "min_to_read" => $request->min_to_read

          ]);
         return redirect(route('blog.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * Optional route parameter needs to have a default value eg: $id = 1
     */
    public function show($id)
    {
       // $post = Post::find($id);

       return view('blog.show', [
            'post' => Post::findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    private function storeImage($request){
        $newImageName = uniqid().'-'. $request->title .'.'. $request->image->extension();

        return $request->image->move(public_path('imagens'), $newImageName);

    }
}
