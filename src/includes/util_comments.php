<?php

$sql = "SELECT `author`, `text`, `rating`, `timestamp`, `post_time` "
    . "FROM xss_comments";
$stmt = get_shop_db()->prepare($sql);
$stmt->execute();

// fake user comments
$fakeComments = array(
    array(
        "author" => "anonymous",
        "text" => "Totally useless!!1! I would never buy this item again!",
        "post_time" => "2 weeks ago"
    ),
    array(
        "author" => "Elliot",
        "text" => "I purchased this product for my girlfriend's birthday. "
            . "Now I am single.",
        "post_time" => "1 hour ago"
    )
);


// get user comment from database if it exists
$sql = "SELECT `author`, `text`, `post_time` FROM xss_comments WHERE `author`=?";

try {
    $stmt = get_shop_db()->prepare($sql);
    $stmt->execute([$_SESSION['userName']]);
} catch (PDOException $e) {
    display_exception_msg($e);
    exit();
}

// merge all comments
$comments = array_merge($fakeComments, $stmt->fetchAll());

$avatarCounter = 0;

// while ($row = $stmt->fetch()) :
foreach ($comments as $comment) :
?>

    <?php
    $avatarCounter += 1;
    ?>
    <div class="container">
        <div class="row">
            <div class="col-8">
                <div class="card card-white post">
                    <div class="post-heading">
                        <div class="float-left image mr-3">
                            <img class="img-fluid mb-3 shadow" src="/assets/img/avatar_<?= $avatarCounter ?>.jpg" alt="Avatar">
                        </div>
                        <div class="float-left meta">
                            <div class="title h5">
                                <strong><?= $comment['author'] ?></strong>
                                says:
                            </div>
                            <h6 class="text-muted time"><?= $comment['post_time'] ?></h6>
                        </div>
                    </div>
                    <div class="post-description">
                        <p><?= $comment['text'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
<?php endforeach; ?>