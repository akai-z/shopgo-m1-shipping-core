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
 * Shipping info model
 *
 * @category    Shopgo
 * @package     Shopgo_ShippingCore
 * @author      Ammar <ammar@shopgo.me>
 */
class Shopgo_ShippingCore_Model_Magento_Shipping_Info extends Mage_Shipping_Model_Info
{
    /**
     * Get tracking info by order
     *
     * @return array
     */
    public function getTrackingInfoByOrder()
    {
        $shipTrack = array();
        $order = $this->_initOrder();
        if ($order) {
            $shipments = $order->getShipmentsCollection();
            foreach ($shipments as $shipment){
                $increment_id = $shipment->getIncrementId();
                $tracks = $shipment->getTracksCollection();

                $trackingInfos = array();
                foreach ($tracks as $track){
                    $trackingInfos[] = $track->getNumberDetail();
                }

                $trackingInfos = Mage::getModel('shippingcore/core')
                    ->processTrackingInfo(
                        $trackingInfos,
                        $order->getShippingCarrier()->getCarrierCode()
                    );

                $shipTrack[$increment_id] = $trackingInfos;
            }
        }
        $this->_trackingInfo = $shipTrack;
        return $this->_trackingInfo;
    }

    /**
     * Get tracking info by ship
     *
     * @return array
     */
    public function getTrackingInfoByShip()
    {
        $shipTrack = array();
        $shipment = $this->_initShipment();
        if ($shipment) {
            $increment_id = $shipment->getIncrementId();
            $tracks = $shipment->getTracksCollection();

            $trackingInfos = array();
            foreach ($tracks as $track){
                $trackingInfos[] = $track->getNumberDetail();
            }

            $trackingInfos = Mage::getModel('shippingcore/core')
                ->processTrackingInfo(
                    $trackingInfos,
                    $shipment->getOrder()->getShippingCarrier()->getCarrierCode()
                );

            $shipTrack[$increment_id] = $trackingInfos;

        }
        $this->_trackingInfo = $shipTrack;
        return $this->_trackingInfo;
    }

    /**
     * Get tracking info by track ID
     *
     * @return array
     */
    public function getTrackingInfoByTrackId()
    {
        $track = Mage::getModel('sales/order_shipment_track')->load($this->getTrackId());
        if ($track->getId() && $this->getProtectCode() == $track->getProtectCode()) {
            $this->_trackingInfo = Mage::getModel('shippingcore/core')
                ->processTrackingInfoByTrackId(
                    $track->getNumberDetail(),
                    $track->getCarrierCode()
                );
        }
        return $this->_trackingInfo;
    }
}
