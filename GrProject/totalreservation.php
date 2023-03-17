<?php

    // Include config file
    require_once "config.php";

    $garagename  = $startdate  = "";
    $garagename_err = $start_err = "";
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        // check garagename
        if(empty(trim($_POST["garagename"]))){
            $garagename_err = "Please enter a garagename";
        } else {
            $sql = "SELECT garagename from reservation where garagename = ?";

            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_name);
                $param_name = trim($_POST["garagename"]);

                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);

                    if(mysqli_stmt_num_rows($stmt) >= 1){
                        $garagename = trim($_POST["garagename"]);
                    } else{
                        $garagename_err = "There is no reservation for this garage";
                    }
                } else{
                    echo "Something went wrong, please try again later";
                }
                mysqli_stmt_close($stmt);
            }
        }

        if(empty(trim($_POST["startdate"]))){
            $start_err = "Please enter a date";
        }elseif( date('Y-m-d', strtotime(trim($_POST["startdate"])))  != trim($_POST["startdate"]) ){
            $start_err = "Invalid date format";
        }else {
            $startdate = trim($_POST["startdate"]);
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Garage</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        #image {
            width: 360px;
            object-position: -20px 0px;
        }
        body{ font: 14px sans-serif; }
        .wrapper{ width: 500px; padding: 20px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="wrapper">
        <img id="image" src="ParkingMaster.png" style="width:360px;">
        <h2>Garage</h2>
        <p>Reservation Status</p>        
        <br>
        <p>view garage reservation status</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Garage Name</label>
                <input type="text" name="garagename" class="form-control <?php echo (!empty($garagename_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $garagename; ?>">
                <span class="invalid-feedback"><?php echo $garagename_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Date(YYYY-MM-DD)</label>
                <input type="text" name="startdate" class="form-control <?php echo (!empty($start_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $startdate; ?>">
                <span class="invalid-feedback"><?php echo $start_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            
        </form>
        <?php
            if($_SERVER["REQUEST_METHOD"] == "POST"){

                
                // Validate credentials
                if(empty($garagename_err) && empty($start_err)){
                    // Prepare a select statement
                    $sql = "select count from reservationnum where  garagename = '$garagename' and date = '$startdate' ";
                    $sql2 = "select numspace from garage where name = '$garagename'";
                    $result = mysqli_query($link, $sql);
                    $result2 = mysqli_query($link, $sql2);
                    echo "<p>Total Revenue:<p>";
                    echo "<table border='2'>";
                    echo "<tr>";
                    echo "<th>Garage</th>";
                    echo "<th>Date</th>";
                    echo "<th>Current reservations</th>";
                    echo "<th>Total space</th>";
                    echo "</tr>";

                    echo "<tr>";

                    $row = mysqli_fetch_row($result);
                    $row2 = mysqli_fetch_row($result2);
                    if(is_null($row)){
                        echo "<td colspan = '3'>no reservation in this time frame</td>";
                        echo "<td>". $row2[0]. "</td>";
                    }else{
                        echo "<td>". $garagename. "</td>";
                        echo "<td>". $startdate. "</td>";
                        echo "<td>". $row[0]. "</td>";
                        echo "<td>". $row2[0]. "</td>";
                    }
                    echo "</tr>";
                    echo "</table>";
                }

            }
        ?>

            <br>
        <p> <a href="paradmin_welcome.php">Back to Homepage</a>.</p>
    </div>
</body>
</html>