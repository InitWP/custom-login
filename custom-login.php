<?php
/**
 * Show custom login form with custom error messages
 *
 * @return string The obfuscated email address.
 *
 * Template usage: 
<div class="login">
	<div class="login--error">
		<?php
		if( isset( $_GET['login'] ) && $_GET['login'] == 'failed' ) {
			_e('Het inloggen is niet gelukt. Probeer het alstublieft nogmaals.', 'reiskoffer');
		}
		else if( isset( $_GET['login'] ) && $_GET['login'] == 'empty' ) {
			_e('Vul zowel het e-mailadres als de persoonlijke code in alstublief.', 'reiskoffer');
		} ?>
	</div>
	<div class="login--form">
		<?php echo reiskoffer_custom_login_form(); ?>
	</div>
</div>
*/

function reiskoffer_custom_login_form() {
	//echo do_shortcode("[members_login_form /]");
	$args = array(
		'echo'			 => false,
		'label_username' => __('E-mailadres', 'reiskoffer'),
		'label_password' => __('Persoonlijke code', 'reiskoffer'),
		'remember'		 => false
	);

	return wp_login_form($args);
}

/**
 * Redirect to custom login page (homepage) when login fails
 *
 */
function reiskoffer_frontend_login_fail( $username ) {
	if(isset($_SERVER['HTTP_REFERER'])) {
 	   $referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
    }
   // if there's a valid referrer, and it's not the default log-in screen
   if ( !empty($referrer) && !strstr($referrer, 'wp-login') && !strstr($referrer, 'wp-admin') ) {
      wp_redirect( $referrer . '?login=failed' );  // let's append some information (login=failed) to the URL for the theme to use
      exit;
  } else {
	  wp_redirect(home_url());
	  exit();
  }
}
add_action( 'wp_login_failed', 'reiskoffer_frontend_login_fail' );  // hook failed login

/**
 * Function Name: check_username_password.
 * Description: This redirects to the custom login page if user name or password is   empty with a modified url
**/
function reiskoffer_check_username_password( $login, $username, $password ) {
	if(isset($_SERVER['HTTP_REFERER'])) {
		$referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
	}
	// if there's a valid referrer, and it's not the default log-in screen
	if ( !empty($referrer) && !strstr($referrer, 'wp-login') && !strstr($referrer, 'wp-admin') ) {
	    if( $username == "" || $password == "" ){
	        wp_redirect( $referrer . '?login=empty' );  // let's append some information (login=empty) to the URL for the theme to use
	        exit;
	    }
	}
	else {
  	  wp_redirect(home_url());
  	  exit();
    }
}
add_action( 'authenticate', 'reiskoffer_check_username_password', 1, 3);
