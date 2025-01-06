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
}

include("config/db_connect");

if(isset($_POST["delete"])) {
    $id_to_delete = $_POST["id_to_delete"];
    $sql = "DELETE FROM stories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_to_delete);
    if($stmt->execute()) {
        header('Location: index.php');
    } else {
        echo "query error: " . $stmt->error;
    }
}

// Vérifier le paramètre id de la requête GET
if(isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "SELECT * FROM stories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $story = $result->fetch_assoc();
    $stmt->free_result();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include('templates/header.php'); ?>
<section class="container grey-text">
    <h4 class="center">Story Details</h4>
    <?php if($story): ?>
        <h5><?php echo htmlspecialchars($story['title']); ?></h5>
        <p>Written by <?php echo htmlspecialchars($story['username']); ?></p>
        <p><?php echo htmlspecialchars($story['content']); ?></p>
        <form action="details.php" method="POST">
            <input type="hidden" name="id_to_delete" value="<?php echo $story['id']; ?>">
            <input type="submit" name="delete" value="Delete" class="btn brand z-depth-0">
        </form>
    <?php else: ?>
        <h5>No such story exists.</h5>
    <?php endif; ?>
</section>
<?php include('templates/footer.php'); ?>
</html>