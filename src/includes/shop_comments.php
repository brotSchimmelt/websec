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

// get number of comments
echo '<h4 class="display-5 mb-4">Comments (' . count($comments) . ')</h4>';

$avatarCounter = 0;

// while ($row = $stmt->fetch()) :
foreach ($comments as $comment) :
?>

    <?php
    $avatarCounter += 1;
    ?>


    <div class="be-comment">
        <div class="be-img-comment">
            <img src="/assets/img/avatar_<?= $avatarCounter ?>.jpg" alt="Avatar" class="be-ava-comment">
        </div>
        <div class="be-comment-content">

            <span class="be-comment-name">
                <?= $comment['author'] ?>
            </span>
            <span class="be-comment-time">
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-clock" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm8-7A8 8 0 1 1 0 8a8 8 0 0 1 16 0z" />
                    <path fill-rule="evenodd" d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z" />
                </svg>
                <?= $comment['post_time'] ?>
            </span>

            <p class="be-comment-text">
                <?= $comment['text'] ?>
            </p>
        </div>
    </div>
    <br>







    <?php
    // upper limit for avatars is 3
    $avatarCounter = ($avatarCounter >= 3) ? 0 : $avatarCounter;
    ?>

<?php endforeach; ?>




<!-- <div class="be-comment">
    <div class="be-img-comment">
        <a href="blog-detail-2.html">
            <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" class="be-ava-comment">
        </a>
    </div>
    <div class="be-comment-content">

        <span class="be-comment-name">
            <a href="blog-detail-2.html">Ravi Sah</a>
        </span>
        <span class="be-comment-time">
            <i class="fa fa-clock-o"></i>
            May 27, 2015 at 3:14am
        </span>

        <p class="be-comment-text">
            Pellentesque gravida tristique ultrices.
            Sed blandit varius mauris, vel volutpat urna hendrerit id.
            Curabitur rutrum dolor gravida turpis tristique efficitur.
        </p>
    </div>
</div>
<div class="be-comment">
    <div class="be-img-comment">
        <a href="blog-detail-2.html">
            <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="" class="be-ava-comment">
        </a>
    </div>
    <div class="be-comment-content">
        <span class="be-comment-name">
            <a href="blog-detail-2.html">Phoenix, the Creative Studio</a>
        </span>
        <span class="be-comment-time">
            <i class="fa fa-clock-o"></i>
            May 27, 2015 at 3:14am
        </span>
        <p class="be-comment-text">
            Nunc ornare sed dolor sed mattis. In scelerisque dui a arcu mattis, at maximus eros commodo. Cras magna nunc, cursus lobortis luctus at, sollicitudin vel neque. Duis eleifend lorem non ant. Proin ut ornare lectus, vel eleifend est. Fusce hendrerit dui in turpis tristique blandit.
        </p>
    </div>
</div>
<div class="be-comment">
    <div class="be-img-comment">
        <a href="blog-detail-2.html">
            <img src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="" class="be-ava-comment">
        </a>
    </div>
    <div class="be-comment-content">
        <span class="be-comment-name">
            <a href="blog-detail-2.html">Cüneyt ŞEN</a>
        </span>
        <span class="be-comment-time">
            <i class="fa fa-clock-o"></i>
            May 27, 2015 at 3:14am
        </span>
        <p class="be-comment-text">
            Cras magna nunc, cursus lobortis luctus at, sollicitudin vel neque. Duis eleifend lorem non ant
        </p>
    </div>










    <div class="container">
        <div class="row justify-content-center">
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
    <br> -->