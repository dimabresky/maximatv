<!---------///////////////-------->    
<div class="cookpopup">
<p class="warningcook">Наш сайт использует файлы cookie для аналитики, рекламы и навигации в соответствии с 
    <a href="https://maximatv.ru/policy/" style="color:#0519b4;"> Политикой конфиденциальности </a>персональных данных. 
    Продолжая использовать этот сайт, вы соглашаетесь с использованием cookie-файлов. 
</p>
<button id="cookok"> Принять </button>
</div>
<script>
jQuery(document).ready(setTimeout(function(e){ 
    var results = document.cookie.match(/cookpopupdone=(.+?)(;|$)/);
    if (!results) {$(".cookpopup").fadeIn();}
},3000));
$("#cookok").on('click', function (e) {
       $(".cookpopup").fadeOut(); document.cookie = "cookpopupdone=ok;max-age=8500000";
});
</script>
<style>
.cookpopup {
    display: none;
    background-color: #ffffff;
    border: solid 2px #dfdedf;
    border-radius: 5px;
    box-shadow: 2px 2px 20px #d1c0de;
    position: fixed;
    width: 700px;
    max-width: 95%;
    bottom:10%;
    left: 50%;
    transform: translate(-50%, -10%);
    z-index: 10000;
}
.warningcook{
    text-align: center;
    padding: 0px 10px;
    margin: 10px 0;
    font: 15px / 20px sans-serif;
    color:#5c5a5a;
}
.warningcook a{text-decoration:none;
    font: 15px / 20px sans-serif;}
#cookok:hover{background-color: #9f4dff;}
#cookok{ 
    margin: 12px auto;
    align-self: center;
    display: block; 
    width: 120px;
    height: 40px; 
    background-color: #8942dc;
    /*background-color: #3549ff;*/
    color: white;
    border-radius: 5px;
    border-style: none;
    font-size: 15px;
    cursor:pointer;
}
</style>
<!---------/////////////--------> 