<?php
/**
 * Plugin Name: Philantro
 * Plugin URI: http://www.philantro.com
 * Description: <strong>Philantro is a better way to accept donations.</strong><br/> To use Philantro, first <a href="https://www.philantro.com/sign-up">create a Philantro account</a>. Once you've logged in and completed your profile, you can begin accepting donations and selling event tickets with powerful analytics, two-day deposits and flexible reporting.
 * Version: 1.3.1
 * Author: Philantro Inc.
 * Author URI: http://www.philantro.com
 * License: GPLv2
 */


define('philantro_logo', plugins_url('asset/philantro-logo.png', __FILE__ ));
define('philantro_icon', plugins_url('asset/wordpress-asset.png', __FILE__ ));
define('philantro_option_page', plugin_dir_path( __FILE__ ) . '/options.php');

function activate_philantro() {
    add_option('EIN', '000000000');
}

function deactive_philantro() {
    delete_option('EIN');
}

function admin_init_philantro() {
    register_setting('philantro', 'EIN');
}



class philantro_button {

    public function __construct() {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
            return;
        if (get_user_option('rich_editing') == 'true') {
            add_filter('mce_buttons', array($this, 'register_philantro_button'));
            add_filter('mce_external_plugins', array($this, 'add_philantro_tinymce_plugin'));
            add_filter('tiny_mce_version', array($this, 'refresh_mce'));
        }
    }

    function register_philantro_button($buttons) {
        array_push($buttons, "|", "philantro");
        return $buttons;
    }

    function add_philantro_tinymce_plugin($plugin_array) {
        $plugin_array['philantro'] = plugins_url('/js/button.js', __FILE__ );
        return $plugin_array;
    }

    //Make TinyMCE check for added plugins
    function refresh_mce($version) {
        return ++$version;
    }
}







// Add Shortcode
function donate_shortcode( $atts ) {

    $id = 'null';
    $color = '#3277A2';
    $label = 'Donate';

    // Attributes
    extract( shortcode_atts(
            array(
                'label' => 'Donate',
                'id' => 'null',
                'color' => '#3277A2',
            ), $atts )
    );
    // Code

    $color = str_replace("#", "", $color);

    if(!preg_match('/^[a-f0-9]{6}$/i',$color)):
        $color = '#' .  $color;
    else:
        $color = '#3277A2';
    endif;

    if($id != 'null'):

        return '<a href="#_'. $id  .'" style="background-color:'.  $color  .'" class="philantro-btn">'. $label  .'</a>';

    else:

        return '<a href="#_givealways" style="background-color:'.  $color  .'" class="philantro-btn">'. $label  .'</a>';

    endif;
}



// Add Shortcode
function event_shortcode( $atts ) {

    $id = 'null';
    $color = '#3277A2';
    $label = 'Purchase Tickets';

    // Attributes
    extract( shortcode_atts(
            array(
                'label' => 'Purchase Tickets',
                'id' => 'null',
                'color' => '#3277A2',
            ), $atts )
    );
    // Code

    $color = str_replace("#", "", $color);

    if(!preg_match('/^[a-f0-9]{6}$/i',$color)):
        $color = '#' .  $color;
    else:
        $color = '#3277A2';
    endif;

    if($id != 'null'):

        return '<a href="#_event-'. $id .'" style="background-color:'.  $color  .'" class="philantro-btn">'. $label  .'</a>';

    else:

        return '<a href="#_givealways" style="background-color:'.  $color  .'" class="philantro-btn">'. $label  .'</a>';

    endif;
}




function admin_menu_philantro() {
    add_menu_page('Philantro', 'Philantro', 'manage_options', 'philantro', 'options_page_philantro', philantro_icon);
}

function options_page_philantro() {
    include(philantro_option_page);
}

function philantro() {
    $EIN = get_option('EIN');
    ?>
    <script type="text/javascript">
        (function() {
            var URI = window.location.href;
            var s = document.createElement('script');
            var ph = document.getElementsByTagName('script')[0];
            s.type = "text/javascript";
            s.src = "//s3-us-west-2.amazonaws.com/philantro/pdf/philantro.js";
            s.async = true;
            window.options = { EIN: '<?php echo $EIN ?>', Referrer: URI};
            ph.parentNode.insertBefore(s, ph);
        })();
    </script>
<?php
}

function load_campaigns() {
    $EIN = get_option('EIN');

    if($EIN):

        $current_user =  wp_get_current_user();
        $plugin_data = get_plugin_data(plugin_dir_path( __FILE__ ) . '/philantro.php');
        $currentScreen = get_current_screen();

        if($currentScreen->parent_file == 'philantro'):

            ?>

            <script type="text/javascript">

                setTimeout(function(){
                    jQuery('#philantro-notification').fadeOut();
                }, 3000);


                var person = {
                    first_name:'<?php echo $current_user->user_firstname; ?>',
                    last_name:'<?php echo $current_user->user_lastname; ?>',
                    email:'<?php echo $current_user->user_email; ?>',
                    url: '<?php echo get_site_url(); ?>',
                    plugin_version: '<?php echo $plugin_data['Version'] ?>'
                };


                jQuery(document).ready(function() {
                    get_links();
                })

                function get_links() {
                    jQuery.ajax({
                        url: "https://www.philantro.com/api/external.php",
                        jsonp: "callback",
                        type: "POST",
                        dataType: "jsonp",
                        data: {
                            EIN: <?php echo $EIN ?>,
                            person: person
                        },
                        success: function( response ) {
                            jQuery('#org_website').html(response.website);
                            if(response.links){
                                jQuery('#campaign_links').html('');
                                jQuery.each(response.links, function(i, item) {
                                    jQuery('#campaign_links').append(
                                        '<p style="margin:0; padding:0 0 10px 0; position:relative;"><b>'+ item['campaign_name']  +'</b><span style="display:inline-block; position:absolute; right: 10px; font-size:12px;"><a href="#_'+ item['campaign_ID']  +'">Try Campaign Link</a></span></p>' +
                                            '<div style="padding: 10px;background-color: #fff; border-radius: 4px;border: 1px solid #dedede;margin-bottom:25px;">' +
                                            '<span style="font-size:13px; color:#bbb; margin:10px 0 0 0; padding:0;">'+ response.website +'</span><code style="display:inline-block; font-size: 90%;color: #c7254e; background-color: #f9f2f4;white-space: nowrap;border-radius: 4px;">#_'+item['campaign_ID']+'</code>' +
                                            '</div>');

                                });
                            }
                        }
                    });
                }
            </script>
        <?php endif; endif;
}

register_activation_hook(__FILE__, 'activate_philantro');
register_deactivation_hook(__FILE__, 'deactive_philantro');

if (is_admin()) {
    add_action('admin_init', 'admin_init_philantro');
    add_action('admin_menu', 'admin_menu_philantro');
    add_action('admin_print_footer_scripts', 'load_campaigns' );
    add_action('admin_print_footer_scripts', 'philantro' );
    add_shortcode( 'donate', 'donate_shortcode' );
    add_shortcode( 'event', 'event_shortcode' );
//Add the button during admin init.
    add_action('init', 'create_philantro_button');

    function create_philantro_button(){
        new philantro_button();
    }
}

if (!is_admin()) {
    add_action('wp_footer', 'philantro');
    add_shortcode( 'donate', 'donate_shortcode' );
    add_shortcode( 'event', 'event_shortcode' );
}



