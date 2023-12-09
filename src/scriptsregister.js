function submitForm() {
  var username = $('#username').val();
  var password = $('#password').val();
  $.post('register.php', {username: username, password: password}, function(data) {
    $("#result").css("display", "block");
    var result = $('#result');
    result.html(data);
  });
}
