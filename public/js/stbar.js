jQuery(".stbar_notice_dismiss").click(function() {
    jQuery(".stbar_notice").remove();
    document.cookie = "stbar_cookies=stbar_text_option; path=/;max-age=36000000"
    })