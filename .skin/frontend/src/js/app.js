import "../css/app.scss";


$( ".hamburger" ).click(function() {
  $( this ).toggleClass( "is-active" );
});

$(function(){
  $(".inputfile").on("change",function(e){
    if (e.target.files.length) {
      console.log(e.target.files[0]);
      $(e.target).parent().find("span.title").text(e.target.files[0].name)
    } else {
      $(e.target).parent().find("span.title").text("Vyberte soubor")
    }
  })
})