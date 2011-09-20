<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   EcomDev
 * @package    EcomDev_ProductPageShipping
 * @copyright  Copyright (c) 2010 Ecommerce Developer Blog (http://www.ecomdev.org)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Module observer
 *
 */
class EcomDev_ProductPageShipping_Model_Observer
{
    /**
     * Config model
     *
     * @var EcomDev_ProductPageShipping_Model_Config
     */
    protected $_config = null;

    /**
     * Retrieve configuration model for module
     *
     * @return EcomDev_ProductPageShipping_Model_Config
     */
    public function getConfig()
    {
        if ($this->_config === null) {
            $this->_config = Mage::getSingleton('ecomdev_productpageshipping/config');
        }

        return $this->_config;
    }

    /**
     * Layouts initializations observer,
     * add the form block into the position that was specified by the configuration
     *
     * @param Varien_Event_Observer $observer
     */
    public function observeLayoutHandleInitialization(Varien_Event_Observer $observer)
    {
        /* @var $controllerAction Mage_Core_Controller_Varien_Action */
        $controllerAction = $observer->getEvent()->getAction();
        $fullActionName = $controllerAction->getFullActionName();
        if ($this->getConfig()->isEnabled() && in_array($fullActionName, $this->getConfig()->getControllerActions())) {
            if ($this->getConfig()->getDisplayPosition() === EcomDev_ProductPageShipping_Model_Config::DISPLAY_POSITION_LEFT) {
                // Display the form in the left column on the page
                $controllerAction->getLayout()->getUpdate()->addHandle(
                    EcomDev_ProductPageShipping_Model_Config::LAYOUT_HANDLE_LEFT
                );
            } elseif ($this->getConfig()->getDisplayPosition() === EcomDev_ProductPageShipping_Model_Config::DISPLAY_POSITION_RIGHT) {
                // Display the form in the right column on the page
                $controllerAction->getLayout()->getUpdate()->addHandle(
                    EcomDev_ProductPageShipping_Model_Config::LAYOUT_HANDLE_RIGHT
                );
            }
        }
    }
}
