(function() {



    tinymce.create('tinymce.plugins.Philantro', {
        init : function(ed, url) {


            var minicolour = url.replace('js', 'js/minicolours.js');
            var css = url.replace('js', 'css/philantro.css');
            jQuery.getScript(minicolour, function() {
                jQuery('head').append( jQuery('<link rel="stylesheet" type="text/css" />').attr('href', css ) );
                jQuery('#philantro-color-picker').css('left',0);
            });

            ed.addButton('philantro', {
                title : 'Insert Philantro Donate Button Shortcode',
                image : url.replace('js', 'asset/wordpress-asset.png'),
                id: 'Philantro-BTN',
                type: 'menubutton',
                menu: [
                    {
                        text: 'Donate Button',
                        onclick : function() {
                            ed.windowManager.open( {
                                title: 'Donate Button Options',
                                body: [{
                                        type: 'textbox',
                                        id: 'philantro-color-picker',
                                        name: 'color',
                                        label: 'Button Color'
                                    },
                                    {
                                        type: 'textbox',
                                        name: 'label',
                                        label: 'Button Text'
                                    },
                                ],
                                onsubmit: function( e ) {

                                    if(!e.data.label){
                                        e.data.label = 'Donate Now';
                                    }

                                    ed.execCommand('mceInsertContent', false, '[donate color="'+ e.data.color  +'" label="'+ e.data.label +'"]');
                                }
                            });

                            jQuery('#philantro-color-picker').minicolors({
                                opacity: false,
                                position: 'top right',
                                defaultValue: '#3277A2'
                            });

                        }
                    },
                    {
                        text: 'Event Button',
                        onclick : function() {
                            ed.windowManager.open( {
                                title: 'Event Button Options',
                                body: [{
                                        type: 'textbox',
                                        name: 'eventid',
                                        label: 'Event ID'
                                    },
                                    {
                                        type: 'textbox',
                                        id: 'philantro-color-picker',
                                        name: 'color',
                                        label: 'Button Color'
                                    },
                                    {
                                        type: 'textbox',
                                        name: 'label',
                                        label: 'Button Text'
                                    },
                                ],
                                onsubmit: function( e ) {

                                    if(!e.data.label){
                                        e.data.label = 'Purchase Tickets';
                                    }


                                    ed.execCommand('mceInsertContent', false, '[event id="'+ e.data.eventid  +'" color="'+ e.data.color  +'" label="'+ e.data.label +'"]');
                                }
                            });

                            jQuery('#philantro-color-picker').minicolors({
                                opacity: false,
                                position: 'top right',
                                defaultValue: '#3277A2'
                            });

                            }
                    }
                ]
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname : "Philantro Donate Button Shortcode",
                author : 'Philantro Inc.',
                authorurl : 'http://www.philantro.com/',
                infourl : 'http://www.philantro.com/',
                version : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('philantro', tinymce.plugins.Philantro);





})();