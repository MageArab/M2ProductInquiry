<?php

namespace MageArab\ProductInquiry\Model;

class Inquiry extends \Magento\Framework\Model\AbstractModel
{
    public function _construct()
    {
        $this->_init('MageArab\ProductInquiry\Model\ResourceModel\Inquiry');
    }
}
