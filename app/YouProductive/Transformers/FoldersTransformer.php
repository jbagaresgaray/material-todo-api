<?php

namespace YouProductive\Transformers;

/**
 * Class FoldersTransformer
 *
 * This class is used to transform folders, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class FoldersTransformer extends Transformer {


    /**
     * This function transforms a single folder -
     * from JSON format with specified fields.
     *
     * @param $item A folder
     * @return array Returns an individual folder,
     * according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id' => $item['id'],
            'name' => $item['name'],
            'description' => $item['description'],
            'organise' => $item['organise'],
            'parent_folder_id' => $item['parent_folder_id'],
            'user_id' => $item['user_id']
        ];
    }
}