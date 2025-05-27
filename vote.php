<?php
include 'functions.php';

// Connect to MySQL
$pdo = pdo_connect_mysql();

// MySQL query that selects all the poll records with status=active
$stmt = $pdo->prepare('SELECT * FROM polls WHERE status=?');
$stmt->execute(['active']);

// Fetch all the records
$polls = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Loop through each poll record
foreach ($polls as $poll) {
    // Check if the poll is active
    if ($poll['status'] == 'Inactive') {
        // Display a message for inactive polls
        header('Location: inactive.php');
    } else {
        // MySQL query that selects all the poll answers with status=active and the same poll_id as the current poll
        $stmt = $pdo->prepare('SELECT * FROM poll_answers WHERE status=? AND poll_id=?');
        $stmt->execute(['active', $poll['id']]);

        // Fetch all the poll answers
        $poll_answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // If the user clicked the "Vote" button...
        if (isset($_POST['poll_answer'])) {
            // Update and increase the vote for the answer the user voted for
            $stmt = $pdo->prepare('UPDATE poll_answers SET votes = votes + 1 WHERE id = ?');
            $stmt->execute([$_POST['poll_answer']]);
            // Redirect user to the result page
            header('Location: regarding.php?id=' . $poll['id']);
            exit;
        }

        // Display the poll and the answers here
        ?>
        <?=template_header('Poll Vote')?>

        <div class="content poll-vote">
            <h2><?=$poll['title']?></h2>
            <p><?=$poll['description']?></p>
            <!-- Change the form action to vote.php and the input type to submit -->
            <form action="vote.php" method="post">
                <?php for ($i = 0; $i < count($poll_answers); $i++): ?>
                    <label>
                        <input type="radio" name="poll_answer" value="<?=$poll_answers[$i]['id']?>"<?=$i == 0 ? ' checked' : ''?>>
                        <?=$poll_answers[$i]['title']?>
                    </label>
                <?php endfor; ?>
                <div>
                    <input type="submit" name="vote" value="Vote">
                    <a href="result.php?id=<?=$poll['id']?>">View Result</a>
                </div>
            </form>
        </div>

        <?=template_footer()?>
    <?php
    }
}
?>
