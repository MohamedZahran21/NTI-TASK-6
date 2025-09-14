<?php
session_start();

if (isset($_SESSION['auth_user'])) {
    header("Location: .");
    die;
}

require './Configurations/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $userName = trim($_POST['Name']);
    $userEmail = trim($_POST['Email']);
    $userPassword = $_POST['Password'];    
    $hashedpassword = password_hash($userPassword, PASSWORD_DEFAULT);
   
    $sql = "INSERT INTO Users (Name, Email, Password) VALUES (?, ?, ?)";
    $statement = $connection->prepare($sql);
    $statement->bind_param("sss", $userName, $userEmail, $hashedpassword);
    
    if ($statement->execute()) {
        header("Location: login.php");
        die;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="./Assets/CSS/style.css">
</head>

<body>
    <div class="container">
        <h1 class="my-5 fw-bold">Register</h1>

        <form class="w-50" method="post">
            <div class="mb-3">
                <label for="exampleInputText1" class="form-label">Username</label>
                <input type="text" name="Name" class="form-control" id="exampleInputtext1">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email Address</label>
                <input type="email" name="Email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" name="Password" class="form-control" id="exampleInputPassword1">
            </div>
            <div class="mb-3">
                <label for="formFile" class="form-label">Profile    Image</label>
                <input class="form-control" type="file" id="formFile">
            </div>
            <div class="mb-3">
                <span>Already Have An Account?</span>
                <a href="./login.php">Login</a>
            </div>
            <button type="submit" class="btn btn-primary" name="action" value="register">Register</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="./Assets/JS/script.js"></script>
</body>

</html>