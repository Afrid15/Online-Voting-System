<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Get the id of the poll from the URL or the form input
$id = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : '');
// Check if the id is valid and exists in the database
if (is_numeric($id) && $id > 0) {
    // Prepare the SQL statement to select the poll by id
    $stmt = $pdo->prepare('SELECT * FROM polls WHERE id = ?');
    // Execute the statement with the id as a parameter
    $stmt->execute([$id]);
    // Fetch the poll record as an associative array
    $poll = $stmt->fetch(PDO::FETCH_ASSOC);
    // Check if the poll record exists
    if ($poll) {
      // The poll record exists, proceed to update
      // Rest of the code
    } else {
      // The poll record does not exist, display an error message
      exit('Invalid poll ID!');
    }
  } else {
    // The id is not valid, display an error message
    exit('Invalid poll ID!');
  }

  // Check if the form data is submitted
if (!empty($_POST)) {
    // Validate the input data
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $starting_date = isset($_POST['starting_date']) ? $_POST['starting_date'] : '';
    $ending_date = isset($_POST['ending_date']) ? $_POST['ending_date'] : '';
    $answers = isset($_POST['answers']) ? explode(PHP_EOL, $_POST['answers']) : '';
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
    // Check if the input data is not empty
    if ($title && $description && $starting_date && $ending_date && $inserted_on&& $status && $answers) {
      // Update the poll record in the polls table
      $stmt = $pdo->prepare('UPDATE polls SET title = ?, description = ?, starting_date = ?, ending_date = ?,inserted_on = ?,status = ? WHERE id = ?');
      $stmt->execute([$title, $description, $starting_date, $ending_date, $inserted_on, $status, $id]);
      // Delete the existing answers in the poll_answers table
      $stmt = $pdo->prepare('DELETE FROM poll_answers WHERE poll_id = ?');
      $stmt->execute([$id]);
      // Insert the new answers in the poll_answers table
      foreach ($answers as $answer) {
        // If the answer is empty, skip it
        if (empty($answer)) continue;
        // Insert the answer in the poll_answers table
        $stmt = $pdo->prepare('INSERT INTO poll_answers (poll_id, title,status) VALUES (?, ?,?)');
        $stmt->execute([$id, $answer,$status]);
      }
      // Display a success message
      $msg = 'Updated Successfully!';
    } else {
      // Display an error message
      $msg = 'Please fill in all the fields!';
    }
  }
  
  

?>

<?=template_header('Update Poll')?>

<div class="content update">
	<h2>Update Poll</h2>
    <form action="update.php?id=<?=$id?>" method="post">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="<?=$poll['title']?>" required>
        <label for="description">Description</label>
        <input type="text" name="description" id="description" value="<?=$poll['description']?>">
        <label for="answers">Answers (per line)</label>
        <textarea name="answers" id="answers" required><?php
        // Get the answers of the poll from the poll_answers table
        $stmt = $pdo->prepare('SELECT * FROM poll_answers WHERE poll_id = ?');
        $stmt->execute([$id]);
        $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Loop through the answers and output them in the textarea
        foreach ($answers as $answer) {
          echo $answer['title'] . "\n";
        }
        ?></textarea>
        <label for="starting_date">Starting Date</label>
        <input type="date" name="starting_date" id="starting_date" value="<?=$poll['starting_date']?>" required>
        <label for="ending_date">Ending Date</label>
        <input type="date" name="ending_date" id="ending_date" value="<?=$poll['ending_date']?>" required>
        <input type="submit" value="Update">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>


<?=template_footer()?>
