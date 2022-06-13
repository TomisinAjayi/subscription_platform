<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\CreateUser;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllBlogs()
    {
        //
        $blogPost = BlogPost::get()->toJson(JSON_PRETTY_PRINT);
        return response($blogPost, 200);
    }

    public function getBlog($id)
    {
        //
        if(BlogPost::where('id', $id)->exists()) {
            $blogPost = BlogPost::where('id', $id)->get()->toJson(JSON_PRETTY_PRINT);
            return response($blogPost, 200);
        } else {
            return response()->json([
                "message" => "Blog post not found"
            ], 404);
        }
    }

    public function create(Request $request)
    {
        //
        $blogPost = new BlogPost;
        $blogPost->title = $request->title;
        $blogPost->description = $request->description;
        $blogPost->body = $request->body;
        $blogPost->save();
    
        $users = CreateUser::whereIn("id", $request->ids)->get();
        Mail::to($users)->send(new UserEmail());

        return response()->json([
            "message" => "blog record created",
            "success"=> "Send email successfully."
        ], 201);
        }
    }


