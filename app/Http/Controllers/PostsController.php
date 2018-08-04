<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use DB;
use Illuminate\Support\Facades\Storage;
class PostsController extends Controller
{

    public function __construct()
    {
        //only allow users to view blogs and show blog information, 
        //Must login to access any other actions
        $this->middleware('auth', ['except' => ['index','show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //retrive data in asc or desc order
        // $post = Post::orderBy('created_at','desc')->get();

        //retrieve all data 
        // $post = Post::all();
        //return the post view


        $posts = POST::orderBy('created_at','desc')->paginate(3);
        return view('posts.index')->with('posts',$posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request,[
            'title'=>'required',
            'body'=>'required',
            //give users a option to load image with max size under 2 MB
            'cover_image' => 'image|nullable|max:1999'
        ]);

        //Handle file upload
        if($request->hasFile('cover_image')){
            //get file name with extension
            $fileNameWithExt = $request->file('cover_image')->getClientOriginalName();
            //Get just fileName
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

            //get just extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //file name to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //upload image
            $path = $request->file('cover_image')->storeAs('public/cover_image',$fileNameToStore);
        } else{
            $fileNameToStore = 'noimage.jpg';
        }


        //Create POSTS
        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        return redirect('/posts')->with('success', 'Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //return post based on ID
        $posts = Post::find($id);
        return view('posts.show')->with('posts',$posts);
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

        $post = Post::find($id);
        //check for correct user
        if(auth()->user()->id !==$post->user_id){
            return redirect('/posts')->with('error','Unauthorized page');

        }
        return view('posts.edit')->with('post',$post);

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
        //Update by finding the id
        $this->validate($request,[
            'title'=>'required',
            'body'=>'required'
        ]);

                 //Handle file upload
        if($request->hasFile('cover_image')){
            //get file name with extension
            $fileNameWithExt = $request->file('cover_image')->getClientOriginalName();
            //Get just fileName
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

            //get just extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //file name to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //upload image
            $path = $request->file('cover_image')->storeAs('public/cover_image',$fileNameToStore);
        } 


        //Create POSTS
        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');

        if($request->hasFile('cover_image')){
            $post->cover_image = $fileNameToStore;
        }

        $post->save();

        return redirect('/posts')->with('success', 'Post Updated');
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
        $post = Post::find($id);

                //check for correct user
                if(auth()->user()->id !==$post->user_id){
                    return redirect('/posts')->with('error','Unauthorized page');
        
                }

                if($post->cover_image != 'noimage.jpg'){
                    //Delete Image
                    Storage::delete('public/cover_image/'.$post->cover_image);
                }
        $post->delete();
        return redirect('/posts')->with('success', 'Post Removed');

    }
}
