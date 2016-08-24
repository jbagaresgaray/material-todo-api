<?php

namespace YouProductive\Transformers;

/**
 * Class NotesTransformer
 *
 * This class is used to transform notes, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class NotesTransformer extends Transformer {


    /**
     * This function transforms a single note -
     * from JSON format with specified fields.
     *
     * @param $item A note
     * @return array Returns an individual note,
     * according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id' => $item['id'],
            'note' => $item['note'],
            'type' => $item['type'],
            'associated_id' => $item['associated_id'],
            'user_id' => $item['user_id']
        ];
    }
}