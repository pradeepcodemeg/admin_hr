$(document).ready(function () {
  fill_content();
  function fill_content(status = "") {
    $.ajax({
      url: "trainings-filter",
      method: "GET",
      data: { status: status },
      dataType: "json",
      success: function (data) {
        data.forEach(function (item) {
          if (item.status == "Active") {
            renderActiveTrainings(item);
          } else {
            renderInactiveTrainings(item);
          }
        });
      },
    });
  }

  var renderActiveTrainings = function (item) {
    $("#training-content").append(
      '<li class="dropdown col-md-3">' +
        '<a href="#" class="dropdown-toggle btn-for-list yes well ' +
        item.color +
        '" data-toggle="dropdown">' +
        '<h3 class="card-title card-text-color" align="center">' +
        item.training_name +
        "</h3>" +
        '<p class="card-text card-text-color" align="center">Status: ' +
        item.status +
        "</p>" +
        '<p class="card-text card-text-color" align="center">Deadline: ' +
        item.training_deadline +
        "</p>" +
        '<p align="center"><i class="glyphicon glyphicon-chevron-down card-text-color"></i></p>' +
        "</a>" +
        '<ul class="dropdown-menu bottom-list list-border">' +
        '<li align="center"><a class="list-block"  href="edit-training/' +
        item.id +
        '">Edit</a>' +
        "</li>" +
        '<li align="center"><a class="list-block remindcls" data-toggle="modal" style="cursor:pointer;" data-href="remind-training/' +
        item.id +
        '">Remind training</a></li>' +
        '<li class="divider"></li>' +
        '<li align="center"><a class="list-block" data-toggle="modal" href="stop-training/' +
        item.id +
        '">Stop training</a></li>' +
        "</ul>" +
        "</li>"
    );
  };

  var renderInactiveTrainings = function (item) {
    $("#training-content").append(
      '<li class="dropdown col-md-3">' +
        '<a href="#" class="dropdown-toggle btn-for-list gray well" data-toggle="dropdown">' +
        '<h3 class="card-title card-text-color" align="center">' +
        item.training_name +
        "</h3>" +
        '<p class="card-text card-text-color" align="center">Status: ' +
        item.status +
        "</p>" +
        '<p class="card-text card-text-color" align="center">Deadline: ' +
        item.training_deadline +
        "</p>" +
        '<p align="center"><i class="glyphicon glyphicon-chevron-down card-text-color"></i></p>' +
        "</a>" +
        '<ul class="dropdown-menu bottom-list list-border">' +
        '<li align="center"><a class="list-block" href="duplicate-training/' +
        item.id +
        '">Duplicate Training</a>' +
        "</li>" +
        "</ul>" +
        "</li>"
    );
  };

  $("#active").click(function () {
    var status = $("#active").val();
    if (status != "") {
      $("#training-content").html("");
      fill_content(status);
    }
  });

  $("#archive").click(function () {
    var status = $("#archive").val();
    if (status != "") {
      $("#training-content").html("");
      fill_content(status);
    }
  });

  $("#inactive").click(function () {
    var status = $("#inactive").val();
    if (status != "") {
      $("#training-content").html("");
      fill_content(status);
    }
  });

  $("#all").click(function () {
    $("#training-content").html("");
    fill_content();
  });
});
