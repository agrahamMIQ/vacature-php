<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Vacature</title>
    <link href="css/main.css" type="text/css" rel="stylesheet" />
</head>
<body class="fav-image">
<?php
require_once 'functions.php';

$cookie = json_decode( $_COOKIE["pressrun"] );

/* Get jobid from last segment of parent URL */
$pageurl = parse_url( $_SERVER['HTTP_REFERER'], PHP_URL_PATH );
$parts = array_pop( explode( '/', $pageurl ) );
$stem = explode( '.', $parts );
$jobid = $stem[0];

$jobid = "12215379"; //testing

if($cookie != '') {
    foreach( $cookie as $key => $value ) {
        if( $key === "userid" ) {
            $userid = $value;
        } else if( $key === "md5" ) {
            $md5 = $value;
        }
    }
    unset( $key );
    
    if( isset( $_POST['jobid'] ) ) {
        $favurl = "http://dashboard.vacature.com/api/user/favouritejob/userid/$userid/jobid/$jobid/md5/$md5/format/json";
        $response = curl_request( $favurl );
    }
    $url = "http://dashboard.vacature.com/api/user/favouritestatus/userid/$userid/jobid/$jobid/md5/$md5/format/json";
    
    $response = curl_request( $url ); ?>
    
    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
    <?php if( $response == 'true' ) { ?>
        <input type="image" name="fav-job" width="100px" src="images/favourite-true.gif" alt="favourite job" />
    <?php } else { ?>
        <input type="image" name="fav-job" width="100px" src="images/favourite-false.gif" alt="favourite job" />
    <?php } ?>
        <input type="hidden" name="jobid" value="<?php echo $jobid; ?>" />
    </form>
<?php
} else {
    //output generic favourite image with login link, which should appear as a popup ?>
    <a href="log-in.html" target="_top"><img width="100px" src="images/favourite-false.gif" alt="log in" /></a>
<?php } ?>
</body>
</html>