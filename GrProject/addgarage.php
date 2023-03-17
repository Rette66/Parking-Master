<?php

// Include config file
require_once "config.php";

$garagename  = $address = $space = $fee = "";
$garagename_err = $address_err = $space_err = $fee_err = "";
$feearray = array();
$feearray_err = array();

$sql1= "select name from venue";
$result1 = mysqli_query($link,$sql1);

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
                    $garagename_err = "Garage already exist";
                } else{
                    $garagename = trim($_POST["garagename"]);
                }
            } else{
                echo "Something went wrong, please try again later";
            }
            mysqli_stmt_close($stmt);
        }
    }

    if(empty(trim($_POST["address"]))){
        $address_err = "Please enter a address";
    }else {
        $address = trim($_POST["address"]);
    }

    if(!preg_match('/^[0-9]+$/', trim($_POST["space"]))){
        $space_err = "please enter a valid integer as space";
    }else{
        $space = trim($_POST["space"]);
    }

    if(!preg_match('/^[0-9]+$/', trim($_POST["fee"])) && !preg_match('/^\d+(\.\d+)$/',trim($_POST["fee"]))){
        $fee_err = "Please enter a valid decimal as fee";
    }else {
        $fee = trim($_POST["fee"]);
    }

    $x = 0;
    while($row = mysqli_fetch_row($result1)){
        if(empty(trim($_POST["distance$x"]))){
            $feearray_err[$x] = "Please enter a valid distance";
            array_push($feearray,"");
        }else {
            $feearray[$x] = trim($_POST["distance$x"]);
            array_push($feearray_err,"");
        }        
        $x++;
    }
    $sql2 = "insert into garage value ('$garagename', '$address', $space, $fee)";
    

    $arrayerr_bool = true;

    foreach ($feearray_err as $err){
        if(!empty($err)){
            $arrayerr_bool = false;
            break;
        }
    }
    if(empty($garagename_err) && empty($space_err)&&empty($address_err) && empty($fee_err) && $arrayerr_bool){

        if(mysqli_query($link, $sql2)){
            $result1 = mysqli_query($link,$sql1);
            $index = 0;

            while($row = mysqli_fetch_row($result1)){
                $sqldistance = "insert into distance_to value ( '$garagename','$row[0]',$feearray[$index])";
                mysqli_query($link, $sqldistance);               
                $index++;
            }
            echo '<script>alert("New Garage Added Successfully!")</script>';
        }else{
            echo '<script>alert("there might be something wrong!")</script>';
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
        .wrapper{ width: 360px; padding: 20px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="wrapper">
        <img id="image" src="ParkingMaster.png" style="width:360px;">
        <h2>Garage</h2>     
        <br>
        <p>Add new garage</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Garage Name</label>
                <input type="text" name="garagename" class="form-control <?php echo (!empty($garagename_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $garagename; ?>">
                <span class="invalid-feedback"><?php echo $garagename_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $address; ?>">
                <span class="invalid-feedback"><?php echo $address_err; ?></span>
            </div>
            <div class="form-group">
                <label>Total Space</label>
                <input type="text" name="space" class="form-control <?php echo (!empty($space_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $space; ?>">
                <span class="invalid-feedback"><?php echo $space_err; ?></span>
            </div>
            <div class="form-group">
                <label>Garage Fee</label>
                <input type="text" name="fee" class="form-control <?php echo (!empty($fee_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $fee; ?>">
                <span class="invalid-feedback"><?php echo $fee_err; ?></span>
            </div>
            <?php
                $result1 = mysqli_query($link,$sql1);
                $i = 0;
                while($venue = mysqli_fetch_row($result1)){
                    array_push($feearray,"");
                    array_push($feearray_err, "");
                    echo "<div class='form-group'>";
                    echo "<label> Distance to ". $venue[0]. "</label>";
                    echo "<input type='text' name='distance". $i. "' class='form-control ";
                    if(!empty($feearray_err[$i])){
                        echo "is-invalid";
                    }
                    echo " 'value=". $feearray[$i]. " >";
                    echo "<span class='invalid-feedback'>". $feearray_err[$i]. "</span>";
                    $i ++;
                    echo "</div>";
                }
            ?>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
        </form>
        <p> <a href="paradmin_welcome.php">Back to Homepage</a>.</p>
    </div>
</body>
</html>