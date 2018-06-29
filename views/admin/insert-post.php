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
            <h2>New Post</h2>
            <p>
                <a class="btn btn-default" href="<?php echo BASE_URL; ?>admin/posts">Back</a>
            </p>
            <?php
            if (isset($result) && $result ) {
                echo '<div class="alert alert-success">Post Saved!</div>';
            }
            ?>

            <div class="col-md-8">

                <form method="post">
                    <div class="form-group">
                        <label for="inputTitle">Title</label>
                        <input class="form-control" type="text" name="title" id="inputTitle">

                    </div>
                    <textarea class="form-control" name="content" id="inputContent" rows="5"></textarea>
                    <br>
                    <input class="btn btn-primary" type="submit" value="Save">
                </form>
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