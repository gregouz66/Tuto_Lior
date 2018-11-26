$(function($) {
  function creerCookie(nom, valeur, jours) {
  // Le nombre de jours est spécifié
          if (jours) {
  var date = new Date();
                  // Converti le nombre de jour en millisecondes
  date.setTime(date.getTime()+(jours*24*60*60*1000));
  var expire = "; expire="+date.toGMTString();
  }
          // Aucune valeur de jours spécifiée
  else var expire = "";
  document.cookie = nom+"="+valeur+expire+"; path=/";
  }

  function lireCookie(name){
       if(document.cookie.length == 0)
         return null;

       var regSepCookie = new RegExp('(; )', 'g');
       var cookies = document.cookie.split(regSepCookie);

       for(var i = 0; i < cookies.length; i++){
         var regInfo = new RegExp('=', 'g');
         var infos = cookies[i].split(regInfo);
         if(infos[0] == name){
           return unescape(infos[1]);
         }
       }
   return null;
  }

  function manageCookie(){
    if(lireCookie('ppe3-ConfirmCookies') == null || lireCookie('ppe3-ConfirmCookies') != 1) { //Si le cookie n'existe pas
      $.confirm({
          icon: 'fa fa-question-circle-o',
          theme: 'supervan',
          title: 'Pas si vite !',
          closeIcon: false,
          content: 'En poursuivant votre navigation, vous acceptez le dépôt de cookies tiers destinés à vous proposer des vidéos, des boutons de partage, des remontées de contenus de plateformes sociales',
          animation: 'scale',
          type: 'orange',
          buttons: {
            Confirmer: function () {
                creerCookie('ppe3-ConfirmCookies', 1, 365);
                // $.alert('Confirmed!');
            },
            Refuser: function () {
                document.cookie.split(";").forEach(function(c) { document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); });
                // $.alert('Canceled!');
            }
          }
      });
    }
  }
  manageCookie();
});
