<?php
/*
 * Page Name: PRO FEATURES 🚀
 */

defined( 'ABSPATH' ) || exit;

$features = [

	[
		'icon'  => 'fa-solid fa-mouse-pointer',
		'bg'    => '#4EC477',
		'title' => __( 'Open by Hover', 'popup-box' ),
		'desc'  => __( 'Showcase your popup when users hover over a specific element.', 'popup-box' ),
	],
	[
		'icon'  => 'fa-solid fa-computer-mouse',
		'bg'    => '#F02E23',
		'title' => __( 'Right Click', 'popup-box' ),
		'desc'  => __( 'Trigger the popup upon a user\'s right-click action.', 'popup-box' ),
	],
	[
		'icon'  => 'fa-solid fa-highlighter',
		'bg'    => '#4A0988',
		'title' => __( 'Selected Text', 'popup-box' ),
		'desc'  => __( 'Prompt the popup to appear when a user selects text on your page.', 'popup-box' ),
	],
	[
		'icon'  => 'fa-solid fa-person-walking-dashed-line-arrow-right',
		'bg'    => '#353DA4',
		'title' => __( 'Exit Intent', 'popup-box' ),
		'desc'  => __( 'Capture visitors attention as they attempt to leave your website (ideal for exit-intent campaigns).', 'popup-box' ),
	],
	[
		'icon'  => 'fa-solid fa-arrow-up-right-from-square',
		'bg'    => '#353DA4',
		'title' => __( 'External Link', 'popup-box' ),
		'desc'  => __( 'Enable this option to automatically show a popup when a user clicks on an external link (a link leading to another website). Useful for warning users before leaving your site or showing custom messages. ', 'popup-box' ),
	],
	[
		'icon'  => 'fa-solid fa-check',
		'bg'    => '#C55FBD',
		'title' => __( 'Open/Close Custom Selectors', 'popup-box' ),
		'desc'  => __( '  Add custom selectors to open or close the popup, providing greater flexibility and control
                    over modal interactions.', 'popup-box' ),
	],

	[
		'icon'  => 'fa-solid fa-link',
		'bg'    => '#37C280',
		'title' => __( 'Activate by URL', 'popup-box' ),
		'desc'  => __( 'Target specific pages based on URL parameters (e.g., show a discount popup only on page with URL parameter).', 'popup-box' ),
	],
	[
		'icon'  => 'fa-solid fa-handshake-alt',
		'bg'    => '#EA392D',
		'title' => __( 'Activate by Referrer URL', 'popup-box' ),
		'desc'  => __( 'Tailor popup experiences for visitors arriving from particular websites (e.g., display a welcome message to users coming from a partner site).', 'popup-box' ),
	],
	[
		'icon'  => 'fa-solid fa-map-marked-alt',
		'bg'    => '#EF80AE',
		'title' => __( 'Geotargeting', 'popup-box' ),
		'desc'  => __( 'Take your popups to the next level with geotargeting functionality! This powerful plugin feature allows you to display targeted popups based on the country location of your website visitors.', 'popup-box' ),
	],
	[
		'icon'  => 'fa-solid fa-repeat',
		'bg'    => '#447FB0',
		'title' => __( 'Loop', 'popup-box' ),
		'desc'  => __( 'Display the popup multiple times with random intervals for sustained engagement.', 'popup-box' ),
	],
	[
		'icon'  => 'fa-solid fa-eye',
		'bg'    => '#E2672A',
		'title' => __( 'Show once per session', 'popup-box' ),
		'desc'  => __( 'Allows to display the popup only once during a user’s session. A session typically lasts until the browser is closed. Enabling this ensures the popup won’t repeatedly appear, enhancing user experience by avoiding redundant interruptions while maintaining visibility for new sessions.', 'popup-box' ),
	],
	[
		'icon'  => 'fa-solid fa-eye-slash',
		'bg'    => '#EEBF58',
		'title' => __( 'Forced Interaction', 'popup-box' ),
		'desc'  => __( 'Briefly disable the close button to ensure users see your crucial information or complete an action (use with caution).', 'popup-box' ),
	],
	[
		'icon'  => 'fa-solid fa-external-link',
		'bg'    => '#C260BD',
		'title' => __( 'Redirect on Close', 'popup-box' ),
		'desc'  => __( 'Optionally redirect visitors to another URL after closing the popup.', 'popup-box' ),
	],
	[
		'icon'  => 'fa-solid fa-window-close',
		'bg'    => '#62DCC5',
		'title' => __( 'Auto Close', 'popup-box' ),
		'desc'  => __( 'Set a delay to automatically close the popup after a certain amount of time.', 'popup-box' ),
	],
	[
		'icon'  => 'fa-solid fa-stopwatch-20',
		'bg'    => '#E9332B',
		'title' => __( 'Close Button Delay', 'popup-box' ),
		'desc'  => __( 'Set a delay before the close button appears on the popup, ensuring users have ample time to
                    view the content.', 'popup-box' ),
	],
	[
		'icon'  => 'fa-solid fa-share-from-square',
		'bg'    => '#62DCC5',
		'title' => __( 'Close After Form Submission', 'popup-box' ),
		'desc'  => __( 'Automatically close the modal after form submission, supporting default forms, Contact Form 7, and WP Forms.', 'popup-box' ),
	],

	[
		'icon'  => 'fa-solid fa-swatchbook',
		'bg'    => '#E98A7F',
		'title' => __( 'Animation Effects', 'popup-box' ),
		'desc'  => __( 'Choose from 28 different animation transitions to enhance your popup\'s visual appeal.', 'popup-box' ),
	],
	[
		'icon'  => 'fa-solid fa-play',
		'bg'    => '#B1556A',
		'title' => __( 'Video Support', 'popup-box' ),
		'desc'  => __( 'Seamlessly integrate videos into your popups for engaging multimedia presentations.', 'popup-box' ),
	],


	[
		'icon'  => 'fa-solid fa-display',
		'bg'    => '#ff9800',
		'title' => __( 'Comprehensive Display Controls', 'popup-box' ),
		'desc'  => __( 'Use post types, tags, categories, or archive pages to fine-tune where and when your popup appears. ', 'popup-box' ),
	],

	[
		'icon'  => 'fa-solid fa-tablet-screen-button',
		'bg'    => '#df6928',
		'title' => __( 'Device-Based Visibility', 'popup-box' ),
		'desc'  => __( 'Remove the popup entirely on mobile or desktop devices to optimize performance and layout per screen. ', 'popup-box' ),
	],

    [
		'icon'  => 'fa-solid fa-users',
		'bg'    => '#4CAF50',
		'title' => __( 'User Role Permissions', 'popup-box' ),
		'desc'  => __( 'Restrict popup visibility for specific user roles (e.g., Admins, Editors, Subscribers).', 'popup-box' ),
	],
	[
		'icon'  => 'fa-solid fa-language',
		'bg'    => '#673ab7',
		'title' => __( 'Multilingual Support', 'popup-box' ),
		'desc'  => __( 'Show popup based on language preferences. Compatible with WPML and Polylang.', 'popup-box' ),
	],
	[
		'icon'  => 'fa-solid fa-calendar-day',
		'bg'    => '#3f51b5',
		'title' => __( 'Scheduling', 'popup-box' ),
		'desc'  => __( 'Schedule popup appearances based on specific days, times, and dates. This allows you to promote temporary events or campaigns without cluttering your website permanently. ', 'popup-box' ),
	],

	[
		'icon'  => 'fa-brands fa-chrome',
		'bg'    => '#3f51b5',
		'title' => __( 'Browser Compatibility', 'popup-box' ),
		'desc'  => __( 'Ensure your popups display correctly across a wide range of browsers. If necessary, you can choose to hide popup for specific browsers to address compatibility issues with outdated software versions. ', 'popup-box' ),
	],

	[
		'icon'  => 'fa-solid fa-chart-line',
		'bg'    => '#3f51b5',
		'title' => __( 'Google Analytics Integration', 'popup-box' ),
		'desc'  => __( ' Track popup behavior directly in GA. ', 'popup-box' ),
	],

];

?>

<div class="wowp-pro-upgrade">
    <div>
        <h3>Unlock PRO Features</h3>
        <p>Upgrade to Popup Box Pro and get advanced features like</p>
        <a href="https://wow-estore.com/item/popup-box-pro/" target="_blank" class="button button-primary">Get Popup Box Pro </a>
    </div>
    <dl class="wowp-pro__profits">
        <div class="wowp-pro__profit">
            <dt><span class="wpie-icon wpie_icon-money-time"></span>No Yearly Fees</dt>
            <dd>One-time payment. Use it forever.</dd>
        </div>
        <div class="wowp-pro__profit">
            <dt><span class="wpie-icon wpie_icon-refund"></span>14-Day Money-Back Guarantee</dt>
            <dd>Try it risk-free. Get a full refund if you’re not satisfied.</dd>
        </div>
        <div class="wowp-pro__profit">
            <dt><span class="wpie-icon wpie_icon-cloud-data-sync"></span>Lifetime Free Updates</dt>
            <dd>Always stay up to date — at no extra cost.</dd>
        </div>
        <div class="wowp-pro__profit">
            <dt><span class="wpie-icon wpie_icon-customer-support"></span>Priority Support</dt>
            <dd>Fast, friendly, and expert help whenever you need it.</dd>
        </div>
    </dl>

</div>

<div class="wowp-pro-features">

	<?php foreach ( $features as $feature ) : ?>

		<?php if ( ! empty( $feature['icon'] ) ): ?>
            <div class="wowp-pro-feature">
                <div class="wowp-pro-feature__icon">
                    <span class="<?php echo esc_attr( $feature['icon'] ); ?>"></span>
                </div>
                <div class="wowp-pro-feature__content">
                    <div class="wowp-pro-feature__title">
						<?php echo esc_html( $feature['title'] ); ?>
                    </div>
                    <div class="wowp-pro-feature__desc">
						<?php echo esc_html( $feature['desc'] ); ?>
                    </div>
                </div>
            </div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
<?php
