<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Venue Administrator HomePage</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        #image {
            width: 360px;
            object-position: -20px 0px;
        }
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; margin: 0 auto; }
        .title{width:500px;padding:20px; margin: 0 auto; }
        .year{width:100px;padding:0px}
        .date{width:90px;padding:0px}
        .month{width:90px;padding:0px}
        .Connection{
            display: inline-block;
            position: relative;
            margin:  5px 0 0;
        }
    </style>
</head>
<body>
<div class="title">
<img id="image" src="ParkingMaster.png" style="width:360px;">
<h2>Venue Administrator HomePage</h2>
</div>
	<div class="wrapper">
           	    	
    
    <div class="form-group">
    	<a href='display_venue.php'>
        	<button class = "btn btn-primary">
            	Display Venue
        	</button>
    	</a> 
    </div>
    
    <div class="form-group">
    	<a href='display_event.php'>
        	<button class = "btn btn-primary">
            	Display Event
        	</button>
    	</a> 
    </div>
    
    <div class="form-group">
    	<a href='delete_event.php'>
        	<button class = "btn btn-primary">
            	Remove Event
        	</button>
    	</a> 
    </div>
    
        <div class="form-group">
    	<a href='Change_date.php'>
        	<button class = "btn btn-primary">
            	Change Date
        	</button>
    	</a> 
    </div>
    
            <div class="form-group">
    	<a href='remove_date.php'>
        	<button class = "btn btn-primary">
            	Remove Date Of A Event
        	</button>
    	</a> 
    </div>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">	
            
        	    	
    </form>
    	<div class="form-group">
    	<a href='create_events.php'>
        <button class = "btn btn-primary">
            Create New Events
        </button>
    	</a>
    </div>
    
    </div>
    <div class="wrapper">
        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
    </div>
</body>
</html>