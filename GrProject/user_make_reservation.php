<?php
session_start();
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$selectedEvent = $_SESSION["selectedEvent"];
$selectedVenue = $_SESSION["selectedVenue"];
$selectedDate = $_SESSION["selectedDate"];
$selectedGarage = $selectedGarage_err = "";
$relativeDistance = "";
$garageName = "";
$totalPrice = null;
$space_err_bool = false;
    
if($_SERVER["REQUEST_METHOD"] == "POST") {


    if (isset($_POST['confirmRes'])){

        if(empty($_POST["garages"])){
            $selectedGarage_err = "Please choose a garage";
        } else {
            $selectedGarage = $_POST["garages"];
            $_SESSION['selectedGarage'] = $selectedGarage;
        }
        //set cancellation status to false initially:
        $cancelStatus = 0;
        //create unique tracking number:
        $trackNum = random_int(0, 30000);
        $sql = "SELECT TrackNum FROM reservation";
        $queryFour = mysqli_query($link, $sql);
        while ($row = mysqli_fetch_assoc($queryFour)){
            if ($row['TrackNum'] == $trackNum){
                $trackNum = $trackNum + 1;
            }
        }
        //substring garage name:
        $subGarName = substr($selectedGarage, 0, strpos($selectedGarage, ' '));
        //we have access to username from the session:
        $username = $_SESSION['username'];

        //FEE:
        //calculate total fee (eventcharge + garagefee) for each garage:
        $sql = "SELECT eventcharge FROM event WHERE Name = '$selectedEvent'";
        $queryTwo = mysqli_query($link, $sql);
        $element = mysqli_fetch_assoc($queryTwo);
        $eventCharge = $element['eventcharge'];

        $sql = "SELECT garagefee FROM garage WHERE Name = '$subGarName'";
        $queryThree = mysqli_query($link, $sql);
        $item = mysqli_fetch_assoc($queryThree);
        $garageFee = $item['garagefee'];
        $totalPrice = $eventCharge + $garageFee;

        //INSERT RESERVATION:
        $sql = "SELECT count from reservationnum WHERE garagename = '$subGarName' and date = '$selectedDate'";
        $queryFive = mysqli_query($link, $sql);
        $count = mysqli_fetch_row($queryFive);

        $sql = "SELECT numspace from garage WHERE name = '$subGarName'";
        $querySix = mysqli_query($link, $sql);
        $totalspace = mysqli_fetch_row($querySix);

        if ($count[0] < $totalspace[0]) {
            $sql = "INSERT into reservation (TrackNum, Date, totalfee, CancelStatus, username, eventname, garagename) values (?,?,?,?,?,?,?)";
            $queryEnd = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($queryEnd, "sssssss", $param_tracknum, $param_date, $param_totalfee, $param_cancelStatus, $param_username, $param_eventname, $param_garagename);
            //set parameters:
            $param_tracknum = $trackNum;
            $param_date = $selectedDate;
            $param_totalfee = $totalPrice;
            $param_cancelStatus = $cancelStatus;
            $param_username = $username;
            $param_eventname = $selectedEvent;
            $param_garagename = $subGarName;
            if (mysqli_stmt_execute($queryEnd)){
                header("location: user_welcome.php");
            }
        } else {
            $space_err_bool = true;
        }
    }   

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        #image {
            width: 360px;
            object-position: -20px 0px;
        }
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="wrapper">
        <img id="image" src="ParkingMaster.png" style="width:360px;">
        <h1>Reservation For</h1>
        <br>
        <h4>Event: <?php echo $selectedEvent;?></h4>
        <h4>Venue: <?php echo $selectedVenue;?></h4>
        <h4>Date: <?php echo $selectedDate;?></h4>
        <br>
        <br>
        <form action="user_make_reservation.php" method="post">
                <div class="form-group">
                    <?php
                    $sql = "SELECT GarageName, Distance FROM distance_to WHERE VenueName = '$selectedVenue' ORDER BY Distance";
                    $queryOne = mysqli_query($link, $sql);
                    echo '<label>Choose a garage:</label>';
                    echo '<select  class ="form-select" name="garages" style="width: 400px">';
                    while ($row = mysqli_fetch_assoc($queryOne)) {

                        //calculate total fee (eventcharge + garagefee) for each garage:
                        $sql = "SELECT eventcharge FROM event WHERE Name = '$selectedEvent'";
                        $queryTwo = mysqli_query($link, $sql);
                        $element = mysqli_fetch_assoc($queryTwo);
                        $eventCharge = $element['eventcharge'];
                        $garageName = $row['GarageName'];

                        $sql = "SELECT garagefee FROM garage WHERE Name = '$garageName'";
                        $queryThree = mysqli_query($link, $sql);
                        $item = mysqli_fetch_assoc($queryThree);
                        $garageFee = $item['garagefee'];
                        $totalFee = $eventCharge + $garageFee;

                        //fill in option value:
                        echo '<option>'.$row['GarageName']." (".$row['Distance']." miles away) ["."$".$totalFee."]".'</option>';
                    }
                    echo '</select>';
                    ?>
                </div>
                <div>
                    <?php
                        if ($space_err_bool == true) {
                            echo '<script>alert("Garage reservations are full!")</script>';
                            $space_err_bool = false;
                        }
                    ?>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-secondary" value="Confirm Selection" name="confirmRes">
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