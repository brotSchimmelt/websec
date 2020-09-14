<?php

// checks if the last row is completely filled with products
if (!($row['prod_title']) && (!$done)) {
    $emptyPlaceholder = '<div class="mr-md-3 pt-3 px-3 pt-md-5 px-md-5"></div>';
    echo str_repeat($emptyPlaceholder, $i); // adds empty divs to fill the row
    $done = true;
}
?>


<?php
// checks if there are still products to display
if (!$done) :
?>
    <div class="mr-md-3 pt-3 px-3 pt-md-5 px-md-5 overflow-hidden">
        <div class="card mb-4 shadow">
            <div class="image-wrap">
                <div class="image-overlay">
                    <div class="overlay-btn">
                        <a href="product.php?id= <?= $row['prod_id'] ?>" class="btn btn-outline-light">Detail Page</a>
                    </div>
                </div>
                <img class="card-img-top" src="<?= $row['img_path'] ?>" alt="Card image cap">
            </div>
            <div class="card-body">
                <h5 class="card-title"><?= $row['prod_title'] ?>
                    <?php if (!$solvedStoredXSS) : ?>
                        <a href="product.php?id= <?= $row['prod_id'] ?>" class="badge badge-pill badge-warning shadow-sm mr-3">Stored XSS</a>
                    <?php else : ?>
                        <a href=<?= SCORE ?> class="badge badge-pill badge-success shadow-sm mr-3">Stored XSS</a>
                    <?php endif; ?></h5>
                <hr>
                <p class="card-text"><?= $row['prod_description'] ?>
                    <a href="product.php?id= <?= $row['prod_id'] ?>" class="text-muted">More details</a></p>
                <div class="prod-btn">
                    <!-- <a href="product.php?id= <?= $row['prod_id'] ?>" class="btn btn-wwu-primary btn-sm">Detail Page</a> -->

                    <form action="overview.php" method="post">
                        <div class="form-row">

                            <div class="col btn-col">
                                <input type="submit" class="btn btn-wwu-cart btn-sm" name="add-preview" value="Add To Cart">
                            </div>
                            <div class="col btn-col">
                                <input class="form-control number-field" type="number" name="quantity" value="1" min="1" max="10" placeholder="-" required>
                                <input type="hidden" name="product_id" value="<?= $row['prod_id'] ?>">
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
<?php endif ?>