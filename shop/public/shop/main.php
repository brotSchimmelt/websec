<?php
session_start();

// include config and basic functions
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require(FUNC_BASE);

if (!is_user_logged_in()) {
    header("location: " . LOGIN_PAGE . "?login=false");
    exit();
}
// include Header
require(HEADER_SHOP);

// get user name
$username = htmlentities($_SESSION['user_name']);
?>

<!doctype html>
<html lang="en">

<body>

    <!-- <script>
        $(document).ready(function() {
            $("#greeting-modal").modal('show');
        });
    </script> -->

    <!-- HEADER IMAGE SECTION -->
    <header id="home-section">
        <div class="dark-overlay">
            <div id="home-inner">
                <div class="container" id="header-container">
                    <div class="row">
                        <div class="col-lg-8">
                            <h1 class="display-4">Welcome to our new shop<?= ", " . $username ?>!</h1>
                            <div class="d-flex flex-row">
                                <div class="p-4 align-self-start">
                                    LEFT COLUMN
                                </div>
                                <div class="p-4 align-self-end">
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum unde consequatur fuga fugit tempore laborum.
                                </div>
                            </div>
                            <div class="d-flex flex-row">
                                <div class="p-4 align-self-start">
                                    LEFT COLUMN
                                </div>
                                <div class="p-4 align-self-end">
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum unde consequatur fuga fugit tempore laborum.
                                </div>
                            </div>
                        </div>
                        <!-- CARD HEADER -->
                        <!-- <div class="col-lg-4">
                            <div class="card text-center">
                                <div class="card-body" id="header-card">
                                    <h3>Headline in card</h3>
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi fugiat nam nisi veniam quos. Libero aliquid aut ullam ab unde ratione suscipit ipsam et hic itaque, magni esse eligendi deleniti odit consequatur nemo. Culpa sed recusandae hic quia optio quam cumque dicta obcaecati laboriosam assumenda similique, voluptatem iusto quo molestias.</p>
                                </div>
                            </div>
                        </div> -->
                        <!-- END CARD -->
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Container 1 -->
    <section id="container-1">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <div class="p-5">
                        <h1 class="display-4">
                            Products
                        </h1>
                        <p class="lead">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Dicta maxime dignissimos voluptatem iste totam assumenda cumque eius architecto temporibus molestias.</p>
                        <a class="btn btn-outline-light" href="product.php">Look at our Products</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Container 2 -->
    <section id="container-2" class="bg-light text-muted py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img class="img-fluid mb-3 rounded-circle" src="../assets/img/iphone.jpg" alt="picture of clothes">
                </div>

                <div class="col-md-6">
                    <h3>Headline about the products</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatum impedit tempora cumque non corrupti fuga quibusdam numquam laudantium similique natus consequuntur consectetur quo qui officia in, modi dolores expedita saepe?</p>
                    <div class="d-flex flex-row">
                        <div class="p-4 align-self-start">
                            LEFT COLUMN
                        </div>
                        <div class="p-4 align-self-end">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum unde consequatur fuga fugit tempore laborum.
                        </div>
                    </div>
                    <div class="d-flex flex-row">
                        <div class="p-4 align-self-start">
                            LEFT COLUMN
                        </div>
                        <div class="p-4 align-self-end">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum unde consequatur fuga fugit tempore laborum.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Container 3 -->
    <section id="container-3">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <div class="p-5">
                        <h1 class="display-4">
                            Find Your Friends
                        </h1>
                        <p class="lead">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Dicta maxime dignissimos voluptatem iste totam assumenda cumque eius architecto temporibus molestias.</p>
                        <a class="btn btn-outline-light" href="#">Find Your Friends</a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Container 4 -->
    <section id="container-4" class="bg-light text-muted py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h3>Headline about your friends</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatum impedit tempora cumque non corrupti fuga quibusdam numquam laudantium similique natus consequuntur consectetur quo qui officia in, modi dolores expedita saepe?</p>
                    <div class="d-flex flex-row">
                        <div class="p-4 align-self-start">
                            LEFT COLUMN
                        </div>
                        <div class="p-4 align-self-end">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum unde consequatur fuga fugit tempore laborum.
                        </div>
                    </div>
                    <div class="d-flex flex-row">
                        <div class="p-4 align-self-start">
                            LEFT COLUMN
                        </div>
                        <div class="p-4 align-self-end">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum unde consequatur fuga fugit tempore laborum.
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <img class="img-fluid mb-3 rounded-circle" src="../assets/img/friends.jpg" alt="friends">
                </div>
            </div>
        </div>
    </section>



    <!-- Container 5 -->
    <section id="container-5">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <div class="p-5">
                        <h1 class="display-4">
                            Contact
                        </h1>
                        <p class="lead">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Dicta maxime dignissimos voluptatem iste totam assumenda cumque eius architecto temporibus molestias.</p>
                        <a class="btn btn-outline-light" href="contact.php">Contact</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Container 6 -->
    <section id="container-6" class="bg-light text-muted py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img class="img-fluid mb-3 rounded-circle" src="../assets/img/contact.jpg" alt="contact">
                </div>

                <div class="col-md-6">
                    <h3>Headline about the support</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatum impedit tempora cumque non corrupti fuga quibusdam numquam laudantium similique natus consequuntur consectetur quo qui officia in, modi dolores expedita saepe?</p>
                    <div class="d-flex flex-row">
                        <div class="p-4 align-self-start">
                            LEFT COLUMN
                        </div>
                        <div class="p-4 align-self-end">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum unde consequatur fuga fugit tempore laborum.
                        </div>
                    </div>
                    <div class="d-flex flex-row">
                        <div class="p-4 align-self-start">
                            LEFT COLUMN
                        </div>
                        <div class="p-4 align-self-end">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum unde consequatur fuga fugit tempore laborum.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    require(FOOTER_SHOP);
    require(JS_SHOP);
    ?>
</body>



</html>