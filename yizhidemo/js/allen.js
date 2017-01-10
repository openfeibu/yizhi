//html fontSize 重置
(function (doc, win) {
  var docEl = doc.documentElement,
  resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
  recalc = function () {
    var clientWidth = docEl.clientWidth;
    if (!clientWidth)
    {
      return;
    } 
    else if(clientWidth>750){
      docEl.style.fontSize = 100 + 'px';
    }
    else if(clientWidth<=750)
    {
      docEl.style.fontSize = (clientWidth / 7.5) + 'px';
    }
  };
 
  if (!doc.addEventListener) return;
  win.addEventListener(resizeEvt, recalc, false);
  doc.addEventListener('DOMContentLoaded', recalc, false);
})(document, window);
(function() {
     var e = "abbr, article, aside, audio, canvas, datalist, details, dialog, eventsource, figure, footer, header, hgroup, mark, menu, meter, nav, output, progress, section, time, video".split(', ');
     var i= e.length;
     while (i--){
         document.createElement(e[i]);
     } 
})() 
var openid,
// localhost = "http://192.168.0.146:8094";
localhost = "http://yizhi.feibu.info/";
//提示框
var fb_time;
function fb_alert(msg){
  clearTimeout(fb_time)
  $(".bottom_aside").remove();
  if(msg == undefined){
    msg = "..."
  }
  var html = '<div class="bottom_aside">'+msg+'</div>';
  $("body").append(html);
  fb_time=setTimeout(function(){
    $(".bottom_aside").remove();
  },2000)
}
//加载中 1
function loading01(flag){
  if(flag){
    $(".loading").show()
  }else{
    $(".loading").hide()

  }
}
//加载中 2
function loading02(flag){
  if(flag){
    $(".loading02").show()
  }else{
    $(".loading02").hide()

  }
}
//获取时间
function getTime(n,time){
  //当天为0 昨天为-1 明天为1
  if(time == undefined){
    var day = new Date();
  }else{
    var day = new Date(time);
  }
  day.setTime(day.getTime()+n*24*60*60*1000);
  var s = day.getFullYear()+"-" + (day.getMonth()+1) + "-" + day.getDate();
  return s;
}
//获取参数
function GetString(name)
{
   var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
   var r = window.location.search.substr(1).match(reg); 
   if(r!=null)return  unescape(r[2]); return null;
}
//手机号码正则
function checkMobile(val){
    return /^1[3|4|5|8|7][0-9]{9}$/.test(val);
  }
$(function(){
	// $.ajaxSettings
   openid = "o9tfIwCMlIppPyujo1Oau9zOgYWI";
   //http://yizhi.feibu.info/server.php/user/login

   // window.location.href="http://yizhi.feibu.info/server.php/user/login";



})

