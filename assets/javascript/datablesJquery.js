import 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';

$(document).ready(function () {
    $('#DataTables-Table').DataTable({
        language: {
            info: '',
            search: '',
        },
        initComplete: function(settings, json) {
            $('.dataTables_filter input[type="search"]').addClass('my-custom-length-class');
            $('.dataTables_length').addClass('my-custom-length-class');
        },
    })
});