

$("form").submit(function () {
    $(this).find(":submit").attr('disabled', 'disabled');
    $(this).find(":submit").html(
        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
    );
});