@extends('layouts.administrator.master')
@section('content')
    <x-form-section title="{{ $title }}">

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row mt-2">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"  name="name" value="{{ old('name') }}" @required(true) placeholder="Name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="product_category_id">Category </label>
                        <select class="form-select select2 @error('product_category_id') is-invalid @enderror" id="product_category_id" name="product_category_id" @required(true)>
                            <option value=""></option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="status">Status </label>
                        <select class="form-select select2 @error('status') is-invalid @enderror" id="status" name="status" @required(true)>
                            <option value=""></option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description">Description</label>
                        <x-classic-ckeditor editor="classic" name="description" :value="old('description')" />
                        @if($errors->has('description'))
                            <div class='text-danger mt-2'>* {{ $errors->first('description') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="content">Content</label>
                        <x-ckeditor name="content" :value="old('content')" />
                        @if($errors->has('content'))
                            <div class='text-danger mt-2'>* {{ $errors->first('content') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <x-btn-submit-form />

        </form>

    </x-form-section>
@endsection
