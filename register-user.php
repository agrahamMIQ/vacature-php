<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Vacature</title>
    <link href="css/main.css" type="text/css" rel="stylesheet" />
</head>
<body class="register">
<img class="logo" src="images/logo.png" width="269" />
<?php

if( isset( $_POST['email'] ) || isset( $_POST['password'] ) ) {
    require_once 'functions.php';
    
    $post_data['email'] = htmlspecialchars( $_POST['email'] );
    $post_data['first_name'] = htmlspecialchars( $_POST['first_name'] );
    $post_data['last_name'] = htmlspecialchars( $_POST['last_name'] );
    $post_data['language'] = htmlspecialchars( $_POST['language'] );
    $post_data['titulation'] = htmlspecialchars( $_POST['titulation'] );
    $post_data['siteregistered'] = htmlspecialchars( $_POST['siteregistered'] );
    
    $errors = array();
    
    if( empty( $_POST['first_name'] ) || strcasecmp( $_POST['first_name'], 'Voornaam' ) == 0 ) {
        $errors[] = '<p>You forgot to enter your first name.</p>';
    }
    
    if( empty( $_POST['last_name'] ) || strcasecmp( $_POST['last_name'], 'Naam' ) == 0 ) {
        $errors[] = '<p>You forgot to enter your last name.</p>';
    }
    
    if( empty( $_POST['email'] ) || strcasecmp( $_POST['email'], 'e-mail' ) == 0 ) {
        $errors[] = '<p>You forgot to enter an email address.</p>';
    }
    
    if( empty( $_POST['language'] ) ) {
        $errors[] = '<p>You forgot to specify a language.</p>';
    }
    
    if( empty( $_POST['titulation'] ) ) {
        $errors[] = '<p>You forgot to specify your gender.</p>';
    } else { //convert values to 0/1
        if( $post_data['titulation'] == "male" ) {
            $post_data['titulation'] = "0";
        } elseif( $post_data['titulation'] == "female" ) {
            $post_data['titulation'] = "1";
        }
    }
    
    if( empty( $errors ) ) {
        $url = 'http://dashboard.vacature.com/api/user/create/format/json';
        
        $json = curl_request( $url, $post_data );
        $data = json_decode( $json );
        
        foreach( $data as $key => $value ) {
            if( $key == 'response' ) {
                $response = $value;
            } else if( $key == "UserMD5" ) {
                $md5 = $value;
            } else if( $key == "userid" ) {
                $userid = $value;
            }
        }
        unset( $key );
        
        if( $response === "added" ) {
            echo "<p>Your account has been created, please activate account by clicking the link that was sent to your email. <a href=\"log-in.html\" target=\"_top\"><img src=\"images/login.png\" alt=\"Login\"/></a></p>";
        } else {
            echo "<p>There was an error in creating your account.</p>";
        }
    }
}

if( ( !isset( $_POST['email'] ) || !isset( $_POST['password'] ) ) || ( !empty($errors) ) ) { ?>
    <form id="register-form" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
        <?php foreach( $errors as $msg ) { ?>
        <p><?php echo $msg; ?></p>
        <?php } ?>
        <input type="text" name="first_name" size="20" maxlength="50" value="<?php if(isset($_POST['first_name'])) { echo $_POST['first_name']; } else { echo "Voornaam"; } ?>" <?php if(!isset($_POST['first_name']) || $_POST['first_name'] == 'Voornaam') { ?> onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" <?php } ?> />
        <input type="text" name="last_name" size="20" maxlength="50" value="<?php if(isset($_POST['last_name'])) { echo $_POST['last_name']; } else { echo "Naam"; } ?>" <?php if(!isset($_POST['last_name']) || $_POST['last_name'] == 'Naam') { ?> onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" <?php } ?> />
        <input type="text" name="email" size="20" maxlength="50" value="<?php if(isset($_POST['email'])) { echo $_POST['email']; } else { echo "E-mail"; } ?>" <?php if(!isset($_POST['email']) || $_POST['email'] == 'E-mail') { ?> onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" <?php } ?> />
        <div class="radio-container">
            <input type="radio" <?php if( $post_data['language'] === 'Dutch' || !isset($post_data['language']) ) { echo 'checked="checked"'; } ?> name="language" id="lang1" value="Dutch"/><label for="lang1">Dutch</label>
            <input type="radio" <?php if( $post_data['language'] === 'French' ) { echo 'checked="checked"'; } ?> name="language" id="lang2" value="French"/><label for="lang2">French</label>
            <input type="radio" <?php if( $post_data['language'] === 'English' ) { echo 'checked="checked"'; } ?> name="language" id="lang3" value="English"/><label for="lang3">English</label>
        </div>
        <div class="radio-container">
            <input type="radio" <?php if( $post_data['titulation'] === '0' || !isset($post_data['titulation']) ) { echo 'checked="checked"'; } ?> name="titulation" id="gender1" value="male"/><label for="gender1">Man</label>
            <input type="radio" <?php if( $post_data['titulation'] === '1' ) { echo 'checked="checked"'; } ?> name="titulation" id="gender2" value="female"/><label for="gender2">Vrouw</label>
        </div>
        <input type="hidden" name="siteregistered" value="0" />
        <input type="image" name="register" class="btn-register" src="images/register.png" alt="Registreer" />
    </form>
<?php } ?>
</body>
</html>