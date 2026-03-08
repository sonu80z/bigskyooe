<?php

if(!function_exists('res_write')) {
    function res_write($data) {
		header('Content-Type: application/json; charset=UTF-8');
        
        echo json_encode($data);
    }
}

/**
 * get dd hh mm from minute number
*/
if(!function_exists('get_ddhhmm')) {
    function get_ddhhmm($minute) {

        $time_counter = "";
        $tm = intval($minute);
        $tdays = intval($tm / 1440);
        $thours = intval(($tm - $tdays * 1440) / 60);
        $tminutes = ($tm - $tdays * 1440) - $thours * 60;
        $time_counter = $tdays."d ".$thours."h ".$tminutes;

        return $time_counter;
    }
}

/**
 * get string that attached zero in date ( month, day )
 */
if(!function_exists('get_zero_attached_num')) {
    function get_zero_attached_num($str) {
        $num = intval($str);
        if ( $num < 10 ) return "0".$str;
        return $str;
    }
}

/**
 * get hour from hh:mm:ss style
*/
if(!function_exists('get_hh_from_hhmmss')) {
    function get_hh_from_hhmmss($str) {
        $arr = explode(":", $str);
        return$arr[0];
    }
}

/**
 * get minute from hh:mm:ss style
 */
if(!function_exists('get_mm_from_hhmmss')) {
    function get_mm_from_hhmmss($str) {
        $arr = explode(":", $str);
        return $arr[1];
    }
}

/**
 * get second from hh:mm:ss style
 */
if(!function_exists('get_ss_from_hhmmss')) {
    function get_ss_from_hhmmss($str) {
        $arr = explode(":", $str);
        return $arr[2];
    }
}

/**
 * encode email address to url possbile string
 * @param $email: email address
 * @return $ret: encoded email that can send get url
*/
if ( !function_exists("enc_email_url")) {
    function enc_email_url( $email ) {
        if ( $email == "" || $email == null ) return null;
        $ret = str_replace("@", "z1z1z1z1z1", $email);
        return $ret;
    }
}

/**
 * decode decoded email address to original email
 * @param $enc: encoded email address
 * @return $ret: decode email
 */
if ( !function_exists("dec_email_url")) {
    function dec_email_url( $enc ) {
        if ( $enc == "" || $enc == null ) return null;
        $ret = str_replace("z1z1z1z1z1", "@", $enc);
        return $ret;
    }
}

/**
 * convert this system`s numerical style
 * @param $given: given number
 * @param $fixed: fixed length ( 0 > && < 6 => digital currency ( fixed => 8), 5 > => fiat currency ( fixed = > 2 ) )
 * @return $ret: converted number
 * 2018-09-25 by hmc
 */
if ( !function_exists("con_nor_num")) {
    function con_nor_num( $given, $fixed ) {

        if ( intval($fixed) > 0 && intval($fixed) < 6 ) $fixed = 8;
        else if ( intval($fixed) > 5 ) $fixed = 2;

        if ( $given == "" || $given == null ) $given = 0;
        $ret = number_format($given, $fixed, ',', '');
        return $ret;
    }
}

/**
 * convert this system`s numerical style
 * @param $str: given string
 * @return $ret: true if has only characters, false if has anotehr symbols like sa space, etc...
 * 2018-10-03 by hmc
 */
if ( !function_exists("has_only_character")) {
    function has_only_character( $str ) {
        if ( ctype_alpha($str) ) return true;
        else return false;
    }
}

