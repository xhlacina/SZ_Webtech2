<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './config/Database.php';
include "./src/includes.php";

if (isset($_SESSION['access_token']) && $_SESSION['access_token'] || isset($_SESSION['loggedin']) && $_SESSION["loggedin"]) {
    if ($_SESSION['role'] == 'Student')
        header("location: /public/student/student.php");
    else
        header("location: /public/teacher/teacher.php");
}

view('header', ['title' => 'Hlavná Stránka']);

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
                    header("location: /public/student/student.php");
                else
                    header("location: /public/teacher/teacher.php");
            } else
                $errmsg .= "<p style='color: red'>Nesprávne meno alebo heslo.</p>";
        } else
            $errmsg .= "<p style='color: red'>Nesprávne meno alebo heslo.</p>";
    } else
        echo "<p>Ups. Nieco sa pokazilo!</p>";

    unset($stmt);
    unset($db);
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-white">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><img src="https://i.imgur.com/bw4kZxa.png" alt="Logo" title="Logo" style="width: 200px; height: 80px"/></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
<!--        <div class="collapse navbar-collapse" id="navbarSupportedContent">-->
<!--            <ul class="navbar-nav me-auto mb-2 mb-lg-0">-->
<!--            </ul>-->

<!--        </div>-->
    </div>
</nav>
<section class="vh-100">
    <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-80">
            <div class="col-lg-12 col-xl-11">
                <div class="card-body p-md-5">
                    <div class="row justify-content-center">
                        <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                            <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4 needs-validation">Prihlásenie</p>
                            <form class="mx-1 mx-md-4" method="post" action="./">
                                <!-- Email input -->
                                <div class="form-outline mb-4">
                                    <label class="form-label" for="email">Email :)</label>
                                    <input type="text" id="email" name="email" class="form-control" required/>
                                    <div class="invalid-feedback">
                                        Zadaj spravny email.
                                    </div>
                                </div>
                                <!-- Password input -->
                                <div class="form-outline mb-4">
                                    <label class="form-label" for="password">Heslo</label>
                                    <input type="password" name="password" id="password" class="form-control" required/>
                                    <div class="invalid-feedback">
                                        Zadaj spravne heslo.
                                    </div>
                                </div>

                                <?php
                                if (!empty($errmsg)) {
                                    echo $errmsg;
                                }
                                ?>

                                <!-- Submit button -->
                                <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                    <button type="submit" name="submit" class="btn btn-primary btn-lg">Prihlásiť</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

