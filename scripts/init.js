/**
 * Initializes the data grid and retrieves the data using an ajax request
 */
$(function() {

    $("#jsGrid").jsGrid({
        height: "auto",
        width: "100%",
        filtering: true,
        sorting: true,
        autoload: true,
        pageSize: 10,
        pageButtonCount: 5,
        controller: {
            loadData: function() {
                return $.ajax({
                    type: "GET",
                    url: "/src/data-request.php",
                });
            } // end loadData
        }, // end controller
        fields: [
            { name: "name", title: "Nombre de Categoria", type: "text", width: 255 },
            { name: "end_date", title: "Cierre", type: "text", width: 100 },
            { name: "convener", title: "Convocante", type: "text", width: 150 },
            { type: "control", editButton: false, deleteButton: false }
        ] // end fields
    }); // end jsGrid

});