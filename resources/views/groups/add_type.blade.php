@extends('layouts.master')
@section('title')
    Add Group Type
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Add Group Type</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => url('Groups/create_group_type'), 'method' => 'post', 'id' => 'add_group_type',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
			<div class="form-group">
				<label>Branches</label>
				<select class="branch_id form-control" name="branch_id">
					<option value="0">Select Branch</option>
					@if(!empty($branches))
						@foreach($branches as $key => $branch)
							<option value="{{$branch->id}}">{{$branch->name}}</option>
						@endforeach
					@endif
				</select>
			</div>
            <div class="form-group">
				<label>Group Type</label>
				<input type="text" name="group_type" class="form-control group_type">
            </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right" id="add_group_type">{{trans_choice('general.save',1)}}</button>
        </div>
        {!! Form::close() !!}
    </div>
<!-- /.box -->

@endsection
@section('footer-scripts')
    <script>
        $('#jstree_div').jstree({
            "core": {
                "themes": {
                    "responsive": true
                },
                // so that create works
                "check_callback": true,
            },
            "plugins": ["checkbox", 'wholerow'],
        });
        $('#add_member').click(function (e) {
            e.preventDefault();
            $('#tags').val($('#jstree_div').jstree("get_selected"))
            $('#add_member_form').submit();
        })
		/*Create Field*/
		$(document).on('click','.create_field',function(){
			var elem = $(this);
			var field_name = elem.parent().parent().find('.custom_field_name').val();
			var Html = '';
			console.log(field_name);
			if(field_name != '')
			{
				Html += '<div class="row">';
				Html += '<div class="col-lg-12 col-sm-12 col-md-12">';				
				Html += '<label>'+field_name+'</label>';
				Html += '<input type="hidden" name="custom_field_label[]">';
				Html += '<input type="text" name="custom_field[]" class="form-control custom_field">';
				Html += '</div>';
				Html += '</div>';				
				Html += '</div>';				
			}
			$('.close').click();
			elem.parent().parent().find('.custom_field_name').val('');
			$('.custom-field').append(Html);
			
		});
		
    </script>
@endsection

