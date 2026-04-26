@extends('common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css')}}">
@endsection

@section('content')
<div class="address-page">

    <form method="POST" action="/purchase/address/{{ $product->id }}">
        @csrf

        <h1 class="address-change">住所の変更</h1>

        <div class="form-area">
            <div class="post_code-field">
                <label>
                    郵便番号
                    @error('post_code')
                    <span class="error">※{{ $message }}</span>
                    @enderror
                </label>
                <input type="text" name="post_code" class="post_code-input">
            </div>

            <div class="address-field">
                <label>
                    住所
                    @error('address')
                    <span class="error">※{{ $message }}</span>
                    @enderror
                </label>
                <input type="text" name="address" class="address-input">
            </div>

            <div class="building-field">
                <label>建物名</label>
                <input type="text" name="building" class="building-input">
            </div>

            <div class="update-button">
                <button type="submit" class="button-text">更新する</button>
            </div>
        </div>

    </form>

</div>

@endsection