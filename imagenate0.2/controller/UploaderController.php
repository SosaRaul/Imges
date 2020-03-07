<?php

/**
 * Maneja todos los requests llegados al controlador.
 * Funciona en base a "action" que reciba vía POST.
 */
 //----------------------------------------------------------------------

function uploader_handleRequest() {
    // Se asigna valor predeterminado para $action.
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    switch ($action) {
        case 'upload':
            // Acción de subir el archivo y mostrar el resultado.
            uploader_uploadAction();
            break;

        default:
            // Acción predeterminada: mostrar el formulario.
            uploader_defaultAction();
    }
}
//----------------------------------------------------------------------

/**
 * Acción predeterminada del controlador.
 * Muestra el formulario de upload en pantalla.
 */
function uploader_defaultAction() {
    $historial = uploader_getHistory();
    
    
    // Se configuran los datos a mostrar en la pantalla.
    $__data = array(
        'pageTitle' => 'Imagenate! - Alpha 0.1',
        'imagenes' => $historial
    );

    // Se muestra la vista correspondiente.
    include 'view/UploaderView.php';
}

//----------------------------------------------------------------------
/** Obtiene y retorna el historial local de imágenes subidas.
 * 
 * Va a ser un array secuencial que en cada posición va a tener otro
 * array con ruta y URL.
 * 
 * El historial se va a guardar en una cookie.
 * 
 */ 

function uploader_getHistory(){
	// Si existe el historial , lo retornamos.
	// Sino,inicializamos y retornamos un array vacío.
	if(isset($_COOKIE['historial'])) {
		$historial = unserialize($_COOKIE['historial']);
	}else {
		$historial = array();
	}
	
	return $historial;
}			

//----------------------------------------------------------------------

/**
 * Manejador de errores del controlador.
 * Siempre muestra la vista de errores, con el mensaje asignado.
 */
function uploader_showError($error) {
    // Se configuran los datos a mostrar en la pantalla.
    $__data = array(
        'pageTitle' => 'Imagenate! - Alpha 0.1',
        'error' => $error
    );

    // Se muestra la vista correspondiente.
    include 'view/ErrorView.php';
}
//----------------------------------------------------------------------

/**
 * Muestra el resultado luego de subir la imagen.
 *
 * @param String $rutaImagen Indica la ruta hacia la imagen subida.
 * @param Array $infoImagen Provee de información sobre la imagen subida. Información retornada por getimagesize.
 */
function uploader_showUploadedImage($rutaImagen, $infoImagen) {
    // Se configuran los datos a mostrar en la pantalla.
    $__data = array(
        'pageTitle' => 'Imagenate! - Alpha 0.1',
        'rutaImagen' => $rutaImagen,
        'infoImagen' => $infoImagen,
        'urlImagen' => $_SERVER['HTTP_ORIGIN'] . $_SERVER['REQUEST_URI'] . $rutaImagen
    );

    // Se muestra la vista correspondiente.
    include 'view/UploadedImageView.php';
}
//----------------------------------------------------------------------

/**
 * Chequea el archivo subido para determinar si es una imagen.
 * En caso de ser una imagen, se procede a asignarle nombre y colocarla en el directorio correspondiente.
 */
 
# Chequeamos si hay errores.
# Chequeamos si es una imagen. 
 
function uploader_uploadAction() {
    if (!empty($_POST['submit']) && $_FILES['foto']['error'] == '0') { 
        $fileInfo = getimagesize($_FILES['foto']['tmp_name']);

        // Se chequea si el archivo subido es una imagen.
        if (is_array($fileInfo)) {
            // El archivo es una imagen. Se procede a subirla.
            uploader_uploadImage($_FILES['foto'], $fileInfo);
        } else {
            // NO hay archivo. Mostrar mensaje de error.
            uploader_showError('El archivo subido no es una imagen.');
        }
    } else {
        // NO hay datos. Mostrar mensaje de error.
        uploader_showError('Acceso no autorizado.');
    }
}
//----------------------------------------------------------------------

/**
 * Mueve la imagen al directorio final.
 *
 * @param Array $imagen Contiene el array proveniente de $_FILES con la posición correspondiente.
 * @param Array $info Provee de información sobre la imagen subida. Información retornada por getimagesize.
 */
function uploader_uploadImage($imagen, $info) {
    $origen = $imagen['tmp_name'];

    $directorio = 'images';
    $nombreImagen = uniqid(microtime(true), true);
    $destino = $directorio . '/' . $nombreImagen;
    
    $linkImagen = $_SERVER['HTTP_ORIGIN'] . $_SERVER['REQUEST_URI'] . $destino;
    if (move_uploaded_file($origen,$destino)) {
		//Guardar historial.
		uploader_setHistory($destino,$linkImagen);	
        // Archivo subido. Mostrar mensaje con link.
        uploader_showUploadedImage($destino, $info);
    } else {
        // Archivo NO subido. Mostrar mensaje de error.
        uploader_showError('Error interno al subir el archivo. Vuelva a intentar m&aacute;s tarde.');
    }
}

//----------------------------------------------------------------------
/**
 * Graba el historial local de imágenes subidas.
 * La persistencia se llevará a cabo en la cookie "historial".
 * 
 * @param String $ruta Ruta hacia la imagen.
 * @param String $url URL hacia la imagen.
 */ 

function uploader_setHistory($ruta,$url){
	//Se obtiene el historial previamente cargado.
	$historial = uploader_getHistory();
	
	//Cargar la nueva imagen.
	$historial[] = array(
	     'ruta' => $ruta,
	     'url' => $url
	);
	
	setcookie('historial',serialize($historial),time() + (60 * 60 * 24));
	
}	
	     
	         
	
	
	
	
		



//----------------------------------------------------------------------
