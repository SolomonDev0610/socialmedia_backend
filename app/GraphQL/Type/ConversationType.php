<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ConversationType extends GraphQLType {

	/**
	 * @property attributes
	 */
	protected $attributes = [
		'name' => 'Conversation',
		'description' => 'A link between users and their messages'
	];

	/**
	 * @method fields
	 */
	public function fields():array {
		return [
			'user' => [
				'type' => GraphQL::type('User'),
				'description' => 'The user'
			],
			'latest_message' => [
				'type' => GraphQL::type('Message'),
				'description' => 'The latest message'
			],
		];
	}


	/**
	 * @method args
	 */
	protected function args() {
		return [

		];
	}

}
