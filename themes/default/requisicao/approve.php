<?php $v->layout('admin') ?>

<div class="flex mb-2">
    <h4 class="title"><?= $title ?? 'Aprovar a requisição' ?></h4>
    <ul class="breadcrumb">
        <li><a href="<?= url('/home') ?>">Home</a></li>
        <li><a href="<?= url('/requisicao') ?>">Requisição</a></li>
        <li>Aprovar</li>
    </ul>
</div>

<div class="grid even">
    <div class="panel">
       <div class="panel-body">
           <table>
               <thead>
                   <tr>
                       <th>Produto</th>
                       <th>Quantidade</th>
                   </tr>
               </thead>
               <tbody>
               <form action="<?= url("requisicao/{$id}") ?>" method="post">
                <?php foreach($itens as $item): ?>
                    <tr>
                        <td><?= $item->produto()->name ?></td>
                        <td><?= $item->quantidade ?></td>
                        
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <?php if($pedido->status == 'pendente'): ?>
                    <td colspan="2"><button type="submit" class="btn btn-primary">Aprovar</button></td>
                    <?php else: ?>
                        <td colspan="2"><span class="btn-label btn-primary">A requisição já foi aprovada</span></td>
                    <?php endif; ?>
                </tr>
                </form>
               </tbody>
           </table>
           
       </div>
    </div>
</div>