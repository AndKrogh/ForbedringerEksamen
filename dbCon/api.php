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
        $sql = "SELECT tasks.navn, tasks.taskStatus, tasks.beskrivelse, kommentar.kommentar, tasks.dato, tasks.bedømmelse_id
         FROM tasks, kommentar WHERE tasks.kommentar_id = kommentar.kommentar_id
         ";
        $query = $conn->query($sql);
        $tasks = array();
 
        while($row = $query->fetch_array()){
            array_push($tasks, $row);
        }
    
        $out['tasks'] = $tasks;

        echo json_encode($out);
    }
    
    if($action=='addtasks'){
        /* $navn = $_POST['navn'];
        $taskStatus = $_POST['taskStatus'];
        $beskrivelse = $_POST['beskrivelse'];
        $dato = $_POST['dato']; */

       
       

        if(mysqli_connect_errno()){
            die("connection error: ". mysqli_connect_errno());
        }
     
        $stmt = $conn->prepare("INSERT INTO tasks (navn, taskStatus, beskrivelse) VALUES (?, ?, ?)");
       
       /*  $stmt = mysqli_stmt_init($conn); */

      /*   if( ! mysqli_stmt_prepare($stmt, $sql)) {
            die(mysqli_error($conn));

        } */
        
        /* $navn = "hejmeddig";
        $taskStatus = 1;
        $beskrivelse = "hejmdd";
 */
        $stmt->bind_param( "sis", $navn, $taskStatus, $beskrivelse);
        /* mysqli_stmt_execute($stmt) */;
        $stmt->execute();
        $stmt->close();

        
        /* var_dump($navn, $taskStatus ,$beskrivelse, $dato); */

       /*  $query=$conn->query($sql); */
    }
    
    if($action=='updatetasks'){
        $navn = $_PUT['navn'];
        $bedømmelse = $_PUT['bedømmelse'];
        $status = $_PUT['taskStatus'];
        $kommentar = $_PUT['kommentar_id'];
     
        $sql="UPDATE tasks SET navn='$navn', bedømmelse='$bedømmelse', taskStatus='$taskStatus', kommentar_id='$kommentar_id'";
        $query=$conn->query($sql);
        
        if($query){
            $out['message']='Task Successfully Updatet';
        }
        else{
            $out['error']=true;
            $out['message']='Error in Updating Occured';
        }
    }
    
    if($action=='deletetasks'){
     
        $sql="DELETE FROM tasks WHERE navn=$navn";
        $query=$conn->query($sql);

    }
    

    //Close connection
    $conn->close();
     
    header("Content-type: application/json");
    die();
  
?>
