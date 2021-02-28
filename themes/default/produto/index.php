<?php $v->layout('admin') ?>

<div class="flex mb-2">
    <h4 class="title"><?= $title ?></h4>

    <ul class="breadcrumb">
        <li>
        <a href="<?= url('/home') ?>">Home</a></li>
        <li>Produtos</li>
    </ul>
</div>

<div class="grid even">
    <div class="panel">
        <div class="panel-head">
            <h6 class="title"><?= "Lista de " . $title ?></h6>
            <?php if(auth()->access_level != 'func'):?>
                <button class="btn btn-primary open-modal" data-open="createProduct">Adicionar</button>
            <?php endif;?>
        </div>
        <div class="panel-body">
            <table class="zebra" id="produtoTable">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Quantidade</th>
                        <th>Categoria</th>
                        <th>Descrição</th>
                        <th>Actualizado em</th>
                        <th>Acção</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<?php $v->start('modal') ?>
<!-- Create User Modal-->
<div class="modal" id="createProduct">
    <div class="modal-dialog">
        <form action="<?= url('/produto/create') ?>" method="post" class="ajax form">
        <div class="modal-header">
            <div class="modal-title">Adicionar produtos</div>
            <p class="close-modal" aria-label="close modal" data-close type="button">x</p>
        </div>
        <div class="modal-content">
            <div class="ajax_modal_response"></div>
                <div class="group">
                    <label for="name">Nome</label>
                    <input type="text" name="name" id="name" placeholder="Nome">
                </div>
                <div class="group">
                    <label for="qty">Quantidade</label>
                    <input type="number" name="qty" id="qty" placeholder="Quantidade">
                </div>
                <div class="group">
                    <label for="descricao">Descrição</label>
                    <textarea name="descricao" id="descricao" cols="30" rows="5" placeholder="Exemplo: Toner B100"></textarea>
                </div>
                <div class="group">
                    <label for="categoria_id">Categoria</label>
                    <select name="categoria_id" id="categoria_id">
                        <?php if(empty($categorias)):?>
                            <option>Nenhuma categoria cadastrada</option>
                        <?php else:?>
                            <?php  foreach ($categorias as $categoria): ?>
                            <option value="<?= $categoria->id ?>"><?= $categoria->name ?></option>
                             <?php endforeach; ?>
                        <?php endif;?>
                                                                          
                    </select>
                </div>
                <?= csrf_input() ?>
                  </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
    </div>
</div>
        <!--Modal-->
<?php $v->end('modal') ?>

<?php $v->start('scripts') ?>
<script>
document.querySelector('.prod-link').classList.add('active');
var produtoTable;
$(document).ready(function(){
    produtoTable = $("#produtoTable").DataTable({
        'ajax': "<?= url('/produto/fetch') ?>",
        'order': []
    })
    
})

function removeProduto(produtoId = null){
    if (produtoId) {
        var confirmar = confirm("Tens a certeza que queres remover?")
        if (confirmar) {
            let url = "<?= url() ?>"
            let redirect = `${url}/produto/${produtoId}/remove`
            window.location.href = redirect
        }
    }
}

</script>
<?php $v->end('scripts') ?>