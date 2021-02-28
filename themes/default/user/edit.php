<?php $v->layout("admin"); ?>

<div class="flex mb-2">
    <h4 class="title">Actualizar</h4>
    <ul class="breadcrumb">
        <li><a href="<?= url('/home') ?>">Home</a></li>
        <li><a href="<?= url('/usuario') ?>">Usuário</a></li>
        <li>Actualizar</li>
    </ul>
</div>


<?php if($user):?>

<div class="grid even">
<div class="panel">
    <div class="panel-head">
        <h6 class="title">Atualizar dados de <?= $user->last_name ?></h6>
    </div>

    <div class="panel-body">
        <form action="<?= url("usuario/{$user->id}") ?>" method="post" class="ajax form" id="ajax">
        <?= csrf_input() ?>
        <input type="hidden" name="action" value="update">
        <div class="grid even">
            <div class="group">
                <label for="first_name">Nome</label>
                <input type="text" name="first_name" id="first_name" value="<?= $user->first_name ?>">
            </div>
            <div class="group">
                <label for="last_name">Apelido</label>
                <input type="text" name="last_name" id="last_name" value="<?= $user->last_name ?>">
            </div>
        </div>
        <div class="group">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" value="<?= $user->email ?>">
        </div>

        <div class="grid even">
            <div class="group">
                <label for="access_level">Nível de acesso</label>
                <select name="access_level" id="access_level">
                    <option value="func" <?= ($user->access_level == 'func') ? 'selected' : '' ?>>Funcionario</option>
                    <option value="fiel"  <?= ($user->access_level == 'fiel') ? 'selected' : '' ?>>Fiel de armazém</option>
                    <option value="admin"  <?= ($user->access_level == 'admin') ? 'selected' : '' ?>>Administrador</option>
                </select>
            </div>
            <?php if(!empty($departamentos)):?>
                <div class="group">
                <label for="departamento">Repartição</label>
                <select name="departamento" id="departamento">
                    <?php foreach($departamentos as $departamento):?>
                        <option value="<?= $departamento->id ?>" <?= ($departamento->id == $user->departamento) ? 'selected' : '' ?>><?= $departamento->name ?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <?php endif; ?>

            <div class="group">
                <label for="password">Password</label> 
                <input type="password" name="password" id="password" placeholder="Password">     
            </div>
        </div>
        <button class="btn btn-primary right">Guardar alterações</button>

        </form>
    </div>
</div>
</div>
<?php endif; ?>