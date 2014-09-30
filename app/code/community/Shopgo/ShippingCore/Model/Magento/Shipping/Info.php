<?php

class Shopgo_ShippingCore_Model_Magento_Shipping_Info extends Mage_Shipping_Model_Info
{
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
