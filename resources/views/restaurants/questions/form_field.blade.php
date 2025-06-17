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
    <div class="form-group col-sm-6">
        <label for="name">English Question:</label>
        <input type="text" placeholder="English Question" name="english_question" class="form-control" value="{{ (isset($question->english_question)) ? $question->english_question : '' }}">
    </div>
    <div class="form-group col-sm-6">
        <label for="name" dir="rtl" style="float: right">سؤال عربي:</label>
        <input type="text" placeholder="سؤال عربي" name="arabic_question" dir="rtl" class="form-control" value="{{ (isset($question->arabic_question)) ? $question->arabic_question : '' }}">
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-12 mt-4">
        <label for="english-description">English Description:</label>
        <textarea 
            id="english-description"
            name="english_description" 
            class="form-control" 
            placeholder="Your Description"
            
        >{!! (isset($question->english_description)) ? $question->english_description : '' !!}</textarea>
    </div>
</div>

<div class="row">
    <div class="form-group col-12 mt-4 ">
        <label for="arabic-description" dir="rtl"  class="d-flex text-end ">سؤال عربي:</label>
        <textarea 
            id="arabic-description"
            name="arabic_description" 
            class="form-control d-flex" 
            placeholder="الوصف هنا"
            dir="rtl"
        >{!! isset($question->arabic_description) ? $question->arabic_description : '' !!}</textarea>
        
    </div>
</div>


<div class="form-group col-sm-12 text-right">
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('question.index') }}" class="btn btn-outline-dark">Cancel</a>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script src="{{ asset('js/CkEditor/ckeditor5-setup.js') }}"></script>


    <script>
        const licenseKey = 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NDk1OTk5OTksImp0aSI6ImU5NTNhOGQ5LTdmZDMtNGNjOC04MjNmLTUzMTEwZGM1Mzg0MyIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiLCJzaCJdLCJ3aGl0ZUxhYmVsIjp0cnVlLCJsaWNlbnNlVHlwZSI6InRyaWFsIiwiZmVhdHVyZXMiOlsiKiJdLCJ2YyI6Ijk4YmEwZjIwIn0.FuNeXRIdWnGs6TaN9LP3h1SK6RB2Aj0weOzIqkZMM46tGJo26MrhWMU2Kg01lVmYEqSNMEIdxFKvhuFFj1xKUQ';

        ['#english-description', '#arabic-description'].forEach(selector => {
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


