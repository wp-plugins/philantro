(function() {

    tinymce.create('tinymce.plugins.Philantro', {
        init : function(ed, url) {
            ed.addButton('philantro', {
                title : 'Insert Philantro Button',
                image : url.replace('js', 'asset/wordpress-asset.png'),
                type: 'menubutton',
                menu: [
                    {
                        text: 'Donate Button',
                        onclick : function() {
                            ed.execCommand('mceInsertContent', false, '[donate label="Donate Now"]');
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
                                    }],
                                onsubmit: function( e ) {
                                    ed.execCommand('mceInsertContent', false, '[event id="'+ e.data.eventid  +'" label=""]');
                                }
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