<?php

// Include config file
require_once "config.php";

$garagename  = "";
$garagename_err  = "";


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
                    $garagename_err = "Garage not exist";
                }
            } else{
                echo "Something went wrong, please try again later";
            }
            mysqli_stmt_close($stmt);
        }
    }
    if(empty($garagename_err)){
        $sql = "delete from garage where name = '$garagename'";
        if(mysqli_query($link, $sql)){
            echo "<script>alert('garage ". $garagename. " deleted successfully!')</script>";
        } else{
            echo "Oops! Something went wrong. Please try again later.";
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
        body{ font: 14px sans-serif; margin: 0 auto; }
        .wrapper{ width: 500px; padding: 20px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="wrapper">
        <img id="image" src="ParkingMaster.png" style="width:360px;">
        <h2>Garage</h2>     
        <br>
        <p>Delete Current Garage</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Garage Name</label>
                <input type="text" name="garagename" class="form-control <?php echo (!empty($garagename_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $garagename; ?>">
                <span class="invalid-feedback"><?php echo $garagename_err; ?></span>
            </div>    

                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
        </form>
        <br>

        <table border="2", style="margin-left:auto;margin-right:auto;">
            <tr>
                <th>Name</th>
                <th>Address</th>
                <th>Total Space</th>
                <th>Garage Fee</th>
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
        <div class="wrapper">
            <p> <a href="paradmin_welcome.php">Back to Homepage</a>.</p>
        </div>
    </div>

    
</body>
</html>