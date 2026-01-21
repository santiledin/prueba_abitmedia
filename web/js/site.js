$(function () {
    // Initialize Bootstrap 5 Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle Delete Confirmation Modal
    $(document).on('click', '.btn-delete-confirm', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var confirmMessage = $(this).data('confirm-message') || '¿Estás seguro de que deseas eliminar este elemento?';

        // Update modal content
        $('#deleteConfirmationModal .modal-body p').text(confirmMessage);

        // Handle Delete Button Click inside Modal
        $('#confirmDeleteBtn').off('click').on('click', function () {
            // Yii2 prefers POST requests for deletion so we can create a form or use data-method="post" logic if we were using yii.js, 
            // but here we are intercepting. The cleanest way is to use Yii's handleAction or a simple form submit.
            // Since we receive a URL, we can create a form dynamically.

            var form = $('<form>', {
                'method': 'POST',
                'action': url
            });

            // Add CSRF token
            var csrfParam = $('meta[name="csrf-param"]').attr("content");
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            if (csrfParam && csrfToken) {
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': csrfParam,
                    'value': csrfToken
                }));
            }

            $('body').append(form);
            form.submit();
        });

        // Show Modal
        var myModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
        myModal.show();
    });
});
