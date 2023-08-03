var loadFile = function(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('preview-foto');
        var output2 = document.getElementById('preview-foto-s');
        output.src = reader.result;
        output2.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
};

var loadFile2 = function(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('preview-foto-s');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
};

