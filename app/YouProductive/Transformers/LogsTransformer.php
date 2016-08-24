<?php

namespace YouProductive\Transformers;

/**
 * Class LogsTransformer
 *
 * This class is used to transform logs, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class LogsTransformer extends Transformer {


    /**
     * This function transforms a single log -
     * from JSON format with specified fields.
     *
     * @param $item A log
     * @return array Returns an individual log,
     * according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id' => $item['id'],
            'description' => $item['description'],
            'type' => $item['type'],
            'associated_id' => $item['associated_id']
        ];
    }
}