<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>
		<?php
		if (isset($pageTitle)) {
			echo $pageTitle;
		} else {
			echo "Home";
		}
		?>
	</title>
	<link rel="stylesheet" type="text/css" href="../../mvc/public/css/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../../mvc/public/boxicons-2.0.7/css/boxicons.min.css">
	<link rel="stylesheet" type="text/css" href="../../mvc/public/css/style.css">
</head>

<body>
