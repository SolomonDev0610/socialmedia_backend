<?php
/**
 * Created by PhpStorm.
 * User: sgs
 * Date: 7/24/2020
 * Time: 1:49 PM
 */

namespace App\GraphQL\Type;

use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;
use App\Model\Messages;
use Auth;
use JWTAuth;

class UserType extends GraphQLType
{
    /**
     * @property attributes
     */
    protected $attributes = [
        'name' => 'Users',
        'description' => 'A type',
        'model' => User::class, // define model for users type
    ];

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
     * @method customFields
     * @return Array
     */
    // define field of type
    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the user'
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'The email of user'
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of the user'
            ],
            'firstname' => [
                'type' => Type::string(),
                'description' => 'The firstname of the user'
            ],
            'lastname' => [
                'type' => Type::string(),
                'description' => 'The lastname of the user'
            ],
            'photo' => [
                'type' => GraphQL::type('Photo'),
                'description' => 'The users avatar photo'
            ],
            'messages' => [
                'type' => Type::listOf(GraphQL::type('Message')),
                'description' => 'The Users Messages',
                'args' => $this->args([
                    ['name' => 'user_id', 'type' => Type::ID()],
                ])
            ],
            'conversations' => [
                'type' => Type::listOf(GraphQL::type('Conversation')),
                'description' => 'A custom list that represents conversations with other users',
                'args' => [
                    ['name' => 'user_id', 'type' => Type::ID()],
                    ['name' => 'token', 'type' => Type::nonNull(Type::string())]
                ],
            ],
            'building_users' => [
                'type' => Type::listOf(GraphQL::type('User')),
                'description' => 'A custom list that building users',
                'args' => $this->args([
                    ['name' => 'user_id', 'type' => Type::ID()],
                    ['name' => 'token', 'type' => Type::nonNull(Type::string())]
                ])
            ],
            'unread_messages' => [
                'type' => Type::listOf(GraphQL::type('Message')),
                'description' => 'Any unread messages sent to this user'
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
    }

    /**
     * @method resolveMessagesField
     * @param root
     * @param args
     * @return Array
     */
    public function resolveMessagesField($root, $args)
    {
        if (!isset($args['user_id']) && !isset($args['message_group_id'])) return [];

        $q = $this->resolveDefaults(Message::query(), $args);

        if (isset($args['user_id'])) {
            $q->where(function ($query) use ($root, $args) {
                $query->where('from_id', $root->id)
                    ->where('to_id', $args['user_id']);
            });

            $q->orWhere(function ($query) use ($root, $args) {
                $query->where('from_id', $args['user_id'])
                    ->where('to_id', $root->id);
            });
        }

        # https://stackoverflow.com/questions/18576762/php-stdclass-to-array
        // return Message::hydrate(json_decode(json_encode($q->get()), true));
        return $q->get();
    }

    /**
     * @method resolveConversationsField
     * @param root
     * @param args
     * @return Array
     */
    public function resolveConversationsField($root, $args)
    {
        $auth = User::authenticate($args['token']);
//        $auth=User::find(2);
        $auth_id = $auth->id;
        $conversations = [];
        $messages = Messages::where('to_id', $auth_id)->orWhere('from_id', $auth_id)->orderBy('created_at', 'desc')->get();
        $userIds = [];
        $latestMessage = [];
        foreach ($messages as $index => $message) {
            if ($message->to_id !== $auth_id) {
                $user_id = $message->to_id;
            } else {
                $user_id = $message->from_id;
            }
            if (!in_array($user_id, $userIds)) {
                $userIds[] = $user_id;
                $latestMessage[$user_id] = $message;
            }
        }
        $users = User::whereIn('id', $userIds)->get()->toArray();
        foreach ($users as $user) {
            $conversations[] = [
                'user' => $user,
                'latest_message' => $latestMessage[$user['id']]
            ];
        }
        return $conversations;
    }

    public function resolveBuildingUsersField($root, $args)
    {
        $building_users = [];
        $users = User::all()->toArray();
        foreach ($users as $user) {
            $building_users[] = $user;
        }
        return $building_users;

    }

    /**
     * @method resolveUnreadMessagesField
     * @param root
     * @param args
     * @return Array
     */
    public function resolveUnreadMessagesField($root, $args)
    {
        return Messages::where('to_id', $root->id)->where('read', '0')->get();
    }
}
