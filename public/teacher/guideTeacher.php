<?php
include "./../../src/includes.php";
include "./../../src/language.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ((isset($_SESSION['access_token']) && $_SESSION['access_token'] || isset($_SESSION['loggedin']) && $_SESSION["loggedin"]) && $_SESSION['role'] == 'Ucitel') {
    $email = $_SESSION['email'];
    $name = $_SESSION['name'];
    $surname = $_SESSION['surname'];
    $role = $_SESSION['role'];
} else {
    header('Location: /SZ/index.php');
}
view('header', ['title' => 'Učiteľ']);

?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
<nav class="navbar navbar-expand-lg navbar-dark bg-white">
    <div class="container-fluid d-flex justify-content-between">
        <a class="navbar-brand" href="/public/student/student.php"><img src="https://i.imgur.com/bw4kZxa.png" alt="Logo" title="Logo" style="width: 200px; height: 80px"/></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div>
            <a href="guideTeacher.php?lang=sk">SK</a> | <a href="guideTeacher.php?lang=en">EN</a>
        </div>
        <div style="color: #7676a7" class="navbar-brand ms-auto">
            <?php echo $email?>
            <a style="color: #ff3333" href="/src/logout.php">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-2 col-md-4 col-sm-12">
            <div class="col-lg-6 col-md-4 col-sm-12">
                <div class="list-group">
                    <a href="teacher.php" class="list-group-item list-group-item-action active"><?php echo $lang['all_students'] ?></a>
                    <a href="studentInfo.php" class="list-group-item list-group-item-action disabled"><?php echo $lang['student'] ?></a>
                    <a href="addFile.php" class="list-group-item list-group-item-action"><?php echo $lang['add_file'] ?></a>
                    <a href="guideTeacher.php" class="list-group-item list-group-item-action "><?php echo $lang['guide']; ?></a>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-10 col-sm-12">
            <!-- Main content goes here -->
            <div class="container" id="makepdf">
                  <h1><?php echo $lang['guide']; ?></h1>
                  <ol>
                    <li><?php echo $lang['teacher1']; ?></li>
                    <li><?php echo $lang['teacher2']; ?></li>
                    <li><?php echo $lang['teacher3']; ?></li>
                    <li><?php echo $lang['teacher4']; ?></li>
                    <li><?php echo $lang['teacher5']; ?></li>
                  </ol>   
            </div>
            <button id="pdfButton"><?php echo $lang['pdf_button']; ?></button>
        </div>
    </div>
</div>
<script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-3.6.0.js"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/7c8801c017.js" crossorigin="anonymous"></script>
<script src="../pdfLogic.js"></script>