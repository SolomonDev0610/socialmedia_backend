<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
class PhotoQuery extends Query {

	/**
	 * @property model
	 */
	public $model = "App\Model\Photos";

	/**
	 * @property attributes
	 */
	protected $attributes = [
		'name' => 'photos',
		'description' => 'Query all the Photos'
	];


    /**
	 * @method type
	 * @return GraphQLType [Photo]
	 */
	public function type(): Type {
		return Type::listOf(GraphQL::type('Photo'));
	}
}
