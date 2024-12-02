<?php
require_once("../26.db_connect.php");

if (!isset($_GET["id"])) {
    echo "請帶入id到此頁";
    exit;
}
$id = $_GET["id"];

$sql = "SELECT * FROM users WHERE id='$id' AND is_deleted=0";

$result = $conn->query($sql);
$row = $result->fetch_assoc();


?>
<!doctype html>
<html lang="en">

<head>
    <title>User</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous" />
    <?php include("../css.php") ?>
</head>

<body>
    <!-- Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">確認刪除</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    確認刪除該帳號?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <a href="51.doDelete.php?id=<?= $row["id"] ?>" class="btn btn-danger">確認</a>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="py-2">

        </div>
        <?php if ($result->num_rows > 0): ?>
            <h1><?= $row["name"] ?></h1>
            <form action="45.doUpdateUser.php" method="post">
                <table class="table table-bordered">
                    <input type="hidden" name="id" value="<?= $row["id"] ?>">
                    <tr>
                        <th>id</th>
                        <td><?= $row["id"] ?></td>
                    </tr>
                    <tr>
                        <th>name</th>
                        <td><input type="name" class="form-control" name="name" value="<?= $row["name"] ?>"></td>
                    </tr>
                    <tr>
                        <th>email</th>
                        <td><input type="email" class="form-control" name="email" value="<?= $row["email"] ?>"></td>
                    </tr>
                    <tr>
                        <th>phone</th>
                        <td><input type="phone" class="form-control" name="phone" value="<?= $row["phone"] ?>"></td>
                    </tr>
                    <tr>
                        <th>created time</th>
                        <td><?= $row["created_at"] ?></td>
                    </tr>
                </table>
                <div class="d-flex justify-content-between">
                    <div>
                        <button class="btn btn-info" type="submit">儲存</button>
                        <a class="btn btn-info" href="41.user.php?id=<?= $row["id"] ?>">取消</a>
                    </div>
                    <div>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal" type="button">刪除</button>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <h1>找不到使用者</h1>
        <?php endif; ?>
    </div>
    <!-- Bootstrap JavaScript Libraries -->
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>

</html>