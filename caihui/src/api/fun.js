
// 暂时没有用上

var colourlifeScanCodeHandler = function(response){
     //var _this=this;
    alert(JSON.stringify(response));
//     Indicator.open();
//     var QRurl=response.qrCode;
//     alert(this.getStrValue("code",QRurl,"1"));
//     let param = {
//         access_token:sessionStorage.getItem("access_token"),
//         code:this.getStrValue("code",QRurl,"1")
//     };
//     scanMobile(param).then(function (data) {
//         alert(JSON.stringify(data))
//         Indicator.close();
//         if(data.code == '0'){
//             _this.Referee=data.content.mobile;
//         }else{
//             Toast(data.message);
//         }
//     })
}

export default colourlifeScanCodeHandler;