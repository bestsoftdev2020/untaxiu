var TableManaged = function () {

    var initTable = function () {

        var table = $('#sample_2');

        table.dataTable({

            // Internationalisation. For more info refer to http://datatables.net/manual/i18n
            "language": {
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                "emptyTable": "No data available in table",
                "info": "Showing _START_ to _END_ of _TOTAL_ records",
                "infoEmpty": "No records found",
                "infoFiltered": "(filtered1 from _MAX_ total records)",
                "lengthMenu": " _MENU_ records",
                "paging": {
                    "previous": "Prev",
                    "next": "Next"
                },
                "search": "Search:",
                "zeroRecords": "No matching records found"
            },

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            //"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

            "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.

            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 5,
            "columnDefs": [{  // set default column settings
                'orderable': false,
                'targets': [0]
            }, {
                "searchable": false,
                "targets": [0]
            }],
            "order": [
                [1, "asc"]
            ] // set first column as a default sort by asc
        });

        var tableWrapper = jQuery('#sample_2_wrapper');

        table.find('.group-checkable').change(function () {
            var set = jQuery(this).attr("data-set");
            var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
                if (checked) {
                    $(this).attr("checked", true);
                } else {
                    $(this).attr("checked", false);
                }
            });
            table.fnDraw() ;
            jQuery.uniform.update(set);
        });

        $("#approve").click(function(e) {
            var nRow = $(this).parents('tr')[0];
            var aData = table.fnGetData(nRow);
            var chstr = aData[1] ;

            jQuery.ajax({
                url: "driverapprove",
                method: 'get',
                data: {
                   lists: chstr
                },
                success: function(result){
                    if(result) {
                        alert("Approve Sucess!") ;
                        var jqTds = $('>td', nRow);
                        jqTds[7].innerHTML = '<center><a href="javascript:;" class="label label-sm label-warning" id="disapprove">Disapprove</a></center>';
                        table.fnDraw() ;
                    }
                    else {
                       alert("Approve Failed!") ;
                    }
                }
            });            
        });

        $("#suspend").click(function(e) {
            e.preventDefault();
            var chstr = '' ;
            var flag = 0 ;
            $('#sample_2 .checkboxes').each(function () {
                var checked = jQuery(this).is(":checked");
                var nRow = $(this).parents('tr')[0];
                var aData = table.fnGetData(nRow);
                var jqTds = $('>td', nRow);
                var jq = $('>span', jqTds[8]);
                if(checked) {
                    
                    if(jq.text() == 'Approved')
                        flag = 1 ;
                    if(chstr == '') {
                        chstr = aData[1] ;
                    }
                    else {
                        chstr += ','+aData[1] ;
                    }
                }
            });

            if(chstr == '') {
                alert("No selected!") ;
                return;
            }

            if(!flag) {
                alert("All are suspended!") ;
                return;
            }

            jQuery.ajax({
                url: "driversuspend",
                method: 'get',
                data: {
                   lists: chstr
                },
                success: function(result){
                    if(result) {
                        alert("Suspend Sucess!") ;
                        $('#sample_2 .checkboxes').each(function () {
                            var checked = jQuery(this).is(":checked");
                            $(this).attr("checked", false);
                            if(checked) {
                                var nRow = $(this).parents('tr')[0];
                                var jqTds = $('>td', nRow);
                                jqTds[8].innerHTML = '<span class="label label-sm label-warning">Suspended</span>';
                            }
                        });
                        jQuery.uniform.update('#sample_2 .checkboxes');
                    }
                    else {
                       alert("Suspend Failed!") ;
                    }
                }
            });
        });

        $("#approve1").click(function(e) {
            e.preventDefault();
            var chstr = '' ;
            var flag = 0 ;
            $('#sample_2 .checkboxes').each(function () {
                var checked = jQuery(this).is(":checked");
                var nRow = $(this).parents('tr')[0];
                var aData = table.fnGetData(nRow);
                var jqTds = $('>td', nRow);
                var jq = $('>span', jqTds[5]);
                if(checked) {
                    
                    if(jq.text() == 'Suspended')
                        flag = 1 ;
                    if(chstr == '') {
                        chstr = aData[1] ;
                    }
                    else {
                        chstr += ','+aData[1] ;
                    }
                }
            });

            if(chstr == '') {
                alert("No selected!") ;
                return;
            }

            if(!flag) {
                alert("All are approved!") ;
                return;
            }

            jQuery.ajax({
                url: "customerapprove",
                method: 'get',
                data: {
                   lists: chstr
                },
                success: function(result){
                    if(result) {
                        alert("Approve Sucess!") ;
                        $('#sample_2 .checkboxes').each(function () {
                            var checked = jQuery(this).is(":checked");
                            $(this).attr("checked", false);
                            if(checked) {
                                var nRow = $(this).parents('tr')[0];
                                var jqTds = $('>td', nRow);
                                jqTds[5].innerHTML = '<span class="label label-sm label-success">Approved</span>';
                            }
                        });
                        jQuery.uniform.update('#sample_2 .checkboxes');
                    }
                    else {
                       alert("Approve Failed!") ;
                    }
                }
            });
        });

        $("#suspend1").click(function(e) {
            e.preventDefault();
            var chstr = '' ;
            var flag = 0 ;
            $('#sample_2 .checkboxes').each(function () {
                var checked = jQuery(this).is(":checked");
                var nRow = $(this).parents('tr')[0];
                var aData = table.fnGetData(nRow);
                var jqTds = $('>td', nRow);
                var jq = $('>span', jqTds[5]);
                if(checked) {
                    
                    if(jq.text() == 'Approved')
                        flag = 1 ;
                    if(chstr == '') {
                        chstr = aData[1] ;
                    }
                    else {
                        chstr += ','+aData[1] ;
                    }
                }
            });

            if(chstr == '') {
                alert("No selected!") ;
                return;
            }

            if(!flag) {
                alert("All are suspended!") ;
                return;
            }

            jQuery.ajax({
                url: "customersuspend",
                method: 'get',
                data: {
                   lists: chstr
                },
                success: function(result){
                    if(result) {
                        alert("Suspend Sucess!") ;
                        $('#sample_2 .checkboxes').each(function () {
                            var checked = jQuery(this).is(":checked");
                            $(this).attr("checked", false);
                            if(checked) {
                                var nRow = $(this).parents('tr')[0];
                                var jqTds = $('>td', nRow);
                                jqTds[5].innerHTML = '<span class="label label-sm label-warning">Suspended</span>';
                            }
                        });
                        jQuery.uniform.update('#sample_2 .checkboxes');
                    }
                    else {
                       alert("Suspend Failed!") ;
                    }
                }
            });
        });

        tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown
    }

    return {

        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }
            initTable();
        }

    };

}();