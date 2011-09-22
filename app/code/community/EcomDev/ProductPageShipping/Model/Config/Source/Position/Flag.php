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
 * Display in position inserting logic source model
 *
 */
class EcomDev_ProductPageShipping_Model_Config_Source_Position_Flag
{
    /**
     * Returns after flag values for insert method
     * 
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array(
                'label' => Mage::helper('ecomdev_productpageshipping')->__('Before Block / First'),
                'value' => 0
            ),
            array(
                'label' => Mage::helper('ecomdev_productpageshipping')->__('After Block / Last'),
                'value' => 1
            )
        );
    }
}
