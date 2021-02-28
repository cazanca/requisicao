<?php $v->layout('admin') ?>

<div class="flex mb-2">
    <h4 class="title">Minhas requisições</h4>
</div>

<div class="grid even">
    <div class="panel">
        <div class="panel-head">
            <h6 class="title"><?= $title ?? 'Minhas requisições' ?></h6>
        </div>
        <div class="panel-body">
            <table class="zebra">
                <thead>
                    <tr>
                        <th>Produtos</th>
                        <th>Quantidade</th>
                        <th>Estado</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($produtos as $produto): ?>
                        <tr>
                            <td><?= $produto->produto()->name ?></td>
                            <td><?= $produto->quantidade ?></td>
                            <td><?= $produto->status ?></td>
                            <td><?= date_fmt($produto->created_at) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $v->start('scripts') ?>
<script>

document.querySelector('.my-link').classList.add('active');
</script>
<?php $v->end('scripts') ?>