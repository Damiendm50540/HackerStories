<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); 
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Strict');

session_start();

if (!isset($_SESSION["username"])) {
    header("location:login.php");
    exit();
} else {
    $current = $_SESSION['username'];
}
?>

<?php
include("config/db_connect");

$errors = array('username'=>'','title'=>'','content'=>'','emptyE'=>'','emptyT'=>'','emptyI'=>'');

$username = $title = $content = '';
if(isset($_POST["submit"])) {
    if(empty($_POST['username'])) {
        $errors['emptyE'] = 'AN username IS REQUIRED <br />';
    } else {
        $username = $_POST['username'];
        if($username != $current) {
            $errors['username'] = 'Username does not match the current session user';
        }
    }

    if(empty($_POST['title'])) {
        $errors['emptyT'] = 'A title IS REQUIRED <br />';
    } else {
        $title = $_POST['title'];
    }

    if(empty($_POST['content'])) {
        $errors['emptyI'] = 'Content IS REQUIRED <br />';
    } else {
        $content = $_POST['content'];
    }

    if(array_filter($errors)) {
    } else {
        $sql = "INSERT INTO stories(username, title, content) VALUES(?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $title, $content);
        if($stmt->execute()) {
            header('Location: index.php');
        } else {
            echo 'query error: ' . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include('templates/header.php'); ?>
<section class="container grey-text">
    <h4 class="center">Add a story</h4>
    <form class="white" action="add.php" method="POST">
        <label for="">Your username:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
        <div class="red-text"><?php echo $errors['emptyE']; ?></div>
        <div class="red-text"><?php echo $errors['username']; ?></div>
        <label for="">Story title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>">
        <div class="red-text"><?php echo $errors['emptyT']; ?></div>
        <label for="">Content (you can style your content ;)):</label>
        <input type="text" name="content" value="<?php echo htmlspecialchars($content); ?>">
        <div class="red-text"><?php echo $errors['emptyI']; ?></div>
        <div class="center">
            <input type="submit" value="submit" name="submit" class="btn waves-effect waves-light">
        </div>
    </form>
    <div class="center" style="margin-top: 20px;">
        <a href="idea.php" class="btn waves-effect waves-light">Get idea from external website</a>
    </div>
</section>
<?php include('templates/footer.php'); ?>
</html>