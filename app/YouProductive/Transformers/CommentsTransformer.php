<?php

namespace YouProductive\Transformers;

/**
 * Class CommentsTransformer
 *
 * This class is used to transform comments, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class CommentsTransformer extends Transformer {


    /**
     * This function transforms a single comment -
     * from JSON format with specified fields.
     *
     * @param $item A comment
     * @return array Returns an individual comment,
     * according to specified fields.
     */
    public function  transform($item)
    {
        return [
            'id' => $item['id'],
            'comment' => $item['comment'],
            'parent_comment_id' => $item['parent_comment_id'],
            'type' => $item['type'],
            'project_id' => $item['project_id'],
            'task_id' => $item['task_id'],
            'user_id' => $item['user_id'],
            'organization_id' => $item['organization_id']
        ];
    }
}