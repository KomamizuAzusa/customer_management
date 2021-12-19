$(function() {

    /*============================
        検索条件開閉
    ============================*/
    $('#search_show').on('click',function(){
        $('#search_show').toggleClass('show');
        $('.form-search-box').toggleClass('show');
    })

    $(document).ready(function(){
        let doFlg = document.getElementById('do_flg').value;
        if(doFlg == 'select'){
            document.getElementById('search_show').classList.add("show")
            document.getElementById('form_search_box').classList.add("show")
        }
    });

    /*============================
        顧客新規追加ajax
    ============================*/
    $('#create_btn').on('click', function() {
        //formのdata取得
        var FirstName = $("#customer_first_name").val();
        var LastName = $("#customer_last_name").val();
        var FirstNameKana = $("#customer_first_name_kana").val();
        var LastNameKana = $("#customer_last_name_kana").val();
        var gender = $("#customer_gender").val();
        var birthdayYear = $("#customer_birthday_year").val();
        var birthdayMonth = $("#customer_birthday_month").val();
        var birthdayDay = $("#customer_birthday_day").val();
        var nickname = $("#customer_nickname").val();
        var postcode = $("#customer_postcode1").val();
        var postcode = $("#customer_postcode2").val();
        var prefecture = $("#customer_prefecture").val();
        var city = $("#customer_city").val();
        var block = $("#customer_block").val();
        var phoneNumber1 = $("#customer_phone_number1").val();
        var phoneNumber2 = $("#customer_phone_number2").val();
        var phoneNumber3 = $("#customer_phone_number3").val();
        var CellPhoneNumber1 = $("#customer_cell_phone_number1").val();
        var CellPhoneNumber2 = $("#customer_cell_phone_number2").val();
        var CellPhoneNumber3 = $("#customer_cell_phone_number3").val();
        var email = $("#customer_email").val();
        var image = document.getElementById("customer_image").files[0];
        var memo = $("#customer_memo").val();

 
        //フォームデータを作成する。(送信するデータ)
        var form = new FormData();
 
        //フォームデータにテキストの内容、アップロードファイルの内容を格納する。
        form.append( "first_name", FirstName );
        form.append( "last_name", LastName );
        form.append( "first_name_kana", FirstNameKana );
        form.append( "last_name_kana", LastNameKana );
        form.append( "gender", gender );
        form.append( "birthday_year", birthdayYear);
        form.append( "birthday_month", birthdayMonth );
        form.append( "birthday_day", birthdayDay );
        form.append( "nickname", nickname );
        form.append( "postcode", postcode );
        form.append( "prefecture", prefecture );
        form.append( "city", city );
        form.append( "block", block );
        form.append( "phone_number1", phoneNumber1 );
        form.append( "phone_number2", phoneNumber2 );
        form.append( "phone_number3", phoneNumber3 );
        form.append( "cell_phone_number1", CellPhoneNumber1 );
        form.append( "cell_phone_number2", CellPhoneNumber2 );
        form.append( "cell_phone_number3", CellPhoneNumber3 );
        form.append( "email", email );
        form.append( "image", image );
        form.append( "memo", memo );



        $.ajaxSetup({
            headers: {
              "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            type: "post",
            url: "/customers/create",
            data: form,
            processData : false,
            contentType : false,
        })

        .then((res) => {
            if(res == 1){
                $('.create-box').slideUp();
                $('.form-create-success').slideDown();
            }else{
                var message = '';
                for(let k of Object.keys(res)) {
                    message += '<li>' + res[k] + '</li>';
                }
                $('#customerErrorMessage').html(message);
                $('#customerErrorMessage').show();
            }
        })
        .fail((err) => {
            console.log(err.statusText);
        });
    });

    /* 続けて新規登録を選択したとき */
    $('.create-continue').on('click', function() {
        $('#customerErrorMessage').html('');
        $('#customerErrorMessage').hide();
        $('#form_create')[0].reset();
        $('.form-create-success').slideUp();
        $('.create-box').slideDown();
    });

    /*============================
        売上追加メニュー開閉
    ============================*/

    $('#create_menu_open').on('click',function(){
        $('#create_menu_modal').show();
    });

    $('#create_menu_close').on('click',function(){
        $('#create_menu_modal').hide();
        var form = document.getElementById('form-sale');
        var message = '';
        for (var i = 0; i < form.elements['sale_menu_id[]'].length; i++) {
			if (form.elements['sale_menu_id[]'][i].checked) {
                var data = form.elements['sale_menu_id[]'][i].getAttribute('data-menu')
                var id = form.elements['sale_menu_id[]'][i].value;
                if(data.length >= 6){
                    var head = data.slice(0,5);
                    var data = head + '...';
                }
				message += '<li class="checked-item"><span class="checked-false" onclick="checkedFalse(this)" data-category="saleMenu" data-id="' + id + '"></span>' + data + '</li>';
			}
		}               
        $('#create_menu_checked_list').html(message);
    });




    /*============================
        売上追加ajax
    ============================*/

    $('#sale_btn').on('click', function() {
        //formのdata取得
        var date = $("#sale_date").val();
        var employeeId = $("#sale_employee_id").val();
        var memo = $("#sale_create_memo").val();
        //sale_menu_id[] form-sale
        var sale_form = document.getElementById('form-sale');
        var menuLength = sale_form.elements['sale_menu_id[]'].length;
        let menuArray = [];
        for(var i = 0; i< menuLength; i++){
            if (sale_form.elements['sale_menu_id[]'][i].checked) {
                var id = sale_form.elements['sale_menu_id[]'][i].value;
                menuArray.push(id);
            }
        }
        var image1 = document.getElementById("sale_image1").files[0];
        var image2 = document.getElementById("sale_image2").files[0];
        var image3 = document.getElementById("sale_image3").files[0];
        var image4 = document.getElementById("sale_image4").files[0];
        var modalId = $("#sale_modal_id").val();
 
        //フォームデータを作成する。(送信するデータ)
        var form = new FormData();
 
        //フォームデータにテキストの内容、アップロードファイルの内容を格納する。
        form.append( "date", date );
        form.append( "employee_id", employeeId );
        form.append( "menu_array",menuArray );
        form.append( "memo", memo );
        form.append( "image1", image1 );
        form.append( "image2", image2 );
        form.append( "image3", image3 );
        form.append( "image4", image4 );
        form.append( "customer_id", modalId );


        $.ajaxSetup({
            headers: {
              "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            type: "post",
            url: "/customers/sale/create",
            data: form,
            processData : false,
            contentType : false,
        })

        .then((res) => {
            if(res == 1){
                location.reload();
            }else{
                var message = '';
                for(let k of Object.keys(res)) {
                    message += '<li>' + res[k] + '</li>';
                }
                $('#saleErrorMessage').html(message);
                $('#saleErrorMessage').show();
            }
        })
        .fail((err) => {
            console.log(err.statusText);
        });
    });

    /*============================
        売上追加モーダル
    ============================*/
    $('.sale-btn').on('click',function(){
        var id = $(this).data('id');
        $('#sale_modal_id').val(id);
        $('.create-sale-modal').show();
        $('.modal-back').show();
    });

    $('#create_sale_close').on('click',function(){
        $('.create-sale-modal').hide();
        $('#create_menu_modal').hide();
        $('.modal-back').hide();
    });

    /*============================
        顧客検索セレクトモーダル
    ============================*/
    $('#select_menu_open').on('click',function(){
        $('.select-menu-modal').show();
    });

    $('#select_menu_close').on('click',function(){
        $('.select-menu-modal').hide();
        var form = document.getElementById('form-search');
        var message = '';
        for (var i = 0; i < form.elements['menu_id[]'].length; i++) {
			if (form.elements['menu_id[]'][i].checked) {
                var data = form.elements['menu_id[]'][i].getAttribute('data-menu')
                var id = form.elements['menu_id[]'][i].value;
                if(data.length >= 6){
                    var head = data.slice(0,5);
                    var data = head + '...';
                }
				message += '<li class="checked-item"><span class="checked-false" onclick="checkedFalse(this)" data-category="menu" data-id="' + id + '"></span>' + data + '</li>';
			}
		}               
        $('#menu_checked_list').html(message);
    });

    $('#select_employee_open').on('click',function(){
        $('.select-employee-modal').show();
    });


    $('#select_employee_close').on('click',function(){
        $('.select-employee-modal').hide();
        var form = document.getElementById('form-search');
        var message = '';
        for (var i = 0; i < form.elements['employee_id[]'].length; i++) {
			if (form.elements['employee_id[]'][i].checked) {
                var data = form.elements['employee_id[]'][i].getAttribute('data-employee')
                var id = form.elements['employee_id[]'][i].value;
                if(data.length >= 6){
                    var head = data.slice(0,5);
                    var data = head + '...';
                }
				message += '<li class="checked-item"><span class="checked-false" onclick="checkedFalse(this)" data-category="employee" data-id="' + id + '"></span>' + data + '</li>';
			}
		}               
        $('#employee_checked_list').html(message);
    });

    /*============================
        検索時の入力チェック残し
    ============================*/

    $(document).ready(function(){
        var form = document.getElementById('form-search');

        var message1 = '';
        for (var i = 0; i < form.elements['menu_id[]'].length; i++) {
			if (form.elements['menu_id[]'][i].checked) {
                var data = form.elements['menu_id[]'][i].getAttribute('data-menu')
                var id = form.elements['menu_id[]'][i].value
                if(data.length >= 6){
                    var head = data.slice(0,5);
                    var data = head + '...';
                }
				message1 += '<li class="checked-item"><span class="checked-false" onclick="checkedFalse(this)" data-category="menu" data-id="' + id + '"></span>' + data + '</li>';
			}
		} 
        $('#menu_checked_list').html(message1);

        var message2 = '';
        for (var i = 0; i < form.elements['employee_id[]'].length; i++) {
			if (form.elements['employee_id[]'][i].checked) {
                var data = form.elements['employee_id[]'][i].getAttribute('data-employee');
                var id = form.elements['employee_id[]'][i].value;
                if(data.length >= 6){
                    var head = data.slice(0,5);
                    var data = head + '...';
                }
				message2 += '<li class="checked-item"><span class="checked-false" onclick="checkedFalse(this)" data-category="employee" data-id="' + id + '"></span>' + data + '</li>';
			}
		}
        $('#employee_checked_list').html(message2);

    });


    
    /*============================
        LINEチェック一括
    ============================*/

    $('#line_all').on('click', function() {
        $("input[name='line_id[]']").prop('checked', this.checked);
    });
    // 2. 「全選択」以外のチェックボックスがクリックされたら、
    $("input[name='line_id[]']").on('click', function() {
    if ($('#form_line :checked').length == $('#form_line :input').length) {
        // 全てのチェックボックスにチェックが入っていたら、「全選択」 = checked
        $('#line_all').prop('checked', true);
    } else {
        // 1つでもチェックが入っていたら、「全選択」 = checked
        $('#line_all').prop('checked', false);
    }
    });

    //チェックで名前入れる
    $('#line_message_open').on('click', function() {
        $("input[name='line_id[]']:checked").each(function() {
            var v = $(this).data('name');
            $('.line-to-list').append('<li class="line-checked-item"><span class="line-checked-false"></span>'+v+'</li>')
        });
    });


    /*============================
        LINE送信モーダル
    ============================*/

    $('#line_message_open').on('click',function(){
        $('.line-message-modal').show();
        $('.modal-back').show();
    })
    $('#line_message_close').on('click',function(){
        $('.line-message-modal').hide();
        $('.modal-back').hide();
        $('#line_message').prop("disabled", false);
        $('#line_confirm_btn').show();
        $('#line_submit_btn').hide();
        $('.line-to-list,.line-checked-item,.line-checked-false').removeClass('confirm');
        $('.line-to-list').html("");
    })
    $('#line_confirm_btn').on('click',function(){
        $('.line-to-list,.line-checked-item,.line-checked-false').addClass('confirm');
        $('#line_message').prop("disabled", true);
        $('#line_confirm_btn').hide();
        $('#line_submit_btn').show();
    });
    $('#line_submit_btn').on('click',function(){
        // LINE送信のajax処理
        $('.line-to-list,.line-checked-item,.line-checked-false').removeClass('confirm');
        $('#line_message').val("");
        $('#line_message').prop("disabled", false);
        $('#line_confirm_btn').show();
        $('#line_submit_btn').hide();
        $('.line-to-list').html("");
        $('.line-message-modal').hide();
        $('.modal-back').hide();
    });

});



/*============================
    売上追加モーダル
    ファイルイメージとファイル名表示
============================*/

document.getElementById('sale_image1').addEventListener('change', function (e) {
    var file = e.target.files[0];
    // ファイルのブラウザ上でのURLを取得する
    var blobUrl = window.URL.createObjectURL(file);
    // img要素に表示
    var img = document.getElementById('create_file_preview_img1');
    img.src = blobUrl;
    document.getElementById('create_file_name1').innerHTML = file.name;
});

document.getElementById('sale_image2').addEventListener('change', function (e) {
    // 1枚だけ表示する
    var file = e.target.files[0];
    // ファイルのブラウザ上でのURLを取得する
    var blobUrl = window.URL.createObjectURL(file);
    // img要素に表示
    var img = document.getElementById('create_file_preview_img2');
    img.src = blobUrl;
    document.getElementById('create_file_name2').innerHTML = file.name;
});
document.getElementById('sale_image3').addEventListener('change', function (e) {
    var file = e.target.files[0];
    // ファイルのブラウザ上でのURLを取得する
    var blobUrl = window.URL.createObjectURL(file);
    // img要素に表示
    var img = document.getElementById('create_file_preview_img3');
    img.src = blobUrl;
    document.getElementById('create_file_name3').innerHTML = file.name;
});
document.getElementById('sale_image4').addEventListener('change', function (e) {
    var file = e.target.files[0];
    // ファイルのブラウザ上でのURLを取得する
    var blobUrl = window.URL.createObjectURL(file);
    // img要素に表示
    var img = document.getElementById('create_file_preview_img4');
    img.src = blobUrl;
    document.getElementById('create_file_name4').innerHTML = file.name;
});


/*============================
    顧客新規登録画面
    ファイルイメージとファイル名表示
============================*/

document.getElementById('customer_image').addEventListener('change', function (e) {
    // 1枚だけ表示する
    var file = e.target.files[0];
    // ファイルのブラウザ上でのURLを取得する
    var blobUrl = window.URL.createObjectURL(file);
    // img要素に表示
    document.getElementById('customer-file-preview-wrap').style.display = 'block';
    var img = document.getElementById('customer-file-preview');
    img.src = blobUrl;
    document.getElementById('customer-file-name').innerHTML = file.name;
});


function checkedFalse(obj){
    var form = document.getElementById('form-search');
    var form_sale = document.getElementById('form-sale');
    var category = obj.getAttribute('data-category');
    var id = obj.getAttribute('data-id');

    if(category == 'menu'){
        for (var i = 0; i < form.elements['menu_id[]'].length; i++) {
            if(form.elements['menu_id[]'][i].value == id){
                form.elements['menu_id[]'][i].checked = false;
                obj.parentNode.remove();
            }
		} 
    }else if(category == 'employee'){
        for (var i = 0; i < form.elements['employee_id[]'].length; i++) {
            if(form.elements['employee_id[]'][i].value == id){
                form.elements['employee_id[]'][i].checked = false;
                obj.parentNode.remove();
            }
		}
    }else if(category == 'saleMenu'){
        for (var i = 0; i < form_sale.elements['sale_menu_id[]'].length; i++) {
            if(form_sale.elements['sale_menu_id[]'][i].value == id){
                form_sale.elements['sale_menu_id[]'][i].checked = false;
                obj.parentNode.remove();
            }
		} 
    }
}