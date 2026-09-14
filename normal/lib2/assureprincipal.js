$(function() 
{
	function log(message){
		$( "<div>" ).text( message ).prependTo( "#log" );
		$( "#log" ).scrollTop( 0 );
		}
		
		$( "#assure_principal" ).autocomplete({
		source: "assureprincipal.php",
		minLength:3,
		select: function( event, ui ) {
		log( ui.item ?
		"Selected: " + ui.item.value + " aka " + ui.item.id :
		"Nothing selected, input was " + this.value );
		}
	});
});
