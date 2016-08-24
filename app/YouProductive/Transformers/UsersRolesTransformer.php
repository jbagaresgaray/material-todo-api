<?php

namespace Transformers;

/**
 * Class UsersRolesTransformer
 *
 * This class is used to transform UsersRoles, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class UsersRolesTransformer extends Transformer {


    /**
     * This function transforms a single UsersRole -
     * from JSON format with specified fields.
     *
     * @param $item A UsersRole
     * @return array Returns an individual UsersRole,
     * according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id' => $item['id'],
            'user_id' => $item['user_id'],
            'role_id' => $item['role_id']
        ];
    }
}