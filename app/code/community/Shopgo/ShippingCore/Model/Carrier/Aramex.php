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
 * Aramex carrier model
 *
 * @category    Shopgo
 * @package     Shopgo_ShippingCore
 * @author      Ammar <ammar@shopgo.me>
 */
class Shopgo_ShippingCore_Model_Carrier_Aramex extends Shopgo_ShippingCore_Model_Carrier_Abstract
{
    const MODULE_NAME = 'Shopgo_AramexShipping';

    /**
     * Check if module is active
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return Mage::helper('core')->isModuleEnabled(self::MODULE_NAME);
    }

    /**
     * Check if shipping method is enabled and used in an order
     *
     * @param string $carrierCode
     * @return boolean
     */
    public function isUsed($carrierCode)
    {
        return $this->isEnabled()
            && $carrierCode == Mage::getModel('aramexshipping/carrier_aramex')->getCarrierCode();
    }

    /**
     * Save shipment
     *
     * @param object $shipment
     * @param array $data
     * @return boolean
     */
    public function saveShipment($shipment, $data)
    {
        $aramexShipment = true;

        if (isset($data['aramex'])) {
            $aramexShipment = Mage::getModel('aramexshipping/shipment')
                ->prepareShipment(
                    $shipment,
                    $data['aramex']
                 );
        }

        if (!$aramexShipment) {
            if (isset($data['aramex']['shipment'])) {
                Mage::getSingleton('adminhtml/session')
                    ->setShipAramexShipmentData($shopgoData['aramex']['shipment']);
            }

            if (isset($data['aramex']['pickup']['enabled'])
                && $data['aramex']['pickup']['enabled']) {
                Mage::getSingleton('adminhtml/session')
                    ->setShipAramexPickupData($data['aramex']['pickup']);
            }
        }

        return $aramexShipment;
    }
}
