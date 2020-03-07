<html>
    <head>
        <title><?php echo $__data['pageTitle']; ?></title>
    </head>

    <body>
        <form method="POST" enctype="multipart/form-data">
            <h1>Imagenate!</h1>
            <h4>Compart&iacute; tu foto</h4>
            <input type="file" name="foto" /><br />
            <input type="hidden" name="action" value="upload" />
            <input type="submit" name="submit" value="Subir!" />
        </form>
        <hr />
        <h4> Tus im&aacute;genes</h4>
        
	    <?php foreach($__data['imagenes'] as $img) { ?>
		    <!--Mostrar esto por cada imagen del array-->    
		        <a href="<?php echo $img['url']; ?>" target="_black">
		             <img src="<?php echo $img['ruta']; ?>" width="100" /><br />
		             <!-- URL de la imagen -->
		        </a><br /><br />
	    <?php } ?>
    
    </body>
    
</html>

