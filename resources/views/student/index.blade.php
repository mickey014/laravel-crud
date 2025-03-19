@extends('layouts.app')

@section('content')
    <!-- Add Student Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addStudentModalLabel">Add Student</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addStudentForm" onsubmit="add_student(event)">
                        <div id="addStudentMsg"></div>
                        <div class="form-group mb-3">
                            <label for="">Name</label>
                            <input type="text" class="form-control" name="name" id="name">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Username</label>
                            <input type="text" class="form-control" name="username" id="username">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="addStudentForm" id="saveStudentBtn">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Student Modal -->
    <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editStudentModalLabel">Edit Student</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateStudentForm" onsubmit="update_student(event)">
                        <div id="updateStudentMsg"></div>
                        <input type="hidden" name="studentId" id="studentId">
                        <div class="form-group mb-3">
                            <label for="">Name</label>
                            <input type="text" class="form-control" name="name" id="name">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Username</label>
                            <input type="text" class="form-control" name="username" id="username">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Age</label>
                            <input type="number" class="form-control" name="age" id="age">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Email</label>
                            <input type="text" class="form-control" name="email" id="email">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Phone</label>
                            <input type="text" class="form-control" name="phone" id="phone">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Course</label>
                            <input type="text" class="form-control" name="course" id="course">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="updateStudentForm"
                        id="updateStudentBtn">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row mb-2">
            @if (Auth::guard('student')->check())
                <form action="{{ route('students.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger float-start"
                        onclick="return confirm('Are you sure you want to logout?')">Logout</button>
                </form>
            @endif

            @if (session()->has('student'))
                <div class="my-2">
                    <p class="m-0">Welcome, {{ session('student')['name'] }}!</p>
                    <p class="m-0">Username: {{ session('student')['username'] }}</p>
                    <p class="m-0">Email: {{ session('student')['email'] }}</p>
                    <p class="m-0">Age: {{ session('student')['age'] }}</p>
                    <p class="m-0">Phone: {{ session('student')['phone'] }}</p>
                </div>
            @endif
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Students Data
                            <a href="" data-bs-toggle="modal" data-bs-target="#addStudentModal"
                                class="btn btn-primary float-end btn-sm">Add Student</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered display" id="studentsTable">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Course</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="studentTbody">
                                <td colspan="6" class="text-center">
                                    <div class="spinner-border text-dark" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </td>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        let timeoutId;
        $(document).ready(() => {
            get_all_student()
        })



        function get_all_student() {
            $.ajax({
                type: 'GET',
                url: "{{ route('students.all') }}",
                dataType: 'json',
                success: function(response) {

                    if ($.fn.DataTable.isDataTable('#studentsTable')) {
                        $('#studentsTable').DataTable().destroy();
                    }

                    $('#studentTbody').html('');

                    $.each(response.students, function(key, item) {
                        $('#studentTbody').append(`
                            <tr>
                                <th>${item.id}</th>
                                <td>${item.name}</td>
                                <td>${item.email}</td>
                                <td>${item.phone}</td>
                                <td>${item.course}</td>
                                <td>
                                    <button 
                                    class="btn btn-primary btn-sm" 
                                    onclick="edit_student('${item.id}')"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editStudentModal">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="delete_student('${item.id}')">Delete</button>
                                </td>
                            </tr>
                        `);
                    });

                    $('#studentsTable').DataTable();
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                },
            });

        }

        function add_student(event) {
            // console.log(event)
            event.preventDefault()

            let form = $('#addStudentForm');
            let data = $(form).serializeArray()
            let submitBtn = $('#saveStudentBtn');

            data.push({
                name: '_token',
                value: $('meta[name="csrf-token"]').attr('content')
            });

            submitBtn.prop('disabled', true);

            $.ajax({
                type: "POST",
                url: "{{ route('students.store') }}",
                data: data,
                dataType: "json",
                success: function(response) {
                    clearTimeout(timeoutId);
                    if (response.status === 400) {
                        $('#addStudentMsg').html('')
                        $('#addStudentMsg').addClass('alert alert-danger')
                        $.each(response.errors, function(key, err) {
                            $('#addStudentMsg').append(`<p class='m-0'>${err}</p>`)
                        })
                    } else if (response.status === 200) {
                        $('#addStudentMsg').html('')
                        $('#addStudentMsg').addClass('alert alert-success')
                        $('#addStudentMsg').append(`<p class='m-0'>${response.message}</p>`)
                        get_all_student()
                        $(form)[0].reset()
                    }

                    timeoutId = setTimeout(() => {
                        $('#addStudentMsg').html('')
                        $('#addStudentMsg').removeClass('alert alert-danger')
                        $('#addStudentMsg').removeClass('alert alert-success')
                    }, 3000);

                },
                error: function(xhr, status, error) {
                    console.error("Error:", error)
                },
                complete: function() {
                    submitBtn.prop('disabled', false);
                }
            });

        }

        function edit_student(studentId) {
            $.ajax({
                type: 'GET',
                url: "{{ route('students.show') }}",
                data: {
                    studentId: studentId
                },
                dataType: 'json',
                success: function(response) {
                    $('#updateStudentForm #studentId').val(response.student.id)
                    $('#updateStudentForm #name').val(response.student.name)
                    $('#updateStudentForm #username').val(response.student.username)
                    $('#updateStudentForm #age').val(response.student.age)
                    $('#updateStudentForm #email').val(response.student.email)
                    $('#updateStudentForm #phone').val(response.student.phone)
                    $('#updateStudentForm #course').val(response.student.course)
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error)
                },
            })
        }

        function update_student(event) {
            event.preventDefault();

            let form = $('#updateStudentForm');
            let data = $(form).serializeArray()
            let updateBtn = $('#updateStudentBtn');


            data.push({
                name: '_token',
                value: $('meta[name="csrf-token"]').attr('content')
            });

            data.push({
                name: '_method',
                value: 'put'
            });

            updateBtn.prop('disabled', true);

            if (!confirm("Are you sure?")) {
                updateBtn.prop('disabled', false);
                return;
            }

            $.ajax({
                type: "POST",
                url: "{{ route('students.update') }}",
                data: data,
                dataType: "json",
                success: function(response) {
                    clearTimeout(timeoutId);
                    if (response.status === 400) {
                        $('#updateStudentMsg').html('')
                        $('#updateStudentMsg').addClass('alert alert-danger')
                        $.each(response.errors, function(key, err) {
                            $('#updateStudentMsg').append(`<p class='m-0'>${err}</p>`)
                        })
                    } else if (response.status === 200) {
                        $('#updateStudentMsg').html('')
                        $('#updateStudentMsg').addClass('alert alert-success')
                        $('#updateStudentMsg').append(`<p class='m-0'>${response.message}</p>`)
                        get_all_student()
                    }

                    timeoutId = setTimeout(() => {
                        $('#updateStudentMsg').html('')
                        $('#updateStudentMsg').removeClass('alert alert-danger')
                        $('#updateStudentMsg').removeClass('alert alert-success')
                    }, 3000);

                },
                error: function(xhr, status, error) {
                    console.error("Error:", error)
                },
                complete: function() {
                    updateBtn.prop('disabled', false);
                }
            });
        }

        function delete_student(studentId) {
            if (!confirm("Are you sure you want to delete this student?")) {
                return;
            }

            $.ajax({
                type: 'POST',
                url: "{{ route('students.delete') }}",
                data: {
                    studentId: studentId,
                    _method: 'DELETE',
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                dataType: 'json',
                success: function(response) {
                    alert(response.message)
                    get_all_student()
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error)
                },
            })
        }
    </script>
@endsection
