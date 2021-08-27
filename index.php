<?php
    session_start();

    if (isset($_GET['auth']) && $_GET['auth']) {
        $_SESSION['username'] = $_GET['username'];
    }

    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        if ($username == "admin"){
            $isAdmin = true;
        }
        ?>
         <p class="welcome">Hi, <?php echo $_SESSION['username'] ?>!</p><?php
    } else {
        $username = "";
        $isAdmin = false;
        echo "";
        ?>
        <p class="welcome">Hi, anonymous! please register :)</p>
        <?php
    }

    $taskId = filter_input(INPUT_POST, 'taskId', FILTER_VALIDATE_INT);
    $taskText = filter_input(INPUT_POST, 'taskText', FILTER_SANITIZE_STRING);
    $inputUsername = filter_input(INPUT_POST, 'inputUsername', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    
    $usernameLogin = filter_input(INPUT_POST, 'usernameLogin', FILTER_SANITIZE_STRING);
    $passLogin = filter_input(INPUT_POST, 'passLogin', FILTER_SANITIZE_STRING);

    $usernameReg = filter_input(INPUT_POST, 'usernameReg', FILTER_SANITIZE_STRING);
    $emailReg = filter_input(INPUT_POST, 'emailReg', FILTER_SANITIZE_STRING);
    $passReg = filter_input(INPUT_POST, 'passReg', FILTER_SANITIZE_STRING);

    $newTask = filter_input(INPUT_POST, 'newTask', FILTER_SANITIZE_STRING);

    $orderBy = filter_input(INPUT_POST, 'orderBy', FILTER_SANITIZE_STRING);
    $sort = filter_input(INPUT_POST, 'sort', FILTER_SANITIZE_STRING);

	require('model/database.php');
    require('model/tasks_db.php');
    require('model/users_db.php');    

    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
    if (!$action) {
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
        if (!$action) {
            $action = 'list_tasks';
        }
    }

	switch ($action) {
        case "delete_task":
            if ($taskId){
                try{
                    delete_task($taskId);
                } catch (PDOException $e){
                    $error = "ERROR";
                    include('view/error.php');
                    exit();
                }
                header("Location: .?action=list_tasks");
            }
            break;

        case "add_task":
            $createTask = add_task($inputUsername, $taskText);
            header("Location: .?action=list_tasks");
            break;
        
        case "update_status":
            $updateStatus = update_status($taskId);
            header("Location: .?action=list_tasks");
            break;

        case "update_text":
            $updateText = update_text($taskId, $newTask);
            header("Location: .?action=list_tasks");
            break;
        
        case "list_tasks":
            $tasks = get_tasks($orderBy, $sort);
            include('view/tasks_list.php');
            break;

        case "filter":
            $tasks = get_tasks($orderBy, $sort);
            include('view/tasks_list.php');
            break;

        case "showLogin":
            include('view/login.php');
            break;
            
        case "login":
            $auth = check_pass($usernameLogin, $passLogin);
            if ($auth){
                header("Location: .?action=list_tasks&auth=true&username=$usernameLogin");
            } else {
                $error = "wrong username/password";
                    include('view/error.php');
                    exit();
            }
            break;

        case "showRegistration":
            include('view/registration.php');
            break;

        case "registration":
            $createUser = create_user($usernameReg, $passReg, $emailReg);
            header("Location: .?action=list_tasks&auth=true&username=$usernameReg");
            break;

        case "logout":
            session_destroy();
            header("Location: .?action=list_tasks");
            break;

        default:
            $tasks = get_tasks($orderBy, $sort);
			include('view/tasks_list.php');
	}


