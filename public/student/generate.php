<?php
include "./../../src/includes.php";
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



function parseLatexFile($filename) {
    // 1. Load the LaTeX file
    $content = file_get_contents($filename);

    // 2. Define regular expressions
    $taskRegex = '/\\\\begin{task}(.*?)\\\\includegraphics{(.*?)}.?\\\\end{task}/s';
    $solutionRegex = '/\\\\begin{equation\*}(.*?)\\\\end{equation\*}/s';


    // 3. Get all matches
    preg_match_all($taskRegex, $content, $taskMatches);
    preg_match_all($solutionRegex, $content, $solutionMatches);

    // Clean up the matches
    $tasks = array_map('trim', $taskMatches[1]);
    $imgs = array_map('trim', $taskMatches[2]);
    $equations = array_map('trim', $solutionMatches[1]);

    // 4. Return the results
    return [
        'tasks' => $tasks,
        'images' => $imgs,
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

function isAssigned($type,$id,$db){
    $query = 'SELECT sa.id, sa.student_id, sa.assignment_id,sa.submited,sa.result ,sa.student_score,a.number, a.type, a.points
    FROM student_assignment sa
    JOIN assignments a ON sa.assignment_id = a.id 
    where a.type="'.$type.'" and sa.assignment_id='.$id.';';
    $stmt = $db->query($query); 
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!sizeof($rows)==0){
        return false;
    }else{
        return true;
    }
}

if(isset($_GET['type'])){
    if(!$_GET['type']==null){
        $query = 'SELECT * FROM assignments WHERE type = "'.$_GET['type'].'" ORDER BY RAND() LIMIT 1;';
        $stmt = $db->query($query); 
        $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);


        try{
            $query = 'INSERT INTO student_assignment (student_id,assignment_id,submited,result,correct,student_score) VALUES (1,'.$assignments[0]['id'].',0,0,'.$assignments[0]['result'].',0)';
            $stmt = $db->query($query); 
            $stmt->execute(); 
        }catch(PDOException $e){
            echo $e;
        }
    }
}

var_dump(parseLatexFile("../../exams/blokovka01pr.tex"))
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-white">
    <div class="container-fluid d-flex justify-content-between">
        <a class="navbar-brand" href="/public/student/student.php"><img src="https://i.imgur.com/bw4kZxa.png" alt="Logo" title="Logo" style="width: 200px; height: 80px"/></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
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
        <div class="col-lg-3 col-md-4 col-sm-12">
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="list-group">
                    <a href="student.php" class="list-group-item list-group-item-action ">Prehľad príkladov</a>
                    <a href="generate.php" class="list-group-item list-group-item-action active">Vygeneruj príklad</a>
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
                <button class="btn btn-success"  >Vygeneruj príklad</button>
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