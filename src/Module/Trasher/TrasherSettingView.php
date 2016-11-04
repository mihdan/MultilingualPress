<?php # -*- coding: utf-8 -*-

namespace Inpsyde\MultilingualPress\Module\Trasher;

use Inpsyde\MultilingualPress\Common\Nonce\Nonce;

/**
 * Trasher setting view.
 *
 * @package Inpsyde\MultilingualPress\Module\Trasher
 * @since   3.0.0
 */
class TrasherSettingView {

	/**
	 * @var Nonce
	 */
	private $nonce;

	/**
	 * @var TrasherSettingRepository
	 */
	private $setting_repository;

	/**
	 * Constructor. Sets up the properties.
	 *
	 * @since 3.0.0
	 *
	 * @param TrasherSettingRepository $setting_repository Trasher setting repository object.
	 * @param Nonce                    $nonce              Nonce object.
	 */
	public function __construct( TrasherSettingRepository $setting_repository, Nonce $nonce ) {

		$this->setting_repository = $setting_repository;

		$this->nonce = $nonce;
	}

	/**
	 * Renders the setting markup.
	 *
	 * @since   3.0.0
	 * @wp-hook post_submitbox_misc_actions
	 *
	 * @return void
	 */
	public function render() {

		$id = 'trasher';
		?>
		<div class="misc-pub-section curtime misc-pub-section-last">
			<?php echo \Inpsyde\MultilingualPress\nonce_field( $this->nonce ); ?>
			<label for="<?php echo esc_attr( $id ); ?>">
				<input type="checkbox" name="<?php echo esc_attr( TrasherSettingRepository::META_KEY ); ?>"
					value="1" id="<?php echo esc_attr( $id ); ?>" <?php checked( $this->setting_repository->get() ); ?>>
				<?php _e( 'Send all the translations to trash when this post is trashed.', 'multilingual-press' ); ?>
			</label>
		</div>
		<?php
	}
}
