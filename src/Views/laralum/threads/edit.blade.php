@extends('laralum::layouts.master')
@php
    $settings = \Laralum\Forum\Models\Settings::first();
@endphp
@section('icon', 'ion-edit')
@section('title', __('laralum_forum::general.edit_thread'))
@section('subtitle', __('laralum_forum::general.edit_thread_desc', ['id' => $thread->id, 'time_ago' => $thread->created_at->diffForHumans()]))
@section('css')
    @if ($settings->text_editor == 'wysiwyg')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.5/tinymce.min.js"></script>
        <script>
            tinymce.init({ selector:'textarea',   plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table contextmenu paste code'
            ] });
        </script>
    @endif
@endsection
@section('breadcrumb')
    <ul class="uk-breadcrumb">
        <li><a href="{{ route('laralum::index') }}">@lang('laralum_forum::general.home')</a></li>
        <li><a href="{{ route('laralum::forum.categories.index') }}">@lang('laralum_forum::general.category_list')</a></li>
        <li><span>@lang('laralum_forum::general.edit_thread')</span></li>
    </ul>
@endsection
@section('content')
    <div class="uk-container uk-container-large">
        <div uk-grid>
            <div class="uk-width-1-1@s uk-width-1-5@l"></div>
            <div class="uk-width-1-1@s uk-width-3-5@l">
                <div class="uk-card uk-card-default">
                    <div class="uk-card-header">
                        {{ __('laralum_forum::general.edit_thread') }}
                    </div>
                    <div class="uk-card-body">
                        <form class="uk-form-stacked" method="POST" action="{{ route('laralum::forum.threads.update', ['thread' => $thread->id]) }}">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}
                            <fieldset class="uk-fieldset">
                                <div class="uk-margin">
                                    <label class="uk-form-label">@lang('laralum_forum::general.title')</label>
                                    <div class="uk-form-controls">
                                        <input value="{{ old('title', $thread->title) }}" name="title" class="uk-input" type="text" placeholder="@lang('laralum_forum::general.title')">
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <label class="uk-form-label">@lang('laralum_forum::general.description')</label>
                                    <div class="uk-form-controls">
                                        <input value="{{ old('description', $thread->description) }}" name="description" class="uk-input" type="text" placeholder="@lang('laralum_forum::general.description')">
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <label class="uk-form-label">@lang('laralum_forum::general.category')</label>
                                    <div class="uk-form-controls">
                                        <select required name="category" class="uk-select">
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" @if($thread->category_id == $category->id) selected @endif >
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <label class="uk-form-label">@lang('laralum_forum::general.content')</label>
                                    @if ($settings->text_editor == 'wysiwyg')
                                        <textarea name="content" rows="15">{{ old('content', $thread->content) }}</textarea>
                                    @else
                                        @php
                                        $text = old('content', $thread->content);
                                        if ($settings->text_editor == 'markdown') {
                                            $converter = new League\HTMLToMarkdown\HtmlConverter();
                                            $text = $converter->convert($text);
                                        }
                                        @endphp
                                        <textarea name="content" class="uk-textarea" rows="15" placeholder="{{ __('laralum_forum::general.content') }}">{{ $text }}</textarea>
                                        @if ($settings->text_editor == 'markdown')
                                            <i>@lang('laralum_forum::general.markdown')</i>
                                        @else
                                            <i>@lang('laralum_forum::general.plain_text')</i>
                                        @endif
                                    @endif
                                </div>

                                <div class="uk-margin">
                                    <a href="{{ route('laralum::forum.categories.show', ['category' => $category->id]) }}" class="uk-button uk-button-default uk-align-left">@lang('laralum_forum::general.cancel')</a>
                                    <button type="submit" class="uk-button uk-button-primary uk-align-right">
                                        <span class="ion-forward"></span>&nbsp; {{ __('laralum_forum::general.edit_thread') }}
                                    </button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            <div class="uk-width-1-1@s uk-width-1-5@l"></div>
        </div>
    </div>
@endsection
