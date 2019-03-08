<?php

namespace MageArab\ProductInquiry\Model\ResourceModel\Inquiry;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init(
            'MageArab\ProductInquiry\Model\Inquiry',
            'MageArab\ProductInquiry\Model\ResourceModel\Inquiry'
        );
    }
}
