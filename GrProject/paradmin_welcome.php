<?php

// Include config file
require_once "config.php";

$garagename  = $space = $reserved = "";
$garagename_err = $space_err = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // check garagename
    if(empty(trim($_POST["garagename"]))){
        $garagename_err = "Please enter a garagename";
    } else {
        $sql = "SELECT name from garage where name = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_name);
            $param_name = trim($_POST["garagename"]);

            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) >= 1){
                    $garagename = trim($_POST["garagename"]);
                } else{
                    $garagename_err = "Garage name not exist!";
                }
            } else{
                echo "<script>alert('Oops! Something went wrong. Please try again later.')</script>";     
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Check if space is empty
    if(empty(trim($_POST["space"])) && intval($_POST["space"])!=0) {
        $space_err = "Please enter a space number.";
    }elseif(!preg_match('/^[0-9]+$/', trim($_POST["space"]))){
        $space_err = "please enter a valid number";
    }else{
        // check if the new space number valid

        $sql = "SELECT date, count from reservationnum where garagename = '$garagename'";
        $result = mysqli_query($link, $sql);
        
 
        while ($count = mysqli_fetch_row($result)) {
            if(date_create()<date_create($count[0]) ){           
            if(trim($_POST["space"]) >= $count[1]){
                $space = trim($_POST["space"]);
            } else {
                $space_err = "New space number is less than current reservation!";
                break;
            }
        }
        }
        if(!($count = mysqli_fetch_row($result))){
            $space = trim($_POST["space"]);
        }

    }

    // Validate credentials
    if(empty($garagename_err) && empty($space_err)){
        // Prepare a select statement
        $sql = "update garage set numspace = ? where name = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){

            mysqli_stmt_bind_param($stmt, "is", $param_sapce, $param_garage);
            
            // Set parameters

            $param_sapce = $space;
            $param_garage = $garagename;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                echo "<script>alert('space number changed successfully!')</script>";     
            } else{
                echo "<script>alert('Oops! Something went wrong. Please try again later.')</script>";     
            }

            mysqli_stmt_close($stmt);


        }
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
        <p>Current garages:</p>

        <table border="2">
            <tr>
                <th>Name</th>
                <th>Address</th>
                <!-- <th>
                    <form action="" method="get">
                    <select name = "q" >
                        <option value= "">Select a date</option>
                    <?php
                        $sql = "select distinct date from reservationnum";
                        $result = mysqli_query($link, $sql);
                        while ($date = mysqli_fetch_row($result)) {
                            echo "<option value = $date[0]> $date[0] </option>"; 
                        }
                    ?>
                    </select> 
                    <input type="text" name="selectdate"  value= "<?php echo $selectdate; ?>">
                    <input type="submit" value="submit">
                    </form>
                </th> -->
                <th>Total Space</th>
                <th>Garage Fee</th>
                <!-- <th><form><input type = "text", name = "space", value = "Total Space"></form></th> -->
            </tr>
            <?Php
                require_once "config.php";
                $sql = "select * from garage";

                $result = mysqli_query($link, $sql);

	                while($row = mysqli_fetch_array($result)){
                        echo "<tr>";
	                    echo "<td>". $row[0]. "</td>";
                        echo "<td>". $row[1]. "</td>";
                        echo "<td>". $row[2].  "</td>";
                        echo "<td>". $row[3].  "</td>";
                        echo "</tr>";
	                }
            ?>
        </table>
        <br>
        
        <br>
        <p>Manage total space:</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Garage Name</label>
                <input type="text" name="garagename" class="form-control <?php echo (!empty($garagename_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $garagename; ?>">
                <span class="invalid-feedback"><?php echo $garagename_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Total space</label>
                <input type="text" name="space" class="form-control <?php echo (!empty($space_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $space; ?>">
                <span class="invalid-feedback"><?php echo $space_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p> <a href="addgarage.php">Add new garage</a></p>
            <p> <a href="deletegarage.php">Delete current garage</a></p>
            <p> <a href="totalrevenue.php">View total revenue</a></p>
            <p> <a href="totalreservation.php">View reservation status</a></p>

            <div>
                <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
            </div>
        </form>


    </div>
</body>
</html>