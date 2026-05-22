jQuery(document).ready(function($){
    var frame;
    var imagesContainer = $('#portfolio_gallery_images_container');
    var imageIdsInput = $('#portfolio_gallery_image_ids');

    // Add Image Button Click
    $('#portfolio_gallery_add_image').on('click', function(e){
        e.preventDefault();

        // If the frame already exists, re-open it.
        if ( frame ) {
            frame.open();
            return;
        }

        // Create a new media frame
        frame = wp.media({
            title: 'Select Images for Gallery',
            button: {
                text: 'Add to Gallery'
            },
            multiple: true
        });

        // When an image is selected in the media frame...
        frame.on( 'select', function() {
            var selection = frame.state().get('selection');
            var ids = imageIdsInput.val() ? imageIdsInput.val().split(',') : [];

            selection.map( function( attachment ) {
                attachment = attachment.toJSON();
                
                if (ids.indexOf(String(attachment.id)) === -1) {
                    ids.push(attachment.id);
                    
                    // Preview image
                    var url = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                    
                    imagesContainer.append(
                        '<div class="gallery-image-preview" style="display:inline-block; margin:5px; position:relative;">' +
                        '<img src="' + url + '" width="80" height="80" style="border:1px solid #ccc;" />' +
                        '<span class="remove-gallery-image" data-id="' + attachment.id + '" style="position:absolute; top:-5px; right:-5px; background:red; color:white; border-radius:50%; width:18px; height:18px; text-align:center; line-height:16px; cursor:pointer;">&times;</span>' +
                        '</div>'
                    );
                }
            });

            imageIdsInput.val(ids.join(','));
        });

        // Finally, open the modal on click
        frame.open();
    });

    // Remove Image
    $(document).on('click', '.remove-gallery-image', function() {
        var idToRemove = $(this).data('id');
        var ids = imageIdsInput.val().split(',');
        
        // Remove id from array
        ids = ids.filter(function(id) {
            return String(id) !== String(idToRemove);
        });
        
        imageIdsInput.val(ids.join(','));
        $(this).parent().remove();
    });
});
