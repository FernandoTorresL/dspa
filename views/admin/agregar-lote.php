<html>
<head>
    <title>Agregar lote</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/bootstrap-3.3.7/dist/css/bootstrap.min.css"/>
    </head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Lotes</h1>
        </div>

        <div class="row">
            <h2>Nuevo Lote</h2>
            <p>
                <a class="btn btn-default" href="<?php echo BASE_URL; ?>admin/lotes">Back</a>
            </p>
            <?php
            if (isset($result) && $result ) {
                echo '<div class="alert alert-success">Lote creado!</div>';
            }
            ?>

            <div class="col-md-8">

                <form method="post">
                    <div class="form-group">
                        <label for="inputLote">Nuevo Lote:</label>
                        <input class="form-control" type="text" required name="lote" id="inputLote" maxlength="4">
                    </div>
                    <textarea class="form-control" name="comentario" id="inputComentario" rows="5"></textarea>
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