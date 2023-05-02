<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">*</span></button>
    <h4 class="modal-title">{{trans_choice('general.volunteer',1)}} {{trans_choice('general.detail',2)}}</h4>
</div>
<div class="modal-body">
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <tr>
                <td>{{trans_choice('general.role',2)}}</td>
                <td>
                    @foreach(unserialize($volunteer->roles) as $key)
                        @if(!empty(\App\Models\VolunteerRole::find($key)))
                            <span class="label label-success">{{\App\Models\VolunteerRole::find($key)->name}}</span>
                        @endif
                    @endforeach
                </td>
            </tr>
            <tr>
                <td>{{trans_choice('general.note',2)}}</td>
                <td>{!! $volunteer->notes !!}</td>
            </tr>
            <tr>
                <td>{{trans_choice('general.created_at',2)}}</td>
                <td>{!! $volunteer->created_at !!}</td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn default"
                data-dismiss="modal">{{trans_choice('general.close',2)}}</button>
    </div>

</div>
