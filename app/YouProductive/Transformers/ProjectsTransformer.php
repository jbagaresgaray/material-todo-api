<?php

namespace YouProductive\Transformers;

/**
 * Class ProjectsTransformer
 *
 * This class is used to transform projects, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class ProjectsTransformer extends Transformer {


    /**
     * This function transforms a single project -
     * from JSON format with specified fields.
     *
     * @param $item A project
     * @return array Returns an individual project,
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