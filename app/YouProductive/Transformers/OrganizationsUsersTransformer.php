<?php

namespace Transformers;

/**
 * Class OrganizationsUsersTransformer
 *
 * This class is used to transform OrganizationsUsers, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class OrganizationsUsersTransformer extends Transformer {


    /**
     * This function transforms a single orgUser -
     * from JSON format with specified fields.
     *
     * @param $item A orgUser
     * @return array Returns an individual orgUser,
     * according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id' => $item['id'],
            'user_id' => $item['user_id'],
            'role_id' => $item['role_id'],
            'organization_id' => $item['organization_id']
        ];
    }
}