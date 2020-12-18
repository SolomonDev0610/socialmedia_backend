<?php

namespace App\Http\Controllers;

use App\Model\Comments;
use App\User;
use DB;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\Types\Nullable;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->has('page') ? $request->get('page') : 1;
        $limit = 10;

        $Comments = Comments::with(['user'])
            ->Where('post_id', $request->post_id)
            ->skip(($page - 1) * $limit)
            ->limit($limit)
            ->orderBy('point', 'asc')->get();

        return $Comments->toJson(JSON_PRETTY_PRINT);
    }
    public function getAllLevelChildIds(Request $request){

        $query = "select * from comments where parent_id = ".$request->parent_id."
                  union select * from comments where parent_id in(select id from comments where parent_id=".$request->parent_id.")";

        $query_result =DB::select($query);
        $result = [];
        foreach($query_result as $item){
            array_push($result, $item->id);
        }
        return response()->json(['child_ids'=> $result]);
    }
    public function getChildComments(Request $request){
        $page = $request->has('page') ? $request->get('page') : 1;
        $limit = 10;

        $Comments = Comments::with(['user'])
            ->Where('parent_id', $request->parent_id)
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
        }catch(\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e){
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
        $create_result = Comments::create($request->all());

        $parent_comment= Comments::find($request->parent_id);
        Comments::where('id', $request->parent_id)->limit(1)->update([
            'child_count' => $parent_comment->child_count + 1]);

        return $create_result->toJson(JSON_PRETTY_PRINT);
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
