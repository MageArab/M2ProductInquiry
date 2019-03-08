<?php

namespace MageArab\ProductInquiry\Block\Adminhtml\Inquiry\Edit\Tab;

class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    public $systemStore;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    public function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('inquiry');

        if ($this->_isAllowedAction('MageArab_ProductInquiry::save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('inquiry_main_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Inquiry Information')]);

        if ($model->getId()) {
            $fieldset->addField('inquiry_id', 'hidden', ['name' => 'inquiry_id']);
        }
        $fieldset->addField(
            'name',
            'text',
            ['name' => 'name', 'label' => __('Name'), 'title' => __('Name'), 'required' => true]
        );
        $fieldset->addField(
            'email',
            'text',
            ['name' => 'email', 'label' => __('Email'), 'title' => __('Email'), 'required' => true]
        );
        $fieldset->addField(
            'telephone',
            'text',
            ['name' => 'telephone', 'label' => __('Telephone'), 'title' => __('Telephone'), 'required' => true]
        );
        $fieldset->addField(
            'product_name',
            'textarea',
            ['name' => 'product_name', 'label' => __('Product Name'), 'title' => __('Product Name'), 'required' => true]
        );
        $fieldset->addField(
            'details',
            'textarea',
            ['name' => 'details', 'label' => __('Product Details'), 'title' => __('Product Details'), 'required' => true]
        );

        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $fieldset->addField(
            'published_at',
            'date',
            [
            'name'     => 'published_at',
            'date_format' => $dateFormat,
            'image'    => $this->getViewFileUrl('images/grid-cal.gif'),
            'value' => $model->getPublishedAt(),
            'label'    => __('Publishing Date'),
            'title'    => __('Publishing Date'),
            'required' => true
            ]
        );

        $this->_eventManager->dispatch('adminhtml_inquiry_edit_tab_main_prepare_form', ['form' => $form]);

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getTabLabel()
    {
        return __('Product Information');
    }

    public function getTabTitle()
    {
        return __('Product Information');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

    public function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
