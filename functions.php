<?php
function curl_request( $url, $post ) {
    if($post != '') {
        foreach ( $post as $key => $value ) {
            $post_items[] = $key . '=' . $value;
        }
        $post_string = implode( '&', $post_items );
    }
    
    $curl_connection = curl_init( $url );
    curl_setopt( $curl_connection, CURLOPT_CONNECTTIMEOUT, 30 );
    curl_setopt( $curl_connection, CURLOPT_USERAGENT,
    "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)" );
    curl_setopt( $curl_connection, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $curl_connection, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $curl_connection, CURLOPT_FOLLOWLOCATION, 1 );
    if($post != '') { curl_setopt( $curl_connection, CURLOPT_POSTFIELDS, $post_string ); }
    $result = curl_exec( $curl_connection );
    curl_close( $curl_connection );
    return $result;
}

function check_post($whitelist) {
    foreach ($_POST as $key=>$item) {
        if (!in_array($key, $whitelist)) {
            die("Please use only the fields in the form");
        }
    }
}

function stripcleantohtml($input){
    return htmlentities(trim(strip_tags(stripslashes($s))), ENT_NOQUOTES, "UTF-8");
}

function set_login_cookie($value) {
    setcookie("pressrun", $value, time() + 1281600); //1 year
}

function clear_login_cookie() {
    setcookie("pressrun", "", time() - 3600);
}
?>