<?php
session_start();
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $_SESSION["username"];
$selectedDate = $selectedDate_err = "";
$selectedEvent = $selectedEvent_err = "";
$selectedVenue = "";
$CreateResBool = false; 
$dateBool = false;

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['checkDate'])){
        if(empty($_POST["events"])){
            $selectedEvent_err = "Please select an event";
        } else {
            $selectedEvent = $_POST["events"];
            $_SESSION["selectedEvent"] = $selectedEvent;
            $dateBool = true;
            //query to get corresponding venue:
            $sql = "SELECT VenueName FROM event WHERE name = '$selectedEvent'";
            $queryVen = mysqli_query($link, $sql);
            $row = mysqli_fetch_assoc($queryVen);
            $selectedVenue = $row['VenueName'];
            $_SESSION["selectedVenue"] = $selectedVenue;
        }
    
    } else if (isset($_POST['confirm'])){
        if(empty($_POST["dates"])){
            $selectedDate_err = "Please select a date";
        } else {
            $selectedDate = $_POST["dates"];
            $_SESSION["selectedDate"] = $selectedDate;
        }
        header("location: user_make_reservation.php");
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
        .wrapper{ width: 600px; padding: 20px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="wrapper">
        <img id="image" src="ParkingMaster.png" style="width:360px;">
        <h1>Welcome <?php echo $username; ?>!</h1>
        <br>
        <br>
        <div>
            <h3>Existing Reservations:</h3>
            <h7 style="color: red;">*Status=1(cancelled), Status=0(reserved)</h7>
            <br><br>
            <table border="2"> 
                <tr>
                    <th> Username: </th>
                    <th>Tracking Number:</th>
                    <th> Date: </th>
                    <th> Price: </th>
                    <th> Status: </th>
                    <th> Event:</th>
                    <th> Garage:</th>
                </tr>
                <?php 
                $sql = "SELECT * from reservation WHERE username = '$username'";
                $stmt = mysqli_query($link, $sql);
                while ($row = mysqli_fetch_array($stmt)){
                    echo "<tr>";
                    echo "<td>".$row[4]."</td>";
                    echo "<td>".$row[0]."</td>";
                    echo "<td>".$row[1]."</td>";
                    echo "<td>".$row[2]."</td>";
                    echo "<td>".$row[3]."</td>";
                    echo "<td>".$row[5]."</td>";
                    echo "<td>".$row[6]."</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
        <br>
        <br>
        <div>
            <h3>Make A Reservation:</h3>
            <form action="user_welcome.php" method="post">
                <div class="form-group">
                    <?php
                    $sql = "SELECT name FROM event";
                    $queryOne = mysqli_query($link, $sql);

                    echo '<select  class ="form-select" name="events" style="width: 400px">';
                    echo '<option value="" disabled selected>Choose an event...</option>';
                    while ($row = mysqli_fetch_assoc($queryOne)) {
                        echo '<option>'.$row['name'].'</option>';
                    }
                    echo '</select>';
                    ?>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-secondary" value="Check Available Dates:" name="checkDate">
                </div>
            </form>
            <form action="user_welcome.php" method="post">
                <div class="form-group">
                    <?php
                    $sql = "SELECT StartDate, EndDate FROM event WHERE name = '$selectedEvent'";
                    $queryTwo = mysqli_query($link, $sql);

                    if ($dateBool == true){
                        //create date objects array:
                        $datesArray = array();
                        $interval = new DateInterval('P1D');
                        $row = mysqli_fetch_assoc($queryTwo);

                        $eventStart = new DateTime($row['StartDate']);
                        if ($row['EndDate'] != null){
                            $eventEnd = new DateTime($row['EndDate']);
                            $eventEnd->add($interval);

                            $period = new DatePeriod($eventStart, $interval, $eventEnd);

                            echo '<label>Now showing dates for '.$selectedEvent.'</label>';
                            echo '<select  class ="form-select" name="dates" style="width: 400px">';
                            foreach ($period as $date){
                                $datesArray[] = $date->format("Y-m-d");
                                echo '<option>'.date_format($date, "Y-m-d").'</option>';
                            }
                            echo '</select>';
                            echo '<br><br>';
                            echo '<div class="form-group">';
                            echo '<input type="submit" class="btn btn-secondary" value="Proceed to confirm" name="confirm">';
                            echo '</div>';
                        } else {
                            echo '<label>Now showing dates for '.$selectedEvent.'</label>';
                            echo '<select  class ="form-select" name="dates" style="width: 400px">';
                            echo '<option>'.date_format($eventStart, "Y-m-d").'</option>';
                            echo '</select>';
                            echo '<br><br>';
                            echo '<div class="form-group">';
                            echo '<input type="submit" class="btn btn-secondary" value="Proceed to confirm" name="confirm">';
                            echo '</div>';
                        }
                        //foreach ($dates as &$value) {
                        //    echo '<option>'.$value.'</option>';
                        //}
                    }

                    ?>
                </div>
            </form>
        </div>
        <br>
        <div>
        <a href='user_cancel_reservation.php'>
            <button class = "btn btn-warning">
                Cancel A Reservation
            </button>
        </div>
        <br>
        <br>
        <div>
            <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
        </div>
    </div>
</body>
</html>