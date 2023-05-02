<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">{{trans_choice('general.edit',1)}}
        {{trans_choice('general.family',1)}} {{trans_choice('general.member',1)}}</h4>
</div>
{!! Form::open(array('url' => url('member/'.$family_member->id.'/family/update_family_member'),'method'=>'post')) !!}
<div class="modal-body">
    <div class="form-group">
        <div class="form-line">
            {!!  Form::label( 'family_role',trans_choice('general.role',1),array('class'=>' control-label')) !!}
            {!! Form::select('family_role',['adult'=>trans_choice('general.adult',1),'spouse'=>trans_choice('general.spouse',1),'head'=>trans_choice('general.head',1),'child'=>trans_choice('general.child',1),'unassigned'=>trans_choice('general.unassigned',1)],$family_member->family_role,array('class'=>'form-control','placeholder'=>'','required'=>'required')) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-info">{{trans_choice('general.save',1)}}</button>
    <button type="button" class="btn default" data-dismiss="modal">{{trans_choice('general.close',1)}}</button>
</div>
{!! Form::close() !!}