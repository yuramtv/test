<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link rel="stylesheet" href="/template/bootstrap/css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="/template/css/style.css" type="text/css" />

    <script src="/template/bootstrap/js/jquery.min.js"></script>
    <script src="/template/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<div class="logo">
				<a href="/">Header</a>
			</div>	
			<div class="menu">
				<?php
				echo (Lib_Menu::getInstance()->getMenu());
				?>
			</div>	
			
		</div>
		<div id="content">
		<hr/>
			
	