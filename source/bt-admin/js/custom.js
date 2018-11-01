$(document).ready(function() {

	$( "#body-overlay, .navbar-nav" ).click(function() {
	  if ($( "#wrapper" ).hasClass( "sidebar" ))
	  {
		  $("#wrapper" ).removeClass( "sidebar" );
		  $("#body-overlay" ).hide();
		  
	  }
	});

	$( "#sidebar-exp" ).click(function() {
		
	  if ($( "#wrapper" ).hasClass( "sidebar" ))
	  {
		  $("#wrapper" ).removeClass( "sidebar" );
		  $("#body-overlay" ).hide();

		  if ($( ".user-dropdown-one" ).hasClass( "showsumbmenu" ))
		  {
			  $(".user-dropdown-one" ).removeClass( "showsumbmenu" );
		  }


	  }
	  else
	  {
		  if ($( ".user-dropdown-one" ).hasClass( "showsumbmenu" ))
		  {
			  $(".user-dropdown-one" ).removeClass( "showsumbmenu" );
		  }
		  $("#wrapper" ).addClass( "sidebar" );
		  $("#body-overlay" ).show();
	  }
	});
	
	$( "#sidebar ul li a.groupMenu" ).click(function() {
		if (!$(this).parent("li").hasClass( "selected" ))
	    {
		  $("#sidebar ul li").removeClass("selected").children("ul").hide();
		  $(this).parent("li").addClass( "selected" ).children("ul").fadeIn();
		}
		else
		{
			$(this).parent("li").removeClass("selected").children("ul").fadeOut();
		}
	});
	$( "#dropdown-usuario" ).click(function() {
		//$( "#submenu_usuario" ).toggle();
		$(this).parent(".user-dropdown-one").toggleClass("showsumbmenu");
	});

});

