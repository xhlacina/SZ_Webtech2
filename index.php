<?php
include "./src/includes.php";
include "./src/language.php";

if (isset($_GET['errmsg'])) {
    $errmsg = $_GET['errmsg'];
}
if (isset($_SESSION['access_token']) && $_SESSION['access_token'] || isset($_SESSION['loggedin']) && $_SESSION["loggedin"]) {
    if ($_SESSION['role'] == 'Student')
        header("location: /public/student/student.php");
    else
        header("location: /public/teacher/teacher.php");
}

view('header', ['title' => 'Hlavná Stránka']);

?>
<nav class="navbar navbar-expand-lg navbar-dark bg-white">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><img src="https://i.imgur.com/bw4kZxa.png" alt="Logo" title="Logo" style="width: 200px; height: 80px"/></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>
<section class="vh-100">
    <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-80">
            <div class="col-lg-12 col-xl-11">
                <div class="card-body p-md-5">
                    <div class="row justify-content-center">
                        <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                            <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4 needs-validation"><?php echo $lang['login'] ?></p>
                            <form class="mx-1 mx-md-4" method="post" action="login.php">
                                <!-- Email input -->
                                <div class="form-outline mb-4">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="text" id="email" name="email" class="form-control" required/>
                                    <div class="invalid-feedback">
                                    <?php echo $lang['invalid_email'] ?>
                                    </div>
                                </div>
                                <!-- Password input -->
                                <div class="form-outline mb-4">
                                    <label class="form-label" for="password"><?php echo $lang['password'] ?></label>
                                    <input type="password" name="password" id="password" class="form-control" required/>
                                    <div class="invalid-feedback">
                                        <?php echo $lang['invalid_password'] ?>
                                    </div>
                                </div>
                                <p id="errorMessage"></p>
                                <?php
                                if (!empty($errmsg)) {
                                    echo $errmsg;
                                }
                                ?>

                                <!-- Submit button -->
                                <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                    <button type="submit" name="submit" class="btn btn-primary btn-lg"><?php echo $lang['login_button'] ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

