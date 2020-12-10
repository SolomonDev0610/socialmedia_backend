<?php
/**
 * Created by PhpStorm.
 * User: sgs
 * Date: 7/24/2020
 * Time: 1:44 PM
 */
namespace App\GraphQL\Query;
use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
class UserQuery extends Query{
    public $model = "App\User";

    protected $attributes = [
        'name' => 'user',
        'description' => 'Get the currently logged in user'
    ];

    public function type(): Type{
        return GraphQL::type('User');
    }

    public function args(): array {
//        return [
//            'id' => [
//                'name' => 'id',
//                'type' => Type::int()
//            ],
//            'email' => [
//                'name' => 'email',
//                'type' => Type::string()
//            ]
//        ];
        return [
            [ 'name' => 'token', 'type' => Type::nonNull(Type::string()) ]
        ];
    }

    public function resolve($root, $args) {
        return User::authenticate($args['token']);
    }
}
