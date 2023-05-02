@extends('layouts.master')
@section('title') {{ trans_choice('general.announcement',1) }} {{ trans_choice('general.type',1) }}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans_choice('general.announcement',1) }} {{ trans_choice('general.type',1) }}</h3>

            <div class="box-tools pull-right">
                <a href="#" data-toggle="modal" data-target="#announcement_type" class="btn btn-info btn-sm add">{{ trans_choice('general.add',1) }} {{ trans_choice('general.announcement',1) }} {{ trans_choice('general.type',1) }}</a>
            </div>
        </div>
        <div class="box-body">
            <table id="" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>{{ trans_choice('general.name',1) }}</th>
                    <th>{{ trans_choice('general.action',1) }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $key)
                    <tr>
                        <td>{{ $key->name }}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-info btn-flat dropdown-toggle"
                                        data-toggle="dropdown" aria-expanded="false">
                                    {{ trans('general.choose') }} <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#" class="edit" data-toggle="modal" data-target="#announcement_type" data-id="{{$key->id}}" data-name="{{$key->name}}"><i
                                                    class="fa fa-edit"></i> {{ trans('general.edit') }} </a></li>
                                    <li><a href="{{ url('announcement/type/'.$key->id.'/delete') }}"
                                           class="delete"><i
                                                    class="fa fa-trash"></i> {{ trans('general.delete') }} </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
<!-- Modal -->
<div class="modal fade" id="announcement_type" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Announcement Type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		  <div class="container-fluid">
			  <div class="row">
				  <div class="col-lg-12 col-sm-12 col-md-12">
					  <label>Announcement Name</label>
					  <input type="hidden" class="announcement_type_id form-control" name="announcement_type_id" value="0">
					  <input type="text" class="announcement_name form-control" name="annoucement">
				  </div>
			  </div>
		  </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary save_change">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- /.box -->
@endsection
@section('footer-scripts')
@endsection
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
/*Save */
$(document).on('click','.save_change',function(){
 	var announcement_name = $('.announcement_name').val();
 	var announcement_type_id = $('.announcement_type_id').val();
	
	$.ajax({
              url: "{{ url('/announcement/type/store') }}",
              method: 'post',
              data: {
                "_token": "{{ csrf_token() }}",
                 announcement_name : announcement_name,
				 announcement_type_id : announcement_type_id
              },
              success: function(result)
			  {
				  if(result.status == '302')
				  {
					  Swal.fire({
						  title: "warning",
						  text: "Announcement Type Already Exist!",
						  icon: "error",
						  timer: 1500,
						  showConfirmButton: false,
					  });

				  }
				  else
				  {
					  Swal.fire({
						  title: "Success",
						  text: "Announcement Type Added Successfully!",
						  icon: "success",
						  timer: 1500,
						  showConfirmButton: false,
					  });

					  location.reload();
				  }
					  

			  }
                      
            });
	
});

$(document).on('click','.edit',function(){
 	var announcement_name = $(this).attr('data-name');
 	var announcement_type_id = $(this).attr('data-id');
	$('.announcement_name').val(announcement_name);
	$('.announcement_type_id').val(announcement_type_id);
	
	console.log(announcement_name);

});
	

$(document).on('click','.add',function(){
	$('.announcement_name').val('');
	$('.announcement_type_id').val('0');
});
	
</script>
