$(document).ready(function(){
    fill_datatable();
    function fill_datatable(status = '')
    {
        var dataTable = $('#myTable').DataTable({
           processing: true,
           serverSide: true,
           ajax:{
                url: "user-management",
                data:{status:status}
            },
           columns: [
                {data: 'image', name: 'image'}, 
                {data: 'firstname', name: 'firstname'},
                {data: 'lastname', name: 'lastname'},
                {data: 'role', name: 'role'},
                {data: 'email', name: 'email'},
                {data: 'status', name: 'status'},
                {data: 'created_at', name: 'created_at'},
                {data: 'edit', name: 'edit'},
                {data: 'delete', name: 'delete'},
                 ],
            order: [ [6, 'desc'] ]
        });  
    }

    $('#active').click(function(){
        var status = $('#active').val();

        if(status != '')
        {
            $('#myTable').DataTable().destroy();
            fill_datatable(status);
        }
    });

    $('#inactive').click(function(){
        var status = $('#inactive').val();

        if(status != '')
        {
            $('#myTable').DataTable().destroy();
            fill_datatable(status);
        }
    });

    $('#all').click(function(){
        $('#myTable').DataTable().destroy();
        fill_datatable();
    });

});