<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once './config/Database.php';
session_start();
$database = new Database();
$db = $database->getConnection();

if(isset($_POST["submit"])){
    $errmsg = "";
    $email = $_POST["email"];
    $password = $_POST["password"];
    $sql = "SELECT id, name, surname, password, email, role FROM users WHERE email=?";
    $stmt = $db->prepare($sql);
    if ($stmt->execute([$email])) {
        if ($stmt->rowCount() == 1) {
            // Uzivatel existuje, skontroluj heslo.
            $row = $stmt->fetch();
            $hashed_password = $row["password"];
            // Nehashujeme ho zatial, problem pri verifikovani ak je hashovany externe
            if ($row["password"] === $_POST['password']) {
                // Uloz data pouzivatela do session.
                $_SESSION["loggedin"] = true;
                $_SESSION["name"] = $row['name'];
                $_SESSION["surname"] = $row['surname'];
                $_SESSION["email"] = $row['email'];
                $_SESSION["role"] = $row['role'];

                $sql1 = "SELECT * FROM users WHERE email=?";
                $sel = $db->prepare($sql1);
                $sel->execute([$_SESSION["email"]]);
                $user = $sel->fetchAll(PDO::FETCH_ASSOC);
                if ($_SESSION['role'] === 'Student')
//                    var_dump("hello");
                    header("location: /public/student/student.php");
                else
//                    var_dump("hello1");
                    header("location: /public/teacher/teacher.php");
            } else
                $errmsg .= "<p style='color: red'>Nesprávne meno alebo heslo.</p>";
                echo "<script>
                    error = document.getElementById('errorMessage');
                    error.innerHTML = '<?php echo $errmsg; ?>
                    </script>";
        } else
            $errmsg .= "<p style='color: red'>Nesprávne meno alebo heslo.</p>";
            echo "<script>
                    error = document.getElementById('errorMessage');
                    error.innerHTML = '<?php echo $errmsg; ?>
                    </script>";
    }

    unset($stmt);
    unset($db);
}
?>
<script>
    error = document.getElementById("errorCode");
    error.innerHTML = "<?php echo $errmsg; ?>";
</script>
