<?php

namespace App\Http\Controllers;

use App\Model\Comments;
use App\Model\Posts;
use App\Model\CommentVoteUsers;
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
            ->Where('depth', 1)
            ->Where('post_id', $request->post_id)
            ->skip(($page - 1) * $limit)
            ->limit($limit)
            ->orderBy('point', 'desc')->get();

        return $Comments->toJson(JSON_PRETTY_PRINT);
    }
    public function loadMoreComments(Request $request){
        $limit = 5;

        $Comments = Comments::with(['user'])
            ->Where('depth', 1)
            ->Where('post_id', $request->post_id)
            ->skip($request->comment_count)
            ->limit($limit)
            ->orderBy('point', 'asc')->get();

        return $Comments->toJson(JSON_PRETTY_PRINT);
    }
    public function loadMoreChildComments(Request $request){
        $limit = 5;

        $Comments = Comments::with(['user'])
            ->Where('parent_id', $request->comment_id)
            ->skip($request->comment_count)
            ->limit(5)
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
        $limit = 5;

        $Comments = Comments::with(['user'])
            ->Where('parent_id', $request->parent_id)
            ->skip(($page - 1) * $limit)
            ->limit($limit)
            ->orderBy('point', 'desc')->get();

        return $Comments->toJson(JSON_PRETTY_PRINT);
    }

    public function upVote(Request $request){

        //add the user into vote_user list
        $comment_vote_user= CommentVoteUsers::where('user_id', $request->user_id)
            ->where('comment_id', $request->comment_id)
            ->get();
        if(count($comment_vote_user) > 0){
            //update the point of comment
            $point = -1 * $comment_vote_user[0]->point;
            $comment = Comments::find($request->comment_id);
            $comment->point += $point;
            $comment->save();

            //update the point of post
            $post= Posts::find($request->post_id);
            $post->total_point += $point;
            if($comment->political_party_id == 1)
                $post->point1 += $point;
            if($comment->political_party_id == 2)
                $post->point2 += $point;
            $post->save();

            //update the point of user
            $sum_query = "select sum(total_point) as user_total_point from posts where user_id=".$request->user_id;
            $sum_result = DB::select($sum_query);

            $post= User::find($request->user_id);
            $post->earned_score = $sum_result[0]->user_total_point;
            $post->save();

            //add the user in the comment_vote_list
            CommentVoteUsers::where('user_id', $request->user_id)
                ->where('comment_id', $request->comment_id)
                ->delete();
            return response()->json(['data'=>$point]);
        }else{
            //update the point of comment
            $comment = Comments::find($request->comment_id);
            $comment->point += $request->add_point;
            $comment->save();

            //update the point of post
            $post= Posts::find($request->post_id);
            $post->total_point += $request->add_point;
            if($comment->political_party_id == 1)
                $post->point1 += $request->add_point;
            if($comment->political_party_id == 2)
                $post->point2 += $request->add_point;
            $post->save();

            //update the point of user
            $sum_query = "select sum(total_point) as user_total_point from posts where user_id=".$request->user_id;
            $sum_result = DB::select($sum_query);

            $post= User::find($request->user_id);
            $post->earned_score = $sum_result[0]->user_total_point;
            $post->save();

            //add the user in the comment_vote_list
            $values = array('comment_id' => $request->comment_id,
                'user_id' => $request->user_id,
                'point' => $request->add_point,
                );

            DB::table('comment_vote_users')->insert($values);
            return response()->json(['data'=>'success']);
        }

    }

    public function downVote(Request $request){

        $comment = Comments::find($request->comment_id);
        $comment->point += $request->add_point;
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

        //----- update the reply count of the parent comment or post
        if($request->depth != 1){
            $parent_comment= Comments::find($request->parent_id);
            Comments::where('id', $request->parent_id)->limit(1)->update([
                'child_count' => $parent_comment->child_count + 1]);
        }else{
            $post= Posts::find($request->post_id);
            Posts::where('id', $request->post_id)->limit(1)->update([
                'comment_count' => $post->comment_count + 1]);
        }

        $created_comment = Comments::with(['user'])->find($create_result->id);

        return $created_comment->toJson(JSON_PRETTY_PRINT);
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
        //----- update the reply count of the parent comment or post
        if($comment->depth != 1){
            $parent_comment= Comments::find($comment->parent_id);
            Comments::where('id', $comment->parent_id)->limit(1)->update([
                'child_count' => $parent_comment->child_count - 1]);
        }else{
            $post= Posts::find($comment->post_id);
            Posts::where('id', $comment->post_id)->limit(1)->update([
                'comment_count' => $post->comment_count - 1]);
        }

        $comment->delete();
    }
}
