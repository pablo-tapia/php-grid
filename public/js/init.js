/**
 * Initializes the data grid and retrieves the data using an ajax request
 */
$(function() {
    // Populate the select boxes
    var selectArray = ['topic', 'applicant', 'status', 'community', 'range'];
    $.each(selectArray, function(index, value) {
        $.get('/async', {field: value}, function (data) {
            $('#' + value).append(data);
        });
    });

    // Create the grid on a POST submission
    // delete the content of the container
    $('#search').submit(function() {
        var _d = $('#search').serializeArray();
        $('#container').html('');
        $('#container').append('<div id="jsGrid"></div>');

            $("#jsGrid").jsGrid({
                height: "auto",
                width: "100%",
                filtering: false,
                sorting: false,
                autoload: true,
                paging: true,
                pageSize: 10,
                pageButtonCount: 5,
                noDataContent: 'No se encontraron coincidencias para tus criterios de busqueda.',
                loadMessage: 'Cargando ...',
                controller: {
                    loadData: function() {
                        return $.ajax({
                            type: "GET",
                            url: "/search",
                            data: _d,
                        });
                    } // end loadData
                }, // end controller
                fields: [
                    { name: "name", title: "Nombre", type: "text", width: 150},
                    { name: "sponsor", title: "Convocante", type: "text", width: 150 },
                    { name: "price", title: "Premio", type: "text", width: 500 },
                    { name: "date", title: "Fecha de", type: "text", width: 150 },
                    { name: "application", title: "Postulacion", type: "text", width: 100 },
                    { name: "scope", title: "Ambito", type: "text", width: 100 },
                    { name: "community", title: "Cobertura", type: "text", width: 150 },
                    {
                        type: "control",
                        editButton: false,
                        deleteButton: false,
                        clearFilterButton: false,
                        modeSwitchButton: false
                    }
                ], // end fields
                onDataLoaded: function (args) {
                    // Hide scope and community if the user uses more filters for the search
                    // _d is a JSON array, index 4 is scope and 5 is community
                    if (_d[4].value != '' || _d[5].value != '') {
                        $('#jsGrid').jsGrid('fieldOption', 'scope', 'visible', false);
                        $('#jsGrid').jsGrid('fieldOption', 'community', 'visible', false);
                    } // end if

                    $.each(args.data, function (index, value) {
                        $('.jsgrid-row .jsgrid-cell:first-child').attr('title', value.description);
                    });

                    tippy('.jsgrid-row .jsgrid-cell:first-child', {
                        position: 'bottom',
                        animation: 'scale',
                        arrow: true
                    });
                }
        }); // end jsGrid

        return false;
    });
});