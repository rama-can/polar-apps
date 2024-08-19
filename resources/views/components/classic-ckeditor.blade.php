@push('css')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.2/ckeditor5.css">
    <style type="text/css">
        .ck-editor__editable {
            min-height: 200px;
            /* height: 15px; */
        }
    </style>
@endpush
<div class="main-container">
    <textarea id="classic" name="{{ $name ?? '' }}">
        {!! $value ?? '' !!}
    </textarea>
</div>
@push('js')
<script src="https://cdn.ckeditor.com/ckeditor5/35.4.0/classic/ckeditor.js"></script>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('#classic').forEach(editor => {
            ClassicEditor
                .create(editor, {
                    toolbar: [
                        'undo', 'redo', '|', 'bold', 'italic', 'link', '|',
                    ]
                })
                .then(editorInstance => {
                    console.log(editorInstance);
                })
                .catch(error => {
                    console.error(error);
                });
        });
    });
</script>
@endpush
