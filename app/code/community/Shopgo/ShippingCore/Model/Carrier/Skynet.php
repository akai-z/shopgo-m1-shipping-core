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
 * Skynet carrier model
 *
 * @category    Shopgo
 * @package     Shopgo_ShippingCore
 * @author      Ammar <ammar@shopgo.me>
 */
class Shopgo_ShippingCore_Model_Carrier_Skynet extends Shopgo_ShippingCore_Model_Carrier_Abstract
{
    /**
     * SkyNet shipping module name
     */
    const MODULE_NAME = 'Shopgo_SkynetShipping';


    /**
     * Check if module is active and shipping service is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        if (Mage::helper('core')->isModuleEnabled(self::MODULE_NAME)) {
            return Mage::getModel('skynetshipping/shipment')->isEnabled();
        }

        return false;
    }

    /**
     * Check if shipping method is enabled and used in an order
     *
     * @param string $carrierCode
     * @return bool
     */
    public function isUsed($carrierCode)
    {
        if ($this->isEnabled()) {
            return $carrierCode == Mage::getModel('skynetshipping/carrier_skynet')
                ->getCarrierCode();
        }

        return false;
    }

    /**
     * Save shipment
     *
     * @param Mage_Sales_Model_Order_Shipment $shipment
     * @param array $data
     * @return bool
     */
    public function saveShipment($shipment, $data)
    {
        $skynetShipment = true;

        if (isset($data['skynet'])) {
            $skynetShipment = Mage::getModel('skynetshipping/shipment')
                ->prepareShipment(
                    $shipment,
                    $data['skynet']
                 );
        }

        if (!$skynetShipment) {
            if (isset($data['skynet']['shipment'])) {
                Mage::getSingleton('adminhtml/session')
                    ->setShipSkynetShipmentData($data['skynet']['shipment']);
            }
        }

        return $skynetShipment;
    }
}
