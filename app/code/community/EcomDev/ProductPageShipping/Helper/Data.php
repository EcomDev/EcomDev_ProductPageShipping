<?php
/**
 * Shipping Estimate extension
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
 * @copyright  Copyright (c) 2011 EcomDev BV (http://www.ecomdev.org)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Ivan Chepurnyi <ivan.chepurnyi@ecomdev.org>
 */

/**
 * Module helper for translations and some layout staff
 *
 */
class EcomDev_ProductPageShipping_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Returns module configuration model singleton
     *
     * @return EcomDev_ProductPageShipping_Model_Config
     */
    protected function _getConfig()
    {
        return Mage::getSingleton('ecomdev_productpageshipping/config');
    }
    
    /**
     * Retieve display positioning logic flag
     *
     * @return boolean
     */
    public function getDisplayPositionFlag()
    {
        return $this->_getConfig()->getDisplayPositionFlag();
    }
    
    /**
     * Retieve display positioning block, 
     * e.g. related sibling element for positioning
     *
     * @return string
     */
    public function getDisplayPositionBlock()
    {
        return $this->_getConfig()->getDisplayPositionBlock();
    }
    
}
