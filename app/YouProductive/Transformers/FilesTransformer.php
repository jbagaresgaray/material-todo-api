<?php

namespace YouProductive\Transformers;

/**
 * Class FilesTransformer
 *
 * This class is used to transform files, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class FilesTransformer extends Transformer {


    /**
     * This function transforms a single files -
     * from JSON format with specified fields.
     *
     * @param $item A files
     * @return array Returns an individual usfileser,
     * according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id' => $item['id'],
            'file_name' => $item['file_name'],
            'hash' => $item['hash'],
            'type' => $item['type'],
            'project_id' => $item['project_id'],
            'user_id' => $item['user_id'],
            'organization_id' => $item['organization_id'],
            'task_id' => $item['task_id'],
            'comment_id' => $item['comment_id'],
            's3_file_uri' => $item['s3_file_uri'],
            's3_file_uri_user_photo' => $item['s3_file_uri_user_photo']
        ];
    }
}