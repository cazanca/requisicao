<?php $v->layout('admin') ?>

<div class="flex mb-2">
    <h4 class="title"><?= $title ?></h4>

    <ul class="breadcrumb">
        <li><a href="<?= url('/home') ?>">Home</a></li>
        <li><a href="<?= url('/reparticao') ?>">Repartições</a></li>
        <li>Actualizar</li>
    </ul>
</div>

<div class="grid even">
    <div class="panel">
        <div class="panel-head">
            <h6 class="title">Actualizar <?= $reparticao->name ?></h6>
        </div>
        <div class="panel-body">
            <form action="<?= url("/reparticao/{$reparticao->id}")?>" class="ajax form" method="post">
            <input type="hidden" name="action" value="update">
            <div class="group">
                <label for="name">Nome</label>
                <input type="text" name="name" id="name" value="<?= $reparticao->name ?>">
            </div>
            <button type="submit" class="btn btn-primary">Guardar alterações</button>
        </form>
        </div>
    </div>
</div>
