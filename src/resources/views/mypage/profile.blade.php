@extends('common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css')}}">
@endsection

@section('content')
<div class="profile-page">
    <h1 class="profile-title">プロフィール設定</h1>

    <form action="/mypage/profile" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-area">

            <div class="profile-top">
                <div class="profile-icon">
                    <img id="preview" src="{{ $profile->image ? asset('storage/' . $profile->image) : asset('images/default.png') }}" alt="">
                </div>

                <label class="profile-icon-button" for="imageInput">
                    画像を選択する
                </label>
                <input type="file" name="image" id="imageInput" hidden>
                @error('image')
                    <span class="error">※{{ $message }}</span>
                @enderror
            </div>

            <div class="username-field">
                <label>
                    ユーザー名
                    @error('name')
                        <span class="error">※{{ $message }}</span>
                    @enderror
                </label>
                <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}">
            </div>

            <div class="post_code-field">
                <label>
                    郵便番号
                    @error('post_code')
                        <span class="error">※{{ $message }}</span>
                    @enderror
                </label>
                <input type="text" name="post_code" value="{{ old('post_code', $profile->post_code ?? '') }}">
            </div>

            <div class="address-field">
                <label>
                    住所
                    @error('address')
                        <span class="error">※{{ $message }}</span>
                    @enderror
                </label>
                <input type="text" name="address" value="{{ old('address', $profile->address ?? '') }}">
            </div>

            <div class="building-field">
                <label>建物名</label>
                <input type="text" name="building" value="{{ old('building', $profile->building ?? '') }}">
            </div>

            <div class="profile-button">
                <button type="submit" class="button-text">
            更新する
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
        }

        reader.readAsDataURL(file);
    }
});
</script>

@endsection