//Createの確認アラート
$(document).on("click", "#createButton", function (e) {
    e.preventDefault(); // 本来のボタン押下の処理のキャンセル  
      
    AlertMessage.confirm(  
        "", // confirm本文  
        "新規登録します。", // confirmタイトル  
        "info", // アラートの種類  
        function (_enter) { // コールバック  
          if (_enter) {  
            // OKが押された時の処理
            document.getElementById("createForm").submit();
          }  
        });  
});

//Editの確認アラート
$(document).on("click", "#editButton", function (e) {
    e.preventDefault(); // 本来のボタン押下の処理のキャンセル  
      
    AlertMessage.confirm(  
        "", // confirm本文  
        "本当に編集しますか？", // confirmタイトル  
        "info", // アラートの種類  
        function (_enter) { // コールバック  
          if (_enter) {  
            // OKが押された時の処理
            document.getElementById("editForm").submit();
          }  
        });  
});
//Deleteの確認アラート
$(document).on("click", "#deleteButton", function (e) {
    e.preventDefault(); // 本来のボタン押下の処理のキャンセル  
      
    AlertMessage.confirm(  
        "", // confirm本文  
        "本当に削除しますか？", // confirmタイトル  
        "info", // アラートの種類  
        function (_enter) { // コールバック  
          if (_enter) {  
            // OKが押された時の処理
            document.getElementById("deleteForm").submit();
          }  
        });  
});

//input中のEnterボタン無効
$("input"). keydown(function(e) {
    if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
        return false;
    } else {
        return true;
    }
});