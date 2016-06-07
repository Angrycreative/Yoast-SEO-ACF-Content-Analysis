jQuery(window).on('YoastSEO:ready', function () {
    var fieldData = "";
    YoastSEO_ACF_Content_Analysis = function() {

        YoastSEO.app.registerPlugin( 'ACF_Content_Analysis', {status: 'loading'} );
        this.appendACFFields();
    };

    YoastSEO_ACF_Content_Analysis.prototype.appendACFFields = function() {

        var $this = this;
        jQuery.ajax({
            url: yoast_acf_settings.ajax_url,
            type: 'POST',
            dataType: 'JSON',
            data: {
                postId : yoast_acf_settings.id,
                action: yoast_acf_settings.ajax_action
            }
        })
        .done(function(acf_fields) {
            $this.setFieldData(acf_fields);
        })
        .fail(function(data) {
            console.log("error");
            console.log(data);
        });
    };

    YoastSEO_ACF_Content_Analysis.prototype.setFieldData = function( data ) {

        YoastSEO.app.pluginReady( 'ACF_Content_Analysis' );
        fieldData = data;
        this.registerModification();

    };

    YoastSEO_ACF_Content_Analysis.prototype.getFieldData = function( data ) {

        return data + ' ' + fieldData;

    };

    YoastSEO_ACF_Content_Analysis.prototype.registerModification = function( data ) {
        YoastSEO.app.registerModification( 'content', this.getFieldData, 'ACF_Content_Analysis', 50 );
    };

    new YoastSEO_ACF_Content_Analysis();

});
