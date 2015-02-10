<div class="wrap" style="width:550px; padding-top:15px;">
    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
        <tr>
            <td style="width:90px; vertical-align:top;"><img src="<?php echo philantro_logo ?>" style="display:block;width:90px;min-height:90px"/></td>
            <td style="width:15px;">&nbsp;</td>
            <td style="vertical-align:middle">
                <p style="margin:0; padding:0 0 5px 0; line-height:35px; font-size:35px; font-weight:bold;">Philantro</p>
                <p style="color:#888; margin:0; font-size:14px; padding:0;">The All-Inclusive Donation Platform for Lean Nonprofits</p>
            </td>
        </tr>
    </table>
<hr style="padding:0; margin:20px 0; border-bottom: 1px solid #F4F4F4; border-top: 1px solid #DDDDDD; border-right:none; border-left:none; height:0px;"/>
    <?php if($_GET['settings-updated']):?>
    <div style="
    padding: 10px;
    background-color: #EBF4CA;
    border: 1px solid #B9D098;
    margin-bottom: 20px;
    font-size: 13px;
    color: #889D6E;
">Your EIN has been updated successfully.</div>
    <?php endif; ?>
    <?php
    function full_path()
    {
        $s = &$_SERVER;
        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
        $sp = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        $port = $s['SERVER_PORT'];
        $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
        $host = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
        $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
        $uri = $protocol . '://' . $host . $s['REQUEST_URI'];
        $segments = explode('?', $uri, 2);
        $url = $segments[0];
        return $url;
    }
    ?>
    <form method="post" action="options.php" style="padding-top:10px; padding-bottom:10px;">
        <?php wp_nonce_field('update-options'); ?>
        <?php settings_fields('philantro'); ?>

        <table cellpadding="0" cellspacing="0" border="0">

            <tr valign="top">
                <td  style="width:200px;">
                    <b style="font-size:14px;">Organization's EIN:</b>
                    <p style="font-size:12px; color:#999; margin:0; padding:0;">
                        9 Digit Tax-ID (ex 462820531)
                    </p>
                </td>
                <td><input type="text" max-length="9" name="EIN" value="<?php echo get_option('EIN'); ?>" style="
    border: 1px solid #ccc;
    padding: 4px;
    border-radius: 3px;
    font-size: 14px;
    color: #888;
    text-indent: 5px;
    line-height: 14px;
    margin: 0;
    vertical-align: top;"/></td>
                <td style="width:20px;">&nbsp;</td>
                <td style="width:100px;">
                    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                </td>
            </tr>

            </tr>

        </table>

        <input type="hidden" name="action" value="update" />


    </form>
    <hr style="padding:0; margin:20px 0; border-bottom: 1px solid #F4F4F4; border-top: 1px solid #DDDDDD; border-right:none; border-left:none; height:0px;"/>
    <p><b style="font-size:14px;">Open The Donation Form On Any Page</b></p>
    <p style="font-size:13px; color:#999; margin:0; padding:0;">
        Add the following suffix to any URL on your website to pull up your donation form.
    </p>

    <div style="padding: 10px;
background-color: #fff;
border-radius: 4px;
border: 1px solid #dedede;
margin-top:15px; margin-bottom:15px;
"><span style="font-size:13px; color:#bbb; margin:10px 0 0 0; padding:0;" id="org_website">http://www.youwebsite.com/</span>
        <code style="font-size: 90%;
        display:inline-block;
color: #c7254e;
background-color: #f9f2f4;
white-space: nowrap;
border-radius: 4px;">
            #_givealways
        </code>
    </div>
    <a href="#_givealways" class="button-primary">Test Philantro Setup</a>
    <hr style="padding:0; margin:20px 0; border-bottom: 1px solid #F4F4F4; border-top: 1px solid #DDDDDD; border-right:none; border-left:none; height:0px;"/>
    <p><b style="font-size:14px;">Links For Specific Campaign Forms</b></p>
    <p style="font-size:13px; color:#999; margin:0 0 15px 0; padding:0;">
       Not only does Philantro allow you to have a donation form where your donors can select from your campaigns, you can also link to specific ones.
       For instance, if you want to share a link to the "Capital Campaign", you can use a link that opens the "Capital Campaign" donation form. Create campaigns from your <a style="color:#4380A5;" href="https://www.philantro.com/sign-in">nonprofit dashboard</a>.
    </p>
    <div id="campaign_links" style="padding-top:20px;">
        <div style="padding: 28px 10px;
background-color: #FFECCF;
border-radius: 4px;
border: 1px dotted #CFB190;
color: #B6894E;">Campaigns Links will show here
        </div>
    </div>

    <hr style="padding:0; margin:20px 0; border-bottom: 1px solid #F4F4F4; border-top: 1px solid #DDDDDD; border-right:none; border-left:none; height:0px;"/>
    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
        <tr>
        <td>
            <p style="color:#999; margin:0; font-size:13px; padding:0;">Access your nonprofit dashboard anytime at <a style="color:#4380A5;" href="https://www.philantro.com/sign-in">Philantro.com</a></p>
        </td>
        </tr>
        </table>
</div>
