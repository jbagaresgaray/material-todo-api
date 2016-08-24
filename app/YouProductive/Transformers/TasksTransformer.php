<?php

namespace YouProductive\Transformers;

/**
 * Class TasksTransformer
 *
 * This class is used to transform tasks, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class TasksTransformer extends Transformer {


    /**
     * This function transforms a single task -
     * from JSON format with specified fields.
     *
     * @param $item A task
     * @return array Returns an individual task,
     * according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id' => $item['id'],
            'name' => $item['name'],
            'description' => $item['description'],
            'start_date' => $item['start_date'],
            'completion_date' => $item['completion_date'],
            'starred' => $item['starred'],
            'priority' => $item['priority'],
            'estimate_time' => $item['estimate_time'],
            'time_spent' => $item['time_spent'],
            'parent_task_id' => $item['parent_task_id'],
            'original_task_id' => $item['original_task_id'],
            'project_id' => $item['project_id'],
            'folder_id' => $item['folder_id'],
            'status' => $item['status'],
            'is_complete' => $item['is_complete']
        ];
    }
}