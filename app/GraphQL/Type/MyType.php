<?php

namespace App\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class MyType extends GraphQLType {

	/**
	 * @method fields
	 */
	public function fields():array {
		$fields = [
			'id' => [
				'type' => Type::nonNull(Type::ID()),
				'description' => 'The id'
			],
			'created_at' => [
				'type' => Type::string(),
				'description' => 'The date created'
			],
			'updated_at' => [
				'type' => Type::string(),
				'description' => 'The date last updated'
			],
			'deleted_at' => [
				'type' => Type::string(),
				'description' => 'The date last deleted'
			],
		];

		if (method_exists($this, 'customFields')) {
			$fields = array_merge($fields, $this->customFields());
		}

		return $fields;
	}

	/**
	 * @method resolveCreatedAtField
	 * @param $root The date object
	 * @return String
	 */
	public function resolveCreatedAtField($root) {
		return $root->created_at;
	}

	/**
	 * @method resolveUpdatedAtField
	 * @param $root The date object
	 * @return String
	 */
	public function resolveUpdatedAtField($root) {
		return $root->updated_at;
	}

	/**
	 * @method resolveDeletedAtField
	 * @param $root The date object
	 * @return String
	 */
	public function resolveDeletedAtField($root) {
		return $root->deleted_at ? $root->deleted_at->toIso8601String() : null;
	}


	/**
	 * @method args
	 */
	protected function args($extraArgs = []) {
		return array_merge([
			[ 'name' => 'id',    'type' => Type::ID()       ],
			[ 'name' => 'limit', 'type' => Type::int()      ],
			[ 'name' => 'page',  'type' => Type::int()      ],
			[ 'name' => 'sort',  'type' => Type::string()   ],
			[ 'name' => 'dir',   'type' => Type::string()   ],
			[ 'name' => 'trash', 'type' => Type::boolean()  ],
		], $extraArgs);
	}


	/**
	 * @method resolveDefaults
	 */
	protected function resolveDefaults($q, $args) {
		if (isset($args['trash'])) {
			$q->withTrashed();
		}

		if(isset($args['id'])) {
			$q->where('id', $args['id']);

		} else {
			if (isset($args['limit'])) {

				$q->take($args['limit']);

				if (isset($args['page'])) {
					$q->skip(($args['page'] - 1) * $args['limit']);
				}
			}

			if (isset($args['sort'])) {
				$q->orderBy($args['sort'], isset($args['dir']) ? $args['dir'] : 'asc');
			}
		}

		return $q;
	}

}
