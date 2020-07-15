<?php
session_start();

function setToken()
{
    $token = sha1(uniqid(mt_rand(), true));
    $_SESSION['token'] = $token;
}

function checkToken()
{
    if (empty($_SESSION['token'])) {
        echo "Sessionが空です";
        exit;
    }

    if (($_SESSION['token']) !== $_POST['token']) {
        echo "不正な投稿です。";
        exit;
    }

    $_SESSION['token'] = null;
}

if (empty($_SESSION['token'])) {
    setToken();
}
?>

<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link crossorigin="anonymous" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" rel="stylesheet">
    <title>TODO App</title>
</head>
<body>

<div class="container">
    <div class="col-md-8">
        <h1 class="text-center text-primary py-3">TODO App</h1>

        <h2 class="text-muted py-3">TODO作成 </h2>
        <form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
            <h6 class="task_name">タスク名</h6>
            <input type="text" class="form-control" name="personal_name" placeholder="タスク名" required><br>
            <h6 class="kigen">期限</h6>
            <!-- 日付入力 -->
            <input type="date"></input>
            <br><br><br><br>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
            <input class="btn btn-primary"  type="submit" name="btn" value="TODOを作成する">
        </form>

        <h3 class="text-muted py-3">やること一覧 </h3>

        <form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
            <input type="hidden" name="method" value="DELETE">
            <button class="btn btn-danger" type="submit">TODOを全削除する</button>
        </form>

        <thead>
            <tr>
                <th>タイトル</th>
                <th>状態</th>
                <th>期限</th>
                <th>更新</th>
            </tr>
            </thead>
<?php

date_default_timezone_set('Asia/Tokyo');
const TODO_FILE = 'todo.txt';

require_once './todo.php';
$todo = new TODO('TODO App');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["method"]) && $_POST["method"] === "DELETE") {
        $todo->delete();
    } else {
        $todo->post($_POST['personal_name'], $_POST['contents']);
    }

    // ブラウザのリロード対策
    $redirect_url = $_SERVER['HTTP_REFERER'];
    header("Location: $redirect_url");
    exit;
}

echo $todo->getList();

?>

<!-- JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>