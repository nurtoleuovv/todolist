<?php 

    function get_tasks($orderBy, $sort) {
        global $db;
        if (isset($orderBy)){
            if ($orderBy == ":username"){
                $query = 'SELECT A.ID, A.TASKTEXT, A.STATUS, A.CHANGEDBYADMIN, C.USERNAME, C.EMAIL FROM tasks A LEFT JOIN users C ON A.USERID = C.ID ORDER BY  C.USERNAME ' . $sort . ' ';
            }
            if ($orderBy == ":email"){
                $query = 'SELECT A.ID, A.TASKTEXT, A.STATUS, A.CHANGEDBYADMIN, C.USERNAME, C.EMAIL FROM tasks A LEFT JOIN users C ON A.USERID = C.ID ORDER BY  C.EMAIL ' . $sort . ' ';
            }
            if ($orderBy == ":status"){
                $query = 'SELECT A.ID, A.TASKTEXT, A.STATUS, A.CHANGEDBYADMIN, C.USERNAME, C.EMAIL FROM tasks A LEFT JOIN users C ON A.USERID = C.ID ORDER BY  A.STATUS ' . $sort . ' ';
            }
        }else{
            $query = 'SELECT A.ID, A.TASKTEXT, A.STATUS, A.CHANGEDBYADMIN, C.USERNAME, C.EMAIL FROM tasks A LEFT JOIN users C ON A.USERID = C.ID';
        }
        $statement = $db->prepare($query);
        $statement->execute();
        $tasks = $statement->fetchAll();
        $statement->closeCursor();
        return $tasks;
    }

    function update_text($task_id, $newTask){
        global $db;
        echo "task id - $taskId, new task - $newTask";
        $query = 'UPDATE `tasks` SET `TASKTEXT`= :newTask, `CHANGEDBYADMIN`=1 WHERE ID = :task_id';
        $statement = $db->prepare($query);
        $statement->bindValue(":task_id", $task_id);
        $statement->bindValue(":newTask", $newTask);
        $statement->execute();
        $statement->closeCursor();
    }

    function update_status($task_id){
        global $db;
        $query = 'SELECT `STATUS` FROM tasks where ID = :task_id ';
        $statement = $db->prepare($query);
        $statement->bindValue(":task_id", $task_id);
        $statement->execute();
        $task = $statement->fetch();
        $statement->closeCursor();
        $taskStatus = $task['STATUS'];
        
        if ($taskStatus == "not done"){
            $query = 'UPDATE `tasks` SET `STATUS`="done" WHERE ID = :task_id';
            $statement = $db->prepare($query);
            $statement->bindValue(":task_id", $task_id);
            $statement->execute();
            $tasks = $statement->fetchAll();
            $statement->closeCursor();
        } else {
            $query = 'UPDATE `tasks` SET `STATUS`="not done" WHERE ID = :task_id';
            $statement = $db->prepare($query);
            $statement->bindValue(":task_id", $task_id);
            $statement->execute();
            $tasks = $statement->fetchAll();
            $statement->closeCursor();
        }
    }

    function delete_task($task_id){
        global $db;
        $query = 'DELETE FROM tasks WHERE ID = :task_id ';
        $statement = $db->prepare($query);
        $statement->bindValue(":task_id", $task_id);
        $statement->execute();
        $tasks = $statement->fetchAll();
        $statement->closeCursor();
    }
    
    function add_task($username, $tasktext) {
        global $db;

        if ($username) {
            $query = 'SELECT ID FROM users where USERNAME = :username ';
            $statement = $db->prepare($query);
            $statement->bindValue(":username", $username);
            $statement->execute();
            $user = $statement->fetch();
            $statement->closeCursor();
            $userId = $user['ID'];
            
            if ($userId == ""){
                $error = "ERROR: user '" .$username. "' is not found";
                include('view/error.php');
                exit();
            }
            
            $query = 'INSERT INTO tasks (TASKTEXT, USERID)
                VALUES
                    (:taskText, :userId)';
            $statement = $db->prepare($query);
            $statement->bindValue(':taskText', $tasktext);
            $statement->bindValue(':userId', $userId);
            $statement->execute();
            $statement->closeCursor();
        } else {
            $query = 'INSERT INTO tasks (TASKTEXT, USERID)
                VALUES
                    (:taskText, NULL)';
            $statement = $db->prepare($query);
            $statement->bindValue(':taskText', $tasktext);
            $statement->execute();
            $statement->closeCursor();
        }  
    }