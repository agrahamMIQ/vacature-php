<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Vacature Login</title>
</head>
<body>
<?php
$cookie = htmlspecialchars($_COOKIE["pressrun"]);

if($cookie != '') {
    echo "You are already logged in.";
} else {
    if( !isset( $_POST['submit'] ) ) { ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="email" size="20" maxlength="50" value="E-mail" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" />
            <input type="password" name="password" size="20" value="" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" />
            <input type="submit" name="submit" value="Submit"/>
        </form>
    
    <?php
    } else {
        error_reporting(-1);
        require_once 'functions.php';
        
        // Building a whitelist array with keys which will send through the form, no others would be accepted later on
        $whitelist = array('email', 'password', 'submit');
        check_post($whitelist);
        
        $post_data['email'] = stripcleantohtml( $_POST['email'] );
        $post_data['password'] = stripcleantohtml( $_POST['password'] );
        $post_data['siteregistered'] = 0;
        $url = 'http://dashboard.vacature.com/api/user/authenticate/format/json';
        
        $json = curl_request( $url, $post_data );
        $data = json_decode( $json );
        $response = "";
        $md5 = "";
        $userid = "";
        
        foreach( $data as $key => $value ) {
            if( $key == 'response' ) {
                $response = $value;
            }
            if( $key === 'user' ){
                foreach( $value as $userfield => $uservalue ) {
                    if( $userfield == "UserMD5" ) {
                        $md5 = $uservalue;
                    } else if( $userfield == "ID" ) {
                        $userid = $uservalue;
                    }
                }
                unset( $userfield );
            }
        }
        unset( $key );
        
        if( $response === "success" ) {
            set_login_cookie( json_encode( array( 'login' => 1, 'md5' => $md5, 'userid' => $userid ) ) );
            echo "<p>You are logged in.</p>";
        } else { 
            echo "<p>The credentials you supplied were incorrect.</p>";
        }
    }
}
?>