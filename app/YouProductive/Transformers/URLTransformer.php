<?php

namespace YouProductive\Transformers;

/**
 * Class URLTransformer
 *
 * This class is used to transform URLs, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class URLTransformer extends Transformer {


    /**
     * This function transforms a single url -
     * from JSON format with specified fields.
     *
     * @param $item A url
     * @return array Returns an individual url,
     * according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id' => $item['id'],
            'url' => $item['url'],
            'hash' => $item['hash'],
            'counter' => $item['counter']
        ];
    }
}