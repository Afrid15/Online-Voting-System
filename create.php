<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';

if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Check if POST variable "title" exists, if not default the value to blank, basically the same for all variables
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $starting_date = $_POST['starting_date'];
$ending_date = $_POST['ending_date'];

// get the current date
$inserted_on = date("Y-m-d");

// create a date object from the current date
$date1 = date_create($inserted_on);

// create a date object from the starting date
$date2 = date_create($starting_date);

// get the difference between the two dates
$diff = date_diff($date1, $date2);
if ((int)$diff->format("%R%a") > 0) {
    $status = "Active";
} else {
    $status = "InActive";
}

// prepare the insert query with placeholders


// execute the prepared statement
// prepare the insert query with placeholders






    // Insert new record into the "polls" table
    $stmt = $pdo->prepare('INSERT INTO polls (title, description,starting_date,ending_date,status,inserted_on) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([ $title, $description, $starting_date, $ending_date, $status, $inserted_on  ]);







    // Below will get the last insert ID, this will be the poll id
    $poll_id = $pdo->lastInsertId();
    // Get the answers and convert the multiline string to an array, so we can add each answer to the "poll_answers" table
    $answers = isset($_POST['answers']) ? explode(PHP_EOL, $_POST['answers']) : '';
    foreach($answers as $answer) {
        // If the answer is empty there is no need to insert
        if (empty($answer)) continue;
        // Add answer to the "poll_answers" table
        $stmt = $pdo->prepare('INSERT INTO poll_answers (poll_id, title,status) VALUES (?, ?, ?)');
        $stmt->execute([ $poll_id, $answer,$status ]);
    }
    // Output message
    $msg = 'Created Successfully!';
}
?>

<?=template_header('Create Poll')?>

<div class="content update">
	<h2>Create Poll</h2>
    <form action="create.php" method="post">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" placeholder="Title" required>
        <label for="description">Description</label>
        <input type="text" name="description" id="description" placeholder="Description">
        <label for="answers">Answers (per line)</label>
        <textarea name="answers" id="answers" placeholder="Description" required></textarea>
        <label for="starting_date">Starting Date</label>
                <input type="text" onfocus="this.type='Date'" name="starting_date" placeholder="Starting Date" class="form-control" required />
            
            <label for="ending_date">Ending Date</label>
                <input type="text" onfocus="this.type='Date'" name="ending_date" placeholder="Ending Date" class="form-control" required />
        
        <input type="submit" value="Create">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
