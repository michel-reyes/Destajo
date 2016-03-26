<?php echo form_open('salva_restaura/salva', array('class'=>'main-form form-validate')); ?>

    <!-- Necesario para guardar -->
    <input type="hidden" name="exportar" value="exportar" />
    
    <div>
        <label for="filename">Nombre del fichero</label>
        <div class="input-append">
            <input class="span2" id="filename" name="filename" type="text" value="mybackup" />
            <span class="add-on">.sql</span>
        </div>
        
        <div>
            <input type="checkbox" name="fn_datetime" id="fn_datetime" checked="checked" />
            <label class="label-i-check" for="fn_datetime">Agregar fecha al nombre del fichero</label>            
        </div>        
               
    </div>
    
</form>