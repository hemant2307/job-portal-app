@extends('front.layout.main')

@section('main')

<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('admin.jobs.list') }}">Jobs</a></li>
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
                    <div class="card-body card-form">
                        <div class="d-flex justify-content-between">                           
                            <div>
                                <h3 class="fs-4 mb-1">All Jobs</h3>
                            </div>
                            <div style="margin-top: -10px;">
                                <a href="{{ route('account.createJob') }}" class="btn btn-primary">Post a Job</a>
                            </div>
                            
                        </div>
                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Created By</th>
                                        <th scope="col">Created At </th>                                               
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                   
                                        @if($jobs != null)
                                        @foreach ( $jobs as $job )
                                        <tr>
                                      
                                        <td>{{ $job->id }}</td>
                                        <td>
                                           <p> {{ $job->title }}</p>
                                            <p>Application : {{ $job->Application->count() }}</p>
                                        
                                        </td>
                                        <td>{{ $job->User->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($job->created_at)->format('d M, Y') }}</td>
                                        <td>
                                            <div class="action-dots ">
                                                <button href="#" class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v" aria-hidden="true"> Action</i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    {{-- <li><a class="dropdown-item" href="{{ route('job.jobdetail',$job->id) }}"> <i class="fa fa-eye" aria-hidden="true"></i> View</a></li> --}}
                                                    <li><a class="dropdown-item" href="{{ route('admin.user.jobs.edit',$job->id) }}"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="deleteJob({{ $job->id }})"   ><i class="fa fa-trash" aria-hidden="true"></i> Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                     
                                    </tr>
                                    @endforeach
                                    @endif
                                   


                                </tbody>
                              
                            </table>
                            {{ $jobs->links() }}
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

function  deleteJob(jobId){  
    if(confirm('are you sure you want to delete this Job ?')){
        $.ajax({
            'url' : '{{ route("admin.user.jobs.delete") }}',
            'type': 'delete',
            'data': {jobId : jobId},
            'dataType':  'json',
            success: function(response){
                window.location.href = "{{ route('admin.jobs.list') }}";
            }
        })


    }
}



    
</script>


@endsection