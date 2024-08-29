<x-front-layout :title="$title" :notIndex="true">
    <div class="container mt-5 border rounded-3">
        <div class="row">
            <h2 class="text-center mt-4 fw-bold">{{ $title ?? '' }}</h2>
            <div class="col-md-12">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center align-items-md-center mt-4">
                    <x-button-back></x-button-back>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3 mb-md-0 d-flex justify-content-center align-items-center flex-grow-1">
                    <img src="{{ $user->image }}"
                        id="profileImage"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        alt="User Profile"
                        class="img-user rounded-circle border border-secondary bg-white"
                        style="width: 100px; height: 100px; object-fit: cover;">
                </div>
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row mt-2">
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" @required(true)>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $user->username) }}" @required(true)>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" @required(true)>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="" placeholder="">
                                <small class="text-muted text-warning">
                                    Leave blank if you do not want to change the password
                                </small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label for="date_birth">Date Birth</label>
                            <div class="input-group date">
                                <input type="text" class="form-control @error('date_birth') is-invalid @enderror" placeholder="dd/mm/yyyy" name="date_birth" value="{{ old('date_birth', $user->profile->date_birth ?? '') }}">
                                <div class="btn border">
                                    <i class="far fa-calendar-alt"></i>
                                </div>
                            </div>
                            @error('date_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label for="phone_number">Phone Number</label>
                                <input type="number" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->profile->phone_number ?? '') }}" placeholder="08xxxxxxxxx">
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label for="gender">
                                    Gender
                                </label>
                                <select class="form-select select2 @error('gender') is-invalid @enderror" id="gender" name="gender">
                                    <option value=""></option>
                                    <option {{ optional($user->profile)->gender == 'laki-laki' ? 'selected' : '' }} value="laki-laki" {{ old('gender') == 'laki-laki' ? 'selected' : '' }}>
                                        Laki-laki
                                    </option>
                                    <option {{ optional($user->profile)->gender == 'perempuan' ? 'selected' : '' }} value="perempuan" {{ old('gender') == 'perempuan' ? 'selected' : '' }}>
                                        Perempuan
                                    </option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label for="avatar">
                                    Avatar
                                </label>
                                <input class="form-control @error('avatar') is-invalid @enderror" type="file" name="avatar" id="avatar">
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label for="address">
                                    Address
                                </label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="address">{{ old('address', $user->profile->address ?? '') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <button type="submit" class="btn btn-primary float-end">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#date_birth').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
            });
        });
    </script>
</x-front-layout>
