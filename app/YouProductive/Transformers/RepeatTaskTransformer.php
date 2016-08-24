<?php

namespace YouProductive\Transformers;

/**
 * Class RepeatTaskTransformer
 *
 * This class is used to transform RepeatTask, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class RepeatTaskTransformer extends Transformer {


    /**
     * This function transforms a single RepeatTask -
     * from JSON format with specified fields.
     *
     * @param $item A RepeatTask
     * @return array Returns an individual RepeatTask,
     * according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id' => $item['id'],
            'task_id' => $item['task_id'],
            'is_group' => $item['is_group'],
            'repeat' => $item['repeat'],
            'repeat_from' => $item['repeat_from']
        ];
    }
}