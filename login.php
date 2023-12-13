<?php
// Database connection settings
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'toserba';

try {
    $conn = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get the entered username and password
    $username = $_POST['username'];
    $password = $_POST['password']; // Password tidak perlu di-MD5 karena sudah di-MD5 pada database

    // Query to check if the username and password match
    $query = "SELECT * FROM login WHERE user = :username";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Check if the entered password matches the hashed password stored in the database
        if (md5($password) === $user['pass']) {
            // Authentication successful
            session_start();
            $_SESSION['username'] = $username;

            // Redirect to index.php with success message
            header('Location: index.php?login_success=true');
            exit();
        } else {
            // Authentication failed
            $error_message = "Invalid username or password!";
        }
    } else {
        // Authentication failed
        $error_message = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.104.2">
    <title>Login</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/sign-in/">
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/signin.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .logo-container {
            margin-bottom: 50px; /* Adjust the margin as needed */
        }

        .form-signin {
            margin-top: -50px; /* Adjust the margin as needed */
        }

        /* Adjust the font size for username and password fields */
        input[type='text'], input[type='password'] {
            font-size: 24px; /* Increase the font size to make them larger */
        }

        /* Adjust the font size for the login button */
        .btn-lg {
            font-size: 24px; /* Increase the font size to make it larger */
        }
    </style>
</head>
<body class="text-center">

<main class="form-signin">
    <div class="logo-container">
        <img class="mb-4" src="logo.png" alt="" width="200" height="200">
    </div>
    
    <form method="POST" action="">
        <h1 class="h3 mb-3 fw-normal" style="margin-top: -20px;">Login</h1>

        <div class="form-floating">
            <input type="text" name="username" class="form-control form-control-lg" id="floatingInput" placeholder="name@example.com" required>
            <label for="floatingInput">Username</label>
        </div>
        <div class="form-floating">
            <input type="password" name="password" class="form-control form-control-lg" id="floatingPassword" placeholder="Password" required>
            <label for="floatingPassword">Password</label>
        </div>

        <?php if (isset($error_message)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php } ?>

        <button class="w-100 btn btn-lg btn-primary" type="submit" name="submit">Login</button>
    </form>
</main>

</body>
</html>
