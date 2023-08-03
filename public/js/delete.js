var user_id;

function deleteUser(user_id)
{
    document.getElementById("yes-delete").onclick = function(){
        document.location.href = 'user-management/delete/' + user_id;
    }
}