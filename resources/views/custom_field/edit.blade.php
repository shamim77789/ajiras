@extends('layouts.master')
@section('title')
{{trans_choice('general.edit',1)}} {{trans_choice('general.custom_field',1)}}
@endsection
@section('content')
        <!-- Default box -->
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{trans_choice('general.edit',1)}} {{trans_choice('general.custom_field',1)}}</h3>

        <div class="box-tools pull-right">

        </div>
    </div>
    {!! Form::open(array('url' => url('custom_field/'.$custom_field->id.'/update'), 'method' => 'post', 'name' => 'form')) !!}

    <div class="box-body">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('category',trans_choice('general.category',1),array('class'=>'')) !!}
                {!! Form::select('category',array('members'=>trans_choice('general.add',1).' '.trans_choice('general.member',1),'events'=>trans_choice('general.add',1).' '.trans_choice('general.event',1)),$custom_field->category, array('class' => 'form-control', 'placeholder'=>trans_choice('general.select',1),'required'=>'required')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('name',trans_choice('general.field',1).' '.trans_choice('general.name',1),array('class'=>'')) !!}
                {!! Form::text('name',$custom_field->name, array('class' => 'form-control', 'placeholder'=>trans_choice('general.field',1).' '.trans_choice('general.name',1),'required'=>'required')) !!}
            </div>
            <div class="form-group">
                <label>{{trans_choice('general.required_field',1)}} </label>
                <label><input type="radio" name="required" required value="0"
                              @if($custom_field->required==0) checked @endif> {{trans_choice('general.no',1)}}</label>
                <label><input type="radio" name="required" required value="1"
                              @if($custom_field->required==1) checked @endif> {{trans_choice('general.yes',1)}}</label>
            </div>
            <div class="form-group">
                <label>{{trans_choice('general.field',1)}} {{trans_choice('general.type',1)}} </label>
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th width="150">&nbsp;</th>
                        <th>{{trans_choice('general.description',1)}}</th>
                        <th>{{trans_choice('general.allowed_value',2)}}</th>
                    </tr>
                    <tr>
                        <td>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="field_type" id="inputType" value="textfield"
                                           required="" @if($custom_field->field_type=="textfield") checked @endif>
                                    <b>{{trans_choice('general.text_field',1)}}</b>

                                </label>
                            </div>
                        </td>
                        <td>{{trans_choice('general.text_field_description',1)}}</td>
                        <td>{{trans_choice('general.any_value',1)}}</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="field_type" id="inputType" value="date" required=""
                                           @if($custom_field->field_type=="date") checked @endif>
                                    <b>{{trans_choice('general.date_field',1)}}</b>

                                </label>
                            </div>
                        </td>
                        <td>{{trans_choice('general.date_field_description',1)}}</td>
                        <td>{{trans_choice('general.only_date',1)}}</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="field_type" id="inputType" value="number" required=""
                                           @if($custom_field->field_type=="number") checked @endif>
                                    <b>{{trans_choice('general.number_field',1)}}</b>

                                </label>
                            </div>
                        </td>
                        <td>{{trans_choice('general.number_field_description',1)}}</td>
                        <td>{{trans_choice('general.only_number',1)}}</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="field_type" id="inputType" value="decimal"
                                           required="" @if($custom_field->field_type=="decimal") checked @endif>
                                    <b>{{trans_choice('general.decimal_field',1)}}</b>

                                </label>
                            </div>
                        </td>
                        <td>{{trans_choice('general.decimal_field_description',1)}}</td>
                        <td>{{trans_choice('general.only_decimal',1)}}</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="field_type" id="inputType" value="textarea"
                                           required="" @if($custom_field->field_type=="textarea") checked @endif>
                                    <b>{{trans_choice('general.textarea',1)}}</b>

                                </label>
                            </div>
                        </td>
                        <td>{{trans_choice('general.textarea_description',1)}}</td>
                        <td>{{trans_choice('general.any_value',1)}}</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="field_type" id="inputType" value="radiobox"
                                           required="" @if($custom_field->field_type=="radiobox") checked @endif>
                                    <b>{{trans_choice('general.radio_box',1)}}</b>

                                </label>
                            </div>
                        </td>
                        <td colspan="2">
                            <p>{{trans_choice('general.separate_with_comma',1)}}</p>
                            <input type="text" name="radio_box_values" value="{{$custom_field->radio_box_values}}"
                                   class="form-control" id="radio_box_values" placeholder="Option 1, Option 2">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="field_type" id="inputType" value="checkbox"
                                           required="" @if($custom_field->field_type=="checkbox") checked @endif>
                                    <b>{{trans_choice('general.checkbox',1)}}</b>

                                </label>
                            </div>
                        </td>
                        <td colspan="2">
                            <p>{{trans_choice('general.separate_with_comma',1)}}</p>
                            <input type="text" name="checkbox_values" value="{{$custom_field->checkbox_values}}"
                                   class="form-control" id="checkbox_values" placeholder="Option 1, Option 2">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="field_type" id="inputType" value="select"
                                           required="" @if($custom_field->field_type=="select") checked @endif>
                                    <b>{{trans_choice('general.select',1)}}</b>

                                </label>
                            </div>
                        </td>
                        <td colspan="2">
                            <p>{{trans_choice('general.separate_with_comma',1)}}</p>
                            <input type="text" name="select_values" value="{{$custom_field->select_values}}"
                                   class="form-control" id="select_values" placeholder="Option 1, Option 2">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-primary">{{trans_choice('general.save',1)}}</button>
    </div>
    {!! Form::close() !!}
    <!-- /.box-body -->
</div>
<!-- /.box -->
@endsection

