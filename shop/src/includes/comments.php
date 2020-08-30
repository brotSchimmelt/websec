<?php

$sql = "SELECT `author`, `text`, `rating`, `timestamp`, `post_time` "
    . "FROM xss_comments";
$stmt = get_shop_db()->prepare($sql);
$stmt->execute();

while ($row = $stmt->fetch()) :
?>
    <div class="container">
        <div class="row">
            <div class="col-8">
                <div class="card card-white post">
                    <div class="post-heading">
                        <div class="float-left image mr-3">
                            <img class="img-fluid mb-3 shadow" src="https://placeimg.com/50/40/animals" alt="Avatar">
                        </div>
                        <div class="float-left meta">
                            <div class="title h5">
                                <strong><?= $row['author'] ?></strong>
                                made a comment.
                            </div>
                            <h6 class="text-muted time"><?= $row['post_time'] ?></h6>
                        </div>
                    </div>
                    <div class="post-description">
                        <p><?= $row['text'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
<?php endwhile; ?>