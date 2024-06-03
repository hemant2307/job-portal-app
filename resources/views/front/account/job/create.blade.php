@extends('front.layout.main')

@section('main')

<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Account Settings</li>
                    </ol>
                    @if (Session::has('success'))
                    <p class="alert alert-success" role="alert">{{ Session::get('success') }}</p>
                    @endif
                </nav>
            </div>
        </div>
        <div class="row">
            @include('front.account.sidebar')
            <div class="col-lg-9">
           <form action="" method="" name="createJobForm" id="createJobForm">
            <div class="card border-0 shadow mb-4 ">
                <div class="card-body card-form p-4">
                    <h3 class="fs-4 mb-1">Job Details</h3>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="" class="mb-2">Title<span class="req">*</span></label>
                            <input type="text" placeholder="Job Title" id="title" name="title" class="form-control">
                       <p class="error_title text-danger" id="error_title"></p>
                        </div>
                        <div class="col-md-6  mb-4">
                            <label for="" class="mb-2">Category<span class="req">*</span></label>
                            <select name="category" id="category" class="form-control">
                                <option value="">Select a Category</option>
                                @if($catagories != '')
                                @foreach ($catagories as $catagory)
                                <option value="{{ $catagory->id }}">{{ $catagory->category }}</option>
                                @endforeach
                                @endif
                        </select>
                        <p class="error_category text-danger" id="error_category"></p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="" class="mb-2">Job Nature<span class="req">*</span></label>
                            <select name="jobType" id="jobType"  class="form-select">
                                @if($jobTypes != '')
                                @foreach ($jobTypes as $jobType)
                                <option value="{{ $jobType->id }}">{{ $jobType->job_type }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6  mb-4">
                            <label for="" class="mb-2">Vacancy<span class="req">*</span></label>
                            <input type="number" min="1" placeholder="Vacancy" id="vacancy" name="vacancy" class="form-control">
                            <p class="error_vacancy text-danger" id="error_vacancy"></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-4 col-md-6">
                            <label for="" class="mb-2">Salary</label>
                            <input type="text" placeholder="Salary" id="salary" name="salary" class="form-control">
                        </div>

                        <div class="mb-4 col-md-6">
                            <label for="" class="mb-2">Location<span class="req">*</span></label>
                            <input type="text" placeholder="Location" id="location" name="location" class="form-control">
                            <p class="error_location text-danger" id="error_location"></p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="" class="mb-2">Description</label>
                        <textarea class="form-control" name="description" id="description" cols="5" rows="5" placeholder="Description"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="" class="mb-2">Benefits</label>
                        <textarea class="form-control" name="benefits" id="benefits" cols="5" rows="5" placeholder="Benefits"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="" class="mb-2">Responsibility</label>
                        <textarea class="form-control" name="responsibility" id="responsibility" cols="5" rows="5" placeholder="Responsibility"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="" class="mb-2">Qualifications</label>
                        <textarea class="form-control" name="qualifications" id="qualifications" cols="5" rows="5" placeholder="Qualifications"></textarea>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="" class="mb-2">Job Experiance<span class="req">*</span></label>
                        <select class="form-select "  name="experiance" id="experiance" class="form-control">
                            <option value="1">1 Year</option>
                            <option value="2">2 Year </option>
                            <option value="3">3 Year </option>
                            <option value="4">4 Year </option>
                            <option value="5">5 Year </option>
                            <option value="6">6 Year </option>
                            <option value="7">7 Year </option>
                            <option value="8">8 Year </option>
                            <option value="9">9 Year </option>
                            <option value="10">10 Year </option>
                            <option value="10_plus">10+ Year </option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="" class="mb-2">Keywords</label>
                        <input type="text" placeholder="keywords" id="keywords" name="keywords" class="form-control">
                    </div>

                    <h3 class="fs-4 mb-1 mt-5 border-top pt-5">Company Details</h3>

                    <div class="row">
                        <div class="mb-4 col-md-6">
                            <label for="" class="mb-2">Name<span class="req">*</span></label>
                            <input type="text" placeholder="Company Name" id="company_name" name="company_name" class="form-control">
                            <p class="error_company_name text-danger" id="error_company_name"></p>
                        </div>

                        <div class="mb-4 col-md-6">
                            <label for="" class="mb-2">Location</label>
                            <input type="text" placeholder="Location" id="company_location" name="company_location" class="form-control">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="" class="mb-2">Website</label>
                        <input type="text" placeholder="Website" id="company_website" name="company_website" class="form-control">
                    </div>
                </div> 
                <div class="card-footer  p-4">
                    <button type="submit" class="btn btn-primary">Save Job</button>
                </div>               
        </div>
    </div>
    </div>
           </form>
        </div>
    </div>
</section>

@endsection

@section('customJs')

<script type="text/javascript"> 
    $('#createJobForm').submit(function(e){
        e.preventDefault(); 
        $("button[type='submit']").prop("disabled", true);

        $.ajax({
            'url' :'{{ (route("account.saveJob")) }}',
            'type':'POST',
            'data': $('#createJobForm').serialize(),
            'dataType': 'json',
            'success': function(response){ 

                $("button[type='submit']").prop("disabled", false);

                if(response.status == false){ 
                    var errors = response.errors;  
                    if(errors.title){
                        $('#error_title').text(errors.title[0]);
                    }else{
                        $('#error_title').text('');
                    }
                    if(errors.category){
                        $('#error_category').text(errors.category[0]);
                    }else{
                        $('#error_category').text('');
                    }
                    if(errors.vacancy){
                        $('#error_vacancy').text(errors.vacancy[0]);
                    }else{
                        $('#error_vacancy').text('');
                    }
                    if(errors.location){
                        $('#error_location').text(errors.location[0]);
                    }else{
                        $('#error_location').text('');
                    }
                    if(errors.experiance){
                        $('#error_experiance').text(errors.experiance[0]);
                    }else{
                        $('#error_experiance').text('');
                    }
                    if(errors.company_name){
                        $('#error_company_name').text(errors.company_name[0]);
                    }else{
                        $('#error_company_name').text('');
                    }

                }else{
                    window.location.href='{{ route("account.myJob") }}';
                
                }
            }
        });
    }); 
</script>

@endsection