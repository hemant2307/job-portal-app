@extends('front.layout.main')

@section('main')

<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Admin Dashboard</li>
                    </ol>
                    @if (Session::has('success'))
                    <p class="alert alert-success" role="alert">{{ Session::get('success') }}</p>
                    @endif
                    @if (Session::has('error'))
                    <p class="alert alert-success" role="alert">{{ Session::get('error') }}</p>
                    @endif
                </nav>
            </div>
        </div>
        <div class="row">
            @include('admin.sidebar')
          
            <div class="col-lg-9">
                <div class="card border-0 shadow mb-4">
                    <div class="card-body">
                        <p class="p-5 text-center">welcome to the admin dashboard</p>
                    </div>
                </div>                         
            </div>
        </div>
    </div>
</section>

@endsection

@section('customJs')

<script type="text/javascript"> 
    $('#userForm').submit(function(e){
        e.preventDefault(); 
        $.ajax({
            'url' : '{{ (route("account.updateProfile")) }}',
            'type': 'POST',
            'data': $('#userForm').serialize(),
            'dataType': 'json',
            'success': function(response){ 
                if(response.status == false){ 
                    var errors = response.errors;  
                    if(errors.name){
                        $('#error_name').text(errors.name[0]);
                    }else{
                        $('#error_name').text('');
                    }
                    if(errors.email){
                        $('#error_email').text(errors.email[0]);
                    }else{
                        $('#error_email').text('');
                    }
                    if(errors.Designation){
                        $('#error_designation').text(errors.Designation[0]);
                    }else{
                        $('#error_designation').text('');
                    }
                    if(errors.mobile){
                        $('#error_mobile').text(errors.mobile[0]);
                    }else{
                        $('#error_mobile').text('');
                    }

                }else{
                    window.location.href='{{ route("account.profile") }}';
                }
            }
        });
    }); 


// for change password
    $('#changePasswordForm').submit(function(e){
        e.preventDefault(); 
        $.ajax({
            url : '{{ (route("job.changepassword")) }}',
            type: 'POST',
            data: $('#changePasswordForm').serialize(),
            dataType: 'json',
            success: function(response){ 
                if(response.status == false){ 
                    var errors = response.errors;  
                    if(errors.old_password){
                        $('#error_old_password').text(errors.old_password[0]);
                    }else{
                        $('#error_old_password').text('');
                    }
                    if(errors.new_passord){
                        $('#error_new_passord').text(errors.new_passord[0]);
                    }else{
                        $('#error_new_passord').text('');
                    }
                    if(errors.confirm_password){
                        $('#error_confirm_password').text(errors.confirm_password[0]);
                    }else{
                        $('#error_confirm_password').text('');
                    }
                    
                }else{
                    window.location.href='{{ route("account.profile") }}';
                }
            }
        });
    }); 
</script>

@endsection