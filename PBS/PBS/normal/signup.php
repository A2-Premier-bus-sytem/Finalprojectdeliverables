<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Admin</title>
            <!-- google fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500&display=swap" rel="stylesheet">
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        <!-- Font Awesome -->
        <script src="https://kit.fontawesome.com/d8cfbe84b9.js" crossorigin="anonymous"></script>
        <!-- External CSS -->
        <?php 
        require '../assets/styles/admin.php';
        require '../assets/styles/signup.php';
        $page="signup";
    ?>
    </head>
<body>

        <!-- Signup Status -->
            <?php
                if(isset($_GET['signup']))
                {
                    if($_GET['signup'])
                    {
                        // Show success alert
                        echo '<div class="my-0 alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Successful!</strong> Account created successfully
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                    }

                    elseif($_GET['user_exists'])
                        // Show error alert
                        echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> Username already exists
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                }
            ?>
            <section id="add-admin">
                <div>
                    <div id="signupForm">
                        <h2>ADD NEW USER</h2>
                        <form action="../assets/partials/_handleNormalSignup.php" method="POST">
                            <div class="d-flex">
                                <div class="form-group">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="firstName" required>
                                </div>
                                <div class="form-group d-flex flex-column">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" name="lastName" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                             <div class="form-group">
                                <label class="form-label">Contact Number</label>
                                <input type="tel" class="form-control" name="cphone" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Password</label>
                                <input id="password" class="form-control" type="password" name="password" required>
                                <span id="passwordErr" class="error"></span>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirm Password</label>
                                <input id="confPassword" class="form-control" type="password" name="confPassword" required>
                                <span id="confPassErr" class="error"></span>
                            </div>
                            <button id="signup-btn" type="submit" name="signup">PROCEED</button>
                        </form>
                    </div>
                </div>
                <div>
                </div>
            </section>
        </div>
    <script src="../assets/scripts/admin_signup.js">
    </script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>  
</body>
</html>