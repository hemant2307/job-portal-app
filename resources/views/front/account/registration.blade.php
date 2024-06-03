@extends("front.layout.main")

@section('main')
<section class="section-5">
    <div class="container my-5">
        <div class="py-lg-2">&nbsp;</div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-5">
                    <h1 class="h3">Register</h1>
                    <form action="" method="" id="registrationForm" name="registrationForm">
                        <div class="mb-3">
                            <label for="" class="mb-2">Name*</label>
                            <input type="text" name="name" id="name" class="form-control " placeholder="Enter Name" value="{{ old('name') }}">
                          {{-- <div> <p> @error('name') {{ $message }} @enderror</p></div> --}}
                          <p class="error_name"></p>

                        </div> 
                        <div class="mb-3">
                            <label for="" class="mb-2 ">Email*</label>
                            <input type="email" name="email" id="email" class="form-control " placeholder="Enter Email" value="{{ old('email') }}">
                            <p class="error_email"></p>
                        </div> 
                        <div class="mb-3">
                            <label for="" class="mb-2">Password*</label>
                            <input type="password" name="password" id="password" class="form-control " placeholder="Enter Password">
                            <p class="error_password"></p>
                        </div> 
                        <div class="mb-3">
                            <label for="" class="mb-2">Confirm Password*</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control " placeholder="Enter confirm Password">
                        <p class="error_c_pass"></p>
                        </div> 
                        <button class="btn btn-primary mt-2">Register</button>
                    </form>                    
                </div>
                <div class="mt-4 text-center">
                    <p>Have an account? <a  href="{{ route('account.login') }}">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('customJs')
<script>
    $("#registrationForm").submit(function(e){
        e.preventDefault();

        $.ajax({
            url: '{{ route("account.registerProcess") }}' ,
            type: 'POST',
            dataType:'json',
            // data: $(this).serialize(),
            data: $('#registrationForm').serialize(),
            success: function (responce) {
                if(responce.status == false){
                  var errors = responce.errors;
                  if(errors.name){
                    $(".error_name").text(errors.name[0]);
                  }else{
                    $(".error_name").text("");
                  }
                  if(errors.email){
                    $('.error_email').text(errors.email[0]);
                  }else{
                    $('.error_email').text("");
                  }
                  if(errors.password){
                    $(".error_password").text(errors.password[0]);
                  }else{
                    $(".error_password").text("");
                  }
                  if(errors.confirm_password){
                    $(".error_c_pass").text(errors.confirm_password[0]);
                  }else{
                    $(".error_c_pass").text("");
                  }
            }else{
                 window.location.href = '{{ route("account.login") }}';
            }
        }
    })
});

</script>

@endsection