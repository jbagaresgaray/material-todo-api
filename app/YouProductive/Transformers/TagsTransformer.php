<?php

namespace YouProductive\Transformers;

/**
 * Class TagsTransformer
 *
 * This class is used to transform tags, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class TagsTransformer extends Transformer {


    /**
     * This function transforms a single tag -
     * from JSON format with specified fields.
     *
     * @param $item A tag
     * @return array Returns an individual tag,
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