@extends('layouts.administrator.master')

@section('content')
    <x-form-section title="{{ $title }}">

        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mt-3">
                    <div class="form-group">
                        <label for="name">
                            Name
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" @required(true)>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="form-group">
                        <label for="phone_number">
                            Phone Number
                        </label>
                        <input type="number" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number"
                            name="phone_number" value="{{ old('phone_number') }}" @required(true)>
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mt-3">
                    <div class="form-group">
                        <label for="email">
                            Email
                        </label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" @required(true)>
                        <small class="text-muted">
                            Email ini akan digunakan sebagai username
                        </small>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="form-group">
                        <label for="password">
                            Password
                        </label>
                        <input type="text" class="form-control @error('password') is-invalid @enderror" id="password"
                            value="password" name="password" @required(true)>
                        <small class="text-muted">
                            Ubah jika ingin mengganti password
                        </small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mt-3">
                    <label for="date_birht">
                        Date Birth
                    </label>
                    <div class="input-group input-append date" data-date-format="dd-mm-yyyy">
                        <input class="form-control @error('date_birht') is-invalid @enderror" type="text"
                            readonly="" autocomplete="off" id="date_birht" name="date_birht"
                            value="{{ old('date_birht') }}" @required(true)>
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="far fa-calendar-alt"></i>
                        </button>
                        @error('date_birht')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="form-group">
                        <label for="gender">
                            Gender
                        </label>
                        <select class="form-select select2 @error('gender') is-invalid @enderror" id="gender"
                            name="gender" @required(true)>
                            <option value=""></option>
                            <option value="laki-laki" {{ old('gender') == 'laki-laki' ? 'selected' : '' }}>
                                Laki-laki
                            </option>
                            <option value="perempuan" {{ old('gender') == 'perempuan' ? 'selected' : '' }}>
                                Perempuan
                            </option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mt-3">
                    <div class="form-group">
                        <label for="role">
                            Role
                        </label>
                        <select class="form-select select2 @error('role') is-invalid @enderror" id="role"
                            name="role" @required(true)>
                            <option value=""></option>
                            @foreach (getRoles() as $role)
                                <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mt-3">
                    <div class="form-group">
                        <label for="avatar">
                            Avatar
                        </label>
                        <input class="form-control @error('avatar') is-invalid @enderror" type="file" name="avatar"
                            id="avatar">
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mt-3">
                    <div class="form-group">
                        <label for="address">
                            Address
                        </label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" @required(true)>{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <x-btn-submit-form />

        </form>

    </x-form-section>
@endsection
