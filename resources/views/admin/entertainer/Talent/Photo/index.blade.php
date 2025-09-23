@extends('admin.layout.app')

@section('title', 'index')

@section('content')

    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            {{-- @dd($data['photos']) --}}
            <div class="section-body">
                <a class="btn btn-primary mb-3"
                href="{{route('entertainer.show',['user_id'=>$data['user_id']])}}">Back</a>
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Photos</h4>
                                </div>
                            </div>
                            {{-- @dd($data) --}}
                            <div class="card-body table-striped table-bordered table-responsive">
                                <table class="table" id="table_id_2">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Sr.</th>
                                            <th class="text-center">Images</th>

                                            <th scope="col" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                     @foreach($data['photos'] as $photo)
                                            <tr>
                                                {{-- @dd($photo->event_photos) --}}
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center"><img src="{{ asset('public/admin/assets/img/entertainer') . '/' . basename($photo->event_photos) }}" alt="" height="50" width="50" class="image">
                                                </td>

                                                <td>

                                                    <div style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                        
                                                        {{-- <a class="btn btn-success"
                                                       href="{{route('entertainer.edit', $entertainer->id)}}">Categories</a> --}}
        
                                                        <a class="btn btn-info"
                                                        href="{{route('entertainer.photo.edit', ['user_id'=>$data['user_id'],'entertainer_details_id'=>$data['entertainer_details_id'],'photo_id'=>$photo->id])}}">Edit</a>
                                                                <form method="GET" action="{{ route('entertainer.talent.photo.delete',['photo_id'=>$photo->id]) }}">
                                                                    @csrf
                                                                    <input name="_method" type="hidden" value="DELETE">
                                                                    <button type="submit" class="btn btn-danger btn-flat show_confirm" data-toggle="tooltip" >Delete</button>
                                                                </form>
                                                    </div>
                                                           </td>
                                                        </tr>
                                      @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@section ('scripts')
@if (\Illuminate\Support\Facades\Session::has('message'))
<script>
    toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
</script>
@endif
<script>
    $(document).ready(function() {
        $('#table_id').DataTable();
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">

$('.show_confirm').click(function(event) {
          var form =  $(this).closest("form");
          var name = $(this).data("name");
          event.preventDefault();
          swal({
              title: `Are you sure you want to delete this record?`,
              text: "If you delete this, it will be gone forever.",
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              form.submit();
            }
          });
      });

</script>
@endsection
