<html>
<head>
    <title>Aplicaciones DSPA</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/bootstrap-3.3.7/dist/css/bootstrap.min.css"/>
    </head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>lista_lotes.php en carpeta admin - Lista de lotes</h1>
        </div>

        <div class="row">
            <div class="col-md-8">
                <h2>Posts</h2>
                <p>
                    <a class="btn btn-primary" href="<?php echo BASE_URL; ?>admin/lotes/crear">Nuevo Lote</a>
                </p>
                <table class="table">
                    <tr>
                        <th>Lote</th>
                        <th>Comentario</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    <?php
                    foreach ($listaLotes as $lote) {
                        echo '<tr>';
                        echo '<td>' . $lote['lote_anio'] . '</td>';
                        echo '<td>' . $lote['comentario'] . '</td>';
                        echo '<td>Edit</td>';
                        echo '<td>Delete</td>';
                        echo '</tr>';

                    }
                    ?>
                </table>
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