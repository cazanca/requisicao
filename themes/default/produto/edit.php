<?php $v->layout('admin') ?>

<div class="flex mb-2">
    <h4 class="title"><?= $title ?></h4>

    <ul class="breadcrumb">
        <li><a href="<?= url('/home') ?>">Home</a></li>
        <li><a href="<?= url('/produto') ?>">Produto</a></li>
        <li>Actualizar</li>
    </ul>
</div>

<div class="grid even">
    <div class="panel">
        <div class="panel-head">
            <h6 class="title"><?= "Actualizar {$produto->name}" ?></h6>
        </div>
        <div class="panel-body">
            <form action="<?= url("produto/{$produto->id}") ?>" method="post" class="ajax form">
                <input type="hidden" name="action" value="update">
                <div class="grid even">
                    <div class="group">
                        <label for="name">Nome</label>
                        <input type="text" name="name" id="name" value="<?= $produto->name ?>">
                    </div>
                    <div class="group">
                        <label for="qty">Quantidade</label>
                        <input type="number" name="qty" id="qty" value="<?= $produto->qty ?>">
                    </div>
                </div>
                <div class="grid even">
                    <div class="group">
                        <label for="categoria_id">Categoria</label>
                        <select name="categoria_id" id="categoria_id">
                            <?php if(empty($categorias)):?>
                                <option>Nenhuma categoria cadastrada</option>
                            <?php else:?>
                                <?php  foreach ($categorias as $categoria): ?>
                                <option value="<?= $categoria->id ?>" <?= ($categoria->id == $produto->categoria_id) ? 'selected' : '' ?>><?= $categoria->name ?></option>
                                <?php endforeach; ?>
                            <?php endif;?>
                                                                            
                        </select>
                    </div>
                    <div class="group">
                        <label for="descricao">Descrição</label>
                        <textarea name="descricao" id="descricao" cols="30" rows="1"><?= $produto->descricao ?>
                        </textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary right">Guardar alterações</button>
            </form>
        </div>
    </div>
</div>