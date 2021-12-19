(function (ns) {  
  
    // Sweet Alert 2が読み込まれてなければ抜ける  
    if (typeof Swal === "undefined") {  
    console.error("プラグイン「SweetAlert2」が読み込まれていません。先にscriptタグで読み込んでください");  
    return false;  
    }  
      
    var AlertMessage = function () {  
      
    // インスタンスがあるかどうかチェック  
    if (typeof AlertMessage.instance === "object") {  
    return AlertMessage.instance;  
    }  
      
    // 無ければキャッシュする  
    AlertMessage.instance = this;  
    return this;  
    };  
      
    /** 
    * confirmラッパー 
    * @param _text String 表示したい内容テキスト 
    * @param _title String 表示したいタイトルテキスト 
    * @param _mode string アラートの種類 bootstrapと同じ or 'question' ( ?マーク ) 
    * @param _callback function アラートの結果を受けて関数を呼び出す。引数に成否を渡す 
    * @param _array [] コールバックに渡したいもの配列 
    * @constructor 
    */  
    AlertMessage.prototype.confirm = function (_text, _title, _mode, _callback,_array) {  
      
    var dispTitle = _title;  
    if (typeof dispTitle === "undefined") {  
    dispTitle = "確認";  
    }  
      
    var o = {  
    allowOutSideClick: false,  
    showCancelButton: true,  
    confirmButtonText: 'OK',  
    cancelButtonText: "キャンセル",  
    customClass: {  
    cancelButton: 'btn btn-gray', //bootstrap4のクラス  
    },  
    title: dispTitle,  
    html: _text  
    };  
      
    if (_mode === "success" ||  
    _mode === "error" ||  
    _mode === "warning" ||  
    _mode === "info" ||  
    _mode === "question") {  
    o["type"] = _mode;  
    };  
      
    Swal.fire(o).then(function (result) {  
    var retBool = false;  
    if (typeof result.value !== "undefined" && result.value === true) {  
    retBool = true;  
    }  
    if(typeof _callback === "function"){  
    _callback.call(this, retBool,_array);  
    }  
    });  
      
    };  
      
    /** 
    * Alertラッパー 
    * @param _text String 表示したい内容テキスト 
    * @param _title String 表示したいタイトルテキスト 
    * @param _mode string アラートの種類 bootstrapと同じ or 'question' ( ?マーク ) 
    * @constructor 
    */  
    AlertMessage.prototype.alert = function (_text, _title, _mode) {  
      
    var o = {  
    allowOutSideClick: false,  
    type: _mode,  
    title: _title,  
    html: _text  
    }  
    Swal.fire(o);  
    };  
      
    ns.AlertMessage = new AlertMessage();  
      
})(window);  