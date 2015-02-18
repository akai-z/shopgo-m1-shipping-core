<?php
/**
 * ShopGo
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License (GPLv2)
 * that is bundled with this package in the file COPYING.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @category    Shopgo
 * @package     Shopgo_ShippingCore
 * @copyright   Copyright (c) 2014 Shopgo. (http://www.shopgo.me)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License (GPLv2)
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
    const MODULE_NAME = 'Shopgo_SkynetShipping';

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
            && $carrierCode == Mage::getModel('skynetshipping/carrier_skynet')->getCarrierCode();
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
                    ->setShipSkynetShipmentData($shopgoData['skynet']['shipment']);
            }

            /*if (isset($data['skynet']['pickup']['enabled'])
                && $data['skynet']['pickup']['enabled']) {
                Mage::getSingleton('adminhtml/session')
                    ->setShipSkynetPickupData($data['skynet']['pickup']);
            }*/
        }

        return $skynetShipment;
    }
}
