<?php

namespace YouProductive\Transformers;

/**
 * Class UsersTransformer
 *
 * This class is used to transform lessons, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class UsersTransformer extends Transformer {


    /**
     * This function transforms a single user -
     * from JSON format with specified fields.
     *
     * @param $item A user
     * @return array Returns an individual user,
     * according to specified fields.
     */
    public function transform($item)
    {
        return [
            'user_id' => $item['user_id'],
            'email' => $item['email'],
            'username' => $item['username'],
            'first_name' => $item['first_name'],
            'last_name' => $item['last_name'],
            'public_key' => $item['public_key'],
            'timezone' => $item['timezone'],
            'country' => $item['country'],
            's3_file_uri_user_photo' => $item['s3_file_uri_user_photo']
        ];
    }
}