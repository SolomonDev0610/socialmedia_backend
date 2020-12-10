<?php

namespace App\GraphQL\Query;

use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
class UsersQuery extends Query {

	/**
	 * @property model
	 */
	public $model = "App\User";

	/**
	 * @property attributes
	 */
	protected $attributes = [
		'name' => 'users',
		'description' => 'Query all the Users'
	];

	public function args():array {
		return [
			["name" => "email", "type" => Type::String()],
            [ 'name' => 'token', 'type' => Type::nonNull(Type::string()) ],
            [ 'name' => 'id',    'type' => Type::ID()      ],
		];
	}


    /**
	 * @method type
	 * @return GraphQLType [User]
	 */
	public function type(): Type{
		return Type::listOf(GraphQL::type('User'));
	}


	public function resolve($q, $args) {
        return User::all();
	}
}
