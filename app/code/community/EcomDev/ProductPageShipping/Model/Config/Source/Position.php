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
     * Shipping Estimator Positions Map
     * 
     * @var Varien_Object
     */
    protected static $_positions = null;
    
    /**
     * Returns list of possible options
     * 
     * @return Varien_Object 
     */
    public static function getPositions()
    {
        if (self::$_positions === null) {
            self::_initPositions();
        }
        
        return self::$_positions;
    }
    
    /**
     * Initializes possible positions for shipping estimator
     * 
     */
    protected static function _initPositions()
    {
        $positions =  new Varien_Object(array(
            EcomDev_ProductPageShipping_Model_Config::DISPLAY_POSITION_LEFT => array(
                'label' => Mage::helper('ecomdev_productpageshipping')->__('Left Column'),
                'handle' => EcomDev_ProductPageShipping_Model_Config::LAYOUT_HANDLE_LEFT
            ),
            EcomDev_ProductPageShipping_Model_Config::DISPLAY_POSITION_RIGHT => array(
                'label' => Mage::helper('ecomdev_productpageshipping')->__('Right Column'),
                'handle' => EcomDev_ProductPageShipping_Model_Config::LAYOUT_HANDLE_RIGHT
            ),
            EcomDev_ProductPageShipping_Model_Config::DISPLAY_POSITION_ADDITIONAL => array(
                'label' => Mage::helper('ecomdev_productpageshipping')->__('Additional Info Block'),
                'handle' => EcomDev_ProductPageShipping_Model_Config::LAYOUT_HANDLE_ADDITIONAL
            ),
            EcomDev_ProductPageShipping_Model_Config::DISPLAY_POSITION_CUSTOM => array(
                'label' => Mage::helper('ecomdev_productpageshipping')->__('Custom Layout'),
                'handle' => EcomDev_ProductPageShipping_Model_Config::LAYOUT_HANDLE_CUSTOM
            )
        ));
        
        Mage::dispatchEvent('ecomdev_productpageshipping_config_source_position_init', array('positions' => $positions));
        
        self::$_positions = $positions;
    }
    
    /**
     * Returns layout handle name for a shipping estimator position
     * 
     * @param string $position
     * @return string
     */
    public function getLayoutHandleName($position)
    {
        if (($handle = self::getPositions()->getData($position . '/handle'))) {
            return $handle;
        }
        
        return false;
    }
    
    /**
     * Return list of options for the system configuration field.
     * These options indicate the position of the form block on the page
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = array();
        
        foreach (array_keys(self::getPositions()->getData()) as $position) {
            $options[] = array(
                'value' => $position, 
                'label' => self::getPositions()->getData($position . '/label')
            );
        }
        
        return $options;
    }
    
    
}
