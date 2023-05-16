$(document).ready(function () {
    $('#allStudentsTable').DataTable({
        "order": [[1, "desc" ]],
        // ordering type by asc & year col
        "columnDefs": [
            {"orderData": [4, 2], "targets": 4}
        ],
        "aoColumns": [
            null,
            null,
            null,
            null,
            null,
        ],
        buttons: [
            'csv'
        ],
        "paging": true,
        "info": true,
        "searching": true
    })
});
