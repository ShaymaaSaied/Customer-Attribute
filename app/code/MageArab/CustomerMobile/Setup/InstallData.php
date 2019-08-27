<?php
/**
 * Created By Shaymaa at 15/04/19 20:49.
 */

/**
 * Created by PhpStorm.
 * User: Shaymaa
 * Date: 15/04/2019
 * Time: 20:49
 */

namespace MageArab\CustomerMobile\Setup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Config;
use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

class InstallData implements InstallDataInterface{

    private $_eavSetupFactory;
    private $_eavConfig;
    private $_customerSetupFactory;
    private $_attributeSetFactory;

    public function __construct(
        EavSetupFactory $eavSetupFactory,
        Config $eavConfig,
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ){
        $this->_eavSetupFactory = $eavSetupFactory;
        $this->_eavConfig       = $eavConfig;
        $this->_customerSetupFactory = $customerSetupFactory;
        $this->_attributeSetFactory = $attributeSetFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context){

        /*Customer Module Setup*/
        $customerSetup = $this->_customerSetupFactory->create(['setup' => $setup]);
        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        /*Get Default Attribute Set*/
        $attributeSet = $this->_attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);


        /*$eavSetup = $this->_eavSetupFactory->create(['setup' => $setup]);*/

        $setup->startSetup();

        /*Attribute Params*/
        $attributeParams=[
            'type'                  => 'varchar',
            'label'                 => 'Customer Mobile',
            'input'                 => 'text',
            'required'              => true,
            'unique'                => false,
            'visible'               => true,
            'user_defined'          => true,
            'position'              => 100,
            'system'                => false,
            'is_used_in_grid'       => true,
            'is_visible_in_grid'    => true,
            'is_filterable_in_grid' => true,
            'is_searchable_in_grid' => true,
            'validate_rules' => '{"max_text_length":14,"input_validation":"number"}'];

        $customerSetup->addAttribute(Customer::ENTITY, 'customer_mobile', $attributeParams);
        $attribute = $customerSetup->getEavConfig()
            ->getAttribute(Customer::ENTITY, 'customer_mobile')
            ->addData(
                [
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms'=> [
                        'adminhtml_customer',
                        'customer_account_create',
                        'customer_account_edit'
                    ]
                ]
            );
        $attribute->save();
        $setup->endSetup();
    }


}