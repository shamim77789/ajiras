@extends('layouts.master')
@section('title')
    Edit Group
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Edit Group</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => url('Groups/'.$groups->id.'/update_group'), 'method' => 'post', 'id' => 'edit_group',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
            <div class="form-group">
				<label>Group Type</label>
				<select class="form-control group_type" name="group_type">
					<option value="0">Select Group Type</option>
					@if(!empty($group_types))
						@foreach($group_types as $type)
							<option value="{{$type->id}}" {{($type->id == $groups->group_type ? 'selected' : '')}}>{{$type->group_type}}</option>
						@endforeach
					@endif
				</select>
            </div>
            <div class="form-group">
				<label>Group Name</label>
				<input type="text" name="group_name" class="form-control group_name" value="{{$groups->group_name}}">
            </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right" id="edit_group">{{trans_choice('general.save',1)}}</button>
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

