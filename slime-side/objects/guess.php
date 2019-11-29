<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Contol-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Contol-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database & object files
include_once '../config/database-loc.php';
include_once '../api.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$api = new API($db);

if (isset($_GET['channelId']) && isset($_GET['guess']) && isset($_GET['twitchUserId'])/*  && isset($_GET['action']) */) {
    if (is_numeric($_GET['channelId']) && is_numeric($_GET['guess']) && is_numeric($_GET['twitchUserId'])){
        $channelId = $_GET['channelId'];
        $twitchUserId = $_GET['twitchUserId'];
        $guess = $_GET['guess'];
    } else {
        http_response_code(406);
        echo json_encode(
            array(
                "message" => "fuck you"
            )
        );
        die;
    }
};

$returnMessage = array();
    
// check if guess is in range
if ($guess <= 10 && $guess >= 1) {
    // check if user is in database
    if (!$api->checkUserInDatabase($twitchUserId, $channelId)){
        // not id database->add to db
        $api->addUserToDatabase($twitchUserId, $channelId);
        // $returnMessage['user'] = "not in db";
    }
    // $returnMessage['user'] = "in db";
    // subtrack poins for playing
    $api->subtractEntryFee($twitchUserId, $channelId, 5);
    if ($api->compareNumbers($guess) == 'won'){
        // award poind for winning
        $api->addPointsToUser($twitchUserId, $channelId, 50);
        //announcing that user won
        $returnMessage['result'] = "won";
    } else {
        // telling user to git gut
        $returnMessage['result'] = "loss";
    };
    // tell user how many poins he/she/it has
    $returnMessage['currentPoints'] = $api->userCurrentPoints($twitchUserId, $channelId);
} else {
    $returnMessage['result'] = "numberRange";
}

// send messages back to client
echo json_encode($returnMessage);