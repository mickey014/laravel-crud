@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 32px;">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <h1>Sign Up</h1>
                <form id="signupStudentForm" onsubmit="signup_student(event)">
                    <div id="signupStudentMsg"></div>
                    <div class="form-group mb-3">
                        <label for="">Name:</label>
                        <input type="text" class="form-control" name="name" id="name">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Username:</label>
                        <input type="text" class="form-control" name="username" id="username">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Password:</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Confirm Password:</label>
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" form="signupStudentForm" id="signupStudentBtn">Sign
                            Up</button>
                    </div>

                    <p class="float-end">Have an account? <a href="{{ route('students.showLogin') }}">Sign In</a></p>
                </form>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        let timeoutId;

        function signup_student(event) {
            // console.log(event)
            event.preventDefault()

            let form = $('#signupStudentForm');
            let data = $(form).serializeArray()
            let submitBtn = $('#signupStudentBtn');

            data.push({
                name: '_token',
                value: $('meta[name="csrf-token"]').attr('content')
            });

            submitBtn.prop('disabled', true);

            $.ajax({
                type: "POST",
                url: "{{ route('students.signup') }}",
                data: data,
                dataType: "json",
                success: function(response) {
                    clearTimeout(timeoutId);
                    console.log(response)
                    if (response.status === 400) {
                        $('#signupStudentMsg').html('')
                        $('#signupStudentMsg').addClass('alert alert-danger')
                        $.each(response.errors, function(key, err) {
                            $('#signupStudentMsg').append(`<p class='m-0'>${err}</p>`)
                        })
                    } else if (response.status === 200) {
                        $(form)[0].reset()
                        $('#signupStudentMsg').html('')
                        $('#signupStudentMsg').addClass('alert alert-success')
                        $('#signupStudentMsg').append(`<p class='m-0'>${response.message}</p>`)
                    }

                    timeoutId = setTimeout(() => {
                        $('#signupStudentMsg').html('')
                        $('#signupStudentMsg').removeClass('alert alert-danger')
                        $('#signupStudentMsg').removeClass('alert alert-success')
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
    </script>
@endsection
