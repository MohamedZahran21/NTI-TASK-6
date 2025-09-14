<?php
session_start();

require './Configurations/connection.php';

if (!isset($_SESSION['auth_user'])) {
    header("Location: ./login.php");
    die;
}

if (isset($_GET['UserID'])) {
    $UserID = $_GET['UserID'];

    $sql = "SELECT * FROM Users WHERE UserID = ? LIMIT 1";
    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $UserID);
    if ($statement->execute()) {
        $result = $statement->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        }
    }

    $statement->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $UserID = $_POST['UserID'];
    $userName = $_POST['Name'];
    $userEmail = $_POST['Email'];

    $sql = "UPDATE Users SET Name = ?, Email = ? WHERE UserID = ?";
    $statement = $connection->prepare($sql);
    $statement->bind_param("ssi", $userName, $userEmail, $UserID);
    if ($statement->execute()) {
        header("Location: .");
    }

    $statement->close();
}

$connection->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./Assets/CSS/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                </ul>
                <?php if (isset($_SESSION['auth_user'])) { ?>
                    <div class="me-2">
                        <span class="navbar-text">Hi, <span class="fw-bold"><?= $_SESSION['auth_user']['Name'] ?></span></span>
                        
                        <form action="./logout.php" method="post" class="d-inline">
                            <button class="btn btn-danger btn-sm ms-2" type="submit">Logout</button>
                        </form>
                    </div>
                <?php } ?>
                <?php if (!isset($_SESSION['auth_user'])) { ?>
                    <div class="me-2">
                        <a href="./login.php" class="btn btn-primary btn-sm ms-2">Login</a>
                        <a href="./register.php" class="btn btn-success btn-sm ms-2">Register</a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </nav>
    <div class="container">
        <h1 class="text-center fw-bold my-3">Update User</h1>
        <form class="m-5" method="post">
            <input type="hidden" name="UserID" value="<?= $row['UserID'] ?>">
            <div class="row">
                <div class="col">
                    <input type="text" name="Name" class="form-control" placeholder="Name" aria-label="Name" value="<?= $row['Name'] ?>">
                </div>
                <div class="col">
                    <input type="email" name="Email" class="form-control" placeholder="Email" aria-label="Email" value="<?= $row['Email'] ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3" name="action" value="update">Update User</button>
            <a href="." class="btn btn-secondary mt-3">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="./Assets/JS/script.js"></script>
</body>

</html>