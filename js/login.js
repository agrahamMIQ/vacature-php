(function() {
    var id = document.getElementById('login-form');
    if (id && id.password) {
        var name = id.password;
        var unclicked = function() {
            if (name.value == '') {
                name.style.background = 'url(images/paswoord.png) 10px no-repeat';
            }
        };
        var clicked = function() {
            name.style.background = '';
        };
        name.onfocus = clicked;
        name.onblur = unclicked;
        unclicked();
    }
})();