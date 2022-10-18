<?php
    require '_functions.php';

    $conn = db_connect();

    if(!$conn)
        die("Oh Shoot!! Connection Failed");

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signup"]))
    {
        // Should be validated client-side
        
        $cname = $_POST["firstName"] . " " . $_POST["lastName"];
        $cphone = $_POST["cphone"];
        $username = $_POST["username"];
        $password = $_POST["password"]; 

        $customer_exists = exist_customers($conn, $cname, $cphone);
        $customer_added = false;

        if(!$customer_exists)
        {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Route is unique, proceed
            $sql = "INSERT INTO `customers` (`customer_name`, `customer_phone`, `customer_created`, `user_name`, `user_password`) VALUES ('$cname', '$cphone', current_timestamp(), '$username', '$hash');";
            $result = mysqli_query($conn, $sql);
            // Gives back the Auto Increment id
            $autoInc_id = mysqli_insert_id($conn);
            // If the id exists then, 
            if($autoInc_id)
            {
                $code = rand(1,99999);
                // Generates the unique userid
                $customer_id = "CUST-".$code.$autoInc_id;
                
                $query = "UPDATE `customers` SET `customer_id` = '$customer_id' WHERE `customers`.`id` = $autoInc_id;";
                $queryResult = mysqli_query($conn, $query);

                if(!$queryResult)
                    echo "Not Working";
            }

            if($result)
                $customer_added = true;
        }

        if($customer_added)
        {
            // Show success alert
            echo '<div class="my-0 alert alert-success alert-dismissible fade show" role="alert">
            <strong>Successful!</strong> Customer Added
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
        else{
            // Show error alert
            echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Customer already exists
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
        
        // Redirect Page
        header("location: ../../index.php?status=".$customer_added);
    }    
?>