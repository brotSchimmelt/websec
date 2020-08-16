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
        <!-- <div class="card mb-4 text-white bg-dark shadow-lg"> -->
        <div class="card mb-4 shadow">
            <img class="card-img-top" src="https://placeimg.com/300/180/animals" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title"><?= $row['prod_title'] ?></h5>
                <hr>
                <p class="card-text"><?= $row['prod_description'] ?></p>
                <div class="prod-btn">
                    <!-- <a href="#" class="btn btn-outline-light btn-sm">Detail Page</a> -->
                    <a href="product.php?id= <?= $row['prod_id']  ?>" class="btn btn-wwu-primary btn-sm">Detail Page</a>

                    <form action="overview.php" method="post">
                        <div class="form-row">

                            <div class="col btn-col">
                                <input type="submit" class="btn btn-wwu-cart btn-sm" name="add-preview" value="Add To Cart">
                            </div>
                            <div class="col btn-col">
                                <input class="form-control number-field" type="number" name="quantity" value="1" min="1" max="3" placeholder="-" required>
                                <input type="hidden" name="product_id" value="<?= $row['prod_id'] ?>">
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
<?php endif ?>