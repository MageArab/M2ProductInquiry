<?php

namespace MageArab\ProductInquiry\Model\ResourceModel;

class Inquiry extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function _construct()
    {
        $this->_init('magearab_productinquiry', 'inquiry_id');
    }
}
