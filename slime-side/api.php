<?php
class API {
    // database connection & table name
    private $conn;
    private $table_name = 'guessNumber';

    // object properties
    private $channel_id;
    private $twitchUserId;
    private $dispaly_name;
    private $points = 50;

    public function __construct($db){
        $this->conn = $db;
    }

    /**
     * Checks if user is already in database
     *
     * @param int $twitchUserId
     * @param int $channelId
     * @return boolean
     */
    public function checkUserInDatabase($twitchUserId, $channelId){
        $query = "SELECT
                    twitchUser_id
                    FROM
                        " . $this->table_name . "
                    WHERE
                        twitchUser_id = " . $twitchUserId . "
                    AND
                        channel_id = " . $channelId . "

        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // echo json_encode($user);

        if ($user) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Adds user to database, and sets default values (points)
     *
     * @param int $twitchUserId
     * @param int $channelId
     * @return void
     */
    public function addUserToDatabase($twitchUserId, $channelId) {
        $query ="INSERT INTO
                        " . $this->table_name . "
                    SET
                        channel_id = :channel_id,
                        twitchUser_id = :twitchUser_id,
                        points = :points
        ";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        //sanitize
        $this->channelId=htmlspecialchars(strip_tags($channelId));
        $this->twitchUserId=htmlspecialchars(strip_tags($twitchUserId));

        $stmt->bindParam(':channel_id', $this->channelId);
        $stmt->bindParam(':twitchUser_id', $this->twitchUserId);
        $stmt->bindParam(':points', $this->points);

        $stmt->execute();
    }

    /**
     * Subtract points from total amount of points for playing
     *
     * @param int $twitchUserId
     * @param int $channelId
     * @param int $amount
     * @return void
     */
    public function subtractEntryFee($twitchUserId, $channelId, $amount){
        $query = "UPDATE
                        " . $this->table_name . "
                    SET 
                        points = (points - " . $amount ."),
                        modified = now()
                    WHERE
                        twitchUser_id = " . $twitchUserId . "
                    AND
                        channel_id = " . $channelId . "
        ";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute
        $stmt->execute();
    }

    /**
     * Adds points to user
     *
     * @param int $twitchUserId
     * @param int $channelId
     * @param int $amount amount of point won
     * @return void
     */
    public function addPointsToUser($twitchUserId, $channelId, $amount){
        $query = "UPDATE
                        " . $this->table_name . "
                    SET 
                        points = (points + " . $amount ."),
                        modified = now()
                    WHERE
                        twitchUser_id = " . $twitchUserId . "
                    AND
                        channel_id = " . $channelId . "
        ";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute
        $stmt->execute();
    }

    /**
     * Gets current number of points of user on channel
     *
     * @param int $twitchUserId
     * @param int $channelId
     * @return int
     */
    public function userCurrentPoints($twitchUserId, $channelId){
        $query = "SELECT
                    points
                    FROM
                        " . $this->table_name . "
                    WHERE
                        twitchUser_id = " . $twitchUserId . "
                    AND
                        channel_id = " . $channelId . "
                ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $currentPoints = $stmt->fetch(PDO::FETCH_ASSOC);

        return $currentPoints;
    }

    /**
     * Generates random number in range
     * 
     * @return int
     */
    public function rollRandomNumber(){
        $randomNumber = floor(rand(1, 10));
        return $randomNumber;
    }

    /**
     * Returns game result
     * 
     * @param int $guess Number from user
     * @return string
     */
    public function compareNumbers($guess){
        if ($guess == $this->rollRandomNumber()){ // numbers match
            return $result = "won";
        } else {
            return $result = "loss";
        }
    }
}