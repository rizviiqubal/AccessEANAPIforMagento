<?php

class Rizvi_AccessEAN_Block_List extends Mage_Catalog_Block_Product_Abstract {

    protected $_itemCollection = null;
    private $ean_api_domain = 'api.ean.com';
    private $ean_key = 'n4p35gjq67c7x6f9zdq2nmg3';
    private $image_domain = 'http://images.travelnow.com';

    public function getItems() {

        if (is_null($this->_itemCollection)) {
            $this->_itemCollection = $this->get_Hotel_List();
        }

        return $this->_itemCollection->HotelListResponse->HotelList->HotelSummary;
    }

    public function getImageDomain() {
        return $this->image_domain;
    }

    function make_request($api_method, $http_method = NULL, $data = NULL) {

        // Set request
        $request_url = 'http://' . $this->ean_api_domain . '/' . $api_method . '?cid=55505&apiKey=' . $this->ean_key . '&' . $data;

        // Create a cURL handle
        $ch = curl_init();
        // Set the request
        curl_setopt($ch, CURLOPT_URL, $request_url);

        // Use HTTP Basic Authentication
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        // Save the response to a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        // Send data as PUT request
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $http_method);


        // Execute cURL request
        $curl_response = curl_exec($ch);



        // Close cURL handle
        curl_close($ch);

        // Save response to class variable for use in debugging

        $parsed_result = $this->parse_response($curl_response);

        // Return parsed response
        return $parsed_result;
    }

    /**
     * Parse Json Response
     * */
    function parse_response($response) {

        $data = json_decode($response);

        return $data;
    }

    /**
     *Make request to fetch all hotel list regardless of search attributes [Only filtering is based in City and Response type
     * JSON]
     * */
    function get_Hotel_List() {
        $data = $this->make_request('ean-services/rs/hotel/v3/list', 'GET', 'city=dubai&stateProvinceCode=dubai&countryCode=UAE&_type=json');
        return $data;
    }

}
