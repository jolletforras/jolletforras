<?php

namespace App\Http\Controllers\Traits;

trait ZipCodeTrait {

    /**
     * Check if it is a valid Hungarian zip_code
     *
     * @param  integer $zip_code
     * @return boolean
     */
    private function getCoordinates($zip_code) {

        if(!is_numeric($zip_code) || $zip_code<1000 || $zip_code>9999) {
            return false;
        }

        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode("HU, ".$zip_code).'&key='.env('GOOGLE_GEOCODE_API_KEY');
        $results = json_decode(file_get_contents($url),true);

        foreach($results['results'] as $rs) {
            $idx = count($rs['address_components'])-1;
            echo $idx.'<br>';
            if($rs['address_components'][$idx]['long_name']=='Hungary') {
                $name = $rs['address_components'][$idx]['long_name'];
                $location=$rs['geometry']['location'];
                break;
            }
        }

        if (empty($location)) {
            return false;
        }


        if (($location['lat']>49) || ($location['lat']<45) || ($location['lng']>23) || ($location['lng']<16)) {
            return false;
        }

        return $location;
    }

}