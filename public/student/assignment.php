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



if(isset($_GET['type'])){
    if(!$_GET['type']==null){
    }
}
if (isset($_POST['givenFormula'])) {
    
   

    // DONE submited++ in student_assignment
    try{
        $query = " UPDATE webtech2.student_assignment SET submited = 1";
        $stmt = $db->query($query); 

    }catch(PDOException $e){
        echo $e->getMessage();
    }

    // DONE submited++  in student
    try{
        $query = " UPDATE webtech2.students
                    SET submited = 
                    (SELECT count(submited) from webtech2.student_assignment 
                    where student_id = 1 and submited = 1)";
        $stmt = $db->query($query); 

    }catch(PDOException $e){
        echo $e->getMessage();
    }

    // get correct result from student_assignment
    try{
        $query = " SELECT correct FROM webtech2.student_assignment";
        $stmt = $db->query($query); 
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);  

    }catch(PDOException $e){
        echo $e->getMessage();
    }


    // API endpoint URL
    $url = 'https://site104.webte.fei.stuba.sk:9001/compare';

    // Custom data to send
    $data = array(
        'expr1' => $_POST['givenFormula'],
        'expr2' => $results[0]['correct']
    );

    // Convert the data to JSON
    $jsonData = json_encode($data);

    // Initialize cURL
    $ch = curl_init($url);

    // Set the request method to POST
    curl_setopt($ch, CURLOPT_POST, 1);

    // Set the JSON data
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // Set the appropriate headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData)
    ));

    // Set option to receive the response as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        // Handle the error appropriately
    } else {
        echo $response;

        if ($response){

            // DONE update student_score in student_assignment
            try{
                $query = " UPDATE webtech2.student_assignment
                            SET student_score = 
                            (SELECT points from webtech2.assignment 
                            where type =".$_GET['type']." and number = ".$_GET['number'].")";
                $stmt = $db->query($query); 

            }catch(PDOException $e){
                echo $e->getMessage();
            }

            // DONE update total_points in student
            try{
                $query = " UPDATE webtech2.student
                            SET total_points = total_points + 
                            (SELECT student_score from webtech2.student_assignment 
                            where type =".$_GET['type']." and number = ".$_GET['number'].")";
                $stmt = $db->query($query); 

            }catch(PDOException $e){
                echo $e->getMessage();
            }

        }
    }

    // Close cURL
    curl_close($ch);

    

}

?>

<link rel="stylesheet" href="assignment.css">

<nav class="navbar navbar-expand-lg navbar-dark bg-white">
    <div class="container-fluid d-flex justify-content-between">
        <a class="navbar-brand" href="/public/student/student.php"><img src="https://i.imgur.com/bw4kZxa.png" alt="Logo" title="Logo" style="width: 200px; height: 80px"/></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div>
            <a href="assignment.php?lang=sk">SK</a> | <a href="assignment.php?lang=en">EN</a>
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
	<div class="">
		<div class="row ">
				<div class="col-lg-3 col-md-4 col-sm-12">
					<div class="col-lg-3 col-md-4 col-sm-12">
						<div class="list-group">
							<a href="student.php" class="list-group-item list-group-item-action active"><?php echo $lang['view_tasks']; ?></a>
							<a href="generate.php" class="list-group-item list-group-item-action "><?php echo $lang['generate_task']; ?></a>
                            <a href="guideStudent.php" class="list-group-item list-group-item-action "><?php echo $lang['guide']; ?></a>
						</div>
					</div>
				</div>
			<div class="col-lg-9 col-md-8 col-sm-12">
				<?php 
					$filename = '../../exams/'.$_GET['type'];
					$result = parseLatexFile($filename);
					
					$tasks = $result['tasks'];
					$images = $result['images'];
					$equations = $result['equations'];
					
					$index=$_GET['number']-1;
					preg_match('/\$(.*?)\$/', $tasks[$index], $matches);
					$position = strpos($tasks[$index], $matches[0]);

					$result = str_replace($matches[0], "", $tasks[$index]);

					$firstHalf = substr($result, 0, $position);
					$secondHalf = substr($result, $position + strlen($matches[0]));

					$zadanie = str_replace($matches[0],"",$tasks[$index] );
					echo "<h3>" . $lang['submit'] . " " . ($index + 1) ."</h3>";
					echo "<p>" . $firstHalf. "<span id='equation' style='display: in-line;'>\[".$matches[1]. "\]</span> ".$secondHalf."</p>";
					echo "<img src='../../exams/" . $images[$index] . "' alt='Task Image'>";
					
				?>
                <div>
                    <br>
                    <form action="#" method="post" id="myForm">
                        <math-field id="formula" name="formula">x=\frac{-b\pm \sqrt{b^2-4ac}}{2a}</math-field>
                        <input type="hidden" name="givenFormula" id="givenFormula" value="">
                        <button class="btn btn-success" type="submit" name="submit" id="submitFormula"><?php echo $lang['submit']; ?></button>
                    </form>
                </div>
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
<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js"></script>
<script defer src="//unpkg.com/mathlive"></script>
<script src="assignment.js"></script>