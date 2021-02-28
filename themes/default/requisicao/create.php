<?php $v->layout('admin') ?>

<?php $v->start('styles') ?>
<style>
   .tdinput{
  border-radius: 5px;
  border: 1px solid #d0d0d6;
  font-size: 1.5rem;
  font-weight: 400;
   }

   .tdinput input,
   .tdinput select{
       width: 100%;
       height:100%;
       font-size: 15px;
       padding: 10px 20px;
       border: 0;
   }
</style>
<?php $v->end('styles') ?>

<div class="flex mb-2">
    <h4 class="title"><?= $title ?? 'Requisitar' ?></h4>
</div>

<div class="grid even">
    <div class="panel">
        <div class="panel-head">
            <h6 class="title">Requisitar produtos</h6>
        </div>
        <div class="panel-body">
            <form action="<?= url('/requisicao') ?>" method="post">
            <table id="productTable">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $arrayNumber = 0;
                        for ($i= 1; $i <= 6; $i++): ?>
                        <tr id="row<?= $i ?>">
                            <td>
                                <div class="tdinput">
                                    <select name="productName[]" id="productName<?= $i ?>" onChange="getProductData(<?= $i ?>)" required>
                                    <option value="">Seleciona um produto</option>
                                        <?php foreach($produtos as $produto): ?>
                                            <option value="<?= $produto->id ?>" id="changeProduct<?= $produto->id ?>"><?= $produto->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </td>
                            <td>
			  					<div class="tdinput" style="max-width:200px;">
			  					<input type="number"  name="quantity[]" id="quantity<= $i; ?>" onkeyup="getTotal(<?= $i ?>)" autocomplete="off" class="form-control" min="1"  required/>
			  					</div>
			  				</td>
                              <td>

			  					<a class="remove btn-remove removeProductRowBtn" style="cursor:pointer;" type="button" id="removeProductRowBtn" onclick="removeProductRow(<?=  $i ?>)"><ion-icon name="trash-outline"></ion-icon></a>
			  				</td>
                        </tr>
                       <?php endfor; ?>
                </tbody>
            </table>
            <button type="submit" id="createOrderBtn" data-loading-text="Loading..." class="btn btn-primary right">Gurdar Alterações</button>

            </form>
           

        </div>
    </div> 
    <div class="div"></div>
</div>

<script>
    document.querySelector('.cart-link').classList.add('active');
    function removeProductRow(row = null) {
	if(row) {
		$("#row"+row).remove();


		//subAmount();
	} else {
		alert('error! Refresh the page again');
	}
}

</script>