<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">*</span></button>
    <h4 class="modal-title">{{trans_choice('general.edit',2)}} {{trans_choice('general.volunteer',1)}}</h4>
</div>
{!! Form::open(array('url' => url('event/'.$volunteer->id.'/update_volunteer'),'method'=>'post','id'=>'')) !!}
<div class="modal-body">
    <input type="hidden" value="{{$volunteer->id}}" name="event_id">
    <div class="form-group">
        <div class="form-line">
            {!!  Form::label('roles',trans_choice('general.role',2),array('class'=>' control-label')) !!}
            {!! Form::select('roles[]',$roles,unserialize($volunteer->roles),array('class'=>'form-control select2','id'=>'roles','multiple'=>'multiple')) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('notes',trans_choice('general.note',2),array('class'=>'')) !!}
        {!! Form::textarea('notes',$volunteer->notes, array('class' => 'form-control tinymce','rows'=>'4')) !!}
    </div>
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-info">{{trans_choice('general.save',2)}}</button>
    <button type="button" class="btn default"
            data-dismiss="modal">{{trans_choice('general.close',2)}}</button>
</div>
{!! Form::close() !!}
