<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Category;
use App\Post;
use App\Tag;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        
        return view("admin.posts.index", compact("posts"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view("admin.posts.create", compact("categories", "tags"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "title"=>"required|string|max:100",
            "content"=>"required|",
            "published"=>"sometimes|accepted",
            "category_id"=>"nullable|exists:categories,id",
            "tags"=>"nullable|exists:tags,id"
        ]);

        $data = $request->all();
        
        $newPost = new Post();
        $newPost->title = $data["title"];
        $newPost->content = $data["content"];
        if (isset($data["published"])) {
            $newPost->published = true;
        }
        $newPost->category_id = $data["category_id"];

        $slug = Str::of($newPost->title)->slug("-");
        $count = 1;

        while( POst::where("slug", $slug)->first()){
            $slug = Str::of($newPost->title)->slug("-") . "-{$count}";
            $count++;
        }

        $newPost->slug = $slug;

        $newPost->save();

        if ( isset($data["tags"]) ){
            $newPost->tags()->sync($data["tags"]);
        }

        return redirect()->route("posts.show", $newPost->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view("admin.posts.show", compact("post"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view("admin.posts.edit", compact("post", "categories", "tags"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            "title"=>"required|string|max:100",
            "content"=>"required|",
            "published"=>"sometimes|accepted",
            "tags"=>"nullable|exists:tags,id"

        ]);

        $data = $request->all();

        if( $post->title != $data["title"]){
            $post->title = $data["title"];

            $slug = Str::of($post->title)->slug("-");

            if($slug != $post->slug) {
                $count = 1;

                while( Post::where("slug", $slug)->first() ) {
                    $slug = Str::of($post->title)->slug("-") . "-{$count}";
                    $count++;
                }

                $post->slug = $slug;
            }
        }

        $post->content = $data["content"];
        $post->category_id = $data["category_id"];
        $post->published = isset($data["published"]);
        

        $post->save();
        if ( isset($data["tags"]) ){
            $post->tags()->sync($data["tags"]);
        }
        return redirect()->route("posts.show", $post->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route("posts.index");
    }
}
