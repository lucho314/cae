<article class="col-xs-12 col-md-10" >
    <div class="row">
        <div class="col-xs-12">
            <h3>Lista de Deportes</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead Style="background-color:#4682B4; color:white;">
                    <th>Nombre</th>
                    <th>Edad minima</th>
                    <th>Edad maxima</th>
                    <th>Profesor titular</th>
                    <th>Profesor suplente</th>
                    </thead>
                    <tbody>
                        <?php foreach ($datos as $val): ?>
                            <tr>
                                <td><?= $val['nombre_categoria'] ?></td>
                                <td><?= $val['edad_minima'] ?></td>
                                <td><?= $val['edad_maxima'] ?></td>
                                <td><?= $val['prof_titular'] ?></td>
                                <td><?= $val['prof_suplente'] ?></td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</article>