{{ Form::open(array('route' => ['attributevalue.update', $attributeValue->id])) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('name', __('Attribute Value'), ['class' => 'form-label']) }}
            {{ Form::text('name', $attributeValue->name, array('class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Attribute Value'), 'id' => 'attribute_value')) }}
        </div>

        <div class="form-group col-md-12">
            <label for="attribute_id" class="form-label">Select Attribute</label>
            <select name="attribute_id" id="attribute_id" class="form-control" required>
                @foreach ($attributes as $attribute)
                    <option value="{{$attribute->id}}" @if($attributeValue->attribute_id == $attribute->id) selected @endif>
                        {{$attribute->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}