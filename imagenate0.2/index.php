<?php

// Si no se especifica el parámetro "page", vamos al controller predeterminado (Uploader, en este caso).
if (empty($_GET['page'])) {
    include 'controller/UploaderController.php';

    // Solicitud para manejar el request recibido. Ver controller/UploaderController.php.
    uploader_handleRequest();
}