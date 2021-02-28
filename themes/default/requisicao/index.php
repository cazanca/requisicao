<?php $v->layout('admin') ?>

<div class="flex mb-2">
    <h4 class="title"><?= $title ?? 'Requisição' ?></h4>
    <ul class="breadcrumb">
        <li><a href="<?= url('/home') ?>">Home</a></li>
        <li>Requisições</li>
    </ul>
</div>

<div class="grid even">
    <div class="panel">
        <div class="panel-head">
        <h6 class="title">Lista de <?= $title ?></h6>
        </div>
        <div class="panel-body">
            <table class="zebra">
                <thead>
                    <tr>
                        <th>Departamento</th>
                        <th>Requisitante</th>
                        <th>Estado</th>
                        <th>Acção</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($requisicao)): ?>
                    <?php 
                    foreach($requisicao as $pedido): 
                        $pedidoId = base64_encode($pedido->id);
                    ?>
                        <tr>
                            <td><?= ($pedido->user()) ? $pedido->user()->departamento()->name : '' ?></td>
                            <td><?= ($pedido->user()) ? $pedido->user()->first_name . ' '.$pedido->user()->first_name : '' ?></td>
                            <td><?= ($pedido->status == 'pendente') ? "<span class='btn-label btn-warning'>$pedido->status</span>" : "<span class='btn-label btn-primary'>$pedido->status</span>"?></td>
                            <td class="">
                                <a href="<?= url("/requisicao/{$pedidoId}") ?>" class="view"><ion-icon name="eye-outline"></ion-icon></a>
                                <a type="button" class="remove" style="cursor:pointer;" onClick="removePedido(<?= $pedido->id?>)"><ion-icon name="trash-outline"></ion-icon></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">Nenhum dado encontrado</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
            <span class="grid right">
            <?= $pager ?>
            </span>
        </div>
    </div>
</div>

<?php $v->start('scripts') ?>
<script>
document.querySelector('.req-link').classList.add('active');
function removePedido(pedidoId = null){
    if (pedidoId) {
        var confirmar = confirm("Tens a certeza que queres remover?")
        if (confirmar) {
            let url = "<?= url() ?>"
            let redirect = `${url}/requisicao/${pedidoId}/remove`
            window.location.href = redirect
        }
    }
}

</script>
<?php $v->end('scripts') ?>