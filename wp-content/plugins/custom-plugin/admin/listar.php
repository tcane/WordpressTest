<div class="wrap">
	<h2>Lista</h2>
	<form method="post" action="options.php">
		
		<?php do_settings_sections( 'super-settings-group' ); ?>
		<?php if(!empty($lista)) : ?>
			<table class="form-table">
				<thead>
				  <tr>
					 <th>Valor</th>
					 <th>Descripcion</th>
					 <th>Fecha</th>
					 <th>Editar</th>
					 <th>Eliminar</th>
				  </tr>
				 </thead>
				 <tbody>
					 <?php foreach($lista as $item ) : ?>
						  <tr>
							 <td><?= $item->valor ?></td>
							 <td><?= $item->descripcion ?></td>
							 <td><?= $item->fecha ?></td>
							 <td><a href="admin.php?page=agregar-custom-plugin-id&cpid=<?= $item->id ?>">Editar</a></td>
							 <td><a href="admin.php?page=custom-plugin-identifier&elid=<?= $item->id ?>">Eliminar</a></td>
						  </tr>
					 <?php endforeach ?>
				 </tbody>
			</table>
		<?php else: ?>
			<h4>No hay datos cargados</h4>
		<?php endif; ?>
	</form>
</div>