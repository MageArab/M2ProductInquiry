<?php

namespace MageArab\ProductInquiry\Block\Adminhtml\Inquiry;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    
    public $coreRegistry = null;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize cms page edit block
     *
     * @return void
     */
    public function _construct()
    {
        $this->_objectId = 'inquiry_id';
        $this->_blockGroup = 'MageArab_ProductInquiry';
        $this->_controller = 'adminhtml_inquiry';

        parent::_construct();

        if ($this->_isAllowedAction('MageArab_ProductInquiry::save')) {
            $this->buttonList->update('save', 'label', __('Save Inquiry'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                -100
            );
        } else {
            $this->buttonList->remove('save');
        }

        if ($this->_isAllowedAction('MageArab_ProductInquiry::inquiry_delete')) {
            $this->buttonList->update('delete', 'label', __('Delete Inquiry'));
        } else {
            $this->buttonList->remove('delete');
        }
    }

    /**
     * Retrieve text for header element depending on loaded page
     *
     * @return string
     */
    public function getHeaderText()
    {
        if ($this->coreRegistry->registry('inquiry')->getId()) {
            return __(
                "Edit Inquiry '%1'",
                $this->escapeHtml($this->coreRegistry->registry('inquiry')->getTitle())
            );
        } else {
            return __('New Inquiry');
        }
    }

    public function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    public function _getSaveAndContinueUrl()
    {
        return $this->getUrl(
            'inquiry/*/save',
            ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']
        );
    }

    /**
     * Prepare layout
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    public function _prepareLayout()
    {
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('page_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'page_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'page_content');
                }
            };
        ";
        return parent::_prepareLayout();
    }
}
