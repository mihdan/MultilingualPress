<?php # -*- coding: utf-8 -*-

/**
 * Send headers for alternative language representations.
 *
 * @link https://support.google.com/webmasters/answer/189077?hl=en
 */
class Mlp_Hreflang_Header_Output {

	/**
	 * @var Mlp_Language_Api_Interface
	 */
	private $language_api;

	/**
	 * @var string[]
	 */
	private $translations;

	/**
	 * Constructor. Sets up the properties.
	 *
	 * @param Mlp_Language_Api_Interface $language_api Language API object.
	 */
	public function __construct( Mlp_Language_Api_Interface $language_api ) {

		$this->language_api = $language_api;
	}

	/**
	 * Renders language attributes into the HTML head.
	 *
	 * @wp-hook wp_head
	 *
	 * @return void
	 */
	public function wp_head() {

		if ( is_paged() ) {
			return;
		}

		$translations = $this->get_translations();
		if ( ! $translations ) {
			return;
		}

		foreach ( $translations as $lang => $url ) {
			$html = sprintf(
				'<link rel="alternate" hreflang="%1$s" href="%2$s">',
				esc_attr( $lang ),
				esc_url( $url )
			);

			/**
			 * Filters the output of the hreflang links in the HTML head.
			 *
			 * @param string $html Markup generated by MultilingualPress.
			 * @param string $lang Language code (e.g., 'en-US').
			 * @param string $url  Target URL.
			 */
			echo apply_filters( 'mlp_hreflang_html', $html, $lang, $url );
		}
	}

	/**
	 * Adds language attributes to the HTTP header.
	 *
	 * @wp-hook template_redirect
	 *
	 * @return void
	 */
	public function http_header() {

		if ( is_paged() ) {
			return;
		}

		$translations = $this->get_translations();
		if ( ! $translations ) {
			return;
		}

		foreach ( $translations as $lang => $url ) {
			$header = sprintf(
				'Link: <%1$s>; rel="alternate"; hreflang="%2$s"',
				esc_url( $url ),
				esc_attr( $lang )
			);

			/**
			 * Filters the output of the hreflang links in the HTTP header.
			 *
			 * @param string $header Header generated by MultilingualPress.
			 * @param string $lang   Language code (e.g., 'en-US').
			 * @param string $url    Target URL.
			 */
			$header = apply_filters( 'mlp_hreflang_http_header', $header, $lang, $url );
			if ( $header ) {
				header( $header, false );
			}
		}
	}

	/**
	 * Returns the translations and caches the result.
	 *
	 * @return string[]
	 */
	private function get_translations() {

		if ( isset( $this->translations ) ) {
			return $this->translations;
		}

		$this->translations = array();

		/** @var Mlp_Translation_Interface[] $translations */
		$translations = $this->language_api->get_translations( array(
			'include_base' => true,
		) );
		if ( ! $translations ) {
			return $this->translations;
		}

		foreach ( $translations as $translation ) {
			$url = $translation->get_remote_url();
			if ( $url ) {
				$language = $translation->get_language();

				if ( preg_match( '/(\?|&)noredirect=/', $url ) ) {
					$url = remove_query_arg( 'noredirect', $url );
				}

				$this->translations[ $language->get_name( 'http' ) ] = $url;
			}
		}

		/**
		 * Filters the available translations before outputting their hreflang links.
		 *
		 * @since 2.7.0
		 *
		 * @param string[] $translations The available translations for the current page.
		 */
		$this->translations = apply_filters( 'multilingualpress.hreflang_translations', $this->translations );

		return $this->translations;
	}
}
