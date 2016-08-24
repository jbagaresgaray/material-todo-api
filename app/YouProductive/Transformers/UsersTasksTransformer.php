<?php

namespace YouProductive\Transformers;

/**
 * Class UsersTasksTransformer
 *
 * This class is used to transform UsersTasks, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class UsersTasksTransformer extends Transformer {


    /**
     * This function transforms a single UsersTask -
     * from JSON format with specified fields.
     *
     * @param $item A UsersTask
     * @return array Returns an individual UsersTask,
     * according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id' => $item['id'],
            'user_id' => $item['user_id'],
            'type' => $item['type'],
            'task_id' => $item['task_id']
        ];
    }
}