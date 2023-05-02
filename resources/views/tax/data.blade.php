@extends('layouts.master')
@section('title'){{trans_choice('general.tax',2)}}
@endsection
@section('current-page'){{trans_choice('general.tax',2)}}
@endsection
@section('content')
        <!-- Default box -->
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">{{trans_choice('general.tax',2)}}</h3>

        <div class="box-tools pull-right">
            <a data-toggle="modal" data-target="#addTax" class="btn btn-info btn-sm add">
                <i class="fa fa-plus"></i> {{trans_choice('general.tax',1)}}
            </a>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-bordered responsive table-stripped table-hover data-table">
            <thead>
            <tr>
                <th>{{trans_choice('general.name',1)}}</th>
                <th>{{trans_choice('general.amount',1)}}</th>
                <th>{{trans_choice('general.type',1)}}</th>
                <th>{{trans_choice('general.action',1)}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $key)
                <tr>
                    <td>{{ $key->name }}</td>
                    <td>{{ $key->amount}}</td>
                    <td>{{ ($key->type == '0' ? 'Fix' : 'Percentage')}}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-info btn-flat dropdown-toggle"
                                    data-toggle="dropdown" aria-expanded="false">
                                {{trans_choice('general.choose',1)}} <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a data-id="{{$key->id}}" data-toggle="modal" data-target="#addTax" class="edit" data-name="{{$key->name}}" data-amount="{{$key->amount}}" data-type="{{$key->type}}"><i
                                                class="fa fa-edit"></i>
                                        {{trans_choice('general.edit',1)}}</a></li>
                                <li><a href="{{ url('tax/'.$key->id.'/delete') }}"
                                       data-toggle="confirmation"><i
                                                class="fa fa-trash"></i> {{trans_choice('general.delete',1)}}</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
    <div class="modal" id="addTax">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Add Tax</h4>
                </div>
                {!! Form::open(array('url' => url('tax/store'),'method'=>'post')) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" style="">

                            <div class="form-group">
                                <div class="form-line">
                                    {!!  Form::label( 'Name',null,array('class'=>' control-label')) !!}
                                    {!! Form::text('name','',array('class'=>'form-control name','required'=>'required')) !!}
									<input type="hidden" name="tax_id" class="form-control tax_id" value="0">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-line">
                                    {!!  Form::label( 'Amount',null,array('class'=>' control-label')) !!}
									<input type="number" class="form-control amount" name="amount" placeholder="0.0">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-line">
                                    {!!  Form::label( 'Amount Type',null,array('class'=>' control-label')) !!}
									<select class="type form-control" name="type">
										<option value="0">Fix</option>
										<option value="1">Percentage</option>
									</select>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">Save</button>
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                </div>
                {!! Form::close() !!}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal" id="editTax">
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <script>
        $('#editTax').on('shown.bs.modal', function (e) {
            var id = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'GET',
                url: "{!!  url('/') !!}/tax/" + id + "/edit",
                success: function (data) {
                    $(e.currentTarget).find(".modal-content").html(data);
                }
            });
        });
		
		$(document).on('click','.add',function(){
			$('.name').val('');
			$('.amount').val('');
			$('.type').val('0');
			$('.tax_id').val('0');
		});

		$(document).on('click','.edit',function(){
			$('.name').val($(this).attr('data-name'));
			$('.amount').val($(this).attr('data-amount'));
			$('.type').val($(this).attr('data-type'));
			$('.tax_id').val($(this).attr('data-id'));

		});
	
	
	</script>
</div>
<!-- /.box -->
@endsection
