function pasGeneration() {
    var password = '';
    var position;
    var words = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM!@#$%^&*()';
    var max_position = words.length - 1;
    for(var  i = 0; i < 8; ++i ) {
        position = Math.floor ( Math.random() * max_position );
        password = password + words.substring(position, position + 1);
    }
    document.getElementById("password0").value = password;
    document.getElementById("password1").value = password;
}

