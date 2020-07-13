<?php
$conex = mysqli_connect("127.0.0.1", "root", "", "automotoresjp");

if (!$conex) {
    echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
    echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
    echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
	exit;
}

$modelo_auto = "";
$marca_auto = "";
$costo_auto = "";
$fecha_salida = ""; 
$cod_auto = "";
$accion = "Agregar";

if (isset($_POST["accion"]) && ($_POST["accion"] == "Agregar")) {
	$stmt = $conex->prepare("INSERT INTO autos (marca_auto, modelo_auto, costo_auto, fecha_salida) VALUES (?, ?, ?, ?)");
	$stmt->bind_param('ssss', $marca_auto, $modelo_auto, $costo_auto, $fecha_salida);
	$marca_auto = $_POST["marca_auto"];
	$modelo_auto = $_POST["modelo_auto"];
	$costo_auto = $_POST["costo_auto"];
	$fecha_salida = $_POST["fecha_salida"];
	$stmt->execute();
	$stmt->close();
	$modelo_auto = "";
	$marca_auto = "";
	$costo_auto = "";
	$fecha_salida = ""; 
} else if (isset($_POST["accion"]) && ($_POST["accion"] == "Modificar")){
	$stmt = $conex->prepare("UPDATE autos set marca_auto = ?, modelo_auto = ?, costo_auto = ?, fecha_salida = ? WHERE cod_auto = ?");
	$stmt->bind_param('ssssi', $marca_auto, $modelo_auto, $costo_auto, $fecha_salida, $cod_auto);
	$marca_auto = $_POST["marca_auto"];
	$modelo_auto = $_POST["modelo_auto"];
	$costo_auto = $_POST["costo_auto"];
	$fecha_salida = $_POST["fecha_salida"];
	$cod_auto = $_POST["cod_auto"];
	$stmt->execute();
	$stmt->close();
	$modelo_auto = "";
	$marca_auto = "";
	$costo_auto = "";
	$fecha_salida = ""; 
} else if (isset($_GET["update"])){
	$result = $conex->query("SELECT * FROM autos WHERE cod_auto=".$_GET["update"]);
	if ($result->num_rows > 0) {
		$row1 = $result-> fetch_assoc();
		$cod_auto = $row1["cod_auto"];
		$marca_auto = $row1["marca_auto"];
		$modelo_auto = $row1["modelo_auto"];
		$costo_auto = $row1["costo_auto"];
		$fecha_salida = $row1["fecha_salida"];
		$accion = "Modificar";
	}
} else if (isset($_POST["elimcod"])){
	$stmt = $conex->prepare("DELETE FROM autos WHERE cod_auto = ?");
	$stmt->bind_param('i', $cod_auto);
	$cod_auto = $_POST["elimcod"];
	$stmt->execute();
	$stmt->close();
	$cod_auto = "";
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Automotores JP</title>

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400">  <!-- Google web font "Open Sans" -->
	<link rel="stylesheet" href="css/fontawesome-all.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/magnific-popup.css"/>
	<link rel="stylesheet" type="text/css" href="slick/slick.css"/>
	<link rel="stylesheet" type="text/css" href="slick/slick-theme.css"/>
	<link rel="stylesheet" href="css/tooplate-style.css">

	<script>
		var renderPage = true;

	if(navigator.userAgent.indexOf('MSIE')!==-1
		|| navigator.appVersion.indexOf('Trident/') > 0){
   		/* Microsoft Internet Explorer detected in. */
   		alert("Please view this in a modern browser such as Chrome or Microsoft Edge.");
   		renderPage = false;
	}
	</script>
</head>

<body>
	<!-- Loader 
	<div id="loader-wrapper">
		<div id="loader"></div>
		<div class="loader-section section-left"></div>
		<div class="loader-section section-right"></div>
	</div>-->
	
	<!-- Page Content -->
	<div class="container-fluid tm-main">
		<div class="row tm-main-row">

			<!-- Sidebar -->
			<div id="tmSideBar" class="col-xl-3 col-lg-4 col-md-12 col-sm-12 sidebar">

				<button id="tmMainNavToggle" class="menu-icon">&#9776;</button>

				<div class="inner">
					<nav id="tmMainNav" class="tm-main-nav">
						<ul>
							<li>
								<a href="index.php" id="tmNavLink1" class="scrolly active" data-bg-img="logoauto.jpg" data-page="#tm-section-1">
									<i class="fas fa-home tm-nav-fa-icon"></i>
									<span>CRUD</span>
								</a>
							</li>								
						</ul>
					</nav>
				</div>
			</div>

			<div class="col-xl-9 col-lg-8 col-md-12 col-sm-12 tm-content">

					<!-- section 1 -->
					<section id="tm-section-1" class="tm-section">
						<div class="ml-auto">
							<form id="forma" action="index.php" method="post" class="contact-form">
							<header class="mb-4"><h1 class="tm-text-shadow"><strong>Automotores Continental JP</strong></h1></header>
							<p class="mb-5 tm-font-big"><strong>Registro de nuevos vehiculos</strong></p>
							<?php
							$conex = mysqli_connect("127.0.0.1", "root", "", "automotoresjp");							
							?>
							<table border =0>
								<tr>
									<td colspan=4 style="width:800px">&nbsp;</td>
									<td><input type="button" name="eliminar" value="Eliminar" class="btn tm-btn-submit tm-btn ml-auto" onclick="eliminarAuto();"></td>
								</tr>
							</table>
							<br>
							<table class="table" border="1">
								<head>
								<tr>
									<td>ID</td>
									<td>MARCA</td>
									<td>MODELO</td>
									<td>COSTO DEL AUTO</td>
									<td>AÑO DEL MODELO</td>
									<td>ELIMINAR</td>
								</tr>
								</head>
								<?php
								$result = $conex->query("SELECT * FROM autos");
								if ($result->num_rows > 0) {
									while($row = $result->fetch_assoc()) {
								
								?>
								<tr>
									<td><a href="index.php?update=<?php echo $row["cod_auto"];?>"><?php echo $row["cod_auto"];?></a></td>
									<td><?php echo $row["marca_auto"];?></td>
									<td><?php echo $row["modelo_auto"];?></td>
									<td><?php echo $row["costo_auto"];?> $ </td>
									<td><?php echo $row["fecha_salida"];?></td>
									<td><input type="radio" name="elimcod" value="<?php echo $row["cod_auto"];?>"></td>
								</tr>
									<?php }
								} else { ?>
								<tr>
									<td colspan="5">NO HAY DATOS</td>
								</tr>
								<?php } ?>	
							</table>
							<br>
							<!--<a href="index.php" class="btn tm-btn tm-font-big">Eliminar</a>--> 
						
							<div class="row tm-page-4-content">
								<div class="col-md-6 col-sm-12 tm-contact-col">
									<div class="contact_message">
										<input type="hidden" name="cod_auto" value="<?php echo $cod_auto; ?>"/>
										<p class="mb-5 tm-font-big"><strong>Ingreso Nuevo Vehiculo</strong></p>
											<div class="form-group">
												<label id="lblmarca" for="marca_auto"><strong> Marca Vehiculo: </strong></label>
												<input type="text" name="marca_auto" class="form-control" value ="<?php echo $marca_auto; ?>" placeholder="Ingrese la marca del Vehiculo" size="25" required>
											</div>
											<div class="form-group">
											<label id="lblmodelo" for="modelo_auto"><strong> Modelo Vehiculo: </strong></label>
											<input type="text" name="modelo_auto" class="form-control" value ="<?php echo $modelo_auto; ?>" placeholder="Ingrese el modelo del Vehiculo" size="25" required>
											</div>
											<div class="form-group">
											<label id="lblcostoAuto" for="costo_auto"><strong> Costo del Vehiculo: </strong></label>
											<input type="text" name="costo_auto" class="form-control" value ="<?php echo $costo_auto; ?>" placeholder="Ingrese el precio del Vehiculo" size="15" required>											</div>
											<div class="form-group">
											<label id="lblfecha" for="fecha_salida"><strong> Año de Vehiculo: </strong></label>
											<input type="text" name="fecha_salida" class="form-control" value ="<?php echo $fecha_salida; ?>" placeholder="Ingrese el año del Vehiculo" size="11" required>											</div>
											
											<button type="submit" name="accion" value = "<?php echo $accion?>" class="btn tm-btn-submit tm-btn ml-auto"><?php echo $accion?></button>
									</div>
								</div>
							</form>
						</div>
					</section>
			</div>			
		</div>
	</div>

		<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="js/jquery.magnific-popup.min.js"></script>
		<script type="text/javascript" src="js/jquery.backstretch.min.js"></script>
		<script type="text/javascript" src="slick/slick.min.js"></script> <!-- Slick Carousel -->

		<script>

		var sidebarVisible = false;
		var currentPageID = "#tm-section-1";

		// Setup Carousel
		function setupCarousel() {

			// If current page isn't Carousel page, don't do anything.
			if($('#tm-section-2').css('display') == "none") {
			}
			else {	// If current page is Carousel page, set up the Carousel.

				var slider = $('.tm-img-slider');
				var windowWidth = $(window).width();

				if (slider.hasClass('slick-initialized')) {
					slider.slick('destroy');
				}

				if(windowWidth < 640) {
					slider.slick({
	              		dots: true,
	              		infinite: false,
	              		slidesToShow: 1,
	              		slidesToScroll: 1
	              	});
				}
				else if(windowWidth < 992) {
					slider.slick({
	              		dots: true,
	              		infinite: false,
	              		slidesToShow: 2,
	              		slidesToScroll: 1
	              	});
				}
				else {
					// Slick carousel
	              	slider.slick({
	              		dots: true,
	              		infinite: false,
	              		slidesToShow: 3,
	              		slidesToScroll: 2
	              	});
				}

				// Init Magnific Popup
				$('.tm-img-slider').magnificPopup({
				  delegate: 'a', // child items selector, by clicking on it popup will open
				  type: 'image',
				  gallery: {enabled:true}
				  // other options
				});
      		}
  		}

  		// Setup Nav
  		function setupNav() {
  			// Add Event Listener to each Nav item
	     	$(".tm-main-nav a").click(function(e){
	     		e.preventDefault();
		    	
		    	var currentNavItem = $(this);
		    	changePage(currentNavItem);
		    	
		    	setupCarousel();
		    	setupFooter();

		    	// Hide the nav on mobile
		    	$("#tmSideBar").removeClass("show");
		    });	    
  		}

  		function changePage(currentNavItem) {
  			// Update Nav items
  			$(".tm-main-nav a").removeClass("active");
     		currentNavItem.addClass("active");

	    	$(currentPageID).hide();

	    	// Show current page
	    	currentPageID = currentNavItem.data("page");
	    	$(currentPageID).fadeIn(1000);

	    	// Change background image
	    	var bgImg = currentNavItem.data("bgImg");
	    	$.backstretch("img/" + bgImg);		    	
  		}

  		// Setup Nav Toggle Button
  		function setupNavToggle() {

			$("#tmMainNavToggle").on("click", function(){
				$(".sidebar").toggleClass("show");
			});
  		}

  		// If there is enough room, stick the footer at the bottom of page content.
  		// If not, place it after the page content
  		function setupFooter() {
  			
  			var padding = 100;
  			var footerPadding = 40;
  			var mainContent = $("section"+currentPageID);
  			var mainContentHeight = mainContent.outerHeight(true);
  			var footer = $(".footer-link");
  			var footerHeight = footer.outerHeight(true);
  			var totalPageHeight = mainContentHeight + footerHeight + footerPadding + padding;
  			var windowHeight = $(window).height();		

  			if(totalPageHeight > windowHeight){
  				$(".tm-content").css("margin-bottom", footerHeight + footerPadding + "px");
  				footer.css("bottom", footerHeight + "px");  			
  			}
  			else {
  				$(".tm-content").css("margin-bottom", "0");
  				footer.css("bottom", "20px");  				
  			}  			
  		}

  		// Everything is loaded including images.
      	$(window).on("load", function(){

      		// Render the page on modern browser only.
      		if(renderPage) {
				// Remove loader
		      	$('body').addClass('loaded');

		      	// Page transition
		      	var allPages = $(".tm-section");

		      	// Handle click of "Continue", which changes to next page
		      	// The link contains data-nav-link attribute, which holds the nav item ID
		      	// Nav item ID is then used to access and trigger click on the corresponding nav item
		      	var linkToAnotherPage = $("a.tm-btn[data-nav-link]");
			    
			    if(linkToAnotherPage != null) {
			    	
			    	linkToAnotherPage.on("click", function(){
			    		var navItemToHighlight = linkToAnotherPage.data("navLink");
			    		$("a" + navItemToHighlight).click();
			    	});
			    }
		      	
		      	// Hide all pages
		      	allPages.hide();

		      	$("#tm-section-1").fadeIn();

		     	// Set up background first page
		     	var bgImg = $("#tmNavLink1").data("bgImg");
		     	
		     	$.backstretch("img/" + bgImg, {fade: 500});

		     	// Setup Carousel, Nav, and Nav Toggle
			    setupCarousel();
			    setupNav();
			    setupNavToggle();
			    setupFooter();

			    // Resize Carousel upon window resize
			    $(window).resize(function() {
			    	setupCarousel();
			    	setupFooter();
			    });
      		}	      	
		});

		</script>
	</body>

	<script>
		function eliminarAuto(){
			document.getElementById('forma').submit();
		}
	</script>	

</html>