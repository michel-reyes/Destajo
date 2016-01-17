<html>
<head>
<title>Upload Infor</title>
</head>
<body>

<h3>Informacion del fichero de actualizacion</h3>

<?php if (isset($pieces)): ?>
	<ul>
		<?php foreach ($pieces as $k => $v): ?>
			<li><?php echo $v; ?></li>
		<?php endforeach; ?>
	</ul>
<?php else: ?>
	No hay datos en el fichero de actualizacion	
<?php endif; ?>

<?php
echo anchor("updates/update", "Ejecutar actualizacion");
echo anchor("entrada", "Cancelar, volver a destajo"); 
?>

</body>
</html>