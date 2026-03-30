<?php
require_once(__DIR__ . '/plugin/Quickbooks/vendor/autoload.php');
use QuickBooksOnline\API\DataService\DataService;

include('config.php');
$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks'");
$row = mysqli_fetch_assoc($result);
$quickbooks = $row['value'];

$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks_base_url'");
$row = mysqli_fetch_assoc($result);
$baseUrl = $row['value'];

$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='url_primary'");
$row = mysqli_fetch_assoc($result);
$url_primary = $row['value'];

$url_primary = 'http://' . $url_primary;
$oauth_redirect_uri = $url_primary . '/quickbooks_callback.php';
$oauth_scope = 'com.intuit.quickbooks.accounting';

$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks_client_id'");
$row = mysqli_fetch_assoc($result);
$client_id = $row['value'];

$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks_client_secret'");
$row = mysqli_fetch_assoc($result);
$client_secret = $row['value'];

$dataService = DataService::Configure(array(
    'auth_mode' => 'oauth2',
    'ClientID' => $client_id,
    'ClientSecret' => $client_secret,
    'RedirectURI' => $oauth_redirect_uri,
    'scope' => $oauth_scope,
    'baseUrl' => $baseUrl
));
$OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
$authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
// Redirect to the Authorization Page (QuickBooks)
header('location:' . $authUrl);
// if($quickbooks == 1){

// }else{
//     echo '<!DOCTYPE html>
//         <html lang="en">
//         <head>
//             <meta charset="UTF-8">
//             <meta name="viewport" content="width=device-width, initial-scale=1.0">
//             <style>
//                 body {
//                 display: flex;
//                 align-items: center;
//                 justify-content: center;
//                 height: 100vh;
//                 margin: 0;
//                 }

//                 div {
//                 text-align: center;
//                 }
//             </style>
//         </head>
//         <body>
//         <div>
//             <h4>Quickbooks is not enabled for this system!</h4>
//         </div>
//         </body>
//     </html>';
// }
?>