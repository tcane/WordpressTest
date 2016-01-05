<div class="wrap">
    <h2>Agregar</h2>
     <?php if(!empty($insert)): ?>
        <h4>Datos agregados, puede serguir cargando</h4>
    <?php endif; ?>
    <form name="customplugin_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="ecpid" value="<?= !empty($edit_id)? $edit_id : '' ?>">
        <p>Valor
            <input type="text" name="cp_valor" value="<?php echo isset($editData->valor)? $editData->valor : ''; ?>">
        </p>     
        <p>Descripcion<textarea name="cp_descripcion"><?php echo isset($editData->descripcion)? $editData->descripcion : ''; ?></textarea></p>     
        <p class="submit">
            <input type="submit" name="Submit" value="Agregar" class="button button-primary" />
        </p>
    </form>
</div>