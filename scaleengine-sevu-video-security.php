<?php
/**
 * @package Sevu
 * @version 1.3.0
 */

/*
Plugin Name: ScaleEngine Virtual Usher
Plugin URI: http://sevu.scaleengine.net/
Description: Embeds video player using the ScaleEngine Virtual Usher system
Author: ScaleEngine
Version: 1.3.0
Author URI: https://www.scaleengine.com/
*/
/*
function sevuStartSession() {
    if(!session_id()) {
        session_start();
    }
}
add_action('init', 'sevuStartSession');

*/

add_action('init', 'myStartSession', 1);
add_action('wp_logout', 'myEndSession');
add_action('wp_login', 'myEndSession');

function myStartSession() {
    if(!session_id()) {
        session_start();
    }
}

function myEndSession() {
    session_destroy ();
}

$plugin_dir = plugin_dir_path(__FILE__);
include_once($plugin_dir.'sevu_options.php');
include_once($plugin_dir.'sevu_api.class.php');

/*
 * Requests a SEVU ticket to be created
 * If a SEVU Ticket already exists in the session the existing ticket will be returned
 * 
 * @param Bool ip_restrict - Restrict ticket to an IP Addres
 * @param String video - path/to/video.mp4, * to allow viewing of any video
 * @param String app_name - name of the application
 * @param Int uses - # of times ticket may be used, 0 for unlimited uses
 * @param String expire - ISO 8601 or RFC 2822 formatted date, or as a relative time string ('+X units')
 *
 *  @return SEVU Ticket Parameter String - Key and Password for the ticket in GET Param format key=x&pass=x
 */
function request_sevu_params($atts)
{
    extract(shortcode_atts(array(
            'ip_restrict' => true,
            'video' => '*',
            'app_name' => '',
            'num_uses' => 99,
            'expire' => date('Y-m-d H:i:s',strtotime('+24 hours')),
        ),$atts)
    );
    
    if(isset($_SESSION['sevu_tickets'][$app_name][$video]))
    {
        $ticket = $_SESSION['sevu_tickets'][$app_name][$video];
    }
    elseif($_SESSION['sevu_tickets'][$app_name]['*'])
    {
        $ticket =& $_SESSION['sevu_tickets'][$app_name]['*'];
    }
    else
    {
        $sevu = new SEVU_API_WP;
        $ticket =& $sevu->request_ticket($ip_restrict, $video, $app_name, $num_uses, $expire);
        if(!$ticket && get_option('sevu_show_errors'))
            return $sevu->message;
        
        $_SESSION['sevu_tickets'][$app_name][$video] = $ticket;
    }
    
    return "key={$ticket['key']}&pass={$ticket['pass']}";
}
add_shortcode('sevu_ticket','request_sevu_params');
?>
