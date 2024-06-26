<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students</title>
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
</head>
<body>
    <?php include('../includes/navbar_admin.php'); ?>
    <div class="container mt-5">
        <h2>Students</h2>
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#add-student-modal">Add Student</button>
        <input style='display:none' type="file" id="pdfInput" accept=".pdf">
        <button onclick="uploadPDF()" id="uploadPDFBtn">Upload PDF</button>
        <table id="studentsTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th style="display: none;">ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Date of Birth</th>
                    <th>Study Year</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be populated dynamically through JavaScript -->
            </tbody>
        </table>
    </div>
    
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000">
        <div class="toast-header">
            <strong class="mr-auto">Notification</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            <!-- Toast Message -->
        </div>
    </div>
    <!-- Bootstrap Modal for Edit Student -->
    <div id="edit-student-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Student</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-student-form">
                        <input type="hidden" id="edit-student-id">
                        <div class="form-group">
                            <label for="edit-first-name">First Name:</label>
                            <input type="text" class="form-control" id="edit-first-name">
                        </div>
                        <div class="form-group">
                            <label for="edit-last-name">Last Name:</label>
                            <input type="text" class="form-control" id="edit-last-name">
                        </div>
                        <div class="form-group">
                            <label for="edit-email">Email:</label>
                            <input type="email" class="form-control" id="edit-email">
                        </div>
                        <div class="form-group">
                            <label for="edit-date_of_birth">Date of Birth:</label>
                            <input type="date" class="form-control" id="edit-date_of_birth">
                        </div>
                        <div class="form-group">
                            <label for="edit-study-year">Study Year:</label>
                            <input type="text" class="form-control" id="edit-study-year">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="update-student-btn">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap Modal for Add Student -->
    <div id="add-student-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Student</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-student-form">
                        <div class="form-group">
                            <label for="add-first-name">First Name:</label>
                            <input type="text" class="form-control" id="add-first-name">
                        </div>
                        <div class="form-group">
                            <label for="add-last-name">Last Name:</label>
                            <input type="text" class="form-control" id="add-last-name">
                        </div>
                        <div class="form-group">
                            <label for="add-email">Email:</label>
                            <input type="email" class="form-control" id="add-email">
                        </div>
                        <div class="form-group">
                            <label for="add-date_of_birth">Date of Birth:</label>
                            <input type="date" class="form-control" id="add-date_of_birth">
                        </div>
                        <div class="form-group">
                            <label for="add-study-year">Study Year:</label>
                            <input type="text" class="form-control" id="add-study-year">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="add-student-btn">Add</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Bootstrap 5 JS -->
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#studentsTable').DataTable({
                ajax: {
                    url: "get_students.php", // Endpoint to fetch students data
                    dataSrc: ""
                },
                columns: [
                    { data: "id", visible: false }, // Hide ID column
                    { data: "first_name" },
                    { data: "last_name" },
                    { data: "email" },
                    { data: "date_of_birth" },
                    { data: "study_year" },
                    { 
                        data: null,
                        render: function(data, type, row) {
                            return '<button class="btn btn-primary btn-sm edit-btn">Edit</button>' +
                                '<button class="btn btn-danger btn-sm delete-btn" data-id="' + row.id + '">Delete</button>'
                                + '<button class="export-student-btn">Export</button>';
                        }
                    }
                ]
            });

            $('#add-student-btn').on('click', function() {
                $('#add-student-modal').show();
            });

            $('#submit-student-btn').on('click', function() {
    var first_name = $('#first_name').val();
    var last_name = $('#last_name').val();
    var email = $('#email').val();
    var date_of_birth = $('#date_of_birth').val();
    var study_year = $('#study_year').val();

    // Check if all fields are filled
    if (first_name.trim() === '' || last_name.trim() === '' || email.trim() === '' || date_of_birth.trim() === '' || study_year.trim() === '') {
        showToast("vull alle informatie van de student in.");
        return;
    }

    $.ajax({
        url: 'add_student.php',
        method: 'POST',
        data: { first_name: first_name, last_name: last_name, email: email, date_of_birth: date_of_birth, study_year: study_year },
        success: function(response) {
            table.ajax.reload();
            $('#add-student-modal').hide();
            showToast("Student is toegevoegd.");
        }
    });
});

function showToast(message) {
    $('.toast-body').text(message);
    $('.toast').toast('show');
}


            $('#students-table tbody').on('click', '.edit-student-btn', function() {
                var rowData = table.row($(this).closest('tr')).data();
                if (rowData && rowData.id) {
                    $('#edit-student-id').val(rowData.id);
                    $('#edit-first-name').val(rowData.first_name);
                    $('#edit-last-name').val(rowData.last_name);
                    $('#edit-email').val(rowData.email);
                    $('#edit-date_of_birth').val(rowData.date_of_birth);
                    $('#edit-study-year').val(rowData.study_year);
                    $('#edit-student-modal').modal('show');
                } else {
                    console.error("No data found for the row.");
                }
            });

            // Handle update student button click
            $('#update-student-btn').on('click', function() {
                var id = $('#edit-student-id').val();
                var firstName = $('#edit-first-name').val();
                var lastName = $('#edit-last-name').val();
                var email = $('#edit-email').val();
                var date_of_birth = $('#edit-date_of_birth').val();
                var studyYear = $('#edit-study-year').val();

                $.ajax({
                    url: 'update_student.php',
                    method: 'POST',
                    data: { id: id, first_name: firstName, last_name: lastName, email: email, date_of_birth: date_of_birth, study_year: studyYear, course_id: courseId },
                    success: function(response) {
                        $('#edit-student-modal').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error updating student:", error);
                    }
                });
            });

            // Handle delete button click
            $('#studentsTable tbody').on('click', '.delete-btn', function() {
                var studentId = $(this).data('id');
                if (studentId) {
                    $.ajax({
                        url: 'delete_student.php',
                        method: 'POST',
                        data: { id: studentId },
                        success: function(response) {
                            table.ajax.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error("Error deleting student:", error);
                        }
                    });
                } else {
                    console.error("No data found for the row.");
                }
            });

            // Handle add student button click
            $('#add-student-btn').on('click', function() {
                var firstName = $('#add-first-name').val();
                var lastName = $('#add-last-name').val();
                var email = $('#add-email').val();
                var date_of_birth = $('#add-date_of_birth').val();
                var studyYear = $('#add-study-year').val();

                $.ajax({
                    url: 'add_student.php',
                    method: 'POST',
                    data: { first_name: firstName, last_name: lastName, email: email, date_of_birth: date_of_birth, study_year: studyYear },
                    success: function(response) {
                        $('#add-student-modal').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error adding student:", error);
                    }
                });
            });
        });

    function readPDF(file) {
        // console.log(file);
        // let formData = new FormData();
        // formData.append('file', file);
        // console.log(formData);
        
        $.ajax({
            url: 'read_pdf.php',
            method: 'POST',
            data: { html_content: file },
            processData: false, 
            contentType: false,
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    
    
    function uploadPDF() {
        document.getElementById("pdfInput").click(); // Trigger file input click
    }
    
    // Event listener for file input change
    document.getElementById('pdfInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        // console.log(file);
        if (file) {
            readPDF(file);
        }
    });
    </script>

    <?php include('../includes/footer.php'); ?>
</body>
</html>
