$(function(){  

    function selected(update,now){
        let a = document.getElementById(update);
        var b = document.getElementById(now).value;
        for($i=0; $i<a.length; $i++){
            if(b && a.options[$i].value == b){
                a.options[$i].setAttribute('selected', 'true')
            }
        }
    }

    selected('update_gender','customer_gender');
    selected('customer_birthday_year','by_hidden');
    selected('customer_birthday_month','bm_hidden');
    selected('customer_birthday_day','bd_hidden');

    $('#display_image').on('click', function() {
        $('.modal-back').show();
        $('#big_image').show();
    });

    $('.modal-back,#big_image_close').on('click', function() {
        $('.modal-back').hide();
        $('#big_image').hide();
    });

    $('#customer_update').on('click',function(){
        $('.detail-update').show();
        $('.modal-back').show();
    })
    
    $('.modal-back,#detail_update_close').on('click', function() {
        $('.modal-back').hide();
        $('.detail-update').hide();
    });

    
    $('.modal-back,#detail_update_close').on('click', function() {
        $('.modal-back').hide();
        $('.detail-update').hide();
    });

    $('#customer_delete').on('click',function(){
        $('#delete_alert').show();
        $('.modal-back').show();
    })
    
    $('#delete_no').on('click',function(){
        $('#delete_alert').hide();
        $('.modal-back').hide();
    })
    
    $('#sale_delete').on('click',function(){
        $('#sale_delete_alert').show();
        $('.modal-back').show();
    })
    
    $('#sale_delete_no').on('click',function(){
        $('#sale_delete_alert').hide();
        $('.modal-back').hide();
    })

    $('#sale_update').on('click',function(){
        $('.sale-update').show();
        $('.modal-back').show();
    })
    
    $('.modal-back,#detail_update_close').on('click', function() {
        $('.modal-back').hide();
        $('.sale-update').hide();
    });
    $('#sale_update').on('click',function(){
        $('.sale-update').show();
        $('.modal-back').show();
    })
    
    $('.modal-back,#detail_update_close').on('click', function() {
        $('.modal-back').hide();
        $('.sale-update').hide();
    });

    $('#sale_create').on('click',function(){
        $('.sale-create').show();
        $('.modal-back').show();
    })
    
    $('.modal-back,#detail_update_close').on('click', function() {
        $('.modal-back').hide();
        $('.sale-create').hide();
    });

    /*============================
        LINE送信モーダル
    ============================*/

    $('#line_message_open').on('click',function(){
        $('.line-message-modal').show();
        $('.modal-back').show();
    });
    $('#line_message_close').on('click',function(){
        $('.line-message-modal').hide();
        $('#line_message').prop("disabled", false);
        $('#line_confirm_btn').show();
        $('#line_submit_btn').hide();
        $('.line-to-list,.line-checked-item,.line-checked-false').removeClass('confirm');
        $('.modal-back').hide();
    });
    
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
        $('.line-message-modal').hide();
        $('.modal-back').hide();
    });

    /*============================
        売上追加モーダル
    ============================*/

    $('#update_menu_open').on('click',function(){
        $('#update_menu_modal').show();
    });

    $('#update_menu_close').on('click',function(){
        $('#update_menu_modal').hide();
        var form = document.getElementById('form_sale_update');
        var message = '';
        for (var i = 0; i < form.elements['update_menu_id[]'].length; i++) {
			if (form.elements['update_menu_id[]'][i].checked) {
                var data = form.elements['update_menu_id[]'][i].getAttribute('data-menu')
                var id = form.elements['update_menu_id[]'][i].value;
                if(data.length >= 6){
                    var head = data.slice(0,5);
                    var data = head + '...';
                }
				message += '<li class="checked-item"><span class="checked-false" onclick="updateCheckedFalse(this)" data-category="menu" data-id="' + id + '"></span>' + data + '</li>';
			}
		}               
        $('#update_menu_checked_list').html(message);
    });


    $('#create_menu_open').on('click',function(){
        $('#create_menu_modal').show();
    });

    $('#create_menu_close').on('click',function(){
        $('#create_menu_modal').hide();
        var form = document.getElementById('form_sale_create');
        var message = '';
        for (var i = 0; i < form.elements['create_menu_id[]'].length; i++) {
			if (form.elements['create_menu_id[]'][i].checked) {
                var data = form.elements['create_menu_id[]'][i].getAttribute('data-menu')
                var id = form.elements['create_menu_id[]'][i].value;
                if(data.length >= 6){
                    var head = data.slice(0,5);
                    var data = head + '...';
                }
				message += '<li class="checked-item"><span class="checked-false" onclick="createCheckedFalse(this)" data-category="menu" data-id="' + id + '"></span>' + data + '</li>';
			}
		}               
        $('#create_menu_checked_list').html(message);
    });


    var update_form = document.getElementById('form_sale_update');
    var message1 = '';
    for (var i = 0; i < update_form.elements['update_menu_id[]'].length; i++) {
        if (update_form.elements['update_menu_id[]'][i].checked) {
            var data = update_form.elements['update_menu_id[]'][i].getAttribute('data-menu')
            var id = update_form.elements['update_menu_id[]'][i].value
            if(data.length >= 6){
                var head = data.slice(0,5);
                var data = head + '...';
            }
            message1 += '<li class="checked-item"><span class="checked-false" onclick="updateCheckedFalse(this)" data-id="' + id + '"></span>' + data + '</li>';
        }
    } 
    $('#update_menu_checked_list').html(message1);


    $('#sale_update_btn').on('click', function() {
        //formのdata取得
        var date = $("#update_sale_date").val();
        var employeeId = $("#update_employee_select").val();
        var menuLength = document.formSaleUpdate.elements["update_menu_id[]"].length;
        let menuArray = []; 
        for(var i = 0; i< menuLength; i++){
            if (update_form.elements['update_menu_id[]'][i].checked) {
                var id = update_form.elements['update_menu_id[]'][i].value;
                menuArray.push(id);
            }
        }
        var memo = $("#update_sale_memo").val();
        var image1 = document.getElementById("update_image1").files[0];
        var image2 = document.getElementById("update_image2").files[0];
        var image3 = document.getElementById("update_image3").files[0];
        var image4 = document.getElementById("update_image4").files[0];
        var saleId = $("#update_sale_id").val();
        var customerId = $("#customer_id").val();
 
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
        form.append( "sale_id", saleId );
        form.append( "customer_id", customerId );


        $.ajaxSetup({
            headers: {
              "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            type: "post",
            url: "/customers/sale/update",
            data: form,
            processData : false,
            contentType : false,
        })

        .done(function(result){
            if(result == 1){
                location.reload();
            }else{
                var message = '';
                for(let k of Object.keys(result)) {
                    message += '<li>' + result[k] + '</li>';
                }
                $('#updateErrorMessage').html(message);
                $('#updateErrorMessage').show();
            }
        })
        .fail(function(data) {
            console.log(err.statusText);
        });
    });

    $('#sale_create_btn').on('click', function() {
        //formのdata取得
        var date = $("#create_sale_date").val();
        var employeeId = $("#sale_employee_id").val();
        var create_form = document.getElementById('form_sale_create');
        var menuLength = create_form.elements['create_menu_id[]'].length;
        let menuArray = [];
        for(var i = 0; i< menuLength; i++){
            if (create_form.elements['create_menu_id[]'][i].checked) {
                var id = create_form.elements['create_menu_id[]'][i].value;
                menuArray.push(id);
            }
        }
        var memo = $("#sale_create_memo").val();
        var image1 = document.getElementById("create_image1").files[0];
        var image2 = document.getElementById("create_image2").files[0];
        var image3 = document.getElementById("create_image3").files[0];
        var image4 = document.getElementById("create_image4").files[0];
        var customerId = $("#customer_id2").val();
 
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
        form.append( "customer_id", customerId );


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
                $('#createErrorMessage').html(message);
                $('#createErrorMessage').show();
            }
        })
        .fail((err) => {
            console.log(err.statusText);
        });
    });
    
});


document.getElementById('update_image1').addEventListener('change', function (e) {
    // 1枚だけ表示する
    var file = e.target.files[0];
    // ファイルのブラウザ上でのURLを取得する
    var blobUrl = window.URL.createObjectURL(file);
    // img要素に表示
    var img = document.getElementById('update_file_preview_img1');
    img.src = blobUrl;
    document.getElementById('update_file_name1').innerHTML = file.name;
    
});
document.getElementById('update_image2').addEventListener('change', function (e) {
    // 1枚だけ表示する
    var file = e.target.files[0];
    // ファイルのブラウザ上でのURLを取得する
    var blobUrl = window.URL.createObjectURL(file);
    // img要素に表示
    var img = document.getElementById('update_file_preview_img2');
    img.src = blobUrl;
    document.getElementById('update_file_name2').innerHTML = file.name;
    
});
document.getElementById('update_image3').addEventListener('change', function (e) {
    // 1枚だけ表示する
    var file = e.target.files[0];
    // ファイルのブラウザ上でのURLを取得する
    var blobUrl = window.URL.createObjectURL(file);
    // img要素に表示
    var img = document.getElementById('update_file_preview_img3');
    img.src = blobUrl;
    document.getElementById('update_file_name3').innerHTML = file.name;
    
});
document.getElementById('update_image4').addEventListener('change', function (e) {
    // 1枚だけ表示する
    var file = e.target.files[0];
    // ファイルのブラウザ上でのURLを取得する
    var blobUrl = window.URL.createObjectURL(file);
    // img要素に表示
    var img = document.getElementById('update_file_preview_img4');
    img.src = blobUrl;
    document.getElementById('update_file_name4').innerHTML = file.name;
    
});
document.getElementById('create_image1').addEventListener('change', function (e) {
    // 1枚だけ表示する
    var file = e.target.files[0];
    // ファイルのブラウザ上でのURLを取得する
    var blobUrl = window.URL.createObjectURL(file);
    // img要素に表示
    var img = document.getElementById('create_file_preview_img1');
    img.src = blobUrl;
    document.getElementById('create_file_name1').innerHTML = file.name;
    
});
document.getElementById('create_image2').addEventListener('change', function (e) {
    // 1枚だけ表示する
    var file = e.target.files[0];
    // ファイルのブラウザ上でのURLを取得する
    var blobUrl = window.URL.createObjectURL(file);
    // img要素に表示
    var img = document.getElementById('create_file_preview_img2');
    img.src = blobUrl;
    document.getElementById('create_file_name2').innerHTML = file.name;
    
});
document.getElementById('create_image3').addEventListener('change', function (e) {
    // 1枚だけ表示する
    var file = e.target.files[0];
    // ファイルのブラウザ上でのURLを取得する
    var blobUrl = window.URL.createObjectURL(file);
    // img要素に表示
    var img = document.getElementById('create_file_preview_img3');
    img.src = blobUrl;
    document.getElementById('create_file_name3').innerHTML = file.name;
    
});
document.getElementById('create_image4').addEventListener('change', function (e) {
    // 1枚だけ表示する
    var file = e.target.files[0];
    // ファイルのブラウザ上でのURLを取得する
    var blobUrl = window.URL.createObjectURL(file);
    // img要素に表示
    var img = document.getElementById('create_file_preview_img4');
    img.src = blobUrl;
    document.getElementById('create_file_name4').innerHTML = file.name;
    
});


function saleDisplay(obj){
    var id = obj.getAttribute('data-id');
    $(function(){
        $.ajax({
            type: "get",
            url: "/customers/sale/display",
            data: {'id': id},
        })

        .then((res) => {
            if(res[0].menu){
                var menu_arr = res[0].menu.split(',');
                var price_arr = res[0].menu_price.split(',');
                var len = menu_arr.length;
                var menu_text = '<div class="data-item-wrap">';
                var sum_price = 0;
                for(i=0; i<len; i++){
                    sum_price += Number(price_arr[i]);
                    menu_text += '<p class="data-item"><span class="name">' + menu_arr[i] + '</span><span class="price">¥' + Number(price_arr[i]).toLocaleString() + '</span></p>';
                }
                menu_text += '</div>';
                menu_text += '<p class="data-item sum"><span class="name">計</span><span class="price">¥' + sum_price.toLocaleString() +'</span></p>';
                
                var update_form = document.getElementById('form_sale_update');
                for (var i = 0; i < update_form.elements['update_menu_id[]'].length; i++) {
                                    
                    if(menu_arr.includes(update_form.elements['update_menu_id[]'][i].getAttribute('data-menu'))){
                        update_form.elements['update_menu_id[]'][i].checked = true;
                    }else{
                        update_form.elements['update_menu_id[]'][i].checked = false;
                    }
                    
                }
                var message1 = '';
                for (var i = 0; i < update_form.elements['update_menu_id[]'].length; i++) {
                    if (update_form.elements['update_menu_id[]'][i].checked) {
                        var data = update_form.elements['update_menu_id[]'][i].getAttribute('data-menu')
                        var id = update_form.elements['update_menu_id[]'][i].value
                        if(data.length >= 6){
                            var head = data.slice(0,5);
                            var data = head + '...';
                        }
                        message1 += '<li class="checked-item"><span class="checked-false" onclick="updateCheckedFalse(this)" data-id="' + id + '"></span>' + data + '</li>';
                    }
                } 
                $('#update_menu_checked_list').html(message1);


            }else{
                menu_text = null;
                var update_form = document.getElementById('form_sale_update');
                for (var i = 0; i < update_form.elements['update_menu_id[]'].length; i++) {
                    update_form.elements['update_menu_id[]'][i].checked = false; 
                }
                $('#update_menu_checked_list').html(''); 
            }

            
            
            var date_split = res[0].date.split('-');
            var date = Number(date_split[0]) + '年' + Number(date_split[1]) + '月' + Number(date_split[2]) + '日';

            $('#focus_sale_id').val(res[0].id);
            $('#delete_sale_id').val(res[0].id);
            $('#update_sale_id').val(res[0].id);

            $('#sale_menu').html(menu_text);
            $('#update_menu_checked_list').html();  

            $('#sale_date').html(date);
            $('#update_sale_date').val(res[0].date);
            $('#update_sale_date').html(date);


            $('#sale_employee').html(res[0].employee);
            var select = document.getElementById('update_employee_select');
            for(var i = 0; i < select.options.length; i++ ){
                if(select.options[i].value == res[0].employee_id){
                    select.options[i].selected = true;
                }else{
                    select.options[i].selected = false;
                }
            }       

            $('#sale_memo').html(res[0].memo);
            $('#update_sale_memo').html(res[0].memo);

            if(res[0].image1){
                $('#sale_image1').attr('src', '/uploads/' + res[0].image1);
                $('#update_file_preview_img1').attr('src', '/uploads/' + res[0].image1);
                $('#update_file_name1').html(res[0].image1);
            }else{
                $('#sale_image1').attr('src', '/uploads/sale-noimage.jpeg');
                $('#update_file_preview_img1').attr('src', '/uploads/sale-noimage.jpeg');
                $('#update_file_name1').html('');
            }
            if(res[0].image2){
                $('#sale_image2').attr('src', '/uploads/' + res[0].image2);
                $('#update_file_preview_img2').attr('src', '/uploads/' + res[0].image2);
                $('#update_file_name2').html(res[0].image2);
            }else{
                $('#sale_image2').attr('src', '/uploads/sale-noimage.jpeg');
                $('#update_file_preview_img2').attr('src', '/uploads/sale-noimage.jpeg');
                $('#update_file_name2').html('');
            }
            if(res[0].image3){
                $('#sale_image3').attr('src', '/uploads/' + res[0].image3);
                $('#update_file_preview_img3').attr('src', '/uploads/' + res[0].image3);
                $('#update_file_name3').html(res[0].image3);
            }else{
                $('#sale_image3').attr('src', '/uploads/sale-noimage.jpeg');
                $('#update_file_preview_img3').attr('src', '/uploads/sale-noimage.jpeg');
                $('#update_file_name3').html('');
            }
            if(res[0].image4){
                $('#sale_image4').attr('src', '/uploads/' + res[0].image4);
                $('#update_file_preview_img4').attr('src', '/uploads/' + res[0].image4);
                $('#update_file_name4').html(res[0].image4);
            }else{
                $('#sale_image4').attr('src', '/uploads/sale-noimage.jpeg');
                $('#update_file_preview_img4').attr('src', '/uploads/sale-noimage.jpeg');
                $('#update_file_name4').html('');
            }



            let saleSmallItems = document.getElementsByClassName('sale-small-items');
            var count = saleSmallItems.length;
            for($i=0; $i<count; $i++){
                if(saleSmallItems[$i].getAttribute('data-id') == document.getElementById('focus_sale_id').value){
                    saleSmallItems[$i].classList.add("focus");
                }else{
                    saleSmallItems[$i].classList.remove("focus")
                }
            }

        })
        .fail((err) => {
            console.log(err.statusText);
        });
    
    });
}

let saleSmallItems = document.getElementsByClassName('sale-small-items');
var count = saleSmallItems.length;
for($i=0; $i<count; $i++){
    if(saleSmallItems[$i].getAttribute('data-id') == document.getElementById('focus_sale_id').value){
        saleSmallItems[$i].classList.add("focus");
    }else{
        saleSmallItems[$i].classList.remove("focus")
    }
}

function updateCheckedFalse(obj){
    var form = document.getElementById('form_sale_update');
    var id = obj.getAttribute('data-id');

        for (var i = 0; i < form.elements['update_menu_id[]'].length; i++) {
            if(form.elements['update_menu_id[]'][i].value == id){
                form.elements['update_menu_id[]'][i].checked = false;
                obj.parentNode.remove();
            }
		} 
    
}

function createCheckedFalse(obj){
    var form = document.getElementById('form_sale_create');
    var id = obj.getAttribute('data-id');

        for (var i = 0; i < form.elements['create_menu_id[]'].length; i++) {
            if(form.elements['create_menu_id[]'][i].value == id){
                form.elements['create_menu_id[]'][i].checked = false;
                obj.parentNode.remove();
            }
		} 
    
}