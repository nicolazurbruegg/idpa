<?php
function build($page){
    ?>
    <!DOCTYPE html>
    <html lang="de">
	<head>
		<title>IDPA</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="UTF-8">
		<!-- <link rel="icon" href="/images/logo.jpg"> -->
		<link rel="stylesheet" type="text/css" href="../css/main.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	</head>



	<body id="body">
		<header>
			<?php require_once './views/header.php'; ?>
		</header>
            <?php require_once $page; ?>
	</body>
	</html>
    <?php
}
?>