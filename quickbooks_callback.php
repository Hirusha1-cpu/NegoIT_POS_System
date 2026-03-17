<?php
session_start();
require_once(__DIR__ . '/plugin/Quickbooks/vendor/autoload.php');
use QuickBooksOnline\API\DataService\DataService;
include('config.php');

$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks'");
$row = mysqli_fetch_assoc($result);
$quickbooks = $row['value'];

$result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='url_primary'");
$row = mysqli_fetch_assoc($result);
$url_primary = $row['value'];
$redirect_url = 'http://' . $url_primary;

function processCallbackCode()
{
    include('config.php');
    $result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='url_primary'");
    $row = mysqli_fetch_assoc($result);
    $url_primary = $row['value'];

    $oauth_scope = 'com.intuit.quickbooks.accounting';
    $oauth_redirect_uri = 'http://' . $url_primary . '/quickbooks_callback.php';

    $result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks_base_url'");
    $row = mysqli_fetch_assoc($result);
    $baseUrl = $row['value'];

    $result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks_client_id'");
    $row = mysqli_fetch_assoc($result);
    $client_id = $row['value'];

    $result = mysqli_query($conn2, "SELECT `value` FROM settings WHERE `setting`='quickbooks_client_secret'");
    $row = mysqli_fetch_assoc($result);
    $client_secret = $row['value'];

    // Create SDK instance
    $dataService = DataService::Configure(array(
        'auth_mode' => 'oauth2',
        'ClientID' => $client_id,
        'ClientSecret' => $client_secret,
        'RedirectURI' => $oauth_redirect_uri,
        'scope' => $oauth_scope,
        'baseUrl' => $baseUrl
    ));

    $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

    /*
     * Update the OAuth2Token
     */
    if (isset($_GET['code']) && isset($_GET['realmId'])) {
        $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($_GET['code'], $_GET['realmId']);
        $dataService->updateOAuth2Token($accessToken);
        $realm_id = $_GET['realmId'];
    } else {
        echo "Error: Missing code or realmId.";
        exit;
    }

    $error = $dataService->getLastError();
    if ($error) {
        echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
        echo "The Response message is: " . $error->getResponseBody() . "\n";
        exit;
    } else {
        // Access token should not be called again
        $refresh_token = $accessToken->getRefreshToken();
        /*
         * Save necessary data in database
         */
        $result = mysqli_query($conn, "UPDATE settings SET `value` = '$realm_id' WHERE `setting` = 'quickbooks_realmid'");
        $result = mysqli_query($conn, "UPDATE settings SET `value` = '$refresh_token' WHERE `setting` = 'quickbooks_refresh_token'");
    }

}
processCallbackCode();
header('location:' . $redirect_url);

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