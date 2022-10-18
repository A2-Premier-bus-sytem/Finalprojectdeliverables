<?php
    // require '../assets/partials/_normal-check.php';
    require '../assets/partials/_functions.php';
    $conn = db_connect();

    // Getting user details
    session_start();
    $customer_id = $_SESSION['customer_id'];
    $sql = "SELECT * FROM customers WHERE customer_id = '$customer_id';";
    $result = mysqli_query($conn, $sql);
    if($row = mysqli_fetch_assoc($result))
    {
        $customer_name = $row["user_name"];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bookings</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
     <?php
        require '../assets/styles/admin.php';
        require '../assets/styles/admin-options.php';
        $page="booking";
    ?>
</head>
<body>

<?php
	if($_SESSION["loggedIn"] && $_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(isset($_POST["edit"]))
            {
                // EDIT BOOKING
                // echo "<pre>";
                // var_export($_POST);
                // echo "</pre>";die;
                $cname = $_POST["cname"];
                $cphone = $_POST["cphone"];
                $id = $_POST["id"];
                $customer_id = $_POST["customer_id"];
                $id_if_customer_exists = exist_customers($conn,$cname,$cphone);

                if(!$id_if_customer_exists || $customer_id == $id_if_customer_exists)
                {
                    $updateSql = "UPDATE `customers` SET
                    `customer_name` = '$cname',
                    `customer_phone` = '$cphone' WHERE `customers`.`customer_id` = '$customer_id';";

                    $updateResult = mysqli_query($conn, $updateSql);
                    $rowsAffected = mysqli_affected_rows($conn);
    
                    $messageStatus = "danger";
                    $messageInfo = "";
                    $messageHeading = "Error!";
    
                    if(!$rowsAffected)
                    {
                        $messageInfo = "No Edits Administered!";
                    }
    
                    elseif($updateResult)
                    {
                        // Show success alert
                        $messageStatus = "success";
                        $messageHeading = "Successfull!";
                        $messageInfo = "Customer details Edited";
                    }
                    else{
                        // Show error alert
                        $messageInfo = "Your request could not be processed due to technical Issues from our part. We regret the inconvenience caused";
                    }
    
                    // MESSAGE
                    echo '<div class="my-0 alert alert-'.$messageStatus.' alert-dismissible fade show" role="alert">
                    <strong>'.$messageHeading.'</strong> '.$messageInfo.'
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }
                else{
                    // If customer details already exists
                    echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Customer already exists
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }

            }
		if(isset($_POST["delete"]))
		{
			// DELETE BOOKING
			$id = $_POST["id"];
			$route_id = $_POST["route_id"];
			// Delete the booking with id => id
			$deleteSql = "DELETE FROM `bookings` WHERE `bookings`.`id` = '$id'";

			$deleteResult = mysqli_query($conn, $deleteSql);
			$rowsAffected = mysqli_affected_rows($conn);
			$messageStatus = "danger";
			$messageInfo = "";
			$messageHeading = "Error!";

			if(!$rowsAffected)
			{
				$messageInfo = "Record Doesn't Exist";
			}

			elseif($deleteResult)
			{   
				$messageStatus = "success";
				$messageInfo = "Booking Details deleted";
				$messageHeading = "Successfull!";

				// Update the Seats table
				$bus_no = get_from_table($conn, "routes", "route_id", $route_id, "bus_no");
				$seats = get_from_table($conn, "seats", "bus_no", $bus_no, "seat_booked");

				// Extract the seat no. that needs to be deleted
				$booked_seat = $_POST["booked_seat"];

				$seats = explode(",", $seats);
				$idx = array_search($booked_seat, $seats);
				array_splice($seats,$idx,1);
				$seats = implode(",", $seats);

				$updateSeatSql = "UPDATE `seats` SET `seat_booked` = '$seats' WHERE `seats`.`bus_no` = '$bus_no';";
				mysqli_query($conn, $updateSeatSql);
			}
			else{

				$messageInfo = "Your request could not be processed due to technical Issues from our part. We regret the inconvenience caused";
			}

			// Message
			echo '<div class="my-0 alert alert-'.$messageStatus.' alert-dismissible fade show" role="alert">
			<strong>'.$messageHeading.'</strong> '.$messageInfo.'
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>';
		}
	}
?>
  <header class="py-3 px-5">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <div class="navbar-brand">
                    Welcome, 
                    <?php echo ($customer_name); ?>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="./index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./bookings.php">Bookings</a>
                        </li>
                    </ul>
                </div>
                <a class="btn btn-primary" href="../assets/partials/_logout.php">LOGOUT</a>
            </div>
        </nav>
    </header>

    <main class="container py-3"> 
    <?php
		$resultSql = "SELECT * FROM `bookings` WHERE `customer_id` = '$customer_id' ORDER BY booking_created DESC;";
						
		$resultSqlResult = mysqli_query($conn, $resultSql);

		if(!mysqli_num_rows($resultSqlResult)){ ?>
			<!-- Bookings are not present -->
			<div class="container mt-4">
				<div id="noCustomers" class="alert alert-dark " role="alert">
					<h1 class="alert-heading">No Bookings Found!!</h1>
				</div>
			</div>
		<?php }
		else { ?>   
		<section id="booking">
			<div id="head" class="mb-4">
				<h4>Booking Status</h4>
			</div>
			<div id="booking-results">
				<table class="table table-hover table-bordered">
					<thead>
						<th>PNR</th>
						<th>Name</th>
						<th>Contact</th>
						<th>Bus</th>
						<th>Route</th>
						<th>Seat</th>
						<th>Amount</th>
						<th>Departure</th>
						<th>Booked</th>
						<th>Actions</th>
					</thead>
					<?php
						while($row = mysqli_fetch_assoc($resultSqlResult))
						{
								// echo "<pre>";
								// var_export($row);
								// echo "</pre>";
							$id = $row["id"];
							$customer_id = $row["customer_id"];
							$route_id = $row["route_id"];

							$pnr = $row["booking_id"];

							$customer_name = get_from_table($conn, "customers","customer_id", $customer_id, "customer_name");
							
							$customer_phone = get_from_table($conn,"customers","customer_id", $customer_id, "customer_phone");

							$bus_no = get_from_table($conn, "routes", "route_id", $route_id, "bus_no");

							$route = $row["customer_route"];

							$booked_seat = $row["booked_seat"];
							
							$booked_amount = $row["booked_amount"];

							$dep_date = get_from_table($conn, "routes", "route_id", $route_id, "route_dep_date");

							$dep_time = get_from_table($conn, "routes", "route_id", $route_id, "route_dep_time");

							$booked_timing = $row["booking_created"];
					?>
					<tr>
						<td>
							<?php 
								echo $pnr;
							?>
						</td>
						<td>
							<?php 
								echo $customer_name;
							?>
						</td>
						<td>
							<?php 
								echo $customer_phone;
							?>
						</td>
						<td>
							<?php 
								echo $bus_no;
							?>
						</td>
						<td>
							<?php 
								echo $route;
							?>
						</td>
						<td>
							<?php 
								echo $booked_seat;
							?>
						</td>
						<td>
							<?php 
								echo '$'.$booked_amount;
							?>
						</td>
						<td>
							<?php 
								echo $dep_date . " , ". $dep_time;
							?>
						</td>
						<td>
							<?php 
								echo $booked_timing;
							?>
						</td>
						<td>
						<button class="button btn-sm edit-button" data-link="<?php echo $_SERVER['REQUEST_URI']; ?>" data-customerid="<?php 
                                                echo $customer_id;?>" data-id="<?php 
                                                echo $id;?>" data-name="<?php 
                                                echo $customer_name;?>" data-phone="<?php 
                                                echo $customer_phone;?>" >Edit</button>
							<button class="button delete-button btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" 
							data-id="<?php 
											echo $id;?>" data-bookedseat="<?php 
											echo $booked_seat;
										?>" data-routeid="<?php 
										echo $route_id;
									?>"> Delete</button>
						</td>
					</tr>
					<?php 
					}
				?>
				</table>
			</div>
		</section>
	<?php } ?> 
	</main>

	<!-- Delete Modal -->
	<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-exclamation-circle"></i></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h2 class="text-center pb-4">
                    Are you sure?
                </h2>
                <p>
                    Do you really want to delete this booking? <strong>This process cannot be undone.</strong>
                </p>
                <!-- Needed to pass id -->
                <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="delete-form"  method="POST">
                    <input id="delete-id" type="hidden" name="id">
                    <input id="delete-booked-seat" type="hidden" name="booked_seat">
                    <input id="delete-route-id" type="hidden" name="route_id">
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="delete-form" name="delete" class="btn btn-danger">Delete</button>
            </div>
            </div>
        </div>
    </div>
  
  <script src="../assets/scripts/normal_booking.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>