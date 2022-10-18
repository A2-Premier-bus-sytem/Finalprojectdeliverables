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
     <!-- Bootstrap CSS -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
     <?php
        require '../assets/styles/admin.php';
        require '../assets/styles/admin-options.php';
        $page="booking";
    ?>
    <title>Home</title>
</head>
<body>
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


    <?php
        /*
            1. Check if an admin is logged in
            2. Check if the request method is POST
        */
        if($_SESSION["loggedIn"] && $_SERVER["REQUEST_METHOD"] == "POST")
        {
            if(isset($_POST["submit"]))
            {
                /*
                    ADDING Bookings
                 Check if the $_POST key 'submit' exists
                */
                // Should be validated client-side
                // echo "<pre>";
                // var_export($_POST);
                // echo "</pre>";
                // die;
                $route_id = $_POST["route_id"];
                $route_source = $_POST["sourceSearch"];
                $route_destination = $_POST["destinationSearch"];
                $route = $route_source . " &rarr; " . $route_destination;
                $booked_seat = $_POST["seatInput"];
                $amount = $_POST["bookAmount"];
                // $dep_timing = $_POST["dep_timing"];

                $booking_exists = exist_booking($conn,$customer_id,$route_id);
                $booking_added = false;
        
                if(!$booking_exists)
                {
                    // Route is unique, proceed
                    $sql = "INSERT INTO `bookings` (`customer_id`, `route_id`, `customer_route`, `booked_amount`, `booked_seat`, `booking_created`) VALUES ('$customer_id', '$route_id','$route', '$amount', '$booked_seat', current_timestamp());";

                    $result = mysqli_query($conn, $sql);
                    // Gives back the Auto Increment id
                    $autoInc_id = mysqli_insert_id($conn);
                    // If the id exists then, 
                    if($autoInc_id)
                    {
                        $key = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                        $code = "";
                        for($i = 0; $i < 5; ++$i)
                            $code .= $key[rand(0,strlen($key) - 1)];
                        
                        // Generates the unique bookingid
                        $booking_id = $code.$autoInc_id;
                        
                        $query = "UPDATE `bookings` SET `booking_id` = '$booking_id' WHERE `bookings`.`id` = $autoInc_id;";
                        $queryResult = mysqli_query($conn, $query);

                        if(!$queryResult)
                            echo "Not Working";
                    }

                    if($result)
                        $booking_added = true;
                }
    
                if($booking_added)
                {
                    // Show success alert
                    echo '<div class="my-0 alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Successful!</strong> Booking Added
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';

                    // Update the Seats table
                    $bus_no = get_from_table($conn, "routes", "route_id", $route_id, "bus_no");
                    $seats = get_from_table($conn, "seats", "bus_no", $bus_no, "seat_booked");
                    if($seats)
                    {
                        $seats .= "," . $booked_seat;
                    }
                    else 
                        $seats = $booked_seat;

                    $updateSeatSql = "UPDATE `seats` SET `seat_booked` = '$seats' WHERE `seats`.`bus_no` = '$bus_no';";
                    mysqli_query($conn, $updateSeatSql);
                }
                else{
                    // Show error alert
                    echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Booking already exists
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }
            }
        }
    ?>


    <main class="container py-3"> 
        <?php
            $resultSql = "SELECT * FROM `routes` ORDER BY route_created DESC";
                            
            $resultSqlResult = mysqli_query($conn, $resultSql);
            if(!mysqli_num_rows($resultSqlResult)){ ?>
                <!-- Routes are not present -->
                <div class="container mt-4">
                    <div id="noRoutes" class="alert alert-dark " role="alert">
                        <h1 class="alert-heading">No Routes Found!!</h1>
                    </div>
                </div>
            <?php }
        else { ?>
            <section id="route">
                <div id="head" class="mb-4 d-flex justify-content-between">
                    <h4>Avaiable Routes</h4>
                    <button id="add-button" class="btn btn-sm btn-dark"type="button"data-bs-toggle="modal" data-bs-target="#addModal">Add Bookings<i class="fas fa-plus"></i></button>
                </div>
                <div id="route-results">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <th>ID</th>
                            <th>Via Cities</th>
                            <th>Bus</th>
                            <th>Departure Date</th>
                            <th>Departure Time</th>
                            <th>Cost</th>
                        </thead>
                        <?php
                            while($row = mysqli_fetch_assoc($resultSqlResult))
                            {
                                $id = $row["id"];
                                $route_id = $row["route_id"];
                                $route_cities = $row["route_cities"];
                                $route_dep_time = $row["route_dep_time"];
                                $route_dep_date = $row["route_dep_date"];
                                $route_step_cost = $row["route_step_cost"];
                                $bus_no = $row["bus_no"];
                                    ?>
                                <tr>
                                    <td>
                                        <?php 
                                            echo $route_id;
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            echo $route_cities;
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            echo $bus_no;
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            echo $route_dep_date;
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            echo $route_dep_time;
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            echo '$'.$route_step_cost;?>
                                    </td>
                                </tr>
                            <?php 
                            }
                        ?>
                    </table>
                </div>
            </section>
        <?php  } ?>
    </main>

    <!-- Requiring _getJSON.php-->
    <!-- Will have access to variables 
        1. routeJson
        2. customerJson
        3. seatJson
        4. busJson
        5. adminJson
        6. bookingJSON
    -->
    <?php require '../assets/partials/_getJSON.php';?>
    
    <!-- All Modals Here -->
    <!-- Add Booking Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Make Bookings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addBookingForm" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                        <!-- Passing Route JSON -->
                        <input type="hidden" id="routeJson" name="routeJson" value='<?php echo $routeJson; ?>'>
                        <!-- Passing Customer JSON -->
                        <input type="hidden" id="customerJson" name="customerJson" value='<?php echo $customerJson; ?>'>
                        <!-- Passing Seat JSON -->
                        <input type="hidden" id="seatJson" name="seatJson" value='<?php echo $seatJson; ?>'>

                        <!-- <div class="mb-3">
                            <label for="cid" class="form-label">Customer ID</label>
                            <div class="searchQuery">
                                <input type="text" class="form-control searchInput" id="cid" name="cid">
                                <div class="sugg">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="cname" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="cname" name="cname" readonly>
                        </div> -->
                        <!-- <div class="mb-3">
                            <label for="cphone" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="cphone" name="cphone" readonly>
                        </div> -->
                        <div class="mb-3">
                            <label for="routeSearch" class="form-label">Route</label>
                            <!-- Search Functionality -->
                            <div class="searchQuery">
                                <input type="text" class="form-control searchInput" id="routeSearch" name="routeSearch">
                                <div class="sugg">
                                </div>
                            </div>
                        </div>
                        <!-- Send the route_id -->
                        <input type="hidden" name="route_id" id="route_id">
                        <!-- Send the departure timing too -->
                        <input type="hidden" name="dep_timing" id="dep_timing">

                        <div class="mb-3">
                            <label for="sourceSearch" class="form-label">Source</label>
                            <!-- Search Functionality -->
                            <div class="searchQuery">
                                <input type="text" class="form-control searchInput" id="sourceSearch" name="sourceSearch">
                                <div class="sugg">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="destinationSearch" class="form-label">Destination</label>
                            <!-- Search Functionality -->
                            <div class="searchQuery">
                                <input type="text" class="form-control searchInput" id="destinationSearch" name="destinationSearch">
                                <div class="sugg">
                                </div>
                            </div>
                        </div>
                        <!-- Seats Diagram -->
                        <div class="mb-3">
                            <table id="seatsDiagram">
                            <tr>
                                <td id="seat-1" data-name="1">1</td>
                                <td id="seat-2" data-name="2">2</td>
                                <td id="seat-3" data-name="3">3</td>
                                <td id="seat-4" data-name="4">4</td>
                                <td id="seat-5" data-name="5">5</td>
                                <td id="seat-6" data-name="6">6</td>
                                <td id="seat-7" data-name="7">7</td>
                                <td id="seat-8" data-name="8">8</td>
                                <td id="seat-9" data-name="9">9</td>
                                <td id="seat-10" data-name="10">10</td>
                            </tr>
                            <tr>
                                <td id="seat-11" data-name="11">11</td>
                                <td id="seat-12" data-name="12">12</td>
                                <td id="seat-131" data-name="13">13</td>
                                <td id="seat-14" data-name="14">14</td>
                                <td id="seat-15" data-name="15">15</td>
                                <td id="seat-16" data-name="16">16</td>
                                <td id="seat-17" data-name="17">17</td>
                                <td id="seat-18" data-name="18">18</td>
                                <td id="seat-19" data-name="19">19</td>
                                <td id="seat-20" data-name="20">20</td>
                            </tr>
                            <tr>
                                    <td class="space">&nbsp;</td>
                                    <td class="space">&nbsp;</td>
                                    <td class="space">&nbsp;</td>
                                    <td class="space">&nbsp;</td>
                                    <td class="space">&nbsp;</td>
                                    <td class="space">&nbsp;</td>
                                    <td class="space">&nbsp;</td>
                                    <td class="space">&nbsp;</td>
                                    <td class="space">&nbsp;</td>
                                    <td class="space">&nbsp;</td>
                            </tr>
                            <tr>
                                <td id="seat-21" data-name="21">21</td>
                                <td id="seat-22" data-name="22">22</td>
                                <td id="seat-23" data-name="23">23</td>
                                <td id="seat-24" data-name="24">24</td>
                                <td id="seat-25" data-name="25">25</td>
                                <td id="seat-26" data-name="26">26</td>
                                <td id="seat-27" data-name="27">27</td>
                                <td class="space">&nbsp;</td>
                                <td id="seat-28" data-name="28">28</td>
                                <td id="seat-29" data-name="29">29</td>
                            </tr>
                            <tr>
                                <td id="seat-30" data-name="30">30</td>
                                <td id="seat-31" data-name="31">31</td>
                                <td id="seat-32" data-name="32">32</td>
                                <td id="seat-33" data-name="33">33</td>
                                <td id="seat-34" data-name="34">34</td>
                                <td id="seat-35" data-name="35">35</td>
                                <td id="seat-36" data-name="36">36</td>
                                <td class="space">&nbsp;</td>
                                <td id="seat-37" data-name="37">37</td>
                                <td id="seat-38" data-name="38">38</td>
                                </tr>
                            </table>
                        </div>
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-auto">
                                <label for="seatInput" class="col-form-label">Seat Number</label>
                            </div>
                            <div class="col-auto">
                                <input type="text" id="seatInput" class="form-control" name="seatInput" readonly>
                            </div>
                            <div class="col-auto">
                                <span id="seatInfo" class="form-text">
                                Select from the above figure, Maximum 1 seat.
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="bookAmount" class="form-label">Total Amount</label>
                            <input type="text" class="form-control" id="bookAmount" name="bookAmount" readonly>
                        </div>
                        <h5 class="py-3">Payment Details</h5>
                        <div class="mb-3">
                            <label for="creditCard" class="form-label">Card Number (Credit or debit)</label>
                            <input type="text" class="form-control" id="creditCard" name="creaditCard" required>
                        </div>
                        <div class="mb-3">
                            <label for="creditCardExpiry" class="form-label">Expiration Date</label>
                            <input type="text" class="form-control" id="creditCardExpiry" name="creditCardExpiry" required>
                        </div>
                        <div class="mb-3">
                            <label for="cw" class="form-label">CW / CVN</label>
                            <input type="text" class="form-control" id="cw" name="cw" required>
                        </div>
                        <button type="submit" class="btn btn-success" name="submit">Submit</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <!-- Add Anything -->
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/scripts/admin_booking.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>