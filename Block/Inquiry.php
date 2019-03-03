<?php
namespace MageArab\ProductInquiry\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Inquiry extends Template
{
    public $scopeConfig;

    public function __construct(
        Context $context,
        array $data = []
    ) {
        $this->scopeConfig = $context->getScopeConfig();
        parent::__construct($context, $data);
    }
    public function isEnable()
    {
        return $this->getConfig('enquiry/general/enable');
    }

    public function getConfig($config)
    {
        return $this->scopeConfig->getValue(
            $config,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getFormAction()
    {
        return $this->getUrl('inquiry/index/save', ['_secure' => true]);
    }
}
