<?php

class Shopgo_ShippingCore_Model_Carrier_Abstract extends Mage_Core_Model_Abstract
{
    public function processTrackingInfo($trackingInfo)
    {
        if (count($trackingInfo) == 1) {
            $trackingInfo = $trackingInfo[0];
        } else {
            foreach ($trackingInfo as $i => $ti) {
                $gti = array();

                if (isset($trackingInfo[$i + 1])) {
                    $gti = array_merge($ti, $trackingInfo[$i + 1]);
                }

                if (!empty($gti)) {
                    $trackingInfo = array_merge($trackingInfo, $gti);
                }
            }
        }

        return $trackingInfo;
    }

    public function processTrackingInfoByTrackId($trackingInfo)
    {
        return array($trackingInfo);
    }
}
