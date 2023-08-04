function filterColumn(i) {
  $("#statistics")
    .DataTable()
    .column(i)
    .search($("#col" + i + "_filter").val())
    .draw();
}

function filterPassed(i) {
  $("#statistics").DataTable().column(i).search($("#col2_filter").val()).draw();
}

$(document).ready(function () {
  if (is_draw == "true") {
    fill_datatable();
  }
  function fill_datatable(min = "", max = "") {
    var dataTable = $("#statistics").DataTable({
      dom: "Blfrtip",
      buttons: [
        //'excel',
        "print",
      ],
      select: true,
      autoWidth: true,
      processing: true,
      // scrollY: true,
      orderMulti: true,
      pagingType: "simple_numbers",
      search: {
        smart: true,
      },
      processing: true,
      serverSide: true,
      ajax: {
        url: "statistics",
        data: { min: min, max: max },
      },
      columns: [
        { data: "firstname", name: "firstname" },
        { data: "lastname", name: "lastname" },
        { data: "training_name", name: "training_name" },
        { data: "passed", name: "passed" },
        { data: "passing_date", name: "passing_date" },
        { data: "credit_hours", name: "credit_hours" },
      ],
      order: [[0, "desc"]],
      drawCallback: function (settings) {
        $("#statistics_processing").hide();
        console.log("DataTables has redrawn the table");
      },
    });
  }

  $("input.column_filter").on("keyup click", function () {
    filterColumn($(this).parents("tr").attr("data-column"));
  });

  $(".passing_filter").on("change", function () {
    filterPassed($(this).parents("tr").attr("data-column"));
  });

  $(".passing_from, .passing_to").change(function () {
    var min = $("#fromDate").val();
    var max = $("#toDate").val();
    $("#statistics").DataTable().destroy();
    fill_datatable(min, max);
  });
});
