<?php

defined('_JEXEC') or die('Acceso denegado');

/* Importando la libreria base de plugins de Joomla */

jimport('joomla.plugin.plugin');

/* Generando el plugin */

class plgContentContenido extends JPlugin{

	/* Asignando plugin de contenido */
    
	function plgContentContenido(&$subject){

		parent::__construct($subject);

	}

	/* Asignando metodo que utilizara el plugin */

    function onContentBeforeDisplay(&$article, &$params, $limitstart){

    	/* Variable que contiene el valor del mensaje predefinido */
        
    	$mensaje = "Hello world";

    	/* Variable que almacena el mensaje maquetado en HTML */

    	$salida = '<h2>'.$mensaje.'<h2>';

    	/* Retornando el valor de la variable $salida */

    	return $salida;

    }  

}