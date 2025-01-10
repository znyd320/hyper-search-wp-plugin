<?php

/**
 * BB Theme Compatibility.
 *
 * @package  header-footer-elementor
 */

use Panda\Header_Footer\Template_Loader;

/**
 * PHF_BB_Theme_Compat setup
 *
 * @since 1.0
 */
class PHF_BB_Theme_Compat
{

	/**
	 * Instance of PHF_BB_Theme_Compat
	 *
	 * @var PHF_BB_Theme_Compat|null
	 */
	private static $instance = null;

	/**
	 *  Initiator
	 *
	 * @return self
	 */
	// phpcs:ignore
	public static function instance(): self
	{
		if (! isset(self::$instance)) {
			self::$instance = new self();

			add_action('wp', [self::$instance, 'hooks']);
		}

		return self::$instance;
	}

	/**
	 * Run all the Actions / Filters.
	 * // phpcs:ignore
	 * @return void 
	 */
	// phpcs:ignore
	public function hooks(): void
	{
		if (phf_header_enabled()) {
			add_filter('fl_header_enabled', '__return_false');
			add_action('fl_before_header', [$this, 'get_header_content']);
		}

		if (phf_before_footer_enabled()) {
			add_action('fl_after_content', 'phf_render_before_footer', 10);
		}

		if (phf_footer_enabled()) {
			add_filter('fl_footer_enabled', '__return_false');
			add_action('fl_after_content', [$this, 'get_footer_content']);
		}
	}

	/**
	 * Display header markup for beaver builder theme.
	 * // phpcs:ignore
	 * @return void
	 */
	// phpcs:ignore
	public function get_header_content(): void
	{
		$header_layout = FLTheme::get_setting('fl-header-layout');

		if ('none' == $header_layout || is_page_template('tpl-no-header-footer.php')) {
			return;
		}

?>

		<header id="masthead" itemscope="itemscope" itemtype="https://schema.org/WPHeader">
			<p class="main-title bhf-hidden" itemprop="headline"><a href="<?php echo bloginfo('url'); ?>"
					title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>"
					rel="home"><?php bloginfo('name'); ?></a></p>
			<?php
			$instance = Template_Loader::get_instance();
			$instance->render_header();
			?>
		</header>

	<?php
	}

	/**
	 * Display footer markup for beaver builder theme.
	 * // phpcs:ignore
	 * @return void
	 */
	// phpcs:ignore
	public function get_footer_content(): void
	{
		if (is_page_template('tpl-no-header-footer.php')) {
			return;
		}

	?>
		<footer itemscope="itemscope" itemtype="https://schema.org/WPFooter">
			<?php
			$instance = Template_Loader::get_instance();
			$instance->render_footer();
			?>
		</footer>
<?php
	}
}

PHF_BB_Theme_Compat::instance();
