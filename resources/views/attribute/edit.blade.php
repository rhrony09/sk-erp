{{ Form::open(array('route' => ['attributes.update', $attribute->id])) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('name', __('Attribute Name'),['class'=>'form-label']) }}
            {{ Form::text('name', $attribute->name, array('class' => 'form-control','required'=>'required', 'placeholder'=>__('Enter Attribute Name'),'id'=>'attribute_name')) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}
