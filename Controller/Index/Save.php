<?php

namespace MageArab\ProductInquiry\Controller\Index;

use MageArab\ProductInquiry\Model\InquiryFactory;
use MageArab\ProductInquiry\Model\ResourceModel\Inquiry as ResourceInquiry;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;

class Save extends \Magento\Framework\App\Action\Action
{
    protected $pageFactory;
    protected $inquiryFactory;
    protected $inquiryResource;
    protected $formKeyValidator;

    /**
     * Save constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param InquiryFactory $inquiryFactory
     * @param ResourceInquiry $inquiryResource
     * @param Validator $formKeyValidator
     * @param FormKey $formKey
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        InquiryFactory $inquiryFactory,
        ResourceInquiry $inquiryResource,
        Validator $formKeyValidator
    ) {
        $this->pageFactory = $pageFactory;
        $this->inquiryFactory = $inquiryFactory;
        $this->inquiryResource = $inquiryResource;
        $this->formKeyValidator  = $formKeyValidator;
        return parent::__construct($context);
    }

    public function execute()
    {
        if (!$this->formKeyValidator->validate($this->getRequest()) && !$this->getRequest()->isPost()) {
            $this->_redirect($this->_redirect->getRefererUrl());
            return;
        }

        try {
            $data = $this->_request->getParams();
            $newsModel = $this->inquiryFactory->create();
            $newsModel->setName($data['name']);
            $newsModel->setEmail($data['email']);
            $newsModel->setTelephone($data['telephone']);
            $newsModel->setDetails($data['details']);
            $newsModel->setProductName($data['product_name']);
            $this->inquiryResource->save($newsModel);

            $emailHelper = $this->_objectManager->get('MageArab\ProductInquiry\Helper\Email');
            $receiverInfo = ['name' => $data['name'],'email' => $data['email']];
            $senderInfo = ['name' => $emailHelper->getSalesName(),$emailHelper->getSalesEmail()];
            $emailTemplateVariables = [];
            $emailTempVariables['name'] = trim($data['name']);
            $emailTempVariables['email'] = trim($data['email']);
            $emailTempVariables['telephone'] = trim($data['telephone']);
            $emailTempVariables['details'] = trim($data['details']);
            $emailTempVariables['product_name'] = trim($data['product_name']);
            // @todo finish customer template
            //$emailHelper->sendMail($emailTemplateVariables, $senderInfo, $receiverInfo);
            //$emailHelper->sendMail($emailTemplateVariables, $senderInfo, $senderInfo, 'admin_inquiry_template');

            $this->messageManager->addSuccessMessage(
                __("Thanks for contacting us with your comments and questions. We'll respond to you very soon.")
            );
            $this->_redirect($this->_redirect->getRefererUrl());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('We can\'t process your request right now. Sorry, that\'s all we know.')
            );
            $this->_redirect($this->_redirect->getRefererUrl());
            return;
        }
    }
}
