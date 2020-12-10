<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;


class MessageType extends MyType {

	/**
	 * @property attributes
	 */
	protected $attributes = [
		'name' => 'Messages',
		'description' => 'A Message'
	];

	/**
	 * @method customFields
	 * @return Array
	 */
	public function customFields() {
		return [
			'content' => [
				'type' => Type::string(),
				'description' => 'Content of the message'
			],
			'read' => [
				'type' => Type::boolean(),
				'description' => 'Has the message been read'
			],
			'emailed' => [
				'type' => Type::boolean(),
				'description' => 'Has the user been sent an email'
			],
			'notified' => [
				'type' => Type::boolean(),
				'description' => 'Has the user been sent a notification'
			],
			'from' => [
				'type' => GraphQL::type('User'),
				'description' => 'The Message sender'
			],
			'to' => [
				'type' => GraphQL::type('User'),
				'description' => 'The Message receiver'
			],

		];
	}
}
