<?php
/**
 * ShopGo
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category    Shopgo
 * @package     Shopgo_ShippingCore
 * @copyright   Copyright (c) 2014 Shopgo. (http://www.shopgo.me)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Dimensional weight attributes button block
 *
 * @category    Shopgo
 * @package     Shopgo_ShippingCore
 * @author      Ammar <ammar@shopgo.me>
 */
class Shopgo_ShippingCore_Block_Adminhtml_System_Config_Form_DwaButton extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Set phtml template
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $template = $this->setTemplate('shopgo/shipping_core/system/config/dwa_button.phtml');
    }

    /**
     * Get the button and scripts contents
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->_toHtml();
    }

    /**
     * Get set attributes ajax action URL
     *
     * @return string
     */
    public function getSetAttributesAjaxActionUrl()
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/shopgo_shippingcore/setdwattributes');
    }

    /**
     * Get attribute set groups ajax action URL
     *
     * @return string
     */
    public function getAsgAjaxActionUrl()
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/shopgo_shippingcore/getdwasetgroups');
    }

    /**
     * Get the button and scripts contents
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')->setData(
            array(
                'id'      => 'shipping_core_dwa_trigger',
                'label'   => $this->helper('adminhtml')->__('Set Attributes'),
                'onclick' => 'javascript:shopgo.shippingCore.dwaButton.setAttributes(); return false;'
            )
        );

        return $button->toHtml();
    }
}
