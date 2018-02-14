if(typeof wp.media !='undefined'){
    if(typeof wp.media.model != 'undefined'){
        _.extend( wp.media.model.Attachment.prototype, {

            saveCompat: function( data, options ) {
                var model = this;
                var $el = jQuery('div.attachment-details');
                var savedTimer;

		        // If we do not have the necessary nonce, fail immeditately.
		        if ( ! this.get('nonces') || ! this.get('nonces').update ) {
			        return $.Deferred().rejectWith( this ).promise();
		        }
        
                $el.removeClass('save-ready');
                $el.removeClass('save-complete');
                $el.addClass('save-waiting');

		        return wp.media.post('save-attachment-compat', _.defaults({
			            id: this.id,
			            nonce: this.get('nonces').update,
			            post_id: wp.media.model.settings.post.id
		            }, data))
                    .done(function(resp,status,xhr) {
			            model.set(model.parse(resp,xhr),options);
                        jQuery('div.attachment-details').removeClass('save-waiting');
                        jQuery('div.attachment-details').addClass('save-complete');
                
                        document.getElementById('attachments['+resp.id+'][team-save-msg]').innerText = '';
                
                        if(savedTimer){
                            clearTimeout(savedTimer);
                        }

                        savedTimer = setTimeout( function() {
                            $el.removeClass('save-complete');
                            $el.addClass('save-ready');  
                            clearTimeout(savedTimer);
			            }, 2000)
                    })
                    .fail(function(resp,status,err){
                        var keys = Object.keys(resp);
                        document.getElementById(keys[0]).innerText = resp[keys[0]];
                        $el.removeClass().addClass('attachment-details');
                        clearTimeout(savedTimer);
                    });
            }
        });
    }
}

(function($){
    if (typeof wp.Uploader === 'function') {
        $.extend( wp.Uploader.prototype, {
            init : function() {
                this.uploader.bind('FileUploaded', function(upldr, file, resp) {

                    if(wprpg!='undefined'){
                        if(wprpg.mediateams > 1){
                            var t = JSON.parse(resp.response);
                            jQuery('div.upload-errors').append('<div class="upload-error"><span class="upload-error-filename">' + t.data.filename + '</span><span class="upload-error-message">Media saved successfully but need to assign teams - redirecting...</span></div>');
                    
                            var newScript = document.createElement("script");
                            var inlineScript = document.createTextNode("(function(){window.setTimeout(redirectMedia, 2000);function redirectMedia(){window.location=window.location.origin+window.location.pathname+'?item="+ t.data.id + "';}})();");
                            newScript.appendChild(inlineScript); 
                            jQuery('div.upload-errors')[0].appendChild(newScript);
                    
                            jQuery('div.media-uploader-status').addClass('errors').attr('style','');
                            jQuery('div.media-sidebar').attr('style','display:block;');
                        }
                    }
                });
            }
        });
    }
})(jQuery);