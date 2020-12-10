<?php

namespace App\GraphQL\Query;
use App\Model\Messages;
use App\GraphQL\Type\ConversationType;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
class MessageQuery extends Query {

	public $model = "App\Model\Messages";

	protected $attributes = [
		'name' => 'messages',
		'description' => 'Query all the Messages'
	];

    public function args(): array {
        $args = [
//            [ 'name' => 'token', 'type' => Type::nonNull(Type::string()) ],
            [ 'name' => 'id',    'type' => Type::ID()      ],
            [ 'name' => 'limit', 'type' => Type::int()     ],
            [ 'name' => 'page',  'type' => Type::int()     ],
            [ 'name' => 'sort',  'type' => Type::string()  ],
            [ 'name' => 'dir',   'type' => Type::string()  ],
            [ 'name' => 'trash', 'type' => Type::boolean() ]
        ];

        if (method_exists($this, 'customArgs')) {
            $args = array_merge($args, $this->customArgs());
        }

        return $args;
    }

	public function type(): Type{
		return GraphQL::type('Message');
	}

    public function resolve($root, $args) {

//        $q = $this->model::query();
//
//        if(isset($args['id'])) {
//            $q->where('id', $args['id']);
//        } else {
//            if (isset($args['limit'])) {
//
//                $q->take($args['limit']);
//
//                if (isset($args['page'])) {
//                    $q->skip(($args['page'] - 1) * $args['limit']);
//                }
//            }
//
//            if (isset($args['sort'])) {
//                $q->orderBy($args['sort'], isset($args['dir']) ? $args['dir'] : 'asc');
//            }
//        }
//
//        if (method_exists($this, 'customResolve')) {
//            $this->customResolve($q, $args);
//        }

//        return $q->get();
        $aaa = Messages::find(2);
        return $aaa;
    }
}
