<?php
namespace MageArab\ProductInquiry\Block;

use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Inquiry extends Template
{
    public $scopeConfig;
    protected $formKey;

    /**
     * Inquiry constructor.
     * @param Context $context
     * @param FormKey $formKey
     * @param array $data
     */
    public function __construct(
        Context $context,
        FormKey $formKey,
        array $data = []
    ) {
        $this->formKey   = $formKey;
        $this->scopeConfig = $context->getScopeConfig();
        parent::__construct($context, $data);
    }
    public function isEnable()
    {
        return $this->getConfig('inquiry/general/enable');
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

    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }
}
