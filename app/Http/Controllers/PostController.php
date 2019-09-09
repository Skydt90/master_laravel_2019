<?php

namespace App\Http\Controllers;

use App\BlogPost;
use App\Http\Requests\StorePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    
    public function index()
    {
        //return view('posts.index', ['posts' => BlogPost::all()]);
        /* DB::connection()->enableQueryLog();

        $posts = BlogPost::with('comments')->get(); //all();

        foreach($posts as $post)
        {
            foreach($post->comments as $comment)
            {
                echo $comment->content;
            }
        }

        dd(DB::getQueryLog()); */

        // comments count for each bp
        return view('posts.index')->with('posts', BlogPost::withCount('comments')->get());
    }


    public function show($id)
    {
        return view('posts.show')->with('post', BlogPost::with('comments')->findOrFail($id)); // ['post' => BlogPost::findOrFail($id)]);
    }


    public function create()
    {
        return view('posts.create');
    }


    public function store(StorePost $request)
    {
        $data = $request->validated();
 
        if($post = BlogPost::create($data)) {
            session()->flash('success', 'Post was created!');
        }
        return redirect()->route('post.show', ['post' => $post->id]);
    }


    public function edit($id)
    {
        return view('posts.edit')->with('post', BlogPost::findOrFail($id));
    }


    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrFail($id);
        $data = $request->validated();
        $post->fill($data);

        if($post->save()) {
            session()->flash('success', 'Post was updated successfully!');
        }
        return redirect()->route('post.show', ['post' => $post->id]);
    }

    
    public function destroy($id)
    {
        if(BlogPost::destroy($id)) {
            session()->flash('success', 'Post was deleted!');
        }
        return redirect()->route('post.index');

    }
}
