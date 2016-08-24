<?php

namespace YouProductive\Transformers;

/**
 * Class NotificationsTransformer
 *
 * This class is used to transform notifications, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class NotificationsTransformer extends Transformer {


    /**
     * This function transforms a single notification -
     * from JSON format with specified fields.
     *
     * @param $item A notification
     * @return array Returns an individual notification,
     * according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id' => $item['id'],
            'description' => $item['description']
        ];
    }
}