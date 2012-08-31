( function( $ ){
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '#logo' ).html( to + '<span class="g"></span>' );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '#slogan' ).html( to );
		} );
	} );
	wp.customize( 'progo_options[headline]', function( value ) {
		value.bind( function( to ) {
			var st = to.replace( '|','<br />');
			$( '.pbpform .tar td' ).html( st );
		} );
	} );
} )( jQuery );