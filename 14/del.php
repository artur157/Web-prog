<?php
	$id = $_GET['id'];
	echo "Удалён файл "  . $id;
	unlink($id);
?>