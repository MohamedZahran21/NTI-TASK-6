<?php
session_start();
require './Configurations/connection.php';

$sql = "SELECT * FROM Users";
$result = $connection->query($sql);

$statement = $connection->prepare($sql);
if ($statement->execute()){
    $result = $statement->get_result();
}
$statement->close();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete'){
    $UserID = $_POST['UserID'];
    $sql = "DELETE FROM Users WHERE UserID = ?";
    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $UserID);
    if ($statement->execute()){
        $_SESSION['success'] = 'User Deleted Successfully';
        header("Location: .");
        die;
    }
}

$connection->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
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
        <?php if (isset($_SESSION['success'])) { ?>
            <div class="alert alert-success my-3" role="alert">
                <?= $_SESSION['success'] ?>
            </div>
        <?php } ?>
        <table class="table table-striped table-hover my-3">
            <caption>List of users</caption>
            <thead class="table-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php $i = 0 ?>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <th scope="row"><?= ++$i ?></th>
                        <td><?= $row['Name'] ?></td>
                        <td><?= $row['Email'] ?></td>
                        <td>
                            <a href="./edit.php?UserID=<?= $row['UserID'] ?>" class="btn btn-success btn-sm"><i class="bi bi-pencil-square"></i></a>
                            <form method="post" class="d-inline">
                                <input type="hidden" name="UserID" value="<?= $row['UserID'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button onclick="deleteConfirm(this)" type="button" class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./assets/scripts/app.js"></script>
    <script>
        let deleteConfirm = btn => {
            const form = btn.closest("form");
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                // icon: "warning",
                toast: false,
                position: "top",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
    <script>
        const alert = document.querySelector(".alert");        
    </script>
</body>

</html>

<?php unset($_SESSION['success']) ?>