;$(function () {
    $('.copy-referral-id')
        .on('click', function () {
            let referral = $('#input-referral').val();
            $('#copy-text').val(referral);
            let copyText = document.getElementById('copy-text');
            copyText.select();
            document.execCommand('copy');
        });
});
