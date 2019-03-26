import "../css/app.scss";
import counterUp from 'counterup2'


const el_employee = document.querySelector('.counter-employee')
const el_company = document.querySelector('.counter-company')
const el_exp = document.querySelector('.counter-exp')
var counter = false

$(document).ready(function () {

  $(".hamburger").click(function () {
    $(this).toggleClass("is-active");
  });


  function isScrolledIntoView(elem) {
    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).height();

    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
  }

  if ($('.counter').length) {

    window.onscroll = function () {
      if (isScrolledIntoView('.counter')) {
        if (counter === false) {
          counterUp(el_employee, {
            duration: 5000,
            delay: 16,
          })
          counterUp(el_company, {
            duration: 5000,
            delay: 16,
          })
          counterUp(el_exp, {
            duration: 5000,
            delay: 16,
          })


          counter = true;
        }

      }
    }

    window.onscroll()
  }
})


