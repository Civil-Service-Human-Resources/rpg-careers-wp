jQuery(function($){
    var frame,
        metaBox = $('#addtag'),
        addImgLink = $('#tag-choose-logo'),
        delImgLink = $('#tag-remove-logo'),
        imgContainer = $('#tag-logo');
        imgIdInput = $('#tag-logo-id');
    
    if(imgContainer.html()!==''){
        addImgLink.addClass('hidden');
        delImgLink.removeClass('hidden');
    }

    //ADD IMAGE LINK
    addImgLink.on( 'click', function(event){
      event.preventDefault();
      
      if (frame) {
        frame.open();
        return;
      }
      
      frame = wp.media({
        title: 'Select or Upload logo for team',
        button: {
          text: 'Use this logo'
        },
        library: {
            type: ['image']
        },
        multiple: false
      });
  
      frame.on('select', function() {
        var attachment = frame.state().get('selection').first().toJSON();
        imgContainer.append('<img src="'+attachment.url+'" alt="" style="max-width:100%;"/>');
        imgIdInput.val(attachment.id);
        addImgLink.addClass('hidden');
        delImgLink.removeClass('hidden');
      });
  
      frame.open();
    });
    
    //DELETE IMAGE LINK
    delImgLink.on('click', function(event){
      event.preventDefault();
      imgContainer.html('');
      addImgLink.removeClass('hidden');
      delImgLink.addClass('hidden');
      imgIdInput.val('');
    });

});