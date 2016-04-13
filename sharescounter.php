<?php

/*
Plugin Name: WPShare Counter
Plugin URI: https://github.com/phalkmin/WPShare-Counter
Description: This plugin allows the user to display the amount of times that an URL have been shared on different social networks. Right now it supports Facebook, Twitter, Google Plus and LinkedIn. (based from GusFune's work https://github.com/gusfune/shares-counter)
Author: GraveHeart
Version: 0.9

*/

function instalar_o_trem() {
	add_option('scfacebook', 'true');
	add_option('scgplus', 'true');
	add_option('sclinkedin', 'true');
}

//incluindo funções no menu
function cria_menu_sc() {
      add_options_page(
                       'Share Counter',         //Title
                       'Share Counter',         //Sub-menu title
                       'manage_options', //Security
                       __FILE__,         //File to open
                       'sharecounter_code_options'  //Function to call
                      );
}

function sharecounter_code_options() {
      echo '<div class="wrap"><h2>Share Counter Options</h2>';
	if ($_REQUEST['submit']) {
		update_sharecounter_options();
	        }
		print_sharecounter_form();
     echo '</div>';
}

function print_sharecounter_form () {
	$scfacebook = get_option('scfacebook');
	$scgplus = get_option('scgplus');
	$sclinkedin = get_option('sclinkedin');

	if ($scfacebook == "true") { $fbtrue = "checked"; } else { $fbfalse = "checked"; }
	if ($scgplus == "true") { $gptrue = "checked"; } else { $gpfalse = "checked"; }
	if ($sclinkedin == "true") { $lktrue = "checked"; } else { $lkfalse = "checked"; }

	echo "<form method=\"post\">";
	echo "<label>Facebook:</label><br /><input type=\"radio\" name=\"scfacebook\" value=\"true\" " . $fbtrue . "> Count <input type=\"radio\" name=\"scfacebook\" value=\"false\" " . $fbfalse . "> Don't Count <br /><br />";
	echo "<label>Google Plus:</label><br /><input type=\"radio\" name=\"scgplus\" value=\"true\" " . $gptrue . "> Count <input type=\"radio\" name=\"scgplus\" value=\"false\" " . $gpfalse . " > Don't Count<br /><br />";
	echo "<label>Linkedin:</label><br /><input type=\"radio\" name=\"sclinkedin\" value=\"true\" " . $lktrue . "> Count <input type=\"radio\" name=\"sclinkedin\" value=\"false\" " . $lkfalse . " > Don't Count<br /><br />";

	echo "<br /> <input type=\"submit\" name=\"submit\" value=\"Submit\" />  </form>";

 }

 function update_sharecounter_options() {

	update_option('scfacebook', $_REQUEST['scfacebook']);
	update_option('scgplus', $_REQUEST['scgplus']);
	update_option('sclinkedin', $_REQUEST['sclinkedin']);


            echo '<div id="message" class="updated fade">';
            echo '<p>Updated</p>';
            echo '</div>';

  }

function sharesCounter($echo = true) {
	$shares = 0;
	$scfacebook = get_option('scfacebook');
	$sclinkedin = get_option('sclinkedin');
	$scgplus = get_option('scgplus');
	$url = get_permalink();

	if ( $scfacebook ) {
		$url_fb = "https://api.facebook.com/restserver.php?method=links.getStats&urls=" . $url;
		$data_fb = simplexml_load_file($url_fb);
		if ( isset($data_fb->link_stat->share_count) ) {
			$shares = $shares + $data_fb->link_stat->share_count;
		}
	}

	if ( $scgplus ) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		$curl_results = curl_exec ($curl);
		curl_close ($curl);
		$json = json_decode($curl_results, true);

		if ( isset($json) && isset($json[0]['result']['metadata']['globalCounts']['count']) ) {
			$result = intval($json[0]['result']['metadata']['globalCounts']['count']);
			$shares = $shares + $result;
		}
	}

	if ( $sclinkedin ) {
		$url_ln = "http://www.linkedin.com/countserv/count/share?url=" . $url . "&format=json";
		if ( isset($data_ln->count) ) {
			$shares = $shares + $data_ln->count;
		}
	}
	if ( $echo ) {
		echo intval($shares);
	} else {
		return intval($shares);
	}
}

register_activation_hook( __FILE__, 'instalar_o_trem' );
add_action('admin_menu','cria_menu_sc');

?>