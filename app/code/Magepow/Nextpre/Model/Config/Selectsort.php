<?php

namespace Magepow\Nextpre\Model\Config;

class Selectsort implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray(){
        return [
            ['value' => 0, 'label' => __('Ascending')],
            ['value' => 1, 'label' => __('Descending')]
        ];
    }
}