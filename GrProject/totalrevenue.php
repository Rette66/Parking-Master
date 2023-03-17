<?php

// Include config file
require_once "config.php";

$garagename  = $startdate = $enddate = "";
$garagename_err = $start_err = $end_err = "";
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
        $start_err = "Please enter a start date";
    }elseif( date('Y-m-d', strtotime(trim($_POST["startdate"])))  != trim($_POST["startdate"]) ){
        $start_err = "Invalid format";
    }else {
        $startdate = trim($_POST["startdate"]);
    }
    
    if(empty(trim($_POST["enddate"]))){
        $end_err = "Please enter a end date";
    }elseif( date('Y-m-d', strtotime(trim($_POST["enddate"])))  != trim($_POST["enddate"]) ){
        $end_err = "Invalid format";
    }else {
        $enddate = trim($_POST["enddate"]);
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
        .wrapper{ width: 360px; padding: 20px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="wrapper">
        <img id="image" src="ParkingMaster.png" style="width:360px;">
        <h2>Garage</h2>
        <p>Total Revenue</p>       
        <br>
        <p>Add new garage</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Garage Name</label>
                <input type="text" name="garagename" class="form-control <?php echo (!empty($garagename_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $garagename; ?>">
                <span class="invalid-feedback"><?php echo $garagename_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Start date(YYYY-MM-DD)</label>
                <input type="text" name="startdate" class="form-control <?php echo (!empty($start_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $startdate; ?>">
                <span class="invalid-feedback"><?php echo $start_err; ?></span>
            </div>
            <div class="form-group">
                <label>End date(YYYY-MM-DD)</label>
                <input type="text" name="enddate" class="form-control <?php echo (!empty($end_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $enddate; ?>">
                <span class="invalid-feedback"><?php echo $end_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            
        </form>
        <?php
            if($_SERVER["REQUEST_METHOD"] == "POST"){

                
                // Validate credentials
                if(empty($garagename_err) && empty($start_err) && empty($end_err)){
                    // Prepare a select statement
                    $sql = "select sum(totalfee) from reservation where cancelstatus = 0 and garagename = '$garagename' and date >= '$startdate' and date <= '$enddate'";
                    $result = mysqli_query($link, $sql);
                    echo "<p>Total Revenue:<p>";
                    echo "<table border='2'>";
                    echo "<tr>";
                    echo "<th>Garage</th>";
                    echo "<th>Start Date</th>";
                    echo "<th>End Date</th>";
                    echo "<th>Total Revenue</th>";
                    echo "</tr>";

                    echo "<tr>";
                    while($row = mysqli_fetch_row($result)){
                        if(empty($row[0])){
                            echo "<td colspan = '4'>no reservation in this time frame</td>";
                        }else{
                            echo "<td>". $garagename. "</td>";
                            echo "<td>". $startdate. "</td>";
                            echo "<td>". $enddate. "</td>";
                            echo "<td>". $row[0]. "</td>";
                        }
                    }
                    echo "</tr>";
                    echo "</table>";
                }

            }
        ?>


        <p> <a href="paradmin_welcome.php">Back to Homepage</a>.</p>
    </div>
</body>
</html>