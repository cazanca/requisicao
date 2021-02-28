<?php $v->layout('admin') ?>

<div class="flex mb-2">
    <h4 class="title">Dashboard</h4>
</div>

<div class="grid even">
    <div class="panel">
        <div class="panel-body">
        <div id="chartContainer" style="height: 370px; width: 100%;font-family: "Roboto", sans-serif;"></div>
        </div>
    </div>
    <div class="panel">
        <div class="panel-body">
            <div id="chartContainer1" style="height: 370px; width: 100%;font-family: "Roboto", sans-serif;"></div>
        </div>
    </div>
</div>

<div class="grid one-two mt-2">
    <div class="panel">
        <div class="panel-head">
            <h6 class="title">Repartições</h6>
        </div>
        <div class="panel-body">
            <?php if(!empty($departamentos)): ?>
            <table class="zebra">
                <tr>
                    <th>Nome</th>
                </tr>
               <?php foreach($departamentos as $dep): ?>
                    <tr>
                        <td><?= $dep->name ?></td>
                    </tr>
               <?php endforeach; ?>
            </table>
            <?php else: ?>
                <p>Nenhuma repartição cadastrada</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="panel">
        <div class="panel-head">
            <h6 class="title">Usuários</h6>
        </div>
        <div class="panel-body">
        <?php if(!empty($usuarios)): ?>
            <table class="zebra">
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Repartição</th>
                    
                </tr>
               <?php foreach($usuarios as $user): ?>
                    <tr>
                        <td><?= $user->first_name . " ". $user->last_name ?></td>
                        <td><?= $user->email ?></td>
                        <td><?= ($user->departamento()) ? $user->departamento()->name : "<span class='btn-label btn-primary'>não definido</span>" ?></td>
                        
                    </tr>
               <?php endforeach; ?>
            </table>
            <?php else: ?>
                <p>Nenhuma repartição cadastrada</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $v->start('scripts') ?>
<script>
document.querySelector('.home-link').classList.add('active');
window.onload = function() {
 
 var chart = new CanvasJS.Chart("chartContainer", {
     animationEnabled: true,
     theme: "light2",
     title:{
         text: "Mais Requisitados"
     },
     axisY: {
         title: "unidades"
     },
     data: [{
         type: "column",
         yValueFormatString: "#,##0.## unidades",
         dataPoints: <?php echo json_encode($data, JSON_NUMERIC_CHECK); ?>
     }]
 });
 chart.render();

 var chart1 = new CanvasJS.Chart("chartContainer1", {
	animationEnabled: true,
	exportEnabled: true,
	title:{
		text: "Estados das requisições"
	},
	data: [{
		type: "pie",
		showInLegend: "true",
		legendText: "{label}",
		indexLabelFontSize: 16,
		indexLabel: "{label} - #percent%",
		yValueFormatString: "#,##0",
		dataPoints: <?php echo json_encode($status, JSON_NUMERIC_CHECK); ?>
	}]
});
chart1.render();
  
 }
</script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<?php $v->end('scripts') ?>