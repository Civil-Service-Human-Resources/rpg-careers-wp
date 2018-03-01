if(typeof wp.media !='undefined'){
    if(typeof wp.media.model != 'undefined'){
        _.extend( wp.media.model.Attachment.prototype, {

            sync: function( method, model, options ) {
		        // If the attachment does not yet have an `id`, return an instantly
		        // rejected promise. Otherwise, all of our requests will fail.
		        if ( _.isUndefined( this.id ) ) {
			        return $.Deferred().rejectWith( this ).promise();
		        }

		        // Overload the `read` request so Attachment.fetch() functions correctly.
		        if ( 'read' === method ) {
			        options = options || {};
			        options.context = this;
			        options.data = _.extend( options.data || {}, {
				        action: 'get-attachment',
				        id: this.id
			        });
			        return wp.media.ajax( options );

		        // Overload the `update` request so properties can be saved.
		        } else if ( 'update' === method ) {
			        // If we do not have the necessary nonce, fail immeditately.
			        if ( ! this.get('nonces') || ! this.get('nonces').update ) {
				        return $.Deferred().rejectWith( this ).promise();
			        }

			        options = options || {};
			        options.context = this;

			        // Set the action and ID.
			        options.data = _.extend( options.data || {}, {
				        action:  'save-attachment',
				        id:      this.id,
				        nonce:   this.get('nonces').update,
				        post_id: wp.media.model.settings.post.id
			        });

			        // Record the values of the changed attributes.
			        if ( model.hasChanged() ) {
				        options.data.changes = {};

				        _.each( model.changed, function( value, key ) {
					        options.data.changes[ key ] = this.get( key );
				        }, this );
			        }

			        return wp.media.ajax(options).done(function(){
                        document.getElementById('attachments['+this.id+'][team-save-msg]').innerText = '';
                    }).fail(function(){
                        var resp = arguments[0];
                        var keys = Object.keys(resp);
                        document.getElementById(keys[0]).innerText = resp[keys[0]];
                    });

		        // Overload the `delete` request so attachments can be removed.
		        // This will permanently delete an attachment.
		        } else if ( 'delete' === method ) {
			        options = options || {};

			        if ( ! options.wait ) {
				        this.destroyed = true;
			        }

			        options.context = this;
			        options.data = _.extend( options.data || {}, {
				        action:   'delete-post',
				        id:       this.id,
				        _wpnonce: this.get('nonces')['delete']
			        });

			        return wp.media.ajax(options).done(function() {
				        this.destroyed = true;
			        }).fail( function() {
				        this.destroyed = false;
			        });

		        // Otherwise, fall back to `Backbone.sync()`.
		        } else {
			        /**
			         * Call `sync` directly on Backbone.Model
			         */
			        return Backbone.Model.prototype.sync.apply( this, arguments );
		        }
	        },
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
                            var f = jQuery('div.upload-errors');
                            if(f.text()===''){
                            var t = JSON.parse(resp.response);
                                f.append('<div class="upload-error"><span class="upload-error-filename">' + t.data.filename + '</span><span class="upload-error-message">Media saved successfully but need to assign teams - redirecting...</span></div>');
                                var newScript = document.createElement("script");
                                var inlineScript = document.createTextNode("(function(){window.setTimeout(redirectMedia, 2000);function redirectMedia(){window.location=window.location.origin+'/wp-admin/post.php?post=" + t.data.id + "&action=edit';}})();");
                                newScript.appendChild(inlineScript); 
                                f[0].appendChild(newScript);
                    
                                jQuery('div.media-uploader-status').addClass('errors').attr('style','');
                                jQuery('div.media-sidebar').attr('style','display:block;');
                            }
                        }
                    }
                });
            }
        });
    }
})(jQuery);