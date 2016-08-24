<?php

namespace Transformers;

/**
 * Class UsersProjectsTransformer
 *
 * This class is used to transform UsersProjects, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class UsersProjectsTransformer extends Transformer {


    /**
     * This function transforms a single UsersProject -
     * from JSON format with specified fields.
     *
     * @param $item A UsersProject
     * @return array Returns an individual UsersProject,
     * according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id' => $item['id'],
            'name' => $item['name'],
            'description' => $item['description'],
            'start_date' => $item['start_date'],
            'end_date' => $item['end_date'],
            'organization_id' => $item['organization_id']
        ];
    }
}