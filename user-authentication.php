<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Vacature</title>
    <link href="css/main.css" type="text/css" rel="stylesheet" />
</head>
<body class="login">
<img class="logo" src="images/logo.png" width="269" />
<?php
$cookie = htmlspecialchars($_COOKIE["pressrun"]);

if($cookie != '') {
    echo "<p>You are already logged in.</p>";
} else {
    if( isset( $_POST['email'] ) || isset( $_POST['password'] ) ) {
        require_once 'functions.php';
        
        //no validation at the moment for initial testing purposes. Need clarification as to whether API is escaping input already.
        $post_data['email'] = $_POST['email'];
        $post_data['password'] = $_POST['password'];
        $post_data['siteregistered'] = $_POST['siteregistered'];
        
        $errors = array();
        
        if( empty( $_POST['email'] ) ) {
            $errors[] = 'U hebt vergeten uw e-mailadres in te voeren';
        }
        
        if( empty( $_POST['password'] ) ) {
            $errors[] = 'U hebt vergeten uw wachtwoord in te voeren';
        }
        
        if( empty( $errors ) ) {
            $url = 'http://dashboard.vacature.com/api/user/authenticate/format/json';
            
            $json = curl_request( $url, $post_data );
            $data = json_decode( $json );
            
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
                        } else if( $userfield == "Mail" ) {
                            $email = $uservalue;
                        }
                    }
                    unset( $userfield );
                }
            }
            unset( $key );
            
            if( $response === "success" ) {
                set_login_cookie( json_encode( array( 'email' => $email, 'md5' => $md5, 'userid' => $userid ) ) );
                echo "<p>U bent nu ingelogd.</p>";
            } else {
                echo "<p>De referenties die u geleverd hebt zijn onjuist.</p>";
            }
        }
    }
    
    if( !isset( $_POST['email'] ) || !isset( $_POST['password'] ) || !empty($errors) ) { ?>
        <form id="login-form" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
            <?php foreach( $errors as $msg ) { ?>
            <p><?php echo $msg; ?></p>
            <?php } ?>
            <input type="text" name="email" size="20" maxlength="50" value="<?php if(isset($_POST['email'])) { echo $_POST['email']; } else { echo "E-mail"; } ?>" <?php if(!isset($_POST['email'])) { ?> onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" <?php } ?> />
            <input type="password" name="password" size="20" value="<?php if(isset($_POST['password'])) { echo $_POST['password']; } ?>" />
            <input type="hidden" name="siteregistered" value="0" />
            <input type="image" class="btn-login" name="submit" src="images/login.png" alt="Verstuur" />
        </form>
        <table class="register">
            <tr>
                <td><span>Of aanmedlen met Nieuwe gebruiker?</span></td>
                <td><a class="btn-register" href="register.html" target="_top"><img src="images/register.png" alt="Registreer"/></a></td>
            </tr>
    <?php }
}
/* Javascript to add the label background to the password input */
?>
<script type="text/javascript">
(function() {
    var id = document.getElementById('login-form');
    if (id && id.password) {
        var name = id.password;
        var unclicked = function() {
            if (name.value == '') {
                name.style.background = 'url(images/paswoord.png) 10px no-repeat';
            }
        };
        var clicked = function() {
            name.style.background = '';
        };
        name.onfocus = clicked;
        name.onblur = unclicked;
        unclicked();
    }
})();
</script>
</body>
</html>