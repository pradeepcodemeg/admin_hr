var user_id;

function deleteUser(list_id)
{
    document.getElementById("yes-delete").onclick = function(){
        document.location.href = 'personal/delete/' + list_id;
    }
}