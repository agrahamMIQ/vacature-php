<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Vacature</title>
    <link href="css/main.css" type="text/css" rel="stylesheet" />
</head>
<body>
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
    
    if( empty( $_POST['email'] ) ) {
        $errors[] = '<p>You forgot to enter an email address.</p>';
    }
    
    if( empty( $_POST['first_name'] ) ) {
        $errors[] = '<p>You forgot to enter your first name.</p>';
    }
    
    if( empty( $_POST['last_name'] ) ) {
        $errors[] = '<p>You forgot to enter your last name.</p>';
    }
    
    if( empty( $_POST['language'] ) ) {
        $errors[] = '<p>You forgot to specify a language.</p>';
    }
    
    if( empty( $_POST['titulation'] ) ) {
        $errors[] = '<p>You forgot to specify your gender.</p>';
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
            echo "<p>Your account has been created, please activate account by clicking the link that was sent to your email. <a href=\"log-in.html\" target=\"_top\"></a></p>";
        } else {
            echo "<p>There was an error in creating your account.</p>";
        }
    } else {
        foreach( $errors as $msg ) { ?>
            <p><?php echo $msg; ?></p>
        <?php
        }
    }
}

if( !isset( $_POST['email'] ) || !isset( $_POST['password'] ) || !empty($errors) ) { ?>
    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
        <input type="text" name="email" size="20" maxlength="50" value="<?php if(isset($_POST['email'])) { echo $_POST['email']; } ?>" />
        <input type="text" name="first_name" size="20" maxlength="50" value="<?php if(isset($_POST['first_name'])) { echo $_POST['first_name']; } ?>" />
        <input type="text" name="last_name" size="20" maxlength="50" value="<?php if(isset($_POST['last_name'])) { echo $_POST['last_name']; } ?>" />
        <select name="language">
            <option value="30200">Dutch</option>
            <option value="30201">French</option>
            <option value="30202">English</option>
        </select>
        <select name="titulation">
            <option value="0">Male</option>
            <option value="1">Female</option>
        </select>
        <input type="hidden" name="siteregistered" value="0" />
        <input type="image" name="register" src="images/register.png" alt="Registreer" />
    </form>
    
    <a href="login.html" target="_top" class="login">Login</a>
<?php }
?>