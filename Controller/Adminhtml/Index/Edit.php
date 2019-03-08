<?php

namespace MageArab\ProductInquiry\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

class Edit extends Action
{
    public $coreRegistry = null;

    public $resultPageFactory;

    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        parent::__construct($context);
    }

    private function initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu(
            'MageArab_ProductInquiry::inquiry_manage'
        )->addBreadcrumb(
            __('Inquiry'),
            __('Inquiry')
        )->addBreadcrumb(
            __('Manage Inquiry'),
            __('Manage Inquiry')
        );
        return $resultPage;
    }

    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('inquiry_id');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $model = $objectManager->create('MageArab\ProductInquiry\Model\Inquiry');

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This inquiry no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        // 3. Set entered data if was error when we do save
        $data = $objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        // 4. Register model to use later in blocks
        $this->coreRegistry->register('inquiry', $model);

        // 5. Build edit form
        $resultPage = $this->initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Inquiry') : __('New Inquiry'),
            $id ? __('Edit Inquiry') : __('New Inquiry')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Inquiry'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('New Inquiry'));
        return $resultPage;
    }
}
