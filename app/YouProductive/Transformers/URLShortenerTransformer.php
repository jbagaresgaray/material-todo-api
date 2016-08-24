<?php

namespace YouProductive\Transformers;

/**
 * Class URLShortenerTransformer
 *
 * This class is used to transform URLShorteners, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class URLShortenerTransformer extends Transformer {


    /**
     * This function transforms a single URLShortener -
     * from JSON format with specified fields.
     *
     * @param $item A URLShortener
     * @return array Returns an individual URLShortener,
     * according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id' => $item['id'],
            'slug' => $item['slug'],
            'type' => $item['type'],
            'associated_id' => $item['associated_id'],
            'user_id' => $item['user_id'],
            'url_id' => $item['url_id']
        ];
    }
}