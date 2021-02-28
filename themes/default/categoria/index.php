<?php $v->layout('admin') ?>

<div class="flex mb-2">
    <h4 class="title"><?= $title ?></h4>

    <ul class="breadcrumb">
        <li>
        <a href="<?= url('/home') ?>">Home</a></li>
        <li>Categorias</li>
    </ul>
</div>

<div class="grid even">
    <div class="panel">
        <div class="panel-head">
            <h6 class="title"><?= "Lista de " . $title ?></h6>
            <button class="btn btn-primary open-modal" data-open="createCat">Adicionar</button>
        </div>
        <div class="panel-body">
            <table class="zebra">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Data de cadastro</th>
                        <th>Acção</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($categorias)): ?>
                <?php foreach($categorias as $categoria): ?>
                    <tr>
                        <td><?= $categoria->name ?></td>
                        <td><?= date_fmt($categoria->created_at) ?></td>
                        <td>
                            <?php $categoriaId = base64_encode($categoria->id) ?>
                            <a href="<?= url("/categoria/{$categoriaId}") ?>" class="edit">
                                <ion-icon name="create-outline"></ion-icon>
                            </a>
                            <a type="button" class="remove" style="cursor:pointer;" onClick="removeCategoria(<?= $categoria->id?>)">
                                <ion-icon name="trash-outline"></ion-icon>
                            </a>
                        </td>
                    </tr>
                <?php
                     endforeach;
                    else:
                ?>
                    <tr>
                        <td colspan="3">Nenhum dado encontrado</td>
                    </tr>
            <?php endif;?>
                </tbody>
            </table>
            <span class="grid right">
            <?= $pager ?>
            </span>
            
        </div>
    </div>
</div>

<?php $v->start('modal') ?>
<!-- Create User Modal-->
<div class="modal" id="createCat">
    <div class="modal-dialog">
        <form action="<?= url("categoria") ?>" method="post" id="catForm" class="ajax form">
        <div class="modal-header">
            <div class="modal-title">Adicionar categoria</div>
            <p class="close-modal" aria-label="close modal" data-close type="button">x</p>
        </div>
        <div class="modal-content">
           
                <div class="group">
                    <label for="name">Nome</label>
                    <input type="text" name="name" id="name" placeholder="Nome">
                </div>
        </div>
        <?= csrf_input() ?>
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
    document.querySelector('.cat-link').classList.add('active');
function removeCategoria(categoriaId = null){
       if (categoriaId) {
           var confirmar = confirm("Tens a certeza que queres remover?")
           if (confirmar) {
               let url = "<?= url() ?>"
               let redirect = `${url}/categoria/${categoriaId}/delete`
              
              window.location.href = redirect
           }
       }
   }
</script>
<?php $v->end('scripts') ?>