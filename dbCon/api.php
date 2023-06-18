<?php
    $host = "127.0.0.1"; //Host name 
    $user = "root"; // User
    $password = ""; // Password 
    $dbname = "roadmap"; // Database name

    $conn = new mysqli("127.0.0.1","root","","roadmap");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $action="show";
    
    if(isset($_GET['action'])){
        $action=$_GET['action'];
    }
    
    // CRUD ********************************
    if($action=='tasks'){
    $sql = "SELECT * FROM tasks";
    $query = $conn->query($sql);
    $tasks = array();
 
    while($row = $query->fetch_array()){
        array_push($tasks, $row);
    }
 
    $out['tasks'] = $tasks;

    echo json_encode($out);
    }

    if($action=='eval'){
    $sql = "SELECT * FROM bedømmelse";
    $query = $conn->query($sql);
    $eval = array();

    while($row = $query->fetch_array()){
        array_push($eval, $row);
    }

    $out['eval'] = $eval;

    echo json_encode($out);
    }

    
    if($action=='jointasks'){
        /* joiner tabellerne tasks, kommentar, og bedømmelse når de bliver hentet ned fra database */
        $sql = "SELECT tasks.navn, tasks.taskStatus, tasks.beskrivelse , tasks.dato
         FROM tasks";
        $query = $conn->query($sql);
        $tasks = array();
 
        while($row = $query->fetch_array()){
            array_push($tasks, $row);
        }
    
        $out['tasks'] = $tasks;

        echo json_encode($out);
    }
    
    if($action=='addtasks'){
        $navn = $_POST['navn'];
        $taskStatus = filter_input(INPUT_POST, "taskStatus", FILTER_VALIDATE_BOOLEAN);
        $beskrivelse = $_POST['beskrivelse'];
        $dato = $_POST['dato'];

        /* $navn = "hejmeddig";
        $taskStatus = "0";
        $beskrivelse = "hejmdd"; */

        $sql = "INSERT INTO tasks(navn, taskStatus, beskrivelse, dato) VALUES (?, ?, ?, ?)";
       
       $stmt = mysqli_stmt_init($conn);

       if ( ! mysqli_stmt_prepare($stmt, $sql)) {
            die(mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "siss", $navn, $taskStatus, $beskrivelse, $dato);

        mysqli_stmt_execute($stmt);

        echo "Ny feature gemt. Du vil vende tilbage til siden om kort tid.";

        header( "refresh:2;http://localhost/ForbedringerEksamen/index.html" );
        /* var_dump($navn, $taskStatus ,$beskrivelse, $dato); */

       /*  $query=$conn->query($sql); */
    }
    
    if($action=='updatetasks'){
        $navn = $_PUT['navn'];
        $taskStatus = filter_input(INPUT_PUT, "taskStatus", FILTER_VALIDATE_BOOLEAN);
        $beskrivelse = $_PUT['beskrivelse'];
        $dato = $_POST['dato'];

        $sql = "UPDATE tasks SET navn='?', taskStatus='?', beskrivelse='?', dato='?'";
       
       $stmt = mysqli_stmt_init($conn);

       if ( ! mysqli_stmt_prepare($stmt, $sql)) {
            die(mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "siss", $navn, $taskStatus, $beskrivelse, $dato);

        mysqli_stmt_execute($stmt);

        echo "Ny feature gemt. Du vil vende tilbage til siden om kort tid.";

        header( "refresh:2;http://localhost/ForbedringerEksamen/index.html" );
    }
    
    if($action=='deletetasks'){
     
        $sql="DELETE FROM tasks WHERE navn=$navn";
        $query=$conn->query($sql);

    }
    

    //Close connection
    $conn->close();
     
    die();
  
?>
