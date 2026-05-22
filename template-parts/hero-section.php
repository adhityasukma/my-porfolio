<?php
/**
 * Template Part: Hero Section
 *
 * Displays the hero banner on the front page with background image,
 * gradient overlay, floating particle decorations, and CTA.
 *
 * @package WazTheme
 * @since 1.0.0
 */

$hero_title        = get_theme_mod( 'waztheme_hero_title', 'Crafting Digital Excellence' );
$hero_subtitle     = get_theme_mod( 'waztheme_hero_subtitle', 'We design and develop premium digital experiences that elevate brands and engage audiences worldwide.' );
$hero_cta_text     = get_theme_mod( 'waztheme_hero_cta_text', 'View Our Work' );
$hero_cta_url      = get_theme_mod( 'waztheme_hero_cta_url', '#portfolio' );
$hero_bg_image     = get_theme_mod( 'waztheme_hero_bg_image' );
$overlay_color     = get_theme_mod( 'waztheme_hero_overlay_color', '#8b5cf6' );
$overlay_opacity   = absint( get_theme_mod( 'waztheme_hero_overlay_opacity', 30 ) );

// Convert hex colour to RGB for rgba() usage.
$overlay_rgb = '';
if ( $overlay_color ) {
	$hex = ltrim( $overlay_color, '#' );
	if ( strlen( $hex ) === 3 ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	$overlay_rgb = hexdec( substr( $hex, 0, 2 ) ) . ', '
				 . hexdec( substr( $hex, 2, 2 ) ) . ', '
				 . hexdec( substr( $hex, 4, 2 ) );
}

// Build background image inline style.
$hero_bg_style = '';
if ( $hero_bg_image ) {
	$hero_bg_style = 'background-image: url(' . esc_url( $hero_bg_image ) . ');';
}

// Build overlay inline style.
$overlay_alpha = $overlay_opacity / 100;
$overlay_style = '';
if ( $overlay_rgb ) {
	$overlay_style = sprintf(
		'background: linear-gradient(135deg, rgba(%1$s, %2$s) 0%%, rgba(%1$s, %3$s) 100%%);',
		$overlay_rgb,
		number_format( $overlay_alpha, 2, '.', '' ),
		number_format( min( $overlay_alpha + 0.3, 1 ), 2, '.', '' )
	);
}
?>

<section class="waz-hero"<?php echo $hero_bg_style ? ' style="' . esc_attr( $hero_bg_style ) . '"' : ''; ?>>

	<?php if ( $overlay_style ) : ?>
		<div class="waz-hero__overlay" style="<?php echo esc_attr( $overlay_style ); ?>"></div>
	<?php endif; ?>

	<!-- Decorative floating particles -->
	<div class="waz-hero__particle waz-hero__particle--1" aria-hidden="true"></div>
	<div class="waz-hero__particle waz-hero__particle--2" aria-hidden="true"></div>
	<div class="waz-hero__particle waz-hero__particle--3" aria-hidden="true"></div>
	<div class="waz-hero__particle waz-hero__particle--4" aria-hidden="true"></div>

	<div class="waz-container">
		<div class="waz-hero__content">

			<?php if ( $hero_title ) : ?>
				<h1 class="waz-hero__title waz-animate">
					<?php echo esc_html( $hero_title ); ?>
				</h1>
			<?php endif; ?>

			<?php if ( $hero_subtitle ) : ?>
				<p class="waz-hero__subtitle waz-animate">
					<?php echo esc_html( $hero_subtitle ); ?>
				</p>
			<?php endif; ?>

			<?php if ( $hero_cta_text && $hero_cta_url ) : ?>
				<div class="waz-hero__cta waz-animate">
					<a href="<?php echo esc_url( $hero_cta_url ); ?>" class="waz-btn waz-btn--primary waz-btn--lg">
						<?php echo esc_html( $hero_cta_text ); ?>
						<svg class="waz-btn__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M5 12h14"></path>
							<path d="M12 5l7 7-7 7"></path>
						</svg>
					</a>
				</div>
			<?php endif; ?>

		</div>
	</div>

</section>
