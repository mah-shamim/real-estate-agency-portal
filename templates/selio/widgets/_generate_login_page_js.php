if(window.location.hash && window.location.hash.substr(1)=='sw_register') {
    $('.sign-up-form').addClass('active show')
    $('.sign-up').addClass('active')
} else {
    $('.log-in-form').addClass('active show')
}

$('.signin-op').on('click',function(e){
    e.preventDefault();
    $('.log-in').click();
})

$('.create-op,.nav-link .reg-op').on('click',function(e){
    e.preventDefault();
    $('.sign-up').click();
})
