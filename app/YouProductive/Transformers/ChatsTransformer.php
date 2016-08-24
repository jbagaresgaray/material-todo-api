<?php

namespace Transformers;

/**
 * Class ChatsTransformer
 *
 * This class is used to transform chats, in JSON format,
 * by explicitly stating which user properties to serve up.
 *
 * @package Transformers
 */
class ChatsTransformer extends Transformer {


    /**
     * This function transforms a single chat -
     * from JSON format with specified fields.
     *
     * @param $item A chat
     * @return array Returns an individual chat,
     * according to specified fields.
     */
    public function transform($item)
    {
        return [
            'id' => $item['id'],
            'group_chat_user_ids' => $item['group_chat_user_ids'],
            'project_group_user_ids' => $item['project_group_user_ids'],
            'is_one_to_chat' => $item['is_one_to_chat'],
            'is_project_group_chat' => $item['is_project_group_chat'],
            'is_group_chat' => $item['is_group_chat'],
            'status_one_to_one' => $item['status_one_to_one'],
            'status_group_chat' => $item['status_group_chat'],
            'status_project_group' => $item['status_project_group'],
            'user_id_receiver' => $item['user_id_receiver'],
            'user_id_sender' => $item['user_id_sender'],
            'project_id' => $item['project_id']  
        ];
    }
}