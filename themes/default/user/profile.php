<?php $v->layout("admin"); ?>

<div class="flex mb-2">
    <h4 class="title">Perfil</h4>
</div>

<div class="grid one-two">
    <div class="panel">
        <div class="panel-head">
        <h6 class="title">Atualizar senha</h6>
        </div>
        <div class="panel-body">
            <form class="ajax form" id="ajax" action="<?= url('/password') ?>" method="post">
           
                <div class="group">
                    <label for="password">Senha atual</label>
                    <input type="password" name="password" id="password" placeholder="Digite a senha atual" required>
                </div>
                <div class="group">
                    <label for="newpassword">Nova senha</label>
                    <input type="password" name="newpassword" id="newpassword" placeholder="Digite nova senha" required>
                </div>
                <div class="group">
                    <label for="confpassword">Confirmar a senha</label>
                    <input type="password" name="confpassword" id="confpassword" placeholder="Confirmar senha" required>
                </div>
                <button type="submit" class="btn btn-primary right">Alterar</button>
            </form>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h6 class="title">Informações pessoais</h6>
        </div>
        <div class="panel-body">
            <form action="<?= url('/profile') ?>" method="post" class="form ajax">
            <?= csrf_input() ?>
                <div class="grid even">
                    <div class="group">
                        <label for="first_name">Nome</label>
                        <input type="text" name="first_name" id="first_name" placeholder="Digite o nome" value="<?= $user->first_name ?>">
                    </div>
                    <div class="group">
                        <label for="last_name">Apelido</label>
                        <input type="text" name="last_name" id="last_name" placeholder="Digite o apelido" value="<?= $user->last_name ?>">
                    </div>
                </div>
                <div class="group">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" placeholder="Digite o e-mail" value="<?= $user->email ?>">
                </div>
                <button type="submit" class="btn btn-primary-outline right">Guardar alterações</button>
            </form>
        </div>
    </div>
</div>