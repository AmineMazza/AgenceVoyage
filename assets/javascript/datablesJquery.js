import 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';

$(document).ready(function () {
    $('#DataTables-Table').DataTable({
        language: {
            info: '',
            search: '',
            "lengthMenu": "Afficher _MENU_ resultats",
        },
        initComplete: function(settings, json) {
            $('.dataTables_filter input[type="search"]').addClass('bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500').attr("placeholder", "Type here to search");
            $('.dataTables_length').find('label').addClass('block mb-2 text-sm font-medium text-gray-900 dark:text-white');
            $('.form-select').addClass('bg-gray-50 inline-block border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500')
            $('#DataTables-Table_wrapper .row:first').addClass('flex justify-around space-x-40 mb-6');
        },
    })
});