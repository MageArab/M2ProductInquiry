<?php

namespace MageArab\ProductInquiry\Controller\Adminhtml\Index;

class PostDataProcessor
{

    public $dateFilter;

    public $messageManager;

    public function __construct(
        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->dateFilter = $dateFilter;
        $this->messageManager = $messageManager;
    }
}
