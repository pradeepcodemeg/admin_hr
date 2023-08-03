$(document).submit(function(){
        for(var i=0;i<=19;i++){
            var que_no = i + 1;
            if($('#que'+i).val() != ""){
                if($('#option_one'+i).val() == "" || $('#option_two'+i).val() == ""){
                    alert("First two options must be filled for the question number "+que_no);
                    return false; 
                }else if(!$('input[name=correct_option'+i+']').is(':checked')){
                    alert("Please choose one correct answer for question number "+que_no);
                    return false;
                }
                if($('#option_one'+i).val() == "" && $('input[name=correct_option'+i+']:checked').val() == "option_one"){
                    alert("Please choose correct answer for correct option in question number "+que_no);
                    return false;
                }else if($('#option_two'+i).val() == "" && $('input[name=correct_option'+i+']:checked').val() == "option_two"){
                    alert("Please choose correct answer for correct option in question number "+que_no);
                    return false;
                }else if($('#option_three'+i).val() == "" && $('input[name=correct_option'+i+']:checked').val() == "option_three"){
                    alert("Please choose correct answer for correct option in question number "+que_no);
                    return false;
                }else if($('#option_four'+i).val() == "" && $('input[name=correct_option'+i+']:checked').val() == "option_four"){
                    alert("Please choose correct answer for correct option in question number "+que_no);
                    return false;
                }
            }            
            if($('#que'+i).val() == ""){
                alert("All questions are mandatory to add");
                return false; 
            }else{
                $('#fader').css('display', 'block');
            }
        }
    });