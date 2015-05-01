<?php
/**
 * Plugin Name: Philantro
 * Plugin URI: http://www.philantro.com
 * Description: <strong>Philantro is a better way to accept donations.</strong><br/> To sign up for Philantro, first <a href="https://www.philantro.com/sign-up">create a Philantro account</a>. Once you've logged in and completed your profile, you can begin accepting donations and selling event tickets with powerful analytics, two-day deposits and flexible reporting.
 * Version: 1.2.7
 * Author: Philantro Inc.
 * Author URI: http://www.philantro.com
 * License: GPLv2
 */


define('philantro_logo', plugins_url('asset/philantro-logo.png', __FILE__ ));
define('philantro_icon', plugins_url('asset/wordpress-asset.png', __FILE__ ));
define('philantro_option_page', plugin_dir_path( __FILE__ ) . '/options.php');

function activate_philantro() {
    add_option('EIN', '462820531');
}

function deactive_philantro() {
    delete_option('EIN');
}

            function admin_init_philantro() {
                register_setting('philantro', 'EIN');
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
}

if (!is_admin()) {
    add_action('wp_footer', 'philantro');
}



