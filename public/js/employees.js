//Deleteの確認アラート
$(document).on("click", ".submit-btn", function (e) {
    e.preventDefault(); // 本来のボタン押下の処理のキャンセル  
    
    var submitButton = $(this);

    AlertMessage.confirm(  
        "", // confirm本文  
        "本当に削除しますか？", // confirmタイトル  
        "info", // アラートの種類  
        function (_enter) { // コールバック  
          if (_enter) {  
            // OKが押された時の処理
            submitButton.parent().submit();
          }  
        });  
});