<?php echo form_open_multipart('salva_restaura/restaura', array('class' => 'form-upload')); ?>    
    
    <div class="fileupload fileupload-new" data-provides="fileupload">
        <div class="input-append">
            <div class="uneditable-input span3">
                <i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
            </div><span class="btn btn-file btn-primary"><span class="fileupload-new">Seleccionar fichero</span><span class="fileupload-exists">Cambiar</span>
                <input type="file" />
            </span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Cancelar</a>
        </div>
    </div>
    
    <div class="alert">
        <strong>&iexcl;Advertencia!</strong>
        <p>Si usted restaura el fichero seleccionado los datos <br>
            que se encuentran en la base de datos<br />
            ser&aacute;n eliminados y no podr&aacute;n recuperarse.
        </p>
    </div>
    
</form>