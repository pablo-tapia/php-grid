/**
 * Initializes the data grid and retrieves the data using an ajax request
 */
$(function() {

    if (window.location.pathname.startsWith('/admin/add')) {
        // Populate the select boxes
        var selectArray = ['topic', 'applicant', 'status', 'community', 'range', 'application'];
        $.each(selectArray, function(index, value) {
            $.get('/async', {field: value}, function (data) {
                $('#' + value).append(data);
            });
        });

        $('#datepicker').datepicker();
    } // end if

    $("#jsGridAdmin").jsGrid({
        height: "auto",
        width: "100%",
        sorting: true,
        editing: false,
        filtering: true,
        autoload: true,
        paging: true,
        noDataContent: 'No hay registros que mostrar.',
        loadMessage: 'Cargando ...',
        deleteConfirm: 'Esta seguro que desea borrar el registro?',
        controller: {
            loadData: function(filter) {
                return $.ajax({
                    type: "GET",
                    url: "/asyncAdmin",
                    data: filter
                });
            }, // end loadData
            deleteItem: function(item) {
                delete item.name;
                delete item.participants;
                delete item.price;
                delete item.topic;
                return $.ajax({
                   type: "GET",
                    url: "/admin/delete",
                    data: item
                });
            } // end deleteItem
        }, // end controller
        fields: [
            { name: "name", title: "Nombre", type: "text", width: 150 },
            { name: "price", title: "Premio", type: "text", width: 500, filtering: false},
            { name: "participants", title: "Participante", type: "text", width: 150 },
            { name: "topic", title: "Tema", type: "text", width: 150 },
            { name: "id", type: "number", visible: false },
            { name: "sponsor", type: "number", visible: false },
            { name: "event", type: "number", visible: false },
            { type: "control", editButton: false, }
        ], // end fields
        rowClick: function(data) {
            window.location = '/admin/add/' + data.item.id;
        }
    }); // end jsGrid

});