<?php

namespace App\Http\Controllers;

use App\Model\Posts;
use App\User;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\Types\Nullable;
use DB;
class PostController extends Controller
{
    public function index(Request $request)
    {
        $limit = 5;

        $Posts = Posts::with(['user','comments.user'])
            ->skip(0)
            ->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get()->map(function($posts) {
                $posts->setRelation('comments', $posts->comments->take(5));
                return $posts;
            });

        return $Posts->toJson(JSON_PRETTY_PRINT);
    }
    public function loadMorePosts(Request $request)
    {
        $limit = 5;

        $Posts = Posts::with(['user','comments.user'])
            ->skip($request->post_count)
            ->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get()->map(function($posts) {
                $posts->setRelation('comments', $posts->comments->take(5));
                return $posts;
            });

        return $Posts->toJson(JSON_PRETTY_PRINT);
    }
    public function searchPost(Request $request)
    {
        $page = $request->has('page') ? $request->get('page') : 1;
        $limit = 10;
        $filter = $request->searchText;

        $Posts = Posts::with(['user','comments.user'])
            ->Where('contents','like' ,'%'.$filter.'%')
            ->orWhere('title','like' ,'%'.$filter.'%')
            ->skip(($page - 1) * $limit)
            ->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get()->map(function($posts) {
                $posts->setRelation('comments', $posts->comments->take(5));
                return $posts;
            });

        return $Posts->toJson(JSON_PRETTY_PRINT);
    }
    public function get_total_score(Request $request){
        $query = "select sum(point) as total_score from comments where post_id=".$request->post_id;
        $result = DB::select($query);
        return response()->json(['data'=>$result[0]->total_score]);
    }

    public function get_scores_by_party(Request $request){

        $query = "select sum(point) as republican_total_score from comments
                  where post_id=".$request->post_id." and political_party_id=1";
        $result1 = DB::select($query);

        $query = "select sum(point) as democratic_total_score from comments
                  where post_id=".$request->post_id." and political_party_id=2";
        $result2 = DB::select($query);

        return response()->json([
            'republican_total_score' => $result1[0]->republican_total_score,
            'democratic_total_score' => $result2[0]->democratic_total_score,
            ]);
    }

    public function post_count(Request $request)
    {
        $Posts = Posts::Where('user_id', $auth->id)->get();

        return response()->json([
            'count' => count($Posts)
        ]);
    }
    public function store(Request $request)
    {
        $create_result =  Posts::create($request->all());

        //----- update the reply count of the parent comment or post

        $created_post = Posts::with(['user','comments.user'])->find($create_result->id);
        return $created_post->toJson(JSON_PRETTY_PRINT);
    }

    public function show($id)
    {
    }

    public function update(Request $request, $id)
    {
        $task = Posts::find($id);
        $task->update($request->all());
    }

    public function destroy($id)
    {
        $task = Posts::find($id);
        $task->delete();
    }
}
