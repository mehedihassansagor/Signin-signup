<?php
session_start();
include('dbcon.php');

// Handle the sign-up process
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    $name = $_POST['signup-name'];
    $email = $_POST['signup-email'];
    $password = $_POST['signup-password'];

    // Simple validation
    if (!empty($name) && !empty($email) && !empty($password)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user details into the database
        $sql = "INSERT INTO student (name, email, password) VALUES ('$name', '$email', '$hashed_password')";

        if (mysqli_query($connection, $sql)) {
            echo "Sign Up successful!";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($connection);
        }
    } else {
        echo "Please fill in all fields.";
    }
}

// Handle the sign-in process
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signin'])) {
    $email = $_POST['signin-email'];
    $password = $_POST['signin-password'];

    // Simple validation
    if (!empty($email) && !empty($password)) {
        // Retrieve the user from the database
        $sql = "SELECT * FROM student WHERE email='$email'";
        $result = mysqli_query($connection, $sql);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Set a session variable to indicate the user is signed in
                $_SESSION['user'] = $user['name'];  // You can store the user's name or other info
                $_SESSION['message'] = "Your sign in is complete!";  // Message to show after sign in
                
                // Redirect to the same page or a new page to show the message
                header("Location: index.php");
                exit();
            } 
        } 
    } 
}
?>

<!-- HTML code -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In / Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">

                    <!-- Show sign in complete message -->
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-success">
                            <?= $_SESSION['message']; ?>
                        </div>
                        <?php unset($_SESSION['message']); // Clear the message after showing it ?>
                    <?php endif; ?>

                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-signin-tab" data-bs-toggle="pill" data-bs-target="#pills-signin" type="button" role="tab" aria-controls="pills-signin" aria-selected="true">Sign In</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-signup-tab" data-bs-toggle="pill" data-bs-target="#pills-signup" type="button" role="tab" aria-controls="pills-signup" aria-selected="false">Sign Up</button>
                        </li>
                    </ul>

                    <!-- Sign In Form -->
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-signin" role="tabpanel" aria-labelledby="pills-signin-tab">
                            <h2 class="text-center">Sign In</h2>
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="signin-email" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="signin-email" name="signin-email" placeholder="Enter your email">
                                </div>
                                <div class="mb-3">
                                    <label for="signin-password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="signin-password" name="signin-password" placeholder="Enter your password">
                                </div>
                                <button type="submit" class="btn btn-primary w-100" name="signin">Sign In</button>
                                <p class="text-center mt-3">Don't have an account? <a href="#" id="pills-signup-tab" data-bs-toggle="pill" data-bs-target="#pills-signup">Sign Up</a></p>
                            </form>
                        </div>

                        <!-- Sign Up Form -->
                        <div class="tab-pane fade" id="pills-signup" role="tabpanel" aria-labelledby="pills-signup-tab">
                            <h2 class="text-center">Sign Up</h2>
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="signup-name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="signup-name" name="signup-name" placeholder="Enter your full name">
                                </div>
                                <div class="mb-3">
                                    <label for="signup-email" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="signup-email" name="signup-email" placeholder="Enter your email">
                                </div>
                                <div class="mb-3">
                                    <label for="signup-password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="signup-password" name="signup-password" placeholder="Create a password">
                                </div>
                                <button type="submit" class="btn btn-success w-100" name="signup">Sign Up</button>
                                <p class="text-center mt-3">Already have an account? <a href="#" id="pills-signin-tab" data-bs-toggle="pill" data-bs-target="#pills-signin">Sign In</a></p>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
