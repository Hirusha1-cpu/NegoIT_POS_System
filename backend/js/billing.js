function generateLogIn() {
    var pass = md5(document.getElementById('passwd').value);
    var token = document.getElementById('token').value;
    var onetime_pass = md5(pass + token);
    document.getElementById('onetime_pass').value = onetime_pass;
    document.getElementById('div_login').innerHTML = "Loading..";
}