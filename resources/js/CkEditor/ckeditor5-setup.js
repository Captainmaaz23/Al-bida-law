import ClassicEditor from 'https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js';

ClassicEditor
    .create(document.querySelector('#ckeditor-description'), {
        licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NDk1OTk5OTksImp0aSI6ImU5NTNhOGQ5LTdmZDMtNGNjOC04MjNmLTUzMTEwZGM1Mzg0MyIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiLCJzaCJdLCJ3aGl0ZUxhYmVsIjp0cnVlLCJsaWNlbnNlVHlwZSI6InRyaWFsIiwiZmVhdHVyZXMiOlsiKiJdLCJ2YyI6Ijk4YmEwZjIwIn0.FuNeXRIdWnGs6TaN9LP3h1SK6RB2Aj0weOzIqkZMM46tGJo26MrhWMU2Kg01lVmYEqSNMEIdxFKvhuFFj1xKUQ'
    })
    .then(editor => {
        console.log('CKEditor initialized', editor);
    })
    .catch(error => {
        console.error('CKEditor error:', error);
    });
