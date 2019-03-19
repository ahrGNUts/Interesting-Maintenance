/*
	Handles help modal behavior on wp-admin settings page.
	
	@since 0.2
	@author Viljami Kuosmanen (code adapted from here: https://gist.github.com/anttiviljami/3cdefd6b5556d80426e66f131a42bef1)
*/
// TODO: modal should resize with window similar to bootstrap modal
jQuery( document ).ready( function( $ ) {
	$('#help-modal').dialog({
    title: 'Sketch Types',
    dialogClass: 'wp-dialog',
    autoOpen: false,
    draggable: false,
    width: 'auto',
    modal: true,
    resizable: false,
    closeOnEscape: true,
    position: {
      my: "center",
      at: "center",
      of: window
    },
    open: function () {
      // close dialog by clicking the overlay behind it
      $('.ui-widget-overlay').bind('click', function(){
        $('#help-modal').dialog('close');
      })
    },
    create: function () {
      // style fix for WordPress admin
      $('.ui-dialog-titlebar-close').addClass('ui-button');
    },
  });
  
  $('#sketch_help').click(function(e) {
  	e.preventDefault();
  	$('#help-modal').dialog('open');
  });	
});
