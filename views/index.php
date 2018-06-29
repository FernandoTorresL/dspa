<html>
<head>
    <title>Blog with Platzi</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/bootstrap-3.3.7/dist/css/bootstrap.min.css"/>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Blog Title</h1>
        </div>

        <div class="row">
            <div class="col-md-8">
                <?php
                foreach ($blogPosts as $blogPost) {
                    echo '<div class="blog-post">';
                    echo '<h2>' . $blogPost['title'] . '</h2>';
                    echo '<p>Jan 1,2020 by <a href="">Alex</a> </p>';
                    echo '<div class="blog-post-image">';
                    echo '<img src="images/keyboard.jpg" alt="">';
                    echo '</div>';
                    echo '<div class="blog-post-content">';
                    echo $blogPost['content'];
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
            <div class="col-md-4">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras dapibus quam et sem finibus facilisis at nec libero. Aenean vitae sollicitudin erat, vel dictum elit. Duis vel urna vel lectus tempor vehicula. Nullam tincidunt quam id condimentum malesuada. Morbi id euismod elit. Etiam quis tincidunt nibh. Proin in diam quis ex hendrerit commodo. Nulla eget pulvinar felis. Duis a sem eu neque convallis egestas ac vel justo. In lacus mauris, tincidunt in libero a, ornare auctor sapien. Sed maximus neque ac felis tincidunt ultricies.
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <footer>
                    This is a footer<br>
                    <a href="<?php echo BASE_URL; ?>admin">Admin Panel</a>
                </footer>
            </div>
        </div>

    </div>
</div>
</body>
</html>