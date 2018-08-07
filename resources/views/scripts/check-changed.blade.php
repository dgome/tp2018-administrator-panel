<script type="text/javascript">
  $('.btn-change-pw').click(function(event) {
    event.preventDefault();
    $('.pw-change-container').slideToggle(100);
    $(this).find('.fa').toggleClass('fa-times');
    $(this).find('.fa').toggleClass('fa-lock');
    $(this).find('span').toggleText('', 'Cancel');


    //Utilizamos esto para intercambiar el valor de la clave, para indicar que se ha pulsado el botón de cambiar clave
    var hiddenField = $('#passwordchange'),
        val = hiddenField.val();

    hiddenField.val(val === "true" ? "false" : "true");


   


  });
  $("input").keyup(function() {
    checkChanged();
  });
  $("select").change(function() {
    checkChanged();
  });
  function checkChanged() {
    if(!$('input').val()){
      $(".btn-save").hide();
    }
    else {
      $(".btn-save").show();
    }
  }

</script>