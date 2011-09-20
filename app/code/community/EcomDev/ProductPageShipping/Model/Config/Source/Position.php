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
 * Display in field options source model
 *
 */
class EcomDev_ProductPageShipping_Model_Config_Source_Position
{
    /**
     * Return list of options for the system configuration field.
     * These options indicate the position of the form block on the page
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => EcomDev_ProductPageShipping_Model_Config::DISPLAY_POSITION_LEFT,
                'label' => Mage::helper('ecomdev_productpageshipping')->__('Left Column')
            ),
            array(
                'value' => EcomDev_ProductPageShipping_Model_Config::DISPLAY_POSITION_RIGHT,
                'label' => Mage::helper('ecomdev_productpageshipping')->__('Right Column')
            ),
            array(
                'value' => EcomDev_ProductPageShipping_Model_Config::DISPLAY_POSITION_CUSTOM,
                'label' => Mage::helper('ecomdev_productpageshipping')->__('Custom Position')
            ),
        );
    }
}
