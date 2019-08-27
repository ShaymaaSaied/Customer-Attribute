<?php
/**
 * Created By Shaymaa at 15/04/19 21:08.
 */

/**
 * Created by PhpStorm.
 * User: Shaymaa
 * Date: 15/04/2019
 * Time: 21:08
 */

namespace MageArab\CustomerMobile\Controller\CustomerMobile;


class Edit extends \Magento\Framework\App\Action\Action {

    protected $_resultPageFactory;

    public function execute(){
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }

}