@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 32px;">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <h1>Sign In</h1>
                <form id="loginStudentForm" onsubmit="login_student(event)">
                    <div id="loginStudentMsg"></div>
                    <div class="form-group mb-3">
                        <label for="">Username:</label>
                        <input type="text" class="form-control" name="username" id="username"
                            value="{{ session()->has('remember_username') ? session('remember_username') : '' }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Password:</label>
                        <input type="password" class="form-control" name="password" id="password"
                            value="{{ session()->has('remember_password') ? Crypt::decryptString(session('remember_password')) : '' }}">
                    </div>
                    <div class="form-group mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                            {{ session()->has('remember_username') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" form="loginStudentForm" id="loginStudentBtn">Sign
                            In</button>
                    </div>

                    <p class="float-end">Don't have an account? <a href="{{ route('students.showSignup') }}">Signup</a></p>
                </form>
            </div>
        </div>

    </div>

    {{-- @if (session()->has('remember_username'))
        <p>Username: {{ session('remember_username') }}</p>
        <p>Password: {{ Crypt::decryptString(session('remember_password')) }}</p>
    @else
        <p>No Remembered Login</p>
    @endif --}}
@endsection

@section('scripts')
    <script type="text/javascript">
        let timeoutId;

        function login_student(event) {
            // console.log(event)
            event.preventDefault()

            let form = $('#loginStudentForm');
            let data = $(form).serializeArray()
            let submitBtn = $('#loginStudentBtn');

            data.push({
                name: '_token',
                value: $('meta[name="csrf-token"]').attr('content')
            });

            submitBtn.prop('disabled', true);

            $.ajax({
                type: "POST",
                url: "{{ route('students.login') }}",
                data: data,
                dataType: "json",
                success: function(response) {
                    clearTimeout(timeoutId);
                    console.log(response)
                    if (response.status === 400) {
                        $('#loginStudentMsg').html('')
                        $('#loginStudentMsg').addClass('alert alert-danger')
                        $.each(response.errors, function(key, err) {
                            $('#loginStudentMsg').append(`<p class='m-0'>${err}</p>`)
                        })
                    } else if (response.status === 401) {
                        $('#loginStudentMsg').html('')
                        $('#loginStudentMsg').addClass('alert alert-danger')
                        $('#loginStudentMsg').append(`<p class='m-0'>${response.message}</p>`)
                        // window.location.href = '/'
                    } else if (response.status === 200) {
                        $(form)[0].reset()
                        window.location.href = '/'
                    }

                    timeoutId = setTimeout(() => {
                        $('#loginStudentMsg').html('')
                        $('#loginStudentMsg').removeClass('alert alert-danger')
                        $('#loginStudentMsg').removeClass('alert alert-success')
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
