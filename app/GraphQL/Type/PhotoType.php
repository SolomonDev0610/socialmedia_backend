<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PhotoType extends GraphQLType {

	/**
	 * @property attributes
	 */
	protected $attributes = [
		'name' => 'Photo',
		'description' => 'A Photo'
	];

	/**
	 * @method customFields
	 * @return Array
	 */
	public function fields():array {
		return [
            'id' => [
                'type' => Type::nonNull(Type::ID()),
                'description' => 'The id'
            ],
			'path' => [
				'type' => Type::string(),
				'description' => 'The path of the photo'
			]
		];
	}
}
