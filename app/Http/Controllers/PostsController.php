<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PostFormRequest;

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
            'posts' => Post::orderBy('updated_at', 'desc')->paginate(20)
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
    public function store(PostFormRequest $request)
    {
       
      
        //  $post = new Post();
        //  $post->title = $request->title;
        //  $post->excerpt = $request->excerpt;
        //  $post->body = $request->body;
        //  $post->image_path = 'temporary';
        //  $post->is_published = $request->is_published === 'on';
        //  $post->min_to_read = $request->min_to_read;
        //  $post->save();

        $request->validated();

         $post = Post::create([
            "user_id" => 1, //Auth::id(),
            "title" => $request->title,
            "excerpt" => $request->excerpt,
            "body" => $request->body,
            "image_path" => $this->storeImage($request),
            "is_published" => $request->is_published === 'on',
            "min_to_read" => $request->min_to_read

          ]);

          $post->meta()->create(
            [
             "post_id" => $post->id,
             "meta_description" => $request->meta_description,
             "meta_keywords" => $request->meta_keywords,
             "meta_robots" => $request->meta_robots                
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
        return view('blog.edit', 
        ['post' => Post::where('id', $id)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostFormRequest $request, $id)
    {

       // dd($request->except(['_token', '_method']));
        $request->validated();


     Post::where('id', $id)->update(
            $request->is_published === 'on' 
                ? array_replace($request->except('_token', '_method'), ['is_published' => true])
                : array_replace($request->except('_token', '_method'), ['is_published' => false])
        );

       return redirect(route('blog.index'));
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      Post::destroy($id);
      
      return redirect(route('blog.index'))->with('message','Post has been deleted');

    }
    
    private function storeImage($request){
        $newImageName = uniqid().'-'. $request->title .'.'. $request->image->extension();

        return $request->image->move(public_path('imagens'), $newImageName);

    }
}
