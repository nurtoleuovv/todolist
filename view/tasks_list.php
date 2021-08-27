<?php include('view/header.php') ?>
<?php include('view/auth.php'); ?>

<section id="list" class="list">
    <header class="list__row list__header">
        <h1>
            TASKS
        </h1>
    </header>

    <div class="filters">
        <form action="." method="post">
            <input type="hidden" name="action" value="filter">
            <select name="orderBy" required>
                <option value="" selected>filter by</option>
                <option value=":username">order by username</option>
                <option value=":email">order by email</option>
                <option value=":status">order by status</option>
            </select>
            <select name="sort">
                <option value="ASC" selected>ASC</option>
                <option value="DESC">DESC</option>
            </select>
            <button class="filter-button">use filter</button>
        </form>    
    </div>
    
    <?php if($tasks) { ?>
    <?php foreach ($tasks as $task) : ?>
    <div class="list__row">
        <div class="list__item">
            <?php if ($task['USERNAME']) { ?>
                <p class="bold"><?= "{$task['USERNAME']}" ?> (<?= "{$task['EMAIL']}" ?>) </p>
            <?php } else { ?>
                <p class="bold">anonymous</p>
            <?php } ?>
            <p>
                <?= "{$task['STATUS']}" ?>
                <?php if($task['CHANGEDBYADMIN']) { ?>
                    (changed by admin) 
                <?php } ?> 
            </p>
            <p>To do: <?= $task['TASKTEXT']; ?></p>
            
            <?php if (isset($isAdmin) && $isAdmin) { ?>   
            <br>
            <form action="." method="post">
                <input type="text" name="newTask" placeholder="write here changed task text" required>
                <input type="hidden" name="action" value="update_text">
                <input type="hidden"  name="taskId" value="<?= $task['ID']; ?>">
                <button class="updateText-button">update task</button>
            </form>            
            <?php } ?>
        </div>
        
        <?php if (isset($isAdmin) && $isAdmin) { ?>        

        <div class="list__removeItem">
            <form action="." method="post">
                <input type="hidden" name="action" value="delete_task">
                <input type="hidden" name="taskId" value="<?= $task['ID']; ?>">
                <button class="remove-button">❌</button>
            </form>
        </div>

        <div class="list__updateItem">
            <form action="." method="post">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="taskId" value="<?= $task['ID']; ?>">
                <button class="update-button">&#9989;</button>
            </form>
        </div>
            
        <?php } ?>
        
    </div>

    <?php endforeach; ?>
    <?php } else { ?>
    <br>
    <?php if ($taskId) { ?>
    <p>No tasks exist yet.</p>
    <?php } else { ?>
    <p>No task exist yet.</p>
    <?php } ?>
    <br>
    <?php } ?>
    
</section>

<section id="add" class="add">
    <h2>Add task</h2>
    <form action="." method="post" id="add__form" class="add__form">
        <input type="hidden" name="action" value="add_task">
        <div class="add__inputs">
            <label>task text:</label>
            <input type="text" name="inputUsername" placeholder="type username">
            <input type="text" name="taskText" maxlength="120" placeholder="type task text" required>
        </div>
        <div class="add__addItem">
            <button class="add-button bold">Add</button>
        </div>
    </form>
</section>
<br>
<?php include('view/footer.php') ?>