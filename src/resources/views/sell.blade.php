@extends('common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css')}}">
@endsection

@section('content')

<form action="/sell" method="POST" enctype="multipart/form-data">
    @csrf

    <h1 class="product-title">商品の出品</h1>

    <div class="product-image">
        <p class="product-image-title">商品画像
            @error('image')
            <span class="error">※{{ $message }}</span>
            @enderror
        </p>

        <div class="image-upload">
            <label for="image" class="upload-button">画像を選択</label>
            <input type="file" id="image" name="image" hidden>
            <img id="preview" src="" alt="">
        </div>
    </div>

    <p class="product-detail-title">商品の詳細</p>
    <hr class="first-line">

    <div class="category-area">
        <p class="category-title">
            カテゴリー
            @error('categories')
            <span class="error">※{{ $message }}</span>
            @enderror
        </p>

        <div class="category-list">
            @foreach($categories as $category)
                <label class="category-item">
                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                    <span>{{ $category->name }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div class="condition-area">
        <p class="condition-title">
            商品の状態
            @error('condition')
            <span class="error">※{{ $message }}</span>
            @enderror
        </p>

        <div class="custom-select condition-select">
            <div class="selected" id="condition-selected">選択してください</div>

            <ul class="options" id="condition-options">
                @foreach($conditions as $condition)
                <li data-value="{{ $condition->id }}">
                    {{ $condition->name }}
                </li>
                @endforeach
            </ul>

            <input type="hidden" name="condition" id="condition-hidden" value="{{ old('condition') }}">
        </div>
    </div>

    <p class="section-title">商品名と説明</p>
    <hr class="second-line">

    <div class="product-name">
        <label>
            商品名
            @error('name')
            <span class="error">※{{ $message }}</span>
            @enderror
        </label>
        <input type="text" name="name" value="{{ old('name') }}">
    </div>

    <div class="brand-name">
        <label>
            ブランド名
            @error('brand')
            <span class="error">※{{ $message }}</span>
            @enderror
        </label>
        <input type="text" name="brand_name" value="{{ old('brand_name') }}">
    </div>

    <div class="description">
        <label>
            商品の説明
            @error('description')
            <span class="error">※{{ $message }}</span>
            @enderror
        </label>
        <textarea name="description">{{ old('description') }}</textarea>
    </div>

    <div class="price">
        <label>
            販売価格
            @error('price')
            <span class="error">※{{ $message }}</span>
            @enderror
        </label>
        <div class="price-input">
            <span class="yen">￥</span>
            <input type="text" name="price" value="{{ old('price') }}">
        </div>
    </div>

    <div class="submit-button">
        <button type="submit" class="button-text">出品する</button>
    </div>
</form>

<script>
    document.getElementById('image').addEventListener('change', function (e) {
        const file = e.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                document.getElementById('preview').src = e.target.result;
            }

            reader.readAsDataURL(file);
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const selected = document.getElementById('condition-selected');
        const options = document.getElementById('condition-options');
        const hidden = document.getElementById('condition-hidden');

        selected.addEventListener('click', () => {
            options.classList.toggle('open');
            selected.style.display = 'none';
            selected.classList.add('no-arrow');
        });

        options.querySelectorAll('li').forEach(option => {
            option.addEventListener('click', () => {

                options.querySelectorAll('li').forEach(li => li.classList.remove('active'));
                option.classList.add('active');

                hidden.value = option.dataset.value;
                selected.textContent = option.textContent;

                options.classList.remove('open');

                selected.style.display ='block';
            });
        });

    });
</script>

@endsection