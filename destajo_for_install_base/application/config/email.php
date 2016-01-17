<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Configurar los parametros del email
$config['protocol'] = 'smtp';
$config['smtp_host'] = 'correo.trans.cupet.cu';
$config['smtp_port'] = 465;
$config['smtp_user'] = 'michel@trans.cupet.cu';
$config['smtp_pass'] = 'abrete08';

$config['charset'] = 'utf-8';
$config['wordwrap'] = TRUE;
$config['wrapchars'] = 80;
$config['mailtype'] = 'html';
$config['priority'] = '2';
$config['newline'] = '\n';