@extends('common.flame')

@section('cssStyle')
<link rel="stylesheet" href="{{ asset('css/c_search_input.css') }}">
<link rel="stylesheet" href="{{ asset('css/customer.css') }}">
@endsection

@section('content')

<div class="inner">
    <input type="hidden" id="do_flg" value="{{ isset($do_flg) ? $do_flg : ''}}">


    <input id="tab1" type="radio" name="tab_btn" checked>
    <input id="tab2" type="radio" name="tab_btn">

    <div class="tab_area">
        <label class="tab1_label" for="tab1">顧客情報一覧</label>
        <label class="tab2_label" for="tab2">顧客新規登録</label>
    </div>

    <div class="panel_area">
        <!-- 顧客情報一覧 -->
        <div id="panel1" class="tab_panel">
            <!-- 直近20件 -->
            <div class="log-wrap">
                <h2 class="block-title">閲覧履歴</h2>
                <ul class="log-list">
                    @foreach($search_data as $data)
                    <li>
                        <a href="{{ route('customers.detail',['id' => $data->id]) }}"
                            style="text-decoration: underline;">
                            {{ $data->first_name }}{{ $data->last_name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- 検索 -->
            <form id="form-search" action="{{ route('customers') }}" method="post">
                @csrf
                <h2 class="block-title" style="display: inline-block;">顧客検索</h2>
                <a class="terms-btn" id="search_show">条件を設定</a>

                <div class="form-search-box" id="form_search_box">
                    <div class="search-block-wrap">
                        <ul class="form-search-block1">
                            <li class="form-search-item">
                                <label>
                                    <p class="form-item-title">名前</p>
                                    <input type="text" name="name" id=""
                                        value="{{ isset($old->name) ? $old->name : ''}}">
                                </label>
                            </li>
                            <li class="form-search-item">
                                <label>
                                    <p class="form-item-title">年齢</p>
                                    <div>
                                        <input type="text" name="age1" id="age1"
                                            value="{{ isset($old->age1) ? $old->age1 : ''}}">
                                        <span>〜</span>
                                        <input type="text" name="age2" id="age2"
                                            value="{{ isset($old->age2) ? $old->age2 : ''}}">
                                        <span>才</span>
                                    </div>
                                </label>
                            </li>
                            <li class="form-search-item">
                                <p class="form-item-title">性別</p>
                                <ul class="select-gender-list">
                                    <li class="select-gender-item">
                                        <label>
                                            <input type="checkbox" name="gender[]" value="1"
                                                {{ isset($old->gender) && in_array(1,$old->gender) ? 'checked' : ''}}>
                                            <span class="check-input-part"></span>
                                            <p style="display: inline-block;">男</p>
                                        </label>
                                    </li>
                                    <li class="select-gender-item">
                                        <label>
                                            <input type="checkbox" name="gender[]" value="2"
                                                {{ isset($old->gender) && in_array(2,$old->gender) ? 'checked' : ''}}>
                                            <span class="check-input-part"></span>
                                            <p style="display: inline-block;">女</p>
                                        </label>
                                    </li>
                                    <li class="select-gender-item">
                                        <label>
                                            <input type="checkbox" name="gender[]" value="3"
                                                {{ isset($old->gender) && in_array(3,$old->gender) ? 'checked' : ''}}>
                                            <span class="check-input-part"></span>
                                            <p style="display: inline-block;">その他</p>
                                        </label>
                                    </li>
                                </ul>
                            </li>
                            <li class="form-search-item">
                                <label>
                                    <p class="form-item-title">メールアドレス</p>
                                    <input type="text" name="email" value="{{ isset($old->email) ? $old->email : ''}}">
                                </label>
                            </li>
                        </ul>
                        <ul class="form-search-block2">
                            <li class="form-search-item">
                                <label>
                                    <p class="form-item-title">電話番号</p>
                                    <input type="text" name="phone_number"
                                        value="{{ isset($old->phone_number) ? $old->phone_number : ''}}">
                                </label>
                            </li>
                            <li class="form-search-item last-sale-between">
                                <label>
                                    <p class="form-item-title">最終来店日</p>
                                    <input type="date" name="last_sale_before"
                                        value="{{ isset($old->last_sale_before) ? $old->last_sale_before : ''}}">
                                    <span>〜</span>
                                    <input type="date" name="last_sale_after"
                                        value="{{ isset($old->last_sale_after) ? $old->last_sale_after : ''}}">
                                </label>
                            </li>
                            <li class="form-search-item">
                                <label>
                                    <p class="form-item-title">来店回数</p>
                                    <input type="text" name="sale_count_before"
                                        value="{{ isset($old->sale_count_before) ? $old->sale_count_before : ''}}">
                                    <span>〜</span>
                                    <input type="text" name="sale_count_after"
                                        value="{{ isset($old->sale_count_after) ? $old->sale_count_after : ''}}">
                                    <span>回</span>
                                </label>
                            </li>

                        </ul>
                        <ul class="form-search-block3">
                            <li class="form-search-item select">
                                <p class="form-item-title">メニュー</p>
                                <a id="select_menu_open">＋メニューを選択</a>
                                <ul class="checked-list" id="menu_checked_list">
                                </ul>
                                <div class="select-menu-modal form-search-modal" style="padding-top:0;">
                                    @if(!empty($menus_categories))
                                    @foreach($menus_categories as $menus_category)
                                    <p class="menu-category-title" style="font-size: 1.3rem;">
                                        {{ $menus_category->name }}
                                    <p>
                                        @foreach($menus as $menu)
                                        @if($menu->menu_category_id == $menus_category->id)
                                    <div class="menu-input-wrap">
                                        <label>
                                            <input type="checkbox" name="menu_id[]" value="{{ $menu->id }}"
                                                data-menu="{{ $menu->name }}"
                                                {{ isset($old->menu_id) && in_array($menu->id ,$old->menu_id) ? 'checked' : ''}}>
                                            <span class="check-input-part"></span>
                                            <span class="menu-title">{{$menu->name}}</span>
                                        </label>
                                    </div>
                                    @endif
                                    @endforeach
                                    @endforeach
                                    @endif
                                    <div style="text-align: center; margin-top:1rem;">
                                        <a id="select_menu_close">選択</a>
                                    </div>
                                </div>
                            </li>
                            <li class="form-search-item select" style="margin-left: 0.1rem;">
                                <p class="form-item-title">担当者</p>
                                <a id="select_employee_open">＋担当者を選択</a>
                                <ul class="checked-list" id="employee_checked_list">
                                </ul>
                                <div class="select-employee-modal form-search-modal">
                                    @if(!empty($employees))
                                    @foreach($employees as $employee)
                                    <div class="employee-input-wrap">
                                        <label>
                                            <input type="checkbox" name="employee_id[]" value="{{ $employee->id }}"
                                                data-employee="{{ $employee->first_name.$employee->last_name }}"
                                                {{ isset($old->employee_id) && in_array($employee->id ,$old->employee_id) ? 'checked' : ''}}>
                                            <span class="check-input-part"></span>
                                            <span
                                                class="employee-name">{{ $employee->first_name.$employee->last_name }}</span>
                                        </label>
                                    </div>
                                    @endforeach
                                    @endif
                                    <div style="text-align: center;margin-top:1rem;">
                                        <a id="select_employee_close">選択</a>
                                    </div>
                                </div>
                            </li>
                            <li class="form-search-item">
                                <label>
                                    <p class="form-item-title">メモ</p>
                                    <input type="text" name="memo" value="{{ isset($old->memo) ? $old->memo : ''}}">
                                </label>
                            </li>
                        </ul>
                    </div>
                    @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $message)
                        <p>{{ $message }}</p>
                        @endforeach
                    </div>
                    @endif
                    <button class="crud-btn" type="submit">検索</button>
                </div>
            </form>
            <!-- ./検索 -->


            <!-- 一覧 -->
            <p class="customer-count">（検索結果：{{ !empty($customers) && $customers!=1 ? count($customers) : '0' }}件）</p>
            @isset($customers)
            <div class="search-table-wrap">
                <table class="search-table">
                    <thead>
                        <tr>
                            <th style="width:5rem;">
                                <label>
                                    <input type="checkbox" id="line_all">
                                    <span class="check-input-part"></span>
                                </label>
                            </th>
                            <th style="width:16rem;">名前</th>
                            <th style="width:18rem;">名前（カナ）</th>
                            <th style="width:10rem;">性別</th>
                            <th style="width:10rem;">年齢</th>
                            <th style="width:17rem;">生年月日</th>
                            <th style="width:17rem;">最終売上日</th>
                            <th style="width:10.1rem;"></th>
                            <th style="width:10.1rem;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($customers == 1 || count($customers) == 0)
                        <tr style="border-bottom:none;display:flex;justify-content:center;">
                            <td style="text-align:center;padding:2rem 0;">検索条件に一致するお客様はいません</td>
                        </tr>
                        @endif
                        @if($customers != 1 && count($customers) > 0)
                        @foreach($customers as $customer)
                        <tr>
                            <td style="width: 5rem;">
                                <label>
                                    <input type="checkbox" name="line_id[]" value="{{ $customer->id }}" data-name="{{$customer->first_name.$customer->last_name}}">
                                    <span class="check-input-part"></span>
                                </label>
                            </td>
                            <td style="width:16rem;">{{ $customer->first_name.$customer->last_name }}</td>
                            <td style="width:18rem;">
                                {{ $customer->first_name_kana.$customer->last_name_kana }}
                            </td>
                            <td style="width:10rem;">{{ MyFunction::numToGender($customer->gender) }}</td>
                            <td style="width:10rem;" style="padding-left: 20px;">{{ $customer->age }}</td>
                            <td style="width:17rem;">{{ $customer->birthday }}</td>
                            <td style="width:17rem;"><a style="color: #3490DC; text-decoration: underline;"
                                    href="{{ route('customers.detail',['id' => $customer->id]) }}">{{ $customer->last_date }}</a></td>
                            <td style="width:10.1rem;" style="padding: 0.6rem;">
                                <a class="sale-btn" id="sale-btn" data-id="{{ $customer->id }}">売上追加</a>
                            </td>
                            <td style="width:10.1rem;" style="padding: 0.6rem; ">
                                <a href="{{ route('customers.detail',['id' => $customer->id]) }}"
                                    class="detail-btn">詳細</a>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div style="text-align: center;">
                <a class="message-btn" id="line_message_open">LINE</a>
            </div>
            <!-- lineモーダル -->
            <form id="form_line" action="" method="post">
                @csrf
                <div class="line-message-modal">
                    <div class="block-title-main">
                        <h2 style="text-align: center;">LINE送信</h2>
                        *LINEについては想定です。(機能未実装)
                    </div>
                    <h2 class="block-title">宛先</h2>
                    <ul class="line-to-list">
                    </ul>
                    <h2 class="block-title">メッセージ</h2>
                    <textarea name="line_message" id="line_message"></textarea>
                    <div class="line-btn-wrap">
                        <a class="yes-btn" id="line_confirm_btn"
                            style="background:#02C755;border:solid 1px #02C755;">確認</a>
                        <a id="line_submit_btn" class="yes-btn"
                            style="display:none;background:#02C755;border:solid 1px #02C755;">送信</a>
                        <a class="cancel-btn" id="line_message_close">閉じる</a>
                    </div>
                </div>
            </form>
            @endisset
        </div>
        <!-- 売上追加モーダル -->
        <div class="create-sale-modal">
            <div class="modal-close-box" id="create_sale_close">
                <span class="modal-close"></span>
            </div>
            <form id="form-sale" name="formSale" method="post">
                @csrf
                <div class="form-sale-inner">
                    <h2 class="block-title" style="text-align: center; margin-bottom:20px;">売上追加</h2>
                <div class="sale-update-top">
                        <div class="sale-update-left _wide">
                            <div class="sale-update-item">
                                <label>
                                    <p class="form-item-title">来店日<span class="required">必須</span></p>
                                    <input type="date" name="date" id="sale_date" value="">
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
                                            <input type="checkbox" name="sale_menu_id[]" value="{{ $menu->id }}"
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
                                                <input type="file" name="image1" id="sale_image1">
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
                                                <input type="file" name="image2" id="sale_image2">
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
                                                <input type="file" name="image3" id="sale_image3">
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
                                                <input type="file" name="image4" id="sale_image4">
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
                    <input type="hidden" value="" name="customer_id" id="sale_modal_id">
                    <ul class="alert alert-danger" id="saleErrorMessage"></ul>
                    <button class="crud-btn" id="sale_btn" type="button">追加</button>
                </div>
            </form>
        </div>
        <div class='modal-back'></div>


        <!-- 顧客新規登録 -->
        <div id="panel2" class="tab_panel">
            <form name="form-create" id="form_create">
                @csrf
                <div class="create-box">
                    <h2 class="block-title">顧客情報登録</h2>
                    <ul class="alert alert-danger" id="customerErrorMessage"></ul>
                    <div class="form-create-inner">
                        <ul class="form-create-block1">
                            <li class="form-create-item">
                                <label>
                                    <p class="form-item-title">姓<span class="required">必須</span></p>
                                    <input type="text" name="first_name" id="customer_first_name" value="">
                                </label>
                            </li>
                            <li class="form-create-item">
                                <label>
                                    <p class="form-item-title">名<span class="required">必須</span></p>
                                    <input type="text" name="last_name" id="customer_last_name" value="">
                                </label>
                            </li>
                            <li class="form-create-item">
                                <label>
                                    <p class="form-item-title">姓（カナ）<span class="required">必須</span></p>
                                    <input type="text" name="first_name_kana" id="customer_first_name_kana" value="">
                                </label>
                            </li>
                            <li class="form-create-item">
                                <label>
                                    <p class="form-item-title">名（カナ）<span class="required">必須</span></p>
                                    <input type="text" name="last_name_kana" id="customer_last_name_kana" value="">
                                </label>
                            </li>
                            <li class="form-create-item">
                                <label>
                                    <p class="form-item-title">性別<span class="required">必須</span></p>
                                    <select name="gender" id="customer_gender">
                                        <option value="">-</option>
                                        <option value='1'>男</option>
                                        <option value='2'>女</option>
                                        <option value='3'>その他</option>
                                    </select>
                                </label>
                            </li>

                        </ul>
                        <ul class="form-create-block2">
                            <li class="form-create-item">
                                <label>
                                    <p class="form-item-title">生年月日</p>
                                    <select name="birthday_year" id="customer_birthday_year">
                                        <option value="">-</option>
                                        {{ MyFunction::yearSelect() }}
                                    </select>
                                    <span>年</span>
                                    <select name="birthday_month" id="customer_birthday_month">
                                        <option value="">-</option>
                                        {{ MyFunction::monthSelect() }}
                                    </select>
                                    <span>月</span>
                                    <select name="birthday_day" id="customer_birthday_day">
                                        <option value="">-</option>
                                        {{ MyFunction::daySelect() }}
                                    </select>
                                    <span>日</span>
                                </label>
                            </li>
                            <li class="form-create-item">
                                <label>
                                    <p class="form-item-title">ニックネーム</p>
                                    <input type="text" name="nickname" id="customer_nickname" value="">
                                </label>
                            </li>
                            <li class="form-create-item">
                                <label>
                                    <p class="form-item-title">メールアドレス</p>
                                    <input type="text" name="email" id="customer_email" value="">
                                </label>
                            </li>
                        </ul>
                        <ul class="form-create-block3">
                            <li class="form-create-item">
                                <label>
                                    <p class="form-item-title">郵便番号</p>
                                    <input type="text" name="postcode1" id="customer_postcode1">
                                    <span>-</span>
                                    <input type="text" name="postcode2" id="customer_postcode2">
                                </label>
                            </li>
                            <li class="form-create-item">
                                <label>
                                    <p class="form-item-title">都道府県</p>
                                    <input type="text" name="prefecture" id="customer_prefecture" value="">
                                </label>
                            </li>
                            <li class="form-create-item">
                                <label>
                                    <p class="form-item-title">市区町村</p>
                                    <input type="text" name="city" id="customer_city" value="">
                                </label>
                            </li>
                            <li class="form-create-item">
                                <label>
                                    <p class="form-item-title">それ以降の住所</p>
                                    <input type="text" name="block" id="customer_block" value="">
                                </label>
                            </li>
                        </ul>
                        <ul class="form-create-block4">
                            <li class="form-create-item">
                                <label>
                                    <p class="form-item-title">電話番号（自宅）</p>
                                    <input type="text" name="phone_number1" id="customer_phone_number1">
                                    <span>-</span>
                                    <input type="text" name="phone_number2" id="customer_phone_number2">
                                    <span>-</span>
                                    <input type="text" name="phone_number3" id="customer_phone_number3">
                                </label>
                            </li>
                            <li class="form-create-item" style="margin-left: 10rem;">
                                <label>
                                    <p class="form-item-title">電話番号（携帯）</p>
                                    <input type="text" name="cell_phone_number1" id="customer_cell_phone_number1">
                                    <span>-</span>
                                    <input type="text" name="cell_phone_number2" id="customer_cell_phone_number2">
                                    <span>-</span>
                                    <input type="text" name="cell_phone_number3" id="customer_cell_phone_number3">
                                </label>
                            </li>
                        </ul>
                        <ul class="form-create-block5" style="margin-top: 2.8rem;">
                            <li class="form-create-item">
                                <label>
                                    <p class="form-item-title">メモ</p>
                                    <textarea name="memo" id="customer_memo" value=""></textarea>
                                </label>
                            </li>
                            <li class="form-create-item" style="margin-left: 8rem;">
                                <label>
                                    <p class="form-item-title">画像</p>
                                    <div class="file-input-wrap" style="width: 14rem;">
                                        <input type="file" name="image" id="customer_image">
                                        <span class="file-input-display">ファイルを選択</span>
                                        <p id="customer-file-name"
                                            style="display: inline-block;word-break: break-all; margin-top:3.5rem;"></p>
                                    </div>
                                </label>
                            </li>
                            <li class="form-create-item">
                                <div id="customer-file-preview-wrap">
                                    <div class="customer-file-preview-picture">
                                        <img id="customer-file-preview" src="/uploads/customer-noimage.png" alt="">
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <button class="crud-btn" type="button" id="create_btn">新規登録</button>
                </div>
                <div class="form-create-success">
                    <p class="success-msg">登録完了しました。</p>
                    <p class="create-continue">＋登録を続ける</p>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('/js/customers.js') }}"></script>
@endsection