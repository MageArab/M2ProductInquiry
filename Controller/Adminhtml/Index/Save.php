<?php

namespace MageArab\ProductInquiry\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

class Save extends Action
{
    public $dataProcessor;

    public function __construct(
        Action\Context $context,
        PostDataProcessor $dataProcessor
    ) {
        $this->dataProcessor = $dataProcessor;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        if ($data) {
            $model = $objectManager->create('MageArab\ProductInquiry\Model\Inquiry');

            $id = $this->getRequest()->getParam('inquiry_id');
            if ($id) {
                $model->load($id);
            }

            $model->addData($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('The Data has been saved.'));
                $objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['inquiry_id' => $model->getId(), '_current' => true]);
                    return;
                }

                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', ['inquiry_id' => $this->getRequest()->getParam('inquiry_id')]);
            return;
        }

        $this->_redirect('*/*/');
    }
}
