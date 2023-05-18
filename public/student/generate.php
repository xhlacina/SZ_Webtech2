<?php
include "./../../src/includes.php";
include "./../../src/language.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../../config/Database.php";

if ((isset($_SESSION['access_token']) && $_SESSION['access_token'] || isset($_SESSION['loggedin']) && $_SESSION["loggedin"]) && $_SESSION['role'] == 'Student') {
    $email = $_SESSION['email'];
    $name = $_SESSION['name'];
    $surname = $_SESSION['surname'];
    $role = $_SESSION['role'];
} else
    header('Location: /index.php');
view('header', ['title' => 'Student']);

$database = new Database();
$db = $database->getConnection();

$query = 'SELECT * FROM users';
$stmt = $db->query($query); 
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

function getRandomTask($filename){
    $countFiles = count($filename);
    echo $countFiles . "<br>";
    $random = rand(0,$countFiles);
    echo $random . "<br>";
    $file_name = $filename[$random];
    echo $file_name . "<br>";
    $tasks = parseLatexFile($file_name);
    $tasksCount = count($tasks['tasks']);
    $task_num = rand(0,$tasksCount);
    return [
        'task' => $tasks['tasks'][$task_num],
        'image' => $tasks['images'][$task_num],
        'equation' => $tasks['equations'][$task_num]
    ];
}
if(isset($_GET['type'])){
    if(!$_GET['type']==null){
        $currentDate = date('Y-m-d');
        $query = 'SELECT *
        FROM assignments s
        WHERE s.id NOT IN (SELECT assignment_id FROM student_assignment WHERE student_id = 1) and s.type="'.$_GET['type'].'" and (s.date>"'.$currentDate.'" or s.date is null) ORDER BY RAND() LIMIT 1;';
        $stmt = $db->query($query); 
        $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(sizeof($assignments)==0){
            echo "Nemožno priradiť ďalšie úlohy.";
        }else{
            $query = 'INSERT INTO student_assignment (student_id,assignment_id,submited,result,correct,student_score) VALUES (1,'.$assignments[0]['id'].',0,0,"'.$assignments[0]['result'].'",0)';
            $stmt = $db->query($query); 

            $query = 'select * from students where id=1';
            $stmt = $db->query($query); 
            $student = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $recieved =$student[0]['recieved']+1;
            $max=$student[0]['max_points']+$assignments[0]['points'];
            $query = 'UPDATE students SET recieved='.$recieved.', max_points='.$max.'';
            $stmt = $db->query($query); 
        }

    }
}


?>
<nav class="navbar navbar-expand-lg navbar-dark bg-white">
    <div class="container-fluid d-flex justify-content-between">
        <a class="navbar-brand" href="/public/student/student.php"><img src="https://i.imgur.com/bw4kZxa.png" alt="Logo" title="Logo" style="width: 200px; height: 80px"/></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div>
            <a href="generate.php?lang=sk">SK</a> | <a href="generate.php?lang=en">EN</a>
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
                    <a href="student.php" class="list-group-item list-group-item-action "><?php echo $lang['view_tasks'] ?></a>
                    <a href="#" class="list-group-item list-group-item-action active"><?php echo $lang['generate_task'] ?></a>
                    <a href="guideStudent.php" class="list-group-item list-group-item-action "><?php echo $lang['guide']; ?></a>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-8 col-sm-12">
        <form action="" method="get">
                <select name="type" >
                    <?php 
                        $query = 'SELECT DISTINCT type FROM assignments';
                        $stmt = $db->query($query); 
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($results as $result){
                            echo '<option value="'.$result['type'].'">'.$result['type'].'</option>';
                        }
                        
                        ?>
                </select>
                <button class="btn btn-success"><?php echo $lang['generate_task'] ?></button>
            </form>
        </div>
    </div>
</div>
<script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-3.6.0.js"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/7c8801c017.js" crossorigin="anonymous"></script>