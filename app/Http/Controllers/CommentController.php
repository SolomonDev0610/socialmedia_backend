<?php

namespace App\Http\Controllers\Api;

use App\Models\Comments;
use App\User;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\Types\Nullable;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        try {
            $auth = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
        $page = $request->has('page') ? $request->get('page') : 1;
        $limit = 10;

        $Comments = Comments::with(['user'])
            ->Where('post_id', $request->post_id)
            ->skip(($page - 1) * $limit)
            ->limit($limit)
            ->orderBy('point', 'asc')->get();

        return $Comments->toJson(JSON_PRETTY_PRINT);
    }

    public function getChildComments(Request $request){
        try {
            $auth = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
        $page = $request->has('page') ? $request->get('page') : 1;
        $limit = 10;

        $Comments = Comments::with(['user'])
            ->Where('parent_id', $request->post_id)
            ->Where('depth', $request->depth)
            ->skip(($page - 1) * $limit)
            ->limit($limit)
            ->orderBy('point', 'asc')->get();

        return $Comments->toJson(JSON_PRETTY_PRINT);
    }

    public function upVote(Request $request){
        try {
            $auth = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        $comment = Comments::find($request->comment_id);
        if($comment->political_party_id != $auth->political_party_id)
            $comment->point += 4;
        else
            $comment->point += 1;
        $comment->like_count ++;

        $comment->save();

        return response()->json(['data'=>['success' => true]]);
    }

    public function downVote(Request $request){
        try {
            $auth = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        $comment = Comments::find($request->comment_id);
        if($comment->political_party_id == $auth->political_party_id)
            $comment->point -= 1;

        $comment->like_count --;

        $comment->save();

        return response()->json(['data'=>['success' => true]]);
    }

    public function Comment_count()
    {
        try {
            $auth = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        $Comments = Comments::Where('user_id', $auth->id)->get();

        return response()->json([
            'count' => count($Comments)
        ]);
    }

    public function store(Request $request)
    {
        return Comments::create($request->all());
    }

    public function show($id)
    {
    }

    public function update(Request $request, $id)
    {
        $comment= Comments::find($id);
        $comment->update($request->all());
    }

    public function destroy($id)
    {
        $comment = Comments::find($id);
        $comment->delete();
    }
}
