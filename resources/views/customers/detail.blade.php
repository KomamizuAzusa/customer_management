@extends('common.flame')

@section('cssStyle')
<link rel="stylesheet" href="{{ asset('css/c_update_input.css') }}">
<link rel="stylesheet" href="{{ asset('css/customer.css') }}">
@endsection

@section('content')
<div class="inner">
    <input type="hidden" id="do_flg" value="{{ !empty($do_flg) ? $do_flg : ''}}">
    <div class="detail-container">
        <!-- 左カラム -->
        <div class="detail-info">
            <div style="display: flex;justify-content:space-between;align-items:center;">
                <h2 class="block-title">顧客情報詳細</h2>
                <a class="message-btn small" id="line_message_open">LINE</a>
            </div>
            <div class="detail-info-head">
                <div class="customer-picture">
                    <img id="display_image"
                        src="/uploads/{{ !empty($customer->image) ? $customer->image : 'customer-noimage.png' }}">
                </div>
                <div id="big_image" class="customer-picture-modal">
                    <img id="display_image"
                        src="/uploads/{{ !empty($customer->image) ? $customer->image : 'customer-noimage.png' }}">
                    <span id="big_image_close" class="big-image-close"></span>
                </div>
                <div class="customer-name-wrap">
                    <div class="customer-first-name-wrap">
                        <p class="customer-first-name-kana">{{$customer->first_name_kana}}</p>
                        <p class="customer-first-name">
                            {{ $customer->first_name }}
                        </p>
                    </div>
                    <div class="customer-last-name-wrap">
                        <p class="customer-last-name-kana">{{ $customer->last_name_kana }}</p>
                        <p class="customer-last-name">
                            {{ $customer->last_name }}
                        </p>
                    </div>
                </div>
                <div class="btn-wrap">
                    <span class="update-icon" id="customer_update"></span>
                    <span class="delete-icon" id="customer_delete"></span>
                </div>
            </div>
            <div class="detail-info-body">
                <ul class="detail-info-items">
                    <li class="detail-info-item">
                        <p class="title">年齢</p>
                        <p class="customer-birthday text">
                            {{ !empty($customer->birthday) ? MyFunction::birthdayToAge($customer->birthday).'歳' :'' }}
                        </p>
                    </li>
                    <li class="detail-info-item">
                        <p class="title">性別</p>
                        <p class="customer-birthday text">{{ MyFunction::numToGender($customer->gender) }}</p>
                    </li>
                    <li class="detail-info-item">
                        <p class="title">生年月日</p>
                        <p class="customer-birthday text">
                            {{ !empty($customer->birthday) ? MyFunction::dateDisplay($customer->birthday) : '' }}</p>
                    </li>
                    <li class="detail-info-item">
                        <p class="title">ニックネーム</p>
                        <p class="customer-nickname text">{{ $customer->nickname }}</p>
                    </li>
                    <li class="detail-info-item">
                        <p class="title">住所</p>
                        <div class="customer-address text">
                            <p>{{ !empty($customer->postcode) ? '〒'.MyFunction::postDisplay($customer->postcode) : '' }}
                            </p>
                            <p>{{ $customer->prefecture.$customer->city.$customer->block }}</p>
                        </div>
                    </li>
                    <li class="detail-info-item">
                        <p class="title">メールアドレス</p>
                        <p class="customer-email text" style="word-break: break-all">{{ $customer->email }}</p>
                    </li>
                    <li class="detail-info-item">
                        <p class="title">電話番号(自宅)</p>
                        <p class="customer-phone text">{{ $customer->phone_number }}</p>
                    </li>
                    <li class="detail-info-item">
                        <p class="title">電話番号(携帯)</p>
                        <p class="customer-cellphone text">{{ $customer->cell_phone_number }}</p>
                    </li>
                    <li class="detail-info-item" style="display: block;margin-top:0; border-bottom:none;">
                        <p class="title" style="width:100%;padding:0.6rem 0 0;">メモ</p>
                        <p class="customer-memo">{{ $customer->memo }}</p>
                    </li>
                </ul>
            </div>
        </div>

        <!-- 右カラム（カルテ） -->
        <div class="detail-right">
            <div class="sale-big">
                <input type="hidden" id="focus_sale_id" value="{{ !empty($sales[0]->id) ? $sales[0]->id : '' }}">
                <div class="sale-big-top">
                    <div class="sale-big-top-left">
                        <div class="sale-big-top-head">
                            <h2 class="block-title">カルテ</h2>
                            <div class="btn-wrap">
                                <span class="create-icon" id="sale_create"></span>
                                @if(!empty($sales))
                                <span class="update-icon" id="sale_update"></span>
                                <span class="delete-icon" id="sale_delete"></span>
                                @endif
                            </div>
                        </div>
                        <ul class="sale-items">
                            <li class="sale-item">
                                <p class="title">来店日時</p>
                                <p class="data" id="sale_date">
                                    {{ !empty($sales[0]->date) ? MyFunction::dateDisplay($sales[0]->date): ''}}</p>
                            </li>
                            <li class="sale-item">
                                <p class="title">担当者</p>
                                <p class="data" id="sale_employee">
                                    {{ !empty($sales[0]->employee) ? $sales[0]->employee : '' }}</p>
                            </li>
                            <li class="sale-item menu">
                                <p class="title">メニュー</p>
                                <div style="width: calc(100% - 8rem);" id="sale_menu">
                                    {{!empty($sales[0]->menu) ? MyFunction::menuDisplay($sales[0]->menu,$sales[0]->menu_price) : ''}}
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="sale-big-top-right">
                        <div class="sale-memo-wrap">
                            <p class="title">メモ</p>
                            <p class="data" id="sale_memo">{{ !empty($sales[0]->memo) ?  $sales[0]->memo : '' }}</p>
                        </div>
                    </div>
                </div>
                <div class="sale-big-bottom">
                    <div class="sale-picture-wrap">
                        <p class="title">ビフォー</p>
                        <div class="sale-pictures">
                            <img id="sale_image1"
                                src="/uploads/{{ !empty($sales[0]->image1) ? $sales[0]->image1 : 'sale-noimage.jpeg'}}"
                                alt="">
                            <img style="margin-left:2rem;" id="sale_image2"
                                src="/uploads/{{ !empty($sales[0]->image2) ? $sales[0]->image2 : 'sale-noimage.jpeg' }}"
                                alt="">
                        </div>
                    </div>
                    <div class="sale-picture-wrap">
                        <p class="title">アフター</p>
                        <div class="sale-pictures">
                            <img id="sale_image3"
                                src="/uploads/{{ !empty($sales[0]->image3) ? $sales[0]->image3 : 'sale-noimage.jpeg' }}"
                                alt="">
                            <img style="margin-left:2rem;" id="sale_image4"
                                src="/uploads/{{ !empty($sales[0]->image4) ? $sales[0]->image4 : 'sale-noimage.jpeg' }}"
                                alt="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="sale-small">
                @if(!empty($sales))
                <ul class="sale-small-list">
                    @foreach($sales as $index => $sale)
                    <div class="sale-small-items" data-id="{{ $sale->id }}" onclick="saleDisplay(this)">
                        <p class="sale-small-title" style="margin:0.2rem 0.2rem 0;">
                            {{ $index + 1  == 1 ? '前回' : $index + 1 .'回前'}}</p>
                        <div class="sale-small-item">
                            <p style="font-weight: bold;">来店日</p>
                            <p>{{ MyFunction::dateDisplay($sale->date) }}</p>
                            <p style="font-weight: bold; margin-top:0.2rem;">担当者</p>
                            <p>{{ $sale->employee }}</p>
                        </div>
                    </div>
                    @endforeach
                </ul>
                @endif
                @if(empty($sales))
                <p class="no-sale-message">現在カルテはありません</p>
                @endif
            </div>
            @if(!empty($sales))
            <!-- sale delete -->
            <form action="{{ route('sale.delete') }}" method="post">
                @csrf
                <div class="alert-box" id="sale_delete_alert">
                    <div class="alert-box-inner">
                        <div>
                            <input type="hidden" name="id" id="delete_sale_id" value="{{ $sales[0]->id }}">
                            <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                            <p class="alert-message" id="alert_message">このカルテを削除しますか。</p>
                            <div class="btn-wrap">
                                <button class="yes-btn" type="submit" id="delete_yes">はい</button>
                                <a class="cancel-btn" id="sale_delete_no">キャンセル</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- sale update -->
            <form id="form_sale_update" name="formSaleUpdate">
                @csrf
                <div class="sale-update">
                    <span id="detail_update_close"></span>
                    <div class="block-title-main">
                        <h2 style="text-align: center;">カルテ編集</h2>
                    </div>
                    <ul class="alert alert-danger" id="updateErrorMessage"></ul>
                    <div class="sale-update-top">
                        <div class="sale-update-left">
                            <div class="sale-update-item">
                                <label>
                                    <p class="form-item-title">来店日<span class="required">必須</span></p>
                                    <input type="date" name="date" id="update_sale_date" value="{{ $sales[0]->date }}">
                                </label>
                            </div>
                            <div class="sale-update-item" style="margin-top: 1.4rem;">
                                <label>
                                    <p class="form-item-title">担当者<span class="required">必須</span></p>
                                    <select name="employee_id" id="update_employee_select">
                                        <option value=''>-</option>
                                        @if(!empty($employees))
                                        @foreach($employees as $employee)
                                        <option value='{{ $employee->id }}'
                                            {{ $sales[0]->employee_id == $employee->id  ? 'selected' : '' }}>
                                            {{ $employee->first_name }}{{ $employee->last_name }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </label>
                            </div>
                            <div class="sale-update-item" style="margin-top: 1.4rem;position:relative;">
                                <p class="form-item-title">メニュー</p>
                                <a id="update_menu_open" class="select-menu-open">＋メニューを選択</a>
                                <ul class="checked-list" id="update_menu_checked_list">
                                </ul>
                                <div class="select-menu-modal form-search-modal" id="update_menu_modal"
                                    style="padding-top:0;">
                                    @if(!empty($menus_categories))
                                    @foreach($menus_categories as $menus_category)
                                    <p class="menu-category-title" style="font-size: 1.3rem;">
                                        {{ $menus_category->name }}
                                    <p>
                                        @foreach($menus as $menu)
                                        @if($menu->menu_category_id == $menus_category->id)
                                    <div class="menu-input-wrap">
                                        <label>
                                            <input type="checkbox" name="update_menu_id[]" value="{{ $menu->id }}"
                                                data-menu="{{ $menu->name }}"
                                                {{ MyFunction::menuChecked($sales[0]->menu,$menu->name) }}>
                                            <span class="check-input-part"></span>
                                            <span class="menu-title">{{$menu->name}}</span>
                                        </label>
                                    </div>
                                    @endif
                                    @endforeach
                                    @endforeach
                                    @endif
                                    <div style="text-align: center; margin-top:1rem;">
                                        <a id="update_menu_close">選択</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sale-update-right">
                            <label>
                                <p class="form-item-title">メモ</p>
                                <textarea name="memo" id="update_sale_memo">{{ $sales[0]->memo }}</textarea>
                            </label>
                        </div>
                    </div>
                    <div class="sale-update-bottom">
                        <div class="sale-update-picture-wrap">
                            <p class="form-item-title">ビフォー</p>
                            <div class="flex">
                                <div class="sale-update-picture">
                                    <div class="file-input">
                                        <div class="file-input-wrap" style="height: 3rem;margin:0 auto;">
                                            <label>
                                                <input type="file" name="image1" id="update_image1">
                                                <span class="file-input-display">ファイルを選択</span>
                                            </label>
                                        </div>
                                        <span id="update_file_name1"
                                            style="display: block;word-break: break-all;text-align:center;">{{ !empty($sales[0]->image1) ? $sales[0]->image1 : 'sale-noimage.jpeg' }}</span>
                                    </div>
                                    <div class="file-output">
                                        <div class="file-preview">
                                            <div class="file-preview-picture" style="margin-top:0.6rem;">
                                                <img id="update_file_preview_img1"
                                                    src="/uploads/{{!empty($sales[0]->image1) ? $sales[0]->image1 : 'sale-noimage.jpeg' }}"
                                                    alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="sale-update-picture" style="margin-left:1rem;">
                                    <div class="file-input">
                                        <div class="file-input-wrap" style="height: 3rem;margin:0 auto;">
                                            <label>
                                                <input type="file" name="image2" id="update_image2">
                                                <span class="file-input-display">ファイルを選択</span>
                                            </label>
                                        </div>
                                        <span id="update_file_name2"
                                            style="display: block;word-break: break-all;text-align:center;">{{ !empty($sales[0]->image2) ? $sales[0]->image2 : 'sale-noimage.jpeg' }}</span>
                                    </div>
                                    <div class="file-output">
                                        <div class="file-preview">
                                            <div class="file-preview-picture" style="margin-top:0.6rem;">
                                                <img id="update_file_preview_img2"
                                                    src="/uploads/{{ !empty($sales[0]->image2) ? $sales[0]->image2 : 'sale-noimage.jpeg' }}"
                                                    alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="sale-update-picture-wrap">
                            <p class="form-item-title">アフター</p>
                            <div class="flex">
                                <div class="sale-update-picture">
                                    <div class="file-input">
                                        <div class="file-input-wrap" style="height: 3rem;margin:0 auto;">
                                            <label>
                                                <input type="file" name="image3" id="update_image3">
                                                <span class="file-input-display">ファイルを選択</span>
                                            </label>
                                        </div>
                                        <span id="update_file_name3"
                                            style="display: block;word-break: break-all;text-align:center;">{{ !empty($sales[0]->image3) ? $sales[0]->image3 : 'sale-noimage.jpeg' }}</span>
                                    </div>
                                    <div class="file-output">
                                        <div class="file-preview">
                                            <div class="file-preview-picture" style="margin-top:0.6rem;">
                                                <img id="update_file_preview_img3"
                                                    src="/uploads/{{ !empty($sales[0]->image3) ? $sales[0]->image3 : 'sale-noimage.jpeg' }}"
                                                    alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="sale-update-picture" style="margin-left:1rem;">
                                    <div class="file-input">
                                        <div class="file-input-wrap" style="height: 3rem;margin:0 auto;">
                                            <label>
                                                <input type="file" name="image4" id="update_image4">
                                                <span class="file-input-display">ファイルを選択</span>
                                            </label>
                                        </div>
                                        <span id="update_file_name4"
                                            style="display:block;word-break: break-all;text-align:center;">{{ !empty($sales[0]->image4) ? $sales[0]->image4 : 'sale-noimage.jpeg' }}</span>
                                    </div>
                                    <div class="file-output">
                                        <div class="file-preview">
                                            <div class="file-preview-picture" style="margin-top:0.6rem;">
                                                <img id="update_file_preview_img4"
                                                    src="/uploads/{{ !empty($sales[0]->image4) ? $sales[0]->image4 : 'sale-noimage.jpeg'}}"
                                                    alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <input type="hidden" name="update_sale_id" id="update_sale_id" value="{{$sales[0]->id}}">
                    <input type="hidden" name="customer_id" id="customer_id" value="{{$customer->id}}">
                    <p class="crud-btn" id="sale_update_btn" style="margin-top:1.4rem;">変更を保存</p>
                </div>
            </form>
            @endif
            <!-- sale create -->
            <form name="createForm" id="form_sale_create">
                @csrf
                <div class="sale-create">
                    <span id="detail_update_close"></span>
                    <div class="block-title-main">
                        <h2 style="text-align: center;">カルテ新規追加</h2>
                    </div>
                    <ul class="alert alert-danger" id="createErrorMessage"></ul>
                    <div class="sale-update-top">
                        <div class="sale-update-left">
                            <div class="sale-update-item">
                                <label>
                                    <p class="form-item-title">来店日<span class="required">必須</span></p>
                                    <input type="date" name="date" id="create_sale_date" value="">
                                </label>
                            </div>
                            <div class="sale-update-item" style="margin-top: 1.4rem;">
                                <label>
                                    <p class="form-item-title">担当者<span class="required">必須</span></p>
                                    <select name="employee_id" id="sale_employee_id">
                                        <option value=''>-</option>
                                        @if(!empty($employees))
                                        @foreach($employees as $employee)
                                        <option value='{{ $employee->id }}'>
                                            {{ $employee->first_name }}{{ $employee->last_name }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </label>
                            </div>
                            <div class="sale-update-item" style="margin-top: 1.4rem;position:relative;">
                                <p class="form-item-title">メニュー</p>
                                <a id="create_menu_open" class="select-menu-open">＋メニューを選択</a>
                                <ul class="checked-list" id="create_menu_checked_list">
                                </ul>
                                <div class="select-menu-modal form-search-modal" id="create_menu_modal"
                                    style="padding-top:0;">
                                    @if(!empty($menus_categories))
                                    @foreach($menus_categories as $menus_category)
                                    <p class="menu-category-title" style="font-size: 1.3rem;">
                                        {{ $menus_category->name }}
                                    <p>
                                        @foreach($menus as $menu)
                                        @if($menu->menu_category_id == $menus_category->id)
                                    <div class="menu-input-wrap">
                                        <label>
                                            <input type="checkbox" name="create_menu_id[]" value="{{ $menu->id }}"
                                                data-menu="{{ $menu->name }}">
                                            <span class="check-input-part"></span>
                                            <span class="menu-title">{{$menu->name}}</span>
                                        </label>
                                    </div>
                                    @endif
                                    @endforeach
                                    @endforeach
                                    @endif
                                    <div style="text-align: center; margin-top:1rem;">
                                        <a id="create_menu_close">選択</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sale-update-right">
                            <label>
                                <p class="form-item-title">メモ</p>
                                <textarea name="memo" id="sale_create_memo"></textarea>
                            </label>
                        </div>
                    </div>
                    <div class="sale-update-bottom">
                        <div class="sale-update-picture-wrap">
                            <p class="form-item-title">ビフォー</p>
                            <div class="flex">
                                <div class="sale-update-picture">
                                    <div class="file-input">
                                        <div class="file-input-wrap" style="height: 3rem;margin:0 auto;">
                                            <label>
                                                <input type="file" name="image1" id="create_image1">
                                                <span class="file-input-display">ファイルを選択</span>
                                            </label>
                                        </div>
                                        <span id="create_file_name1"
                                            style="display: block;word-break: break-all;text-align:center;"></span>
                                    </div>
                                    <div class="file-output">
                                        <div class="file-preview">
                                            <div class="file-preview-picture" style="margin-top:0.6rem;">
                                                <img id="create_file_preview_img1" src="/uploads/sale-noimage.jpeg"
                                                    alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="sale-update-picture" style="margin-left:1rem;">
                                    <div class="file-input">
                                        <div class="file-input-wrap" style="height: 3rem;margin:0 auto;">
                                            <label>
                                                <input type="file" name="image2" id="create_image2">
                                                <span class="file-input-display">ファイルを選択</span>
                                            </label>
                                        </div>
                                        <span id="create_file_name2"
                                            style="display: block;word-break: break-all;text-align:center;"></span>
                                    </div>
                                    <div class="file-output">
                                        <div class="file-preview">
                                            <div class="file-preview-picture" style="margin-top:0.6rem;">
                                                <img id="create_file_preview_img2" src="/uploads/sale-noimage.jpeg"
                                                    alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="sale-update-picture-wrap">
                            <p class="form-item-title">アフター</p>
                            <div class="flex">
                                <div class="sale-update-picture">
                                    <div class="file-input">
                                        <div class="file-input-wrap" style="height: 3rem;margin:0 auto;">
                                            <label>
                                                <input type="file" name="image3" id="create_image3">
                                                <span class="file-input-display">ファイルを選択</span>
                                            </label>
                                        </div>
                                        <span id="create_file_name3"
                                            style="display: block;word-break: break-all;text-align:center;"></span>
                                    </div>
                                    <div class="file-output">
                                        <div class="file-preview">
                                            <div class="file-preview-picture" style="margin-top:0.6rem;">
                                                <img id="create_file_preview_img3" src="/uploads/sale-noimage.jpeg"
                                                    alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="sale-update-picture" style="margin-left:1rem;">
                                    <div class="file-input">
                                        <div class="file-input-wrap" style="height: 3rem;margin:0 auto;">
                                            <label>
                                                <input type="file" name="image4" id="create_image4">
                                                <span class="file-input-display">ファイルを選択</span>
                                            </label>
                                        </div>
                                        <span id="create_file_name4"
                                            style="display:block;word-break: break-all;text-align:center;"></span>
                                    </div>
                                    <div class="file-output">
                                        <div class="file-preview">
                                            <div class="file-preview-picture" style="margin-top:0.6rem;">
                                                <img id="create_file_preview_img4" src="/uploads/sale-noimage.jpeg"
                                                    alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <input type="hidden" name="customer_id" id="customer_id2" value="{{$customer->id}}">
                    <p class="crud-btn" id="sale_create_btn" style="margin-top:1.4rem;">変更を保存</p>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- delete -->
<form action="{{ route('customers.delete') }}" method="post">
    @csrf
    <div class="alert-box" id="delete_alert">
        <div class="alert-box-inner">
            <div>
                <input type="hidden" name="id" value="{{ $customer->id }}">
                <p class="alert-message" id="alert_message">顧客情報を削除しますか。</p>
                <div class="btn-wrap">
                    <button class="yes-btn" type="submit" id="delete_yes">はい</button>
                    <a class="cancel-btn" id="delete_no">キャンセル</a>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- update -->
<div class="detail-update">
    <div class="detail-update-inner">
        <div>
            <span id="detail_update_close"></span>
            <div class="block-title-main">
                <h2 style="text-align: center;">顧客情報編集</h2>
            </div>
            <form id="form-customer-update" method="post" action="{{ route('customers.update') }}"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $customer->id }}">
                <ul class="customer-update-block1">
                    <li class="customer-update-item">
                        <label>
                            <p class="form-item-title">姓<span class="required">必須</span></p>
                            <input type="text" name="first_name" id="" value="{{ $customer->first_name }}">
                        </label>
                    </li>
                    <li class="customer-update-item">
                        <label>
                            <p class="form-item-title">名<span class="required">必須</span></p>
                            <input type="text" name="last_name" id="" value="{{ $customer->last_name }}">
                        </label>
                    </li>
                    <li class="customer-update-item">
                        <label>
                            <p class="form-item-title">姓（カナ）<span class="required">必須</span></p>
                            <input type="text" name="first_name_kana" id="" value="{{ $customer->first_name_kana }}">
                        </label>
                    </li>
                    <li class="customer-update-item">
                        <label>
                            <p class="form-item-title">名（カナ）<span class="required">必須</span></p>
                            <input type="text" name="last_name_kana" id="" value="{{ $customer->last_name_kana }}">
                        </label>
                    </li>
                    <li class="customer-update-item">
                        <p class="form-item-title">性別<span class="required">必須</span></p>
                        <input type="hidden" id="customer_gender" value="{{ $customer->gender }}">
                        <select name="gender" id="update_gender">
                            <option disabled value>-</option>
                            <option value='1'>男</option>
                            <option value='2'>女</option>
                            <option value='3'>その他</option>
                        </select>
                    </li>
                </ul>
                <ul class="customer-update-block2">

                    <li class="customer-update-item">
                        <input type="hidden" id="by_hidden"
                            value="{{ !empty($customer->birthday) ? MyFunction::dateYear($customer->birthday) : '' }}">
                        <input type="hidden" id="bm_hidden"
                            value="{{ !empty($customer->birthday) ? MyFunction::dateMonth($customer->birthday) : '' }}">
                        <input type="hidden" id="bd_hidden"
                            value="{{ !empty($customer->birthday) ? MyFunction::dateDay($customer->birthday) : '' }}">
                        <label>
                            <p class="form-item-title">生年月日</p>
                            <select name="birthday_year" id="customer_birthday_year">
                                <!-- <option value="">-</option> -->
                                <option value="">-</option>
                                {{ MyFunction::yearSelect() }}
                            </select>
                            <span>年</span>
                            <select name="birthday_month" id="customer_birthday_month">
                                <!-- <option value="">-</option> -->
                                <option value="">-</option>
                                {{ MyFunction::monthSelect() }}
                            </select>
                            <span>月</span>
                            <select name="birthday_day" id="customer_birthday_day">
                                <!-- <option value="">-</option> -->
                                <option value="">-</option>
                                {{ MyFunction::daySelect() }}
                            </select>
                            <span>日</span>
                        </label>
                    </li>
                    <li class="customer-update-item">
                        <label>
                            <p class="form-item-title">ニックネーム</p>
                            <input type="text" name="nickname" id="" value="{{ $customer->nickname }}">
                        </label>
                    </li>
                    <li class="customer-update-item">
                        <label>
                            <p class="form-item-title">メールアドレス</p>
                            <input type="text" name="email" id="" value="{{ $customer->email }}">
                        </label>
                    </li>

                </ul>
                <ul class="customer-update-block3">
                    <li class="customer-update-item">
                        <label>
                            <p class="form-item-title">郵便番号</p>
                            <input type="text" name="postcode1" id=""
                                value="{{ !empty($customer->postcode) ? MyFunction::postBefore($customer->postcode) : '' }}">
                            <span>-</span>
                            <input type="text" name="postcode2" id=""
                                value="{{ !empty($customer->postcode) ? MyFunction::postAfter($customer->postcode) : '' }}">
                        </label>
                    </li>
                    <li class="customer-update-item">
                        <label>
                            <p class="form-item-title">都道府県</p>
                            <input type="text" name="prefecture" id="" value="{{ $customer->prefecture }}">
                        </label>
                    </li>
                    <li class="customer-update-item">
                        <label>
                            <p class="form-item-title">市区町村</p>
                            <input type="text" name="city" id="" value="{{ $customer->city }}">
                        </label>
                    </li>
                    <li class="customer-update-item">
                        <label>
                            <p class="form-item-title">それ以降の住所</p>
                            <input type="text" name="block" id="" value="{{ $customer->block }}">
                        </label>
                    </li>
                </ul>
                <ul class="customer-update-block4">
                    <li class="customer-update-item">
                        <label>
                            <p class="form-item-title">電話番号（自宅）</p>
                            <input type="text" name="phone_number1" id=""
                                value="{{ !empty($customer->phone_number) ? MyFunction::phoneToArray($customer->phone_number)[0] : '' }}">
                            <span>-</span>
                            <input type="text" name="phone_number2" id=""
                                value="{{ !empty($customer->phone_number) ? MyFunction::phoneToArray($customer->phone_number)[1] : '' }}">
                            <span>-</span>
                            <input type="text" name="phone_number3" id=""
                                value="{{ !empty($customer->phone_number) ? MyFunction::phoneToArray($customer->phone_number)[2] : '' }}">
                        </label>
                    </li>
                    <li class="customer-update-item" style="margin-left: 3rem;">
                        <label>
                            <p class="form-item-title">電話番号（携帯）</p>
                            <input type="text" name="cell_phone_number1" id=""
                                value="{{ !empty($customer->cell_phone_number) ? MyFunction::phoneToArray($customer->cell_phone_number)[0] : '' }}">
                            <span>-</span>
                            <input type="text" name="cell_phone_number2" id=""
                                value="{{ !empty($customer->cell_phone_number) ? MyFunction::phoneToArray($customer->cell_phone_number)[1] : '' }}">
                            <span>-</span>
                            <input type="text" name="cell_phone_number3" id=""
                                value="{{ !empty($customer->cell_phone_number) ? MyFunction::phoneToArray($customer->cell_phone_number)[2] : '' }}">
                        </label>
                    </li>
                </ul>
                <ul class="customer-update-block5">
                    <li class="customer-update-item">
                        <label>
                            <p class="form-item-title">メモ</p>
                            <textarea name="memo">{{ $customer->memo }}</textarea>
                        </label>
                    </li>
                    <li class="customer-update-item" style="display: flex; margin-left:5rem;">
                        <div class="file-input-left">
                            <p class="form-item-title">画像</p>
                            <div class="file-input-wrap">
                                <label>
                                    <input type="file" name="image" id="image">
                                    <span class="file-input-display">ファイルを選択</span>
                                </label>
                            </div>
                            <span id="file_name"
                                style="display: inline-block;word-break: break-all">{{ !empty($customer->image) ? $customer->image : 'customer-noimage.png' }}</span>
                        </div>
                        <div class="file-input-right">
                            <div class="file-preview" style="margin-left:2rem;">
                                <div class="file-preview-picture">
                                    <img id="file_preview_img"
                                        src="/uploads/{{ !empty($customer->image) ? $customer->image : 'customer-noimage.png' }}"
                                        alt="">
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <button class="crud-btn" type="submit" style="margin-top:1.4rem;">変更を保存</button>
            </form>
        </div>
    </div>
</div>
<!-- line message -->
<form id="form_line" action="" method="post">
    @csrf
    <div class="line-message-modal">
        <div class="block-title-main">
            <h2 style="text-align: center;">LINE送信</h2>
            *LINEについては想定です。(機能未実装)
        </div>
        <h2 class="block-title">宛先</h2>
        <ul class="line-to-list">
            <li class="line-checked-item"><span class="line-checked-false"></span>駒水あずさ</li>
        </ul>
        <h2 class="block-title">メッセージ</h2>
        <textarea name="line_message" id="line_message"></textarea>
        <div class="line-btn-wrap">
            <a class="yes-btn" id="line_confirm_btn" style="background:#02C755;border:solid 1px #02C755;">確認</a>
            <a id="line_submit_btn" class="yes-btn"
                style="display:none;background:#02C755;border:solid 1px #02C755;">送信</a>
            <a class="cancel-btn" id="line_message_close">閉じる</a>
        </div>
    </div>
</form>
<div class="modal-back"></div>
@endsection

@section('js')
<script src="{{ asset('/js/customer_detail.js') }}"></script>
@endsection