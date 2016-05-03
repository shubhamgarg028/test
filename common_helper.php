<?php

/**
 * @Name : formatActivatedDateDisplay()
 * @Purpose : To change the format of date.
 * @Call from : Can be called from any controller file.
 * @Functionality : display "Today", "yesterday" or the actual date 
 * @Receiver params : date string
 * @Return params : Return date or (string today/yesterday ) 
 * @Created : Hardeep Kaur <hardeep.intersoft@gmail.com> on 16 July 2015
 * @Modified :
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('getIpCountry')) {

    function getIpCountry() {
        $ip = $_SERVER["REMOTE_ADDR"];
        if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip),TRUE);
        return $ipdat['geoplugin_countryName'];
    }

}
?>