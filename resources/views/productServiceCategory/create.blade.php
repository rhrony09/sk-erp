{{ Form::open(array('route' => 'product-category.store', 'enctype' => 'multipart/form-data')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('name', __('Category Name'),['class'=>'form-label']) }}
            {{ Form::text('name', '', array('class' => 'form-control','required'=>'required', 'placeholder'=>__('Enter Category Name'),'id'=>'category_name')) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('slug', __('Category Slug'),['class'=>'form-label']) }}
            {{ Form::text('slug', '', array('class' => 'form-control','required'=>'required', 'placeholder'=>__('Enter Category Slug'),'id'=>'category_slug')) }}
        </div>
        <div class="form-group col-md-12 d-block">
            {{ Form::label('type', __('Category Type'),['class'=>'form-label']) }}
            {{ Form::select('type',$types,null, array('class' => 'form-control select cattype ','required'=>'required')) }}
        </div>
        <div class="form-group col-md-12 account d-none">
            {{Form::label('chart_account_id',__('Account'),['class'=>'form-label'])}}
            <select class="form-control select" name="chart_account" id="chart_account">
            </select>
        </div>

        <div class="form-group col-md-12">
            {{ Form::label('color', __('Category Color'),['class'=>'form-label']) }}
            {{ Form::text('color', '', array('class' => 'form-control jscolor','required'=>'required')) }}
            <small>{{__('For chart representation')}}</small>
        </div>

        <div class="form-group col-md-12">
            <div class="custom-control custom-checkbox">
                {{ Form::checkbox('is_featured', '1', false, ['class' => 'custom-control-input', 'id' => 'is_featured']) }}
                {{ Form::label('is_featured', __('Featured'), ['class' => 'custom-control-label form-label']) }}
            </div>
        </div>

        <div class="col-md-12 form-group">
            {{ Form::label('cat_image', __('Category Image'), ['class' => 'form-label']) }}
            <div class="choose-file ">
                <label for="cat_image" class="form-label">
                    <input type="file" class="form-control" name="cat_image" id="cat_image" data-filename="cat_image_create">
                    <img id="image" class="mt-3" style="width:25%;" />

                </label>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}


<script>

    //hide & show chartofaccount

    $(document).on('click', '.cattype', function ()
    {
        var type = $(this).val();
        if (type != 'product & service') {
            $('.account').removeClass('d-none')
            $('.account').addClass('d-block');
        } else {
            $('.account').addClass('d-none')
            $('.account').removeClass('d-block');
        }
    });


    $(document).on('change', '#type', function () {
        var type = $(this).val();

        $.ajax({
            url: '{{route('productServiceCategory.getaccount')}}',
            type: 'POST',
            data: {
                "type": type,
                "_token": "{{ csrf_token() }}",
            },

            success: function (data) {
                $('#chart_account').empty();
                $.each(data.chart_accounts, function (key, value) {
                    $('#chart_account').append('<option value="' + key + '" class="subAccount">' + value + '</option>');
                    $.each(data.sub_accounts, function (subkey, subvalue) {
                        if(key == subvalue.account)
                        {
                            $('#chart_account').append('<option value="' + subvalue.id + '">' + '&nbsp; &nbsp;&nbsp;' + subvalue.name + '</option>');
                        }
                });
                });
            }

        });
    });
</script>

<script>
    document.getElementById('cat_image').onchange = function() {
        var src = URL.createObjectURL(this.files[0])
        document.getElementById('image').src = src
    }
</script>

<script>
    $(document).ready(function() {
        $('#category_name').on('keyup change', function() {
            var nameValue = $(this).val();
            var slugValue = nameValue
                .toLowerCase()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                .replace(/^-+/, '')             // Trim - from start of text
                .replace(/-+$/, '');            // Trim - from end of text
            
            $('#category_slug').val(slugValue);
        });
    });
</script>