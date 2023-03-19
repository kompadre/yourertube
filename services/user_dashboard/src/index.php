<?php
session_start();

if (isset($_FILES['video'])) {
	$uploaded_file = '/media/uploaded/' . $_FILES['video']['name'];
	move_uploaded_file($_FILES['video']['tmp_name'], $uploaded_file);
	var_dump($uploaded_file);
}

phpinfo();