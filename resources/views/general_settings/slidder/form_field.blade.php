<?php
$menus = $menus ?? [];

// $availability = [
//     '0' => 'Not Available',
//     '1' => 'Available',
// ];

?>

<div class="row">
    @if(Auth::user()->rest_id == 0)
        <div class="form-group col-sm-6">
            <label for="rest_id">Restaurant:</label>
            <select name="rest_id" class="form-control js-select2 form-select rest_select2" placeholder="select" {{ isset($Model_Data) || isset($item_rest_id) ? 'disabled' : '' }}>
                <option value="" selected disabled>select</option>
                @foreach ($restaurant as $value => $label)
                    <option value="{{ $value }}" {{ (isset($Model_Data) && $value == $Model_Data->rest_id) || (isset($item_rest_id) && $value == $item_rest_id) ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    @else
        <input type="hidden" id="rest_id" name="rest_id" value="{{ Auth::user()->rest_id }}" />
    @endif

    <div class="form-group col-sm-6 d-none">
        <label for="menu_id">Menu:</label>
        <select name="menu_id" class="form-control js-select2 form-select menu_select2" placeholder="select">
            <option value="" selected disabled>select</option>
            @foreach ($menus as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>   
    </div>
</div>

    <div class="row">
        <div class="form-group col-sm-12 mt-4">
            <label for="arabic-description">English Description:</label>
            <textarea 
                id="text"
                name="text" 
                class="form-control" 
                placeholder="Description"
                style="text-align: right;"
            >{!! isset($slidder->text) ? $slidder->text : '' !!}</textarea>
        </div>
    </div>

    <div class="row text-end">
        <div class="form-group col-sm-12 mt-4">
            <label for="arabic-description" dir="rtl" class="text-end d-flex">الوصف باللغة العربية:</label>
            <textarea 
    id="arabictext"
    name="arabic_text" 
    class="form-control" 
    dir="rtl"
    placeholder="الوصف باللغة العربية:"
>{!! isset($slidder->arabic_text) ? $slidder->arabic_text : '' !!}</textarea>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12 mt-4">
            <label for="arabic-description">English Summary:</label>
            <textarea 
                name="summary" 
                class="form-control" 
                placeholder="Summary"
                style="text-align: left;"
            >{!! isset($slidder->summary) ? $slidder->summary : '' !!}</textarea>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12 mt-4">
            <label for="arabic-description" dir="rtl" class="text-end d-flex">ملخص اللغة العربية:</label>
            <textarea 
                name="arabic_summary" 
                class="form-control" 
                placeholder="ملخص اللغة العربية"
                dir="rtl"
            >{!! isset($slidder->arabic_summary	) ? $slidder->arabic_summary	 : '' !!}</textarea>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6 mt-4">
            <label for="name">English Name:</label>
            <input type="text" name="name" placeholder="Name" class="form-control" value="{{ isset($slidder->name) ? $slidder->name : '' }}">
        </div>

        <div class="form-group col-sm-6 mt-4">
            <label for="arabic_name" dir="rtl" class="text-end d-flex">الاسم العربي:</label>
            <input type="text" name="arabic_name" dir="rtl" placeholder="الاسم العربي" class="form-control" value="{{ isset($slidder->arabic_name) ? $slidder->arabic_name : '' }}">
        </div>

    </div>
    
    <div class="row">
        <div class="form-group col-sm-6 mt-4">
                <label for="arabic-description">English Attorny :</label>
                <input type="text"  placeholder="Attorny" name="attorny" class="form-control" value="{{ isset($slidder->attorny) ? $slidder->attorny : '' }}">
        </div>
            <div class="form-group col-sm-6 mt-4">
                <label for="arabic-description" dir="rtl" class="text-end d-flex">المحامي العربي :</label>
                <input 
                    type="text" 
                    name="arabic_attorny" 
                    dir="rtl" 
                    class="form-control" 
                    placeholder="أدخل اسم المحامي" 
                    value="{{ isset($slidder->arabic_attorny) ? $slidder->arabic_attorny : '' }}"
                >
            </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12">
            <label for="image">Image:</label>
            <input type="file" name="image" class="form-control">
        </div>
    </div>

<div class="form-group col-sm-12 text-right">
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('slidder.index') }}" class="btn btn-outline-dark">Cancel</a>
</div>


<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script src="{{ asset('js/CkEditor/ckeditor5-setup.js') }}"></script>


    <script>
        const licenseKey = 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NDk1OTk5OTksImp0aSI6ImU5NTNhOGQ5LTdmZDMtNGNjOC04MjNmLTUzMTEwZGM1Mzg0MyIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiLCJzaCJdLCJ3aGl0ZUxhYmVsIjp0cnVlLCJsaWNlbnNlVHlwZSI6InRyaWFsIiwiZmVhdHVyZXMiOlsiKiJdLCJ2YyI6Ijk4YmEwZjIwIn0.FuNeXRIdWnGs6TaN9LP3h1SK6RB2Aj0weOzIqkZMM46tGJo26MrhWMU2Kg01lVmYEqSNMEIdxFKvhuFFj1xKUQ';

        ['#text','#arabictext'].forEach(selector => {
            ClassicEditor
                .create(document.querySelector(selector), {
                    licenseKey: licenseKey,
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'strikethrough', '|',
                        'bulletedList', 'numberedList', '|',
                        'blockQuote', 'insertTable', 'undo', 'redo'
                    ]
                })
                .then(editor => {
                    editor.ui.view.editable.element.style.minHeight = '150px';
                })
                .catch(error => {
                    console.error(`${selector} editor error:`, error);
                });
        });
    </script>




