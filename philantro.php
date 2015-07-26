<?php
/**
 * Plugin Name: Philantro
 * Plugin URI: http://www.philantro.com
 * Description: <strong>Philantro is a better way to accept donations.</strong><br/> To use Philantro, first <a href="https://www.philantro.com/sign-up">create a Philantro account</a>. Once you've logged in and completed your profile, you can begin accepting donations and selling event tickets with powerful analytics, two-day deposits and flexible reporting.
 * Version: 1.5.0
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



class Philantro_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'philantro', // Base ID
            __( 'Philantro Donate Button', 'text_domain' ), // Name
            array( 'description' => __( 'Add a donate button to your sidebar.', 'text_domain' ), ) // Args
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
        }
        // Use shortcodes in form like Landing Page Template.

        if($instance['campaign_ID']): $campaign_shortcode = 'id="' . $instance['campaign_ID']. '"'; else: $campaign_shortcode = null; endif;


        echo do_shortcode( '[donate ' . $campaign_shortcode . 'label="'. $instance['label']  .'"  color="'. $instance['color']  .'"]' );
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $label = ! empty( $instance['label'] ) ? $instance['label'] : __( 'Donate', 'text_domain' );
        $color = ! empty( $instance['color'] ) ? $instance['color'] : __( '#3277A2', 'text_domain' );
        $campaign_ID = ! empty( $instance['campaign_ID'] ) ? $instance['campaign_ID'] : __( '', 'text_domain' );
        ?>

        <p style="color:#999; border-bottom:1px dotted #eaeaea; padding-bottom:20px;">Below you'll find a few options to help customize your donation button. Don't forget to save your changes.</p>


        <p>
            <label style="padding-bottom:8px; display: block;" for="<?php echo $this->get_field_id( 'color' ); ?>"><?php _e( 'Button Color:' ); ?></label>
            <span style="display:block; position:relative" id="color-shield">
            <input class="widefat color-selectored" id="<?php echo $this->get_field_id( 'color' ); ?>" name="<?php echo $this->get_field_name( 'color' ); ?>" type="text" value="<?php echo esc_attr( $color ); ?>">
            </span>
        </p>


        <p>
            <label style="padding-bottom:8px; display: block;" for="<?php echo $this->get_field_id( 'label' ); ?>"><?php _e( 'Button Text:' ); ?></label>
            <input class="widefat button-text" id="<?php echo $this->get_field_id( 'label' ); ?>" name="<?php echo $this->get_field_name( 'label' ); ?>" type="text" value="<?php echo esc_attr( $label ); ?>">


            <a href="#_<?php if(!$campaign_ID): echo 'givealways'; else: echo $campaign_ID; endif; ?>" class="philantro-btn" style="background-color: <?php echo $color ?>; display: block;
            padding: 17px;
            text-align: center;
            border-radius: 30px;
            font-size: 16.25px;
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            text-indent: 45px;
            text-decoration: none;
            min-height: 20px;
            color: rgb(255, 255, 255);
            max-height: 62px;
            min-width: 150px;
            background-image: url(https://www.philantro.com/css/images/security-confirm.png);
            background-position: 0% 50%;
            background-repeat: no-repeat;"><?php echo $label ?></a>

        </p>

        <div style="border-top:1px dotted #eaeaea; margin-top:30px; padding-top:10px;">
            <p style="color:#999;">To have the donate form open to a specific donation campaign, enter the Campaign ID below.</p>
        </div>
        <p>
            <label style="padding-bottom:8px; display: block;" for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Campaign ID:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'campaign_ID' ); ?>" name="<?php echo $this->get_field_name( 'campaign_ID' ); ?>" type="text" value="<?php echo esc_attr( $campaign_ID ); ?>">
        </p>

    <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['color'] = ( ! empty( $new_instance['color'] ) ) ? strip_tags( $new_instance['color'] ) : '';
        $instance['label'] = ( ! empty( $new_instance['label'] ) ) ? strip_tags( $new_instance['label'] ) : '';
        $instance['campaign_ID'] = ( ! empty( $new_instance['campaign_ID'] ) ) ? strip_tags( $new_instance['campaign_ID'] ) : '';

        return $instance;
    }

}


function register_philantro_widget() {
    register_widget( 'Philantro_Widget' );
}
add_action( 'widgets_init', 'register_philantro_widget' );
add_filter( 'widget_text', 'do_shortcode' );


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

add_action( 'current_screen', 'thisScreen' );

function thisScreen() {

    $currentScreen = get_current_screen();

    if($currentScreen->id == 'widgets'){
        wp_enqueue_script( 'color_code_script', plugin_dir_url( __FILE__ ) . '/js/minicolours.js' );
        wp_enqueue_style( 'color_code_style', plugin_dir_url( __FILE__ ) . '/css/philantro.css' );
    }


    if( $currentScreen->id === "toplevel_page_philantro" ) {

        add_action( 'admin_head', 'admin_css' );

        function admin_css(){

            ?>
            <style>
                ul#adminmenu a.wp-has-current-submenu:after, ul#adminmenu>li.current>a.current:after{
                    border-right-color: #f3f3f3;
                }
                #wpcontent{
                    background-color: #f3f3f3;
                }
                .update-nag{
                    display: none;
                }
            </style>
        <?php
        }
    }
}

function load_colors(){ ?>
    <script type="text/javascript">

        function loadColor(){
            jQuery('.color-selectored').minicolors('destroy');


            jQuery('.color-selectored').minicolors({
                                        opacity: false,
                                        position: 'top right',
                                        defaultValue: '#3277A2',
                                        change: function(hex) {
                                            if(!hex){
                                                hex = '#3277a2';
                                            }
                                            jQuery(this).closest('p').nextAll().find('.philantro-btn').css('background-color',hex);
                                            jQuery(this).closest('div').nextAll().find('.philantro-btn').css('background-color',hex);
                                        }
            })
        }



    function removeP(){
        jQuery('.widget-content p').each(function() {
            var $this = jQuery(this);
            if($this.html().replace(/\s|&nbsp;/g, '').length == 0)
                $this.remove();
        });
    }

    jQuery("#widget-list div[id*='_philantro-'] .widget-top").css({'background-color':'#3277a2', 'color':'#fff'});

    removeP();
    loadColor();





    jQuery(document).ajaxComplete(function() {

        if(jQuery( ".color-selectored" ).length ) {
            removeP();
            loadColor();
        }

    });




    function modify_button(thisObj){
        var button_text = thisObj.val();

        if(!button_text){
            button_text = 'Donate';
        }

        jQuery(thisObj).next('.philantro-btn').text(button_text);
    }


    jQuery(document).on('keyup', '.button-text', function(){
        modify_button(jQuery(this));
    })
    jQuery(document).on('change', '.button-text', function(){
        modify_button(jQuery(this));
    })
    jQuery(document).on('click', '.button-text', function(){
        modify_button(jQuery(this));
    })
    jQuery(document).on("paste",".button-text", function() {
        modify_button(jQuery(this));
    });



    </script>


 <?php
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

    if(!preg_match('/#([a-fA-F0-9]{3}){1,2}\b/',$color)):
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
    add_action('admin_print_footer_scripts', 'load_colors' );
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



