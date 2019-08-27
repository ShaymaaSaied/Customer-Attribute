<?php
/**
 * Created By Shaymaa at 15/04/19 21:09.
 */

/**
 * Created by PhpStorm.
 * User: Shaymaa
 * Date: 15/04/2019
 * Time: 21:09
 */

namespace MageArab\CustomerMobile\Controller\CustomerMobile;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\App\Action\Context;

class Save extends \Magento\Framework\App\Action\Action {

    protected $customerRepository;

    protected $formKeyValidator;

    protected $session;

    protected $customer;

    protected $customerFactory;

    protected $sessionModel;

    public function __construct(
        Context $context,
        \Magento\Customer\Model\SessionFactory                 $customerSession,
        \Magento\Customer\Api\CustomerRepositoryInterface      $customerRepository,
        \Magento\Customer\Model\CustomerFactory                $customerFactory,
        \Magento\Customer\Model\Customer                       $customerModel,
        Validator                                              $formKeyValidator,
        \Magento\Customer\Model\Session                        $sessionModel
    ) {
        parent::__construct($context);
        $this->session = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->formKeyValidator = $formKeyValidator;
        $this->customerFactory  = $customerFactory;
        $this->customer=$customerModel;
        $this->sessionModel=$sessionModel;
    }

    public function execute(){
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$this->validateFormKey()) {
            return $resultRedirect->setPath('*/*/edit');
        }
        if ($data=$this->getRequest()->getPostValue()) {

            $sessionModel = $this->session->create();

            $currentCustomer = $this->customerFactory->create()->load($sessionModel->getCustomer()->getId());
            $currentCustomer->setGithubUrl($data['customer_mobile']);
            try {
                $currentCustomer->save();
                //$this->customerRepository->save($currentCustomer);
                $this->messageManager->addSuccess(__('You saved the account information.'));
                return $resultRedirect->setPath('customer/account');
            } catch (UserLockedException $e) {
                $message = __(
                    'You did not sign in correctly or your account is temporarily disabled.'
                );
                $this->session->logout();
                $this->session->start();
                $this->messageManager->addError($message);
                return $resultRedirect->setPath('customer/account/login');
            } catch (InputException $e) {
                $this->messageManager->addError($e->getMessage());
                foreach ($e->getErrors() as $error) {
                    $this->messageManager->addError($error->getMessage());
                }
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('We can\'t save the customer.'));
            }

            $this->sessionModel->setCustomerFormData($this->getRequest()->getPostValue());
        }
        //
        return $resultRedirect->setPath('*/*/edit');
    }

    protected function validateFormKey(){
        return $this->formKeyValidator->validate($this->getRequest());
    }
    public function getCustomer(){
        $sessionModel = $this->session->create();
        return $this->customer->load($sessionModel->getCustomer()->getId());
    }

}