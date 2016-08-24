<?php

namespace Transformers;

/**
 * Class StatusTransformer
 *
 * This class is used to transform statuses, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class StatusTransformer extends Transformer {


    /**
     * This function transforms a single status -
     * from JSON format with specified fields.
     *
     * @param $item A status
     * @return array Returns an individual status,
     * according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id' => $item['id'],
            'name' => $item['name']
        ];
    }
}