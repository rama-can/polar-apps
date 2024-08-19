@push('css')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.2/ckeditor5.css">
@endpush
<div class="main-container">
    <textarea id="editor" name="{{ $name ?? '' }}">
        {!! $value ?? '' !!}
    </textarea>
</div>
@push('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/42.0.2/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'), {
            })
            .then(editor => {
                window.editor = editor;
            })
            .catch(error => {
                console.error(error);
            });
    </script>

    <!-- A friendly reminder to run on a server, remove this during the integration. -->
    <script>
            window.onload = function() {
                if ( window.location.protocol === "file:" ) {
                    alert( "This sample requires an HTTP server. Please serve this file with a web server." );
                }
            };
    </script>
@endpush
