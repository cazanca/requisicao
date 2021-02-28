<?php $v->layout('admin') ?>

<div class="flex mb-2">
    <h4 class="title"><?= $title ?></h4>

    <ul class="breadcrumb">
        <li>
        <a href="<?= url('/home') ?>">Home</a></li>
        <li>Repartições</li>
    </ul>
</div>

<div class="grid even">
    <div class="panel">
        <div class="panel-head">
            <h6 class="title"><?= "Lista de " . $title ?></h6>
            <button class="btn btn-primary open-modal" data-open="createRep">Adicionar</button>
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
                <?php if (!empty($reparticoes)): ?>
                <?php foreach($reparticoes as $reparticao): ?>
                    <tr>
                        <td><?= $reparticao->name ?></td>
                        <td><?= date_fmt($reparticao->created_at) ?></td>
                        <td>
                            <?php $reparticaoId = base64_encode($reparticao->id) ?>
                            <a href="<?= url("/reparticao/{$reparticaoId}") ?>" class="edit">
                                <ion-icon name="create-outline"></ion-icon>
                            </a>
                            <a type="button" class="remove" style="cursor:pointer;" onClick="removeReparticao(<?= $reparticao->id?>)">
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
            <span class=" grid right">
            <?= $pager ?>
            </span>
            
        </div>
    </div>
</div>

<?php $v->start('modal') ?>
<!-- Create User Modal-->
<div class="modal" id="createRep">
    <div class="modal-dialog">
        <form action="<?= url("reparticao") ?>" method="post" id="catForm" class="ajax form">
        <div class="modal-header">
            <div class="modal-title">Adicionar reparticao</div>
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
    document.querySelector('.dep-link').classList.add('active');
function removeReparticao(reparticaoId = null){
       if (reparticaoId) {
           var confirmar = confirm("Tens a certeza que queres remover?")
           if (confirmar) {
               let url = "<?= url() ?>"
               let redirect = `${url}/reparticao/${reparticaoId}/delete`
              window.location.href = redirect
           }
       }
   }
</script>
<?php $v->end('scripts') ?>