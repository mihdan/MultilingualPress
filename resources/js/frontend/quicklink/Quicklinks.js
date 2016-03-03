/* global MultilingualPress */
(function( $ ) {
	'use strict';

	/**
	 * Constructor for the MultilingualPress Quicklinks module.
	 * @returns {{initialize: initialize}}
	 * @constructor
	 */
	var Quicklinks = function() {
		/**
		 * Redirects the user to the given URL.
		 * @param {string} url - The URL.
		 */
		var setLocation = function( url ) {
			window.location.href = url;
		};

		/**
		 * Triggers a redirect on form submission.
		 * @param {Event} event - The submit event of the form.
		 */
		var submitForm = function( event ) {
			var $select = $( event.target ).find( 'select' );
			if ( $select.length ) {
				event.preventDefault();

				setLocation( $select.val() );
			}
		};

		return {
			/**
			 * Initializes the module.
			 *
			 * @memberof Quicklinks
			 */
			initialize: function() {
				var $form = $( '#mlp-quicklink-form' );
				if ( $form.length ) {
					$form.on( 'submit', submitForm );
				}
			}
		};
	};

	/**
	 * The MultilingualPress Quicklinks instance.
	 * @memberof MultilingualPress.Modules
	 * @type {Quicklinks}
	 */
	MultilingualPress.Modules.Quicklinks = new Quicklinks();

	$( MultilingualPress.Modules.Quicklinks.initialize );
})( jQuery );
