<?php
namespace MageArab\ProductInquiry\Block;

use Magento\Framework\Registry;

class Test extends \Magento\Framework\View\Element\Template
{
    public $inquiryCollection = null;
    public $scopeConfig;
    public $inquiryCollectionFactory;
    public $dataHelper;
    public $registry;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \MageArab\ProductInquiry\Model\ResourceModel\Inquiry\CollectionFactory $inquiryCollectionFactory,
        \MageArab\ProductInquiry\Helper\Data $dataHelper,
        Registry $registry,
        array $data = []
    ) {
        $this->scopeConfig = $context->getScopeConfig();
        $this->inquiryCollectionFactory = $inquiryCollectionFactory;
        $this->dataHelper = $dataHelper;
        $this->registry = $registry;
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

    public function _getCollection()
    {
        $collection = $this->inquiryCollectionFactory->create();
        return $collection;
    }

    public function getCollection()
    {
        if ($this->inquiryCollection === null) {
            $this->inquiryCollection = $this->_getCollection();
            $this->inquiryCollection->setCurPage($this->getCurrentPage());
            $this->inquiryCollection->setPageSize($this->dataHelper->getInquiryPerPage());
            $this->inquiryCollection->setOrder('published_at', 'asc');
        }

        return $this->inquiryCollection;
    }

    public function getCurrentPage()
    {
        return $this->getData('current_page') ? $this->getData('current_page') : 1;
    }

    public function getItemUrl($inquiryItem)
    {
        return $this->getUrl('*/*/view', ['id' => $inquiryItem->getId()]);
    }

    public function getPager()
    {
        $pager = $this->getChildBlock('inquiry_list_pager');
        if ($pager instanceof \Magento\Framework\Object) {
            $inquiryPerPage = $this->dataHelper->getInquiryPerPage();
            $pager->setAvailableLimit([$inquiryPerPage => $inquiryPerPage]);
            $pager->setTotalNum($this->getCollection()->getSize());
            $pager->setCollection($this->getCollection());
            $pager->setShowPerPage(true);
            $pager->setFrameLength(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            )->setJump(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame_skip',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            );
            return $pager->toHtml();
        }

        return null;
    }

    public function getFormAction()
    {
        return $this->getUrl('inquiry/index/save', ['_secure' => true]);
    }

    public function getApiDefaultData($title)
    {
        return $this->scopeConfig->getValue(
            $title,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }
}
