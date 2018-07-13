<?php
ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] . '/../tmp');
//ini_set('session.save_path', realpath(dirname($_SERVER['DOCUMENT_ROOT']).'/../tmp'));
ini_set('session.gc_probability', 1);
ini_set('session.gc_maxlifetime', 3600);
//session_save_path(realpath(dirname($_SERVER['DOCUMENT_ROOT']).'/../tmp'));
session_start();