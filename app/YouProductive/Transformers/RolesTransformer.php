<?php

namespace Transformers;

/**
 * Class RolesTransformer
 *
 * This class is used to transform roles, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class RolesTransformer extends Transformer {


    /**
     * This function transforms a single role -
     * from JSON format with specified fields.
     *
     * @param $item A role
     * @return array Returns an individual role,
     * according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id' => $item['id'],
            'name' => $item['name'],
            'description' => $item['description']
        ];
    }
}