(function ($) {
    $(document).ready(function () {
        //call media open function
        $("#choose-featured-image").click(function (e) {
            e.preventDefault();
            var frame;
            // If the media frame already exists, reopen it.
            if (frame) {
                frame.open();
                return;
            }

            // Create a new media frame
            frame = wp.media({
                title: 'Set Post featured image',
                button: {
                    text: 'Set image'
                },
                multiple: false // Set to true to allow multiple files to be selected
            });

            // When an image is selected in the media frame...
            frame.on("select", function () {
                // Get media attachment details from the frame state
                var attachment = frame.state().get("selection").first().toJSON();
                $(".guest-post-featured-image").empty().html('<span class="img-remove" style="color:red;cursor: pointer;">Delete</span> <img src="' + attachment.url + '" alt=""/>');
                $("#featured_image").val(attachment.id);
            });
            // Finally, open the modal on click
            frame.open();
        });

        // Remove image
        $("body").on("click", ".img-remove", function () {
            $("#featured-image").val('');
            $(".guest-post-featured-image").empty();
        });


        $.validator.setDefaults({
            ignore: "input[type='text']:hidden,textarea[name='post_content']:none"
        });
        $('form#add-post-form').validate({
            ignore: "",
            rules: {
                post_title: {
                    required: true
                },
                post_type: {
                    required: true
                },
                post_content: {
                    required: true
                },
                post_excerpt: {
                    required: true
                },
                featured_image: {
                    number: true,
                    min: 1,
                    required: true
                }
            },
            submitHandler: function (form) {
                // e.preventDefault();
                //get tinymce editor data
                let content = '';
                let mce_editor = tinymce.get('post_content');
                if (mce_editor) {
                    content = wp.editor.getContent('post_content');
                } else {
                    content = $("#post_content").val();
                }

                //Ajax call
                $.ajax({
                    type: 'POST',
                    url: gp_data.ajaxurl,
                    data: {
                        action: 'post_form_submit',
                        security: gp_data.nonce,
                        post_data: $("form#add-post-form").serialize(),
                        content: content
                    },
                    success: function (res) {
                        if (res.success) {
                            $(".response").empty().html('<div class="alert alert-success" role="alert">' + gp_data.sucess + '</div>').removeClass('d-none');
                        } else {
                            $(".response").empty().html('<div class="alert alert-danger" role="alert">' + gp_data.error + '</div>').removeClass('d-none');
                        }
                        $(".guest-post-featured-image").empty();
                        $("form#add-post-form").trigger("reset"); //Reset form

                    },
                    error: function (xhr, textStatus, errorThrown) {
                        $(".response").empty().html('<div class="alert alert-danger" role="alert">' + gp_data.error + '</div>');
                        return false;
                    }
                });
                return false;
            },
            // other options
        });
    });
})(jQuery);