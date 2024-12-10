@extends('layouts.default')

@section('content')
<div class="container-xxl">
    <!-- Page Header -->
    <div class="hk-pg-header pg-header-wth-tab pt-7">
        <div class="d-flex">
            <div class="d-flex flex-wrap justify-content-between flex-1">
                <div class="mb-lg-0 mb-2 me-8">
                    <h1 class="pg-title">Update Page - {{$page->title}}</h1>
                    <div class="mt-2">
                        @include('layouts.partials.messages')
                    </div>
                </div>
                <div class="hk-pg-body" data-bs-spy="scroll" data-bs-target="#vertical_nav" data-bs-root-margin="0px 0px -65%" data-bs-smooth-scroll="true">
                    {!! Form::model($page, ['method' => 'PATCH', 'route' => ['pages.update', $page->id]]) !!}
                    <div class="row gx-3">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Title</label>
                                {{ Form::text('title', null, array('class' => 'form-control'.($errors->has('title') ? ' is-invalid' : ''), 'id'=>'title', 'required'=>true)) }}
                            </div>
                        </div>
                        <div class="col-sm-12">
                            {{ Form::textarea('content', null, array('class' => 'form-control'.($errors->has('content') ? ' is-invalid' : ''), 'id'=>'content')) }}
                        </div>
                        <div class="col-sm-6 mt-5">
                            <button type="submit" class="btn btn-primary">Update</button> 
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script src="{{asset('vendors/tinymce/tinymce.min.js')}}"></script>
<script type='text/javascript'>
tinymce.init(
        {
            selector: 'textarea#content',
            plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
            imagetools_cors_hosts: ['picsum.photos'],
            menubar: 'file edit view insert format tools table help',
            toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
            toolbar_sticky: true,
            autosave_ask_before_unload: true,
            autosave_interval: '30s',
            autosave_prefix: '{path}{query}-{id}-',
            autosave_restore_when_empty: false,
            autosave_retention: '2m',
            image_advtab: true,
            link_list: [
                {title: 'My page 1', value: 'http://www.tinymce.com'}
                ,
                {title: 'My page 2', value: 'http://www.moxiecode.com'}
            ],
            image_list: [
                {title: 'My page 1', value: 'http://www.tinymce.com'
                }
                ,
                {title: 'My page 2', value: 'http://www.moxiecode.com'
                }
            ],
            image_class_list: [
                {title: 'None', value: ''},
                {title: 'Some class', value: 'class-name'}
            ],
            importcss_append: true,
            file_picker_callback: function (callback, value, meta) {
                /* Provide file and text for the link dialog */
                if (meta.filetype === 'file') {
                    callback('https://www.google.com/logos/google.jpg', {text: 'My text'});
                }

                /* Provide image and alt text for the image dialog */
                if (meta.filetype === 'image') {
                    callback('https://www.google.com/logos/google.jpg', {alt: 'My alt text'});
                }

                /* Provide alternative source and posted for the media dialog */
                if (meta.filetype === 'media') {
                    callback('movie.mp4', {source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg'});
                }
            }
            ,
            templates: [
                {title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>'},
                {title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...'},
                {title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>'}
            ],
            template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
            template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
            height: 400,
            image_caption: true,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            noneditable_noneditable_class: 'mceNonEditable',
            toolbar_mode: 'sliding',
            contextmenu: 'link image imagetools table',
            content_style:
                    'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });
</script>    
@endpush