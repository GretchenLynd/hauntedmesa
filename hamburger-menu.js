( function( window, $, undefined ) {
    'use strict';
    $( 'nav' ).before( '<button class="menu-toggle" role="button" aria-pressed="false"></button>' ); 
    $( 'nav .sub-menu' ).before( '<button class="sub-menu-toggle" role="button" aria-pressed="false"></button>' ); 
    $( '.menu-toggle, .sub-menu-toggle' ).on( 'click', function() {
        var $this = $( this );
        $this.attr( 'aria-pressed', function( index, value ) {
            return 'false' === value ? 'true' : 'false';
        });
        $this.toggleClass( 'activated' );
        $this.next( 'nav, .sub-menu' ).slideToggle( 'fast' );
    });
})( this, jQuery );