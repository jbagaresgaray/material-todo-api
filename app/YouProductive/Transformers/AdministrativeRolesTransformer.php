<?php

namespace Transformers;

/**
 * Class AdministrativeRolesTransformer
 *
 * This class is used to transform AdminRoles, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class AdministrativeRolesTransformer extends Transformer {


    /**
     * This function transforms a single AdminRole -
     * from JSON format with specified fields.
     *
     * @param $item A AdminRole
     * @return array Returns an individual AdminRole,
     * according to specified fields.
     */
    public function transform($item)
    {
        return [
            'name' => $item['name'],
            'user_id' => $item['user_id']
        ];
    }
}