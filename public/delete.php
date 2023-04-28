=<?php

require "../common.php";

$success = "";

if (isset($_GET["id"])) {
    try {
        require_once '../src/DBconnect.php';
        $id = intval($_GET["id"]);
        $sql = "DELETE FROM users WHERE id = :id";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();
        $success = "User " . $id . " successfully deleted";
    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}

try {
    require_once '../src/DBconnect.php';
    $sql = "SELECT * FROM users WHERE id != 1";
    $statement = $connection->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll();
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
?>
<?php require "templates/header.php"; ?>
<div class="container mt-4">
    <h2>Delete users</h2>
    <?php if ($success) : ?>
        <div class="alert alert-success" role="alert">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email Address</th>
            <th>Age</th>
            <th>User Role</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($result as $row) : ?>
        <tr>
            <td><?php echo htmlspecialchars($row["id"]); ?></td>
            <td><?php echo htmlspecialchars($row["name"]); ?></td>
            <td><?php echo htmlspecialchars($row["surname"]); ?></td>
            <td><?php echo htmlspecialchars($row["email"]); ?></td>
            <td><?php echo htmlspecialchars($row["age"]); ?></td>
            <td><?php echo ($row["user_role"] == "admin") ? '<span style="color: red;">Admin</span>' : 'User'; ?></td>
            <?php if ($row["id"] == 1) : ?>
                <td colspan="2" style="color: red;">Admin profile cannot be edited</td>
            <?php else : ?>
            <td><a href="update_profile.php?id=<?php echo htmlspecialchars($row["id"]); ?>" class="btn btn-primary">Edit</a></td>
            <td><a href="delete.php?id=<?php echo htmlspecialchars($row["id"]); ?>" class="btn btn-danger">Delete</a></td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>

        </tbody>
    </table>
    <a href="admin.php" class="btn btn-primary">Back to Admin Panel</a>
</div>
<?php require "templates/footer.php"; ?>