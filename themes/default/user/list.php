<?php $v->layout("admin"); ?>

<div class="flex mb-2">
    <h4 class="title">Usuários</h4>
    <ul class="breadcrumb">
        <li>
        <a href="<?= url('/home') ?>">Home</a></li>
        <li>Usuários</li>
    </ul>
</div>

<div class="grid">
    <div class="panel">
        <div class="panel-head">
            <h6 class="title">Lista de usuários</h6>
            <button class="btn btn-primary open-modal" data-open="userCreate">Adicionar</button>
        </div>
        <div class="panel-body">
            <table class="zebra reload" id="userTable">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Apelido</th>
                        <th>E-mail</th>
                        <th>Nível de acesso</th>
                        <th>Repartição</th>
                        <th>Data de cadastro</th>
                        <th>Acção</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<?php $v->start('modal') ?>
 <!--Modal-->
 <div class="modal" id="userCreate">
        <div class="modal-dialog">
        <form action="<?= url('usuario') ?>" method="post" class="form ajax">
        <div class="modal-header">
            <div class="modal-title">Adicionar novo usuário</div>
            <p class="close-modal" aria-label="close modal" data-close type="button">x</p>
        </div>
        <div class="modal-content">
            <div class="ajax_modal_response"></div>
                <div class="group">
                    <label for="name">Nome</label>
                    <input type="text" name="first_name" id="name" placeholder="Nome">
                </div>
                <div class="group">
                    <label for="last_name">Apelido</label>
                    <input type="text" name="last_name" id="last_name" placeholder="Apelido">
                </div>
                <div class="group">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" placeholder="E-mail">
                </div>
                <div class="group">
                    <label for="access_level">Nível de acesso</label>
                    <select name="access_level" id="access_level">
                        <option value="func">Funcionario</option>
                        <option value="fiel">Fiel de armazém</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                <?php if(!empty($departamentos)):?>
                    <div class="group">
                    <label for="departamento">Repartição</label>
                    <select name="departamento" id="departamento">
                        <?php foreach($departamentos as $departamento):?>
                            <option value="<?= $departamento->id ?>"><?= $departamento->name ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <?php endif; ?>
                                <div class="group">
                    <label for="password">Senha</label>
                    <input type="text" name="password" id="password" placeholder="Senha" value="<?= CONF_SITE_NAME . time() ?>">
                </div>
            <?= csrf_input() ?>        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary user-save" >Guardar</button>
        </div>
    </form>
        </div>
    </div>
    <!--Modal-->
 
<?php $v->end('modal') ?>

<?php $v->start('scripts') ?>
<script>
    document.querySelector('.user-link').classList.add('active');
var userTable;
$(document).ready(function(){
    userTable = $("#userTable").DataTable({
        'ajax': "<?= url('/usuario/fetch') ?>",
        'order': []
    })
    
})

function removeUser(userId = null){
    if (userId) {
        var confirmar = confirm("Tens a certeza que queres remover?")
        if (confirmar) {
            let url = "<?= url() ?>"
            let redirect = `${url}/usuario/${userId}/remove`
            window.location.href = redirect
        }
    }
}

</script>
<?php $v->end('scripts') ?>