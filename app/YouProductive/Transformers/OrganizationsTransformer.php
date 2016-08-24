<?php

namespace YouProductive\Transformers;

/**
 * Class OrganizationsTransformer
 *
 * This class is used to transform organizations, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class OrganizationsTransformer extends Transformer {


    /**
     * This function transforms a single organization -
     * from JSON format with specified fields.
     *
     * @param $item A organization
     * @return array Returns an individual organization,
     * according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id' => $item['id'],
            'name' => $item['name'],
            'description' => $item['description'],
            'user_id' => $item['user_id']
        ];
    }
}