jQuery(document).ready(function () {

    jQuery(".post-like .down").click(function () {

        heart = jQuery(this);

        post_id = heart.data("post_id");
        class_a = heart.attr("class");

        jQuery.ajax({
            type: "post",
            url: ajax_var.url,
            data: "action=post-like&nonce=" + ajax_var.nonce + "&post_like=&post_id=" + post_id + "&class_a=" + class_a,
            success: function (count) {
                heart.siblings(".count").text(count);
            }

        });
    });


    jQuery(".post-like .up").click(function () {

        heart = jQuery(this);

        post_id = heart.data("post_id");
        class_a = heart.attr("class");

        jQuery.ajax({
            type: "post",
            url: ajax_var.url,
            data: "action=post-like&nonce=" + ajax_var.nonce + "&post_like=&post_id=" + post_id + "&class_a=" + class_a,
            success: function (count) {
                heart.siblings(".count").text(count);
            }

        });
    });
});