<?php

namespace MageArab\ProductInquiry\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_ITEMS_PER_PAGE     = 'inquiry/view/items_per_page';

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Image\Factory $imageFactory
    ) {
        $this->_imageFactory = $imageFactory;
        parent::__construct($context);
    }

    public function getInquiryPerPage()
    {
        return abs(
            (int)$this->scopeConfig->getValue(
                self::XML_PATH_ITEMS_PER_PAGE,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            )
        );
    }
}
