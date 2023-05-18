<?php
include "./../../src/includes.php";
include "./../../src/language.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../../config/Database.php');
$database = new Database();
$db = $database->getConnection();

if ((isset($_SESSION['access_token']) && $_SESSION['access_token'] || isset($_SESSION['loggedin']) && $_SESSION["loggedin"]) && $_SESSION['role'] == 'Ucitel') {
    $email = $_SESSION['email'];
    $name = $_SESSION['name'];
    $surname = $_SESSION['surname'];
    $role = $_SESSION['role'];
} else {
    header('Location: /SZ/index.php');
}
view('header', ['title' => 'Ucitel']);

function parseLatexFile($filename) {
    // 1. Load the LaTeX file
    $content = file_get_contents($filename);

    // 2. Define regular expressions
    $taskRegex = '/\\\\begin\{task\}((?:(?!\\\\end\{task\}).)*)\\\\includegraphics\{([^}]*)\}/s';
    $solutionRegex = '/\\\\begin\{equation\*\}((?:(?!\\\\end\{equation\*\}).)*)\\\\end\{equation\*\}/s';

    // 3. Get all matches
    preg_match_all($taskRegex, $content, $taskMatches);
    preg_match_all($solutionRegex, $content, $solutionMatches);

    // Clean up the matches
    $tasks = array_map('trim', $taskMatches[1]);
    $images = array_map('trim', $taskMatches[2]);
    $equations = array_map('trim', $solutionMatches[1]);

    // 4. Return the results
    return [
        'tasks' => $tasks,
        'images' => $images,
        'equations' => $equations
    ];
}

if (isset($_POST['submitFile'])) {

    $file = $_POST['exerciseSelect'];

    $filePath = "../../exams/".$file;
    
    $assignmentsArray = parseLatexFile($filePath);

    $points = $_POST['points'];
    if (isset($_POST['ifDeadline'])){
        $date = $_POST['deadline'];
    }
    else{
        $date = null;
    }
    $i = 0;
    foreach ($assignmentsArray as $assignment){
        $query = "  INSERT INTO assignments (type, number, points, date, result)
                        VALUES (
                            :type, 
                            :number, 
                            :points,
                            :date,
                            :result)";

        // Bind parametrov do SQL
        $stmt = $db->prepare($query);

        $stmt->bindParam(":type", $file, PDO::PARAM_STR);
        $stmt->bindParam(":number", $i, PDO::PARAM_STR);
        $stmt->bindParam(":points", $points, PDO::PARAM_STR);
        $stmt->bindParam(":date", $date, PDO::PARAM_STR);
        $stmt->bindParam(":result", $assignmentsArray["equations"][$i], PDO::PARAM_STR);

        if ($stmt->execute()) {
        } else {
            echo "Ups. Nieco sa pokazilo";
        }

        $i++;
    }

    

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    


    <title>Document</title>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-white">
        <div class="container-fluid d-flex justify-content-between">
            <a class="navbar-brand" href="/public/teacher/teacher.php"><img src="https://i.imgur.com/bw4kZxa.png" alt="Logo" title="Logo" style="width: 200px; height: 80px"/></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div>
                <a href="addFile.php?lang=sk">SK</a> | <a href="addFile.php?lang=en">EN</a>
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
        <div class="row ">
            <div class="col-lg-2 col-md-4 col-sm-12">
                <div class="col-lg-6 col-md-4 col-sm-12">
                    <div class="list-group">
                        <a href="teacher.php" class="list-group-item list-group-item-action"><?php echo $lang['all_students'] ?></a>
                        <a href="studentInfo.php" class="list-group-item list-group-item-action disabled"><?php echo $lang['student'] ?></a>
                        <a href="addFile.php" class="list-group-item list-group-item-action active"><?php echo $lang['add_file'] ?></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-12">
                <!-- Main content goes here -->

                <div class="container text-center">
                    <form method="post">
                    <label for="exerciseSelect"><?php echo $lang['file'] ?></label>
                        <select name="exerciseSelect" id="exercises">
                        <?php 
                                $dir="../../exams";
                                $files = scandir($dir); 
                                foreach ($files as $file) {
                                    // Exclude directories and only process files
                                    if (is_file($dir . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'tex') {
                                        echo '<option value="'.$file.'">'.$file.'</option>';
                                    }
                                }
                        ?>
                        </select>
                        <br><br>
                        <label for="points"><?php echo $lang['file_tasks_points'] ?></label>
                        <input type="number" name="points" id="points" value="1">
                        <br><br>
                        <label for="ifDeadline"><?php echo $lang['add_deadline'] ?></label>
                        <input type="checkbox" name="ifDeadline" id="deadlineCheckbox" onclick="checkboxFunction()">
                        <br><br>
                        <div id="deadline" class="d-none">
                            <label for="deadline"><?php echo $lang['deadline'] ?></label>
                            <input type="date" name="deadline" id="deadlineDate">
                            <br><br>
                        </div>
                        <button type="submit" name="submitFile"><?php echo $lang['add_file'] ?></button>
                    </form>
                </div>



            </div>
        </div>
    </div>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-3.6.0.js"></script>
    <!--<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/7c8801c017.js" crossorigin="anonymous"></script>
    
    
    <!-- DataTables sciprts -->
    <link href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.13.4/af-2.5.3/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/cr-1.6.2/date-1.4.1/fc-4.2.2/fh-3.3.2/kt-2.9.0/r-2.4.1/rg-1.3.1/rr-1.3.3/sc-2.1.1/sb-1.4.2/sp-2.1.2/sl-1.6.2/sr-1.2.2/datatables.min.css" rel="stylesheet"/>
 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.13.4/af-2.5.3/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/cr-1.6.2/date-1.4.1/fc-4.2.2/fh-3.3.2/kt-2.9.0/r-2.4.1/rg-1.3.1/rr-1.3.3/sc-2.1.1/sb-1.4.2/sp-2.1.2/sl-1.6.2/sr-1.2.2/datatables.min.js"></script>
    
    <script src="../tableLogic.js"></script>
    <script src="../formLogic.js"></script>

</body>
</html>