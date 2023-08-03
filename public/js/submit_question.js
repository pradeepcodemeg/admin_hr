function submitQuestion()
{
    document.getElementById("yes-delete").onclick = function(){
        document.location.href = 'personal/delete/' + list_id;
    }
}