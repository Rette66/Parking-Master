<?php
session_start();
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $_SESSION['username'];
$selectedCancelRes = $selectedCancelRes_err = "";
$resTrackNum = null;
$cancel_err = "";
    
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cancelConfirm'])){
        //remove element:
        if(empty($_POST["reservations"])){
            $selectedCancelRes_err = "Please select a reservation";
        } else{
            $selectedCancelRes = $_POST["reservations"];
            $resTrackNum = substr($selectedCancelRes, 0, strpos($selectedCancelRes, ' '));
            $sql = "SELECT Date FROM reservation WHERE TrackNum = '$resTrackNum'";
            $query = mysqli_query($link, $sql);
            $row = mysqli_fetch_assoc($query);
            $resDate = $row['Date'];
            $currDate = date("Y-m-d");
            $date1 = new DateTime($resDate);
            $date2 = new DateTime($currDate);
            $interval = $date1->diff($date2);
            if ($interval->days >= 3){
                $sql = "UPDATE reservation SET CancelStatus = 1 WHERE TrackNum='$resTrackNum'";
                $queryTwo = mysqli_query($link,$sql);
                header("location: user_welcome.php");
            } else {
                echo "<script>alert('Error: Reservation cannot be cancelled within 3 days of event!')</script>";     
            }
        }
    }
    else if (isset($_POST['backToHome'])){
        header("location: user_welcome.php");
    }
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cancel Reservation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 600px; padding: 20px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="wrapper">
        <img id="image" src="ParkingMaster.png" style="width:360px;">
        <h4>Existing Reservations:</h4>
        <form action="user_cancel_reservation.php" method="post">
                <div class="form-group">
                    <?php
                    $sql = "SELECT * FROM reservation WHERE username = '$username'  AND CancelStatus = 0";
                    $queryOne = mysqli_query($link, $sql);

                    echo '<select  class ="form-select" name="reservations" style="width: 400px">';
                    echo '<option value="" disabled selected>Choose a reservation...</option>';
                    while ($row = mysqli_fetch_assoc($queryOne)) {
                        echo '<option>'."".$row['TrackNum']." [".$row['eventname']."] [".$row['garagename']."] [".$row['Date']."]".'</option>';
                    }
                    echo '</select>';
                    ?>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Confirm Cancellation" name="cancelConfirm">
                    <br><br>
                </div>
        </form>
        <form action="user_cancel_reservation.php" method="post">
            <div>
                <input type="submit" class="btn btn-danger" value="Back To Home" name="backToHome">
            </div>
        </form>
    </div>    
</body>
</html>