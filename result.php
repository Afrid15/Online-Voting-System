<?php
include 'functions.php';
// Connect to MySQL
$pdo = pdo_connect_mysql();
// MySQL query that selects all the poll records with status=active
$stmt = $pdo->prepare('SELECT * FROM polls WHERE status=?');
$stmt->execute(['active']);
// Fetch all the records
$polls = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Loop through each poll recordc
foreach ($polls as $poll) {
    // MySQL Query that will get all the answers from the "poll_answers" table ordered by the number of votes (descending) and status=active
    $stmt = $pdo->prepare('SELECT * FROM poll_answers WHERE status=? AND poll_id=? ORDER BY votes DESC');
    $stmt->execute(['active', $poll['id']]);
    // Fetch all poll answers
    $poll_answers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Total number of votes, will be used to calculate the percentage
    $total_votes = 0;
    foreach($poll_answers as $poll_answer) {
        // Every poll answers votes will be added to total votes
        $total_votes += $poll_answer['votes'];
    }
    // Display the poll and the answers here
}

?>

<?=template_header('Poll Results')?>

<div class="content poll-result">
	<h2><?=$poll['title']?></h2>
	<p><?=$poll['description']?></p>
    <div class="wrapper">
        <?php foreach ($poll_answers as $poll_answer): ?>
        <div class="poll-question">
            <p><?=$poll_answer['title']?> <span>(<?=$poll_answer['votes']?> Votes)</span></p>
            <div class="result-bar" style= "width:<?=@round(($poll_answer['votes']/$total_votes)*100)?>%">
                <?=@round(($poll_answer['votes']/$total_votes)*100)?>%
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?=template_footer()?>