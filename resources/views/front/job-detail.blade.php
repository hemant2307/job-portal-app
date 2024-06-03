@extends('front.layout.main')

@section('main')
<section class="section-4 bg-2">    
    <div class="container pt-5">
        <div class="row">
            <div class="col">
                @if(Session::has('error'))
                <p class="alert alert-danger">{{ Session::get('error') }}</p>
                @endif
                @if(Session::has('success'))
                <p class="alert alert-success">{{ Session::get('success') }}</p>
                @endif
                <nav aria-label="breadcrumb" class=" rounded-3 p-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home.job') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp;Back to Jobs</a></li>
                    </ol>
                </nav>
            </div>
        </div> 
    </div>
    <div class="container job_details_area">
        <div class="row pb-5">
            <div class="col-md-8">
                <div class="card shadow border-0">
                    <div class="job_details_header">
                        <div class="single_jobs white-bg d-flex justify-content-between">
                            <div class="jobs_left d-flex align-items-center">
                                
                                <div class="jobs_conetent">
                                    <a href="#">
                                        <h4>{{ $jobs->title }}</h4>
                                    </a>
                                    <div class="links_locat d-flex align-items-center">
                                        <div class="location">
                                            <p> <i class="fa fa-map-marker"></i>{{ $jobs->location }}</p>
                                        </div>
                                        <div class="location">
                                            <p> <i class="fa fa-clock-o"></i>{{ $jobs->jobType->job_type }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="jobs_right">
                                <div class="apply_now">
                                    <a class="heart_mark {{ ($count == 1)? 'saved_job' : '' }}" href="javascript:void(0)" onclick="savedJob({{ $jobs->id }})"> <i class="fa fa-heart-o" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="descript_wrap white-bg">
                        @if($jobs->description != '')
                        <div class="single_wrap">
                            <h4>Job description</h4>
                            {!! nl2br ($jobs->description) !!}
                        </div>
                        @endif
                        @if($jobs->responsibility != '')
                        <div class="single_wrap">
                            <h4>Responsibility</h4>
                            <ul>
                                {!! nl2br ($jobs->responsibility) !!}
                            </ul>
                        </div>
                        @endif
                        @if($jobs->qualification != '')
                        <div class="single_wrap">
                            <h4>Qualifications</h4>
                            <ul>
                                {!! nl2br ($jobs->qualification) !!}
                            </ul>
                        </div>
                        @endif
                        @if($jobs->benifits != '')
                        <div class="single_wrap">
                            <h4>Benefits</h4>
                            {!! nl2br( $jobs->benifits) !!}
                        </div>
                        @endif
                        <div class="border-bottom"></div>
                        <div class="pt-3 text-end">
                            
                            @if(Auth::check())
                            <a href="#" onclick="saveJob('{{ $jobs->id }}')" class="btn btn-secondary">Save</a>
                            @else
                            <a href="javascript::void(0)" class="btn btn-secondary disabled">Login to Save</a>
                            @endif
                            @if(Auth::check())
                            <a href="#" onclick="applyJob('{{ $jobs->id }}')" class="btn btn-primary">Apply</a>
                            @else
                            <a href="javascript::void(0)" class="btn btn-primary disabled">Login to Apply</a>
                            @endif
                        </div>
                    </div>
                </div>

            @if (Auth::user())
            @if (Auth::user()->id == $jobs->user_id)

             {{-- applicants page start --}}
             <div class="card shadow border-0 mt-4">
                <div class="job_details_header">
                    <div class="single_jobs white-bg d-flex justify-content-between">
                        <div class="jobs_left d-flex align-items-center">
                            
                            <div class="jobs_conetent">
                                <a href="#">
                                    <h4>Job Applicants</h4>
                                </a> 
                            </div>
                        </div>
                        <div class="jobs_right"></div>
                    </div>
                </div>
                <div class="descript_wrap white-bg">
                    <table class="table table-striped">
                        <tr >
                            <th>ID</th>
                            <th>name</th>
                            <th>email</th>
                            <th>Mobile</th>
                            <th>Applied date</th>
                        </tr>
                            @if ($applicants->isNotEmpty() )
                            @foreach ($applicants as $applicant)
                            <tr>
                            <td>{{ $applicant->user->id }}</td>
                            <td>{{ $applicant->user->name }}</td>
                            <td>{{ $applicant->user->email }}</td>
                            <td>{{ $applicant->user->mobile }}</td>
                            <td>{{ \carbon\carbon::parse($applicant->applied_date)->format('d M , Y') }}</td>
                        </tr>
                            @endforeach
                            @else
                            <tr>
                                <td collspan='3'>No application found</td>
                            </tr>
                         @endif                 
                    </table>
                    </div>
                </div>
          {{-- applicants page end --}}            
            @endif                
    @endif
        </div>

            <div class="col-md-4">
                <div class="card shadow border-0">
                    <div class="job_sumary">
                        <div class="summery_header pb-1 pt-4">
                            <h3>Job Summery</h3>
                        </div>
                        <div class="job_content pt-3">
                            <ul>
                                <li>Published on: <span>{{ \carbon\carbon::parse($jobs->created_at)->format('D M,y') }}</span></li>
                                <li>Vacancy: <span>  {{ $jobs->vacancy }}</span></li>
                                @if($jobs->salary != '')
                                <li>Salary: <span>  {{ $jobs->salary }}</span></li>
                                @endif
                                @if($jobs->location != '')
                                <li>Location: <span>  {{ $jobs->location }}</span></li>
                                @endif
                                <li>Job Nature: <span>  {{ $jobs->jobType->job_type }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card shadow border-0 my-4">
                    <div class="job_sumary">
                        <div class="summery_header pb-1 pt-4">
                            <h3>Company Details</h3>
                        </div>
                        <div class="job_content pt-3">
                            <ul>
                                <li>Name: <span>  {{ $jobs->company_name }}</span></li>
                                <li>Locaion: <span>  {{ $jobs->company_location }}</span></li>
                                <li>Webite: <span><a href="{{ $jobs->company_website }}">{{ $jobs->company_website }}</a></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')

<script type="text/javascript">

function applyJob(id){
    if(confirm("are you sure to apply on this job")){
        $.ajax({
            url : '{{ route("job.jobapply") }}',
            type : 'POST',
            data : {id:id},
            dataType : 'json',
            success : function(response) {
                window.location.href = '{{ url()->current() }}';
            }
        })
    }
}


function saveJob(id){
    if(confirm("are you sure to apply on this job")){
        $.ajax({
            url : '{{ route("job.saveJob") }}',
            type : 'POST',
            data : {id:id},
            dataType : 'json',
            success : function(response) {
                window.location.href = '{{ url()->current() }}';

            }
        })
    }

}



</script>



@endsection