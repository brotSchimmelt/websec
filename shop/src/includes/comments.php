<?php

$sql = "SELECT `author`, `text`, `rating`, `timestamp` FROM xss_comments";
$stmt = get_shop_db()->prepare($sql);
$stmt->execute();

while ($row = $stmt->fetch()) :
?>
    <div class="prod-center page-center container">
        <div class="row">
            <div class="col-8">
                <div class="card card-white post">
                    <div class="post-heading">
                        <div class="float-left image mr-3">
                            <img class="img-fluid mb-3 shadow" src="https://placeimg.com/50/40/animals" alt="Avatar">
                        </div>
                        <div class="float-left meta">
                            <div class="title h5">
                                <a href="#"><b><?= $row['author'] ?></b></a>
                                made a comment.
                            </div>
                            <h6 class="text-muted time">1 day ago</h6>
                        </div>
                    </div>
                    <div class="post-description">
                        <p><?= $row['text'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endwhile; ?>