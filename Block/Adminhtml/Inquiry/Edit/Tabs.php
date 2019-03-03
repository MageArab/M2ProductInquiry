<?php

namespace MageArab\ProductInquiry\Block\Adminhtml\Inquiry\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    public function _construct()
    {
        parent::_construct();
        $this->setId('page_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Inquiry Information'));
    }
}
