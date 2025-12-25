<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CKEditor Super Build Test</title>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/super-build/ckeditor.js"></script>
</head>
<body style="padding: 40px;">

    <h2>Editor Test</h2>
    <textarea id="editor">Hello world!</textarea>

    <script>
        console.log(CKEDITOR); // Just to confirm it loaded

        CKEDITOR.ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: [ 'heading', 'bold', 'italic', 'fontSize', 'fontFamily', 'undo', 'redo' ],
                fontSize: {
                    options: [ 9, 11, 13, 'default', 17, 19, 21 ],
                    supportAllValues: true
                },
                fontFamily: {
                    options: [
                        'default',
                        'Arial, Helvetica, sans-serif',
                        'Courier New, Courier, monospace',
                        'Georgia, serif',
                        'Tahoma, Geneva, sans-serif',
                        'Times New Roman, Times, serif',
                        'Verdana, Geneva, sans-serif'
                    ],
                    supportAllValues: true
                }
            })
            .catch(error => console.error('Editor error:', error));
    </script>

</body>
</html>
            