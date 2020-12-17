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
        $page = $request->has('page') ? $request->get('page') : 1;
        $limit = 10;
        $filter = $request->searchText;

        if ($filter == null) {
            $Posts = Posts::with(['user','comments.user'])
                ->skip(($page - 1) * $limit)
                ->limit($limit)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $Posts = Posts::with(['user'])
                ->Where(['content','like' ,'%'.$filter.'%'])
                ->skip(($page - 1) * $limit)
                ->limit($limit)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return $Posts->toJson(JSON_PRETTY_PRINT);
    }

    public function get_total_score(Request $request){
        $query = "select  id, sum(point) as total_point
                from (select * from comments order by parent_id id) children_sorted,
                (select @pv := 1) initialisation
                where   find_in_set(parent_id, @pv) > 0
                and @pv := concat(@pv, ',', id)";
        $result = DB::select($query);
        return response()->json(['data'=>$result[0]->total_point]);
    }

    public function get_scores_by_party(Request $request){
        $query ="select  id, sum(point) as total_point
                 from (select * from comments where political_party_id = 1 order by parent_id id) children_sorted,
                 (select @pv := 1) initialisation where find_in_set(parent_id, @pv) > 0 and @pv := concat(@pv, ',', id)";
        $result1 = DB::select($query);

        $query ="select  id, sum(point) as total_point
                 from (select * from comments where political_party_id = 2 order by parent_id id) children_sorted,
                 (select @pv := 1) initialisation where find_in_set(parent_id, @pv) > 0 and @pv := concat(@pv, ',', id)";
        $result2 = DB::select($query);

        return response()->json(['data'=>[
            'Democratic' => $result1[0]->total_point,
            'Republican' => $result2[0]->total_point,
            ]]);
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
        return Posts::create($request->all());
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
