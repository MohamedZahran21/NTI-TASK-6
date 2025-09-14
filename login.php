<?php
session_start();

if (isset($_SESSION['auth_user'])) {
    header("Location: .");
    die;
}

require './Configurations/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $userEmail = $_POST['Email'];
    $userPassword = $_POST['Password'];

    $sql = "SELECT * FROM Users WHERE Email = ? LIMIT 1";
    $statement = $connection->prepare($sql);
    $statement->bind_param("s", $userEmail);
    if ($statement->execute()) {
        $result = $statement->get_result();
        if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $isPasswordCorrect = password_verify($userPassword, $row['Password']);
                if ($isPasswordCorrect) {
                
                    $_SESSION['auth_user'] = [
                        'UserID' => $row['UserID'],
                        'Name' => $row['Name']
                    ];
                    header("Location: .");
                    die;
                } else {
                echo "Error";
            }
        } else {
            "Error";
        }
    }

    $statement->close();
    $connection->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="./Assets/CSS/style.css">
</head>

<body>
    <div class="container">
        <h1 class="my-5 fw-bold">Login</h1>

        <form class="w-50" method="post">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email Address</label>
                <input type="email" name="Email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" name="Password" class="form-control" id="exampleInputPassword1">
            </div>
            <div class="mb-3">
                <span>Create New Account:   </span>
                <a href="./register.php">Register</a>
            </div>
            <button type="submit" class="btn btn-primary" name="action" value="login">Login</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="./Assets/JS/script.js"></script>
</body>

</html>