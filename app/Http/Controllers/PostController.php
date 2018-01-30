<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $currentUser;
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
        $this->currentUser = $this->jwt->user();
    }

    /**
     * Return whole list of posts<br>
     * No authorization required
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize('create', Post::class);
        $posts = Post::where('user_id', $this->currentUser->id)->select('id', 'user_id', 'subject', 'created_at', 'updated_at')->paginate(10);

        foreach ($posts as &$post) {
            $user = User::where('id', $post->user_id)->select('id', 'name', 'email')->first();
            $post->user = $user;
        }

        return response()->json($posts);
    }

    /**
     * Return whole list of posts<br>
     * No authorization required
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details(Request $request, $post_id)
    {
        $this->authorize('create', Post::class);
        $posts = Post::where('id', $post_id)->where('user_id', $this->currentUser->id)->first();
        if($posts){
            $posts->user = User::where('id', $posts->user_id)->select('id', 'name', 'email')->first();
        }
               
        return response()->json($posts);
    }

    /**
     * Create new post<br>
     * Only if the Posts' policy allows it
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $rules = array(
            'subject'   => 'required|string',
            'body'      => 'required|string',
        );
        $this->validate($request->instance(), $rules);

        $this->authorize('create', Post::class);

        $post = new Post();
        $post->subject = Input::get('subject');
        $post->body = Input::get('body');
        Auth::user()->posts()->save($post);

        return response()->json($post);
    }

    /**
     * Update post<br>
     * Only if the Posts' policy allows it
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $post_id)
    {
        $rules = array(
            'subject'   => 'required|string',
            'body'      => 'required|string',
        );
        $this->validate($request->instance(), $rules);

        $post = Post::find($post_id);
        $this->authorize('update', $post);

        try {
            $post->subject = Input::get('subject');
            $post->body = Input::get('body');
            $post->save();

            return response()->json($post);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Post not updated',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}