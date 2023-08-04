<!DOCTYPE html>
<html lang="en">

<head>
    @include('backend/global/head')
    <!-- jQuery Datatables-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/t/dt/jq-2.2.0,jszip-2.5.0,dt-1.10.11,af-2.1.1,b-1.1.2,b-colvis-1.1.2,b-html5-1.1.2,b-print-1.1.2,cr-1.3.1,fc-3.2.1,fh-3.1.1,kt-2.1.1,rr-1.1.1,sc-1.4.1/datatables.min.css" />
    <script type="text/javascript"
        src="https://cdn.datatables.net/t/bs/jq-2.2.0,jszip-2.5.0,dt-1.10.11,af-2.1.1,b-1.1.2,b-colvis-1.1.2,b-html5-1.1.2,b-print-1.1.2,cr-1.3.1,fc-3.2.1,fh-3.1.1,kt-2.1.1,rr-1.1.1,sc-1.4.1/datatables.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
    <style rel="stylesheet">
        .dataTables_length {
            margin: 0 10px;
        }

        .topsmall {
            margin-top: 10px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            color: white !important;
            border: none;
            background-color: none;
            background: none;
        }


        /* div#statistics_processing {
            position: fixed;
            top: 0;
            background: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            right: 0;
            bottom: 0;
            width: 100%;
            min-height: 100%;
            text-align: center;
        }

        div#statistics_processing .inner {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
        }

        div#statistics_processing .inner h2 {
            color: #fff;
            font-size: 28px;
        } */
    </style>
    {{-- Select-2 css --}}
    <style>
        .select2-results__option {
            padding-right: 20px;
            vertical-align: middle;
        }

        .select2-results__option:before {
            content: "";
            display: inline-block;
            position: relative;
            height: 20px;
            width: 20px;
            border: 2px solid #e9e9e9;
            border-radius: 4px;
            background-color: #fff;
            margin-right: 20px;
            vertical-align: middle;
        }

        .select2-results__option[aria-selected=true]:before {
            font-family: fontAwesome;
            /* content: "\f00c"; */
            color: #fff;
            background-color: #1083f6;
            border: 0;
            display: inline-block;
            padding-left: 3px;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #fff;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #eaeaeb;
            color: #272727;
        }

        .select2-container--default .select2-selection--multiple {
            margin-bottom: 10px;
        }

        .select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
            border-radius: 4px;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #1083f6;
            border-width: 2px;
        }

        .select2-container--default .select2-selection--multiple {
            border-width: 2px;
        }

        .select2-container--open .select2-dropdown--below {

            border-radius: 6px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);

        }

        .select2-selection .select2-selection--multiple:after {
            content: 'hhghgh';
        }

        /* select with icons badges single*/
        .select-icon .select2-selection__placeholder .badge {
            display: none;
        }

        .select-icon .placeholder {
            display: none;
        }

        .select-icon .select2-results__option:before,
        .select-icon .select2-results__option[aria-selected=true]:before {
            display: none !important;
            /* content: "" !important; */
        }

        .select-icon .select2-search--dropdown {
            display: none;
        }

        .select2-container--default .select2-selection--multiple {
            background-color: white;
            border: 1px solid #aaaaaa6e;
            border-radius: 4px;
            cursor: text;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        @include('backend/global/header')
    </nav>
    <div id="statistics_processing" class="dataTables_processing panel panel-default">
        <div class="inner">
            <h2>Processing...</h2>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-md-2 sidebar collapse">
                @include('backend/global/sidebar')
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <h1 class="page-header">Statistics</h1>
                <!-- Display filename inside the button instead of its label -->
                <div class="container-fluid">
                    <form method="GET" action="">
                        <div class="sort-form col-md-7">
                            <div class="form-group">
                                <label>Trainings:</label>
                                <select size="10" class="js-select2" name="training[]" multiple>
                                    @foreach ($trainings as $training)
                                        <option value="{{ $training['id'] }}" 
                                            @php
                                                if($t_id)
                                                {
                                                    if(in_array($training['id'],$t_id)){
                                                        echo'selected';
                                                    }
                                                }
                                            @endphp>{{ $training['training_name'] }}
                                            {{ $training['status'] == 'Archive' ? '(archieved)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!--<input class="left btn btn-info" type="submit" name="delete_button" value="Delete">-->
                            <!--<input class="left btn btn-danger" type="submit" name="complete_delete_button" value="Delete With Files" style="margin-left: 20px;">-->
                            <input class="right btn btn-success" type="submit" name="sort_button" value="Run Report" />
                        </div>
                    </form>
                    <div class="sort-form col-md-5 form-group">
                        <table cellpadding="3" cellspacing="0" border="0"
                            style="width: 80%; margin: 0 auto 2em auto;">
                            <tbody>
                                <tr id="filter_col1" data-column="0">
                                    <td align="left"><span style="font-size:15px;">First Name :</span></td>
                                    <td align="center"><input type="text" class="column_filter form-control"
                                            id="col0_filter">
                                    </td>
                                    <!--<td align="center"><input type="checkbox" class="column_filter" id="col0_regex"></td>
                            <td align="center"><input type="checkbox" class="column_filter" id="col0_smart" checked="checked"></td>-->
                                </tr>
                                <tr id="filter_col2" data-column="1">
                                    <td align="left"><span style="font-size:15px;">Last Name :</span></td>
                                    <td align="center"><input type="text" class="column_filter form-control topsmall"
                                            id="col1_filter"></td>
                                    {{-- <td align="center"><input type="checkbox" class="column_filter" id="col1_regex">
                                    </td>
                                    <td align="center"><input type="checkbox" class="column_filter" id="col1_smart"
                                            checked="checked"></td> --}}
                                </tr>
                                {{-- <tr id="filter_col3" data-column="2">
                                    <td align="left"><span style="font-size:15px;">Training Name :</span></td>
                                    <td align="center"><input type="text" class="column_filter form-control topsmall"
                                            id="col2_filter"></td>
                                    <td align="center"><input type="checkbox" class="column_filter" id="col2_regex">
                                    </td>
                                    <td align="center"><input type="checkbox" class="column_filter" id="col2_smart"
                                            checked="checked"></td>
                                </tr> --}}
                                <tr id="filter_col3" data-column="3">
                                    <td align="left">
                                        <span style="font-size:15px;">PASSED :</span>
                                    </td>
                                    <td align="center">
                                        <select class="passing_filter form-control topsmall" id="col2_filter">
                                            <option value="">Please select the filter</option>
                                            <option value="Passed">Passed</option>
                                            <option value="No">No</option>
                                        </select>
                                    </td>
                                    {{-- <td align="center"><input type="checkbox" class="column_filter" id="col3_regex">
                                    </td>
                                    <td align="center"><input type="checkbox" class="column_filter" id="col3_smart"
                                            checked="checked"></td> --}}
                                </tr>
                                {{-- <tr id="filter_col5" data-column="4">
                                    <td align="left"><span style="font-size:15px;">Credit Hours (HH:MM):</span></td>
                                    <td align="center"><input type="text" class="column_filter form-control topsmall"
                                            id="col4_filter"></td>
                                    <td align="center"><input type="checkbox" class="column_filter" id="col5_regex">
                                    </td>
                                    <td align="center"><input type="checkbox" class="column_filter" id="col5_smart"
                                            checked="checked"></td>
                                </tr> --}}

                                <tr id="filter_col4" data-column="4">
                                    <td align="left">
                                        <span style="font-size:15px;">From passing date:</span>
                                    </td>
                                    <td align="center">
                                        <input type="date" class="passing_from form-control topsmall" id="fromDate">
                                    </td>
                                </tr>

                                <tr id="filter_col4" data-column="4">
                                    <td align="left">
                                        <span style="font-size:15px;">To passing date:</span>
                                    </td>
                                    <td align="center">
                                        <input type="date" class="passing_to form-control topsmall" id="toDate">
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="form-inline col-md-12">
                        @if (!empty($_REQUEST['training']))
                            <a href="{{ url('excel-statistics?training=' . implode(',', $_REQUEST['training'])) }}"
                                class="btn btn-default buttons-excel buttons-html5" tabindex="0"
                                aria-controls="statistics"><span>Excel</span></a>
                        @else
                            <a href="{{ url('excel-statistics') }}"
                                class="btn btn-default buttons-excel buttons-html5" tabindex="0"
                                aria-controls="statistics"><span>Excel</span></a>
                        @endif
                        <!-- <input type="text" class="form-control" placeholder="Поиск"> -->
                        <!-- <button class="btn btn-primary form-control right"><i class="glyphicon glyphicon-save-file"></i></button> -->
                        <!-- <button class="btn btn-primary form-control right"><i class="glyphicon glyphicon-print"></i></button> -->
                    </div>
                </div>

                <table id="statistics" class="display" cellspacing="0" width="100%">
                    <thead>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Training Name</th>
                        <th>PASSED</th>
                        <th>Passing Date</th>
                        <th>Credit Hours (HH:MM)</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    </div>
    <script src="{{ asset('public/js/bootstrap.js') }}"></script>
    <script src="{{ asset('public/js/filter_statistics.js') }}"></script>

    <script>
        var is_draw = 'false';
    </script>
    @if ($draw_table)
        <script>
            is_draw = 'true';
        </script>
    @endif
    <script type="text/javascript">
        $('#statistics').on('draw.dt', function() {
            console.log('Tst0000000');
            $('#statistics_processing').hide();
        });
        // $('input.column_filter').on('keyup click', function () {
        //         console.log('Tst1111111');
        //         $('#statistics_processing').show();
        //         filterColumn($(this).parents('tr').attr('data-column'));
        //     });

        //     $('.passing_filter').on('change', function () {
        //         console.log('Tst222222');
        //         $('#statistics_processing').show();
        //         filterPassed($(this).parents('tr').attr('data-column'));
        //     });

        //     $('.passing_from, .passing_to').change( function() {
        //         console.log('Tst444444');
        //         $('#statistics_processing').show();
        //         var min = $('#fromDate').val();
        //         var max = $('#toDate').val();
        //         $('#statistics').DataTable().destroy();
        //         fill_datatable(min, max);
        //     });

        $(".js-select2").select2({
            closeOnSelect: false,
            placeholder: "Placeholder",
            allowHtml: true,
            allowClear: true,
            tags: true // создает новые опции на лету
        });

        $('.icons_select2').select2({
            width: "100%",
            templateSelection: iformat,
            templateResult: iformat,
            allowHtml: true,
            placeholder: "Placeholder",
            dropdownParent: $('.select-icon'), //обавили класс
            allowClear: true,
            multiple: false
        });

        function iformat(icon, badge, ) {
            var originalOption = icon.element;
            var originalOptionBadge = $(originalOption).data('badge');

            return $('<span><i class="fa ' + $(originalOption).data('icon') + '"></i> ' + icon.text +
                '<span class="badge">' + originalOptionBadge + '</span></span>');
        }
    </script>
</body>

</html>
