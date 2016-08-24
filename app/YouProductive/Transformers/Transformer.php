<?php

namespace YouProductive\Transformers;

/**
 * Class Transformer
 *
 * This class abstracts responsibilities of transforming
 * JSON data for the API.
 *
 * @package CLINK\Transformers
 */
abstract class Transformer {

    /**
     * Function transformCollection
     *
     * This function transforms a collection of items,
     * according to the abstract transform method.
     *
     * @param array $items
     * @return array A collection of items.
     */
    public function transformCollection(array $items)
    {
        return array_map([$this, 'transform'], $items);
    }

    public abstract function transform($item);
}