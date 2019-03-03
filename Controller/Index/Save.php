<?php

namespace MageArab\ProductInquiry\Controller\Index;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use MageArab\ProductInquiry\Model\InquiryFactory;
use MageArab\ProductInquiry\Model\ResourceModel\Inquiry as ResourceInquiry;

class Save extends \Magento\Framework\App\Action\Action
{

    protected $pageFactory;
    protected $modelNewsFactory;
    protected $transportBuilder;
    protected $inlineTranslation;
    protected $storeManager;
    protected $modelNewsResource;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        InquiryFactory $modelNewsFactory,
        ResourceInquiry $modelNewsResource,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->pageFactory = $pageFactory;
        $this->modelNewsFactory = $modelNewsFactory;
        $this->modelNewsResource = $modelNewsResource;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->storeManager = $storeManager;
        return parent::__construct($context);
    }

    public function execute()
    {
        if (!$this->getRequest()->isPost()) {
            $this->_redirect($this->_redirect->getRefererUrl());
            return;
        }
        
        try {
            $data = $this->_request->getParams();
            $newsModel = $this->modelNewsFactory->create();
            $newsModel->setName($data['name']);
            $newsModel->setEmail($data['email']);
            $newsModel->setTelephone($data['telephone']);
            $newsModel->setDetails($data['details']);
            $newsModel->setProductName($data['product_name']);
            $this->modelNewsResource->save($newsModel);

            $emailHelper = $this->_objectManager->get('MageArab\ProductInquiry\Helper\Email');
            $receiverInfo = ['name' => $data['name'],'email' => $data['email']];
            $senderInfo = ['name' => $emailHelper->getSalesName(),$emailHelper->getSalesEmail()];
            $emailTemplateVariables = array();
            $emailTempVariables['name'] = trim($data['name']);
            $emailTempVariables['email'] = trim($data['email']);
            $emailTempVariables['telephone'] = trim($data['telephone']);
            $emailTempVariables['details'] = trim($data['details']);
            $emailTempVariables['product_name'] = trim($data['product_name']);
            // @todo finish customer template
            //$emailHelper->sendMail($emailTemplateVariables, $senderInfo, $receiverInfo);
            $emailHelper->sendMail($emailTemplateVariables, $senderInfo, $senderInfo, 'admin_inquiry_template');

            $this->messageManager->addSuccessMessage(
                __("Thanks for contacting us with your comments and questions. We'll respond to you very soon.")
            );
            $this->_redirect($this->_redirect->getRefererUrl());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                $e->getMessage()
                //__('We can\'t process your request right now. Sorry, that\'s all we know.')
            );
            $this->_redirect($this->_redirect->getRefererUrl());
            return;
        }
    }
}
