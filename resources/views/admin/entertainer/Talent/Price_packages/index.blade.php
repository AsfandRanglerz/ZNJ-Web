@extends('admin.layout.app')

@section('title', 'index')

@section('content')

    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3"
                href="{{route('entertainer.show',['user_id'=>$data['user_id'],'entertainer_details_id'=>$data['entertainer_details_id']])}}">Back</a>
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Price Packages</h4>

                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                {{-- <a class="btn btn-primary mb-3"
                                href="{{route('entertainer.show',$data['user_id'])}}">Back</a> --}}
                                <a class="btn btn-success mb-3"
                                href="{{route('entertainer.talent.price_packages.add',['user_id'=>$data['user_id'],'entertainer_details_id'=>$data['entertainer_details_id']])}}">Add Price Package</a>
                                <table class="table" id="table_talent_price">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Price</th>
                                            <th>Number of Hours</th>
                                            <th>Created At</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     @foreach($data['price_packages'] as $price)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>$ {{ $price->price_package }}</td>
                                                @if( explode(':',$price->time)[0]>=12)
                                                <td>{{ $price->time }}</td>
                                                @else
                                                <td>{{ $price->time }}</td>
                                                @endif
                                               <td>{{ $price->created_at }}</td>
                                                <td>
                                                    <div style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                        <a class="btn btn-info"
                                                       href="{{route('entertainer.talent.price_packages.edit',['user_id'=>$data['user_id'],'price_package_id'=>$price->id])}}">Edit</a>
                                                                <form method="POST" action="{{ route('entertainer.talent.price_packages.delete', $price->id) }}">
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
        $('#table_talent_price').DataTable();
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

