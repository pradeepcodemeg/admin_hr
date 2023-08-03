$(function() {
    var imagesPreview = function(input, placeToInsertImagePreview) {
        if (input.files) {
            var filesAmount = input.files.length;
            for (i = 0; i < filesAmount; i++) {                
            var file = $('#img').val();
            var fE = file.replace(/^.*\./, '');
                if ((fE == 'jpg') || (fE == 'png') || (fE == 'jpeg') || (fE == 'gif')) {
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                    }
                    reader.readAsDataURL(input.files[i]);
                }else{
                    $('#preview').html('<img src="public/images/txt.png"></img>');
                }
            }
        }
    };
    $('#img').on('change', function() {
        $('#preview').html('');
        imagesPreview(this, '#preview');
    });
}); 