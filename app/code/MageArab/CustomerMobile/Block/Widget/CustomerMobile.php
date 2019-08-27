<?php
/**
 * Created By Shaymaa at 15/04/19 21:03.
 */
namespace MageArab\CustomerMobile\Block\Widget;


class CustomerMobile extends \Magento\Customer\Block\Widget\AbstractWidget {

    /*Attribute Code is customer_mobile*/
    const ATTRIBUTE_CODE = 'customer_mobile';

    protected $_customerMetadata;
    protected $_addressHelper;
    protected $_customerSession;
    protected $_customerRepository;
    protected $options;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context         $context,
        \Magento\Customer\Model\Options                          $options,
        \Magento\Customer\Helper\Address                         $addressHelper,
        \Magento\Customer\Api\CustomerMetadataInterface          $customerMetadata,
        \Magento\Customer\Model\SessionFactory                   $customerSession,
        \Magento\Customer\Model\Customer                         $customerModel,

        array $data = []
    ) {
        $this->options = $options;
        parent::__construct($context, $addressHelper, $customerMetadata, $data);
        $this->_customerMetadata=$customerMetadata;
        $this->_addressHelper=$addressHelper;
        $this->_customerSession = $customerSession;
        $this->_customerRepository = $customerModel;
        $this->_isScopePrivate = true;
    }

    public function _construct()
    {
        parent::_construct();

        // default template location
        $this->setTemplate('widget/customer_mobile.phtml');
    }

    /* Check attribute property 'visible' */
    public function isEnabled(){

        return $this->_getAttribute(self::ATTRIBUTE_CODE) ? (bool)$this->_getAttribute(self::ATTRIBUTE_CODE)->isVisible() : false;
    }

    /* Check for required property*/
    public function isRequired(){
        return $this->_getAttribute(self::ATTRIBUTE_CODE) ? (bool)$this->_getAttribute(self::ATTRIBUTE_CODE)
            ->isRequired() : false;
    }

    /* Get validation rules 'url validate','max length'*/
    public function getAttributeValidationClass(){
        return $this->_getAttribute(self::ATTRIBUTE_CODE)->getFrontendClass();
    }

    protected function _getAttribute($attributeCode){
        try {
            return $this->_customerMetadata->getAttributeMetadata($attributeCode);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return null;
        }
    }

    /* Get Customer to get attribute value*/
    public function getCustomer(){
        $sessionModel = $this->_customerSession->create();
        return $this->_customerRepository->load($sessionModel->getCustomer()->getId());
    }

    /* Return attribute value*/
    public function getCustomerMobile(){
        return $this->getCustomer()->getCustomerMobile();
    }

}