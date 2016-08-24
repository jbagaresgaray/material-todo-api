<?php

namespace Transformers;

/**
 * Class UsersNotesTransformer
 *
 * This class is used to transform UsersNotes, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class UsersNotesTransformer extends Transformer {


    /**
     * This function transforms a single UsersNote -
     * from JSON format with specified fields.
     *
     * @param $item A UsersNote
     * @return array Returns an individual UsersNote,
     * according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id' => $item['id'],
            'user_id' => $item['user_id'],
            'note_id' => $item['note_id'],
            'permissions' => $item['permissions']
        ];
    }
}