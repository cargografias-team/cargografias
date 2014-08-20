$(function(){

    // Submit by ajax
    $(document).on('submit', 'form[data-remote="1"]', function(event) {
        event.stopPropagation();
        $.ajax({
               type: $(event.target).attr('type'),
               url: $(event.target).attr('action'),
               data: $(event.target).serialize(),
               success: function(data)
               {
                   eval(data);
               }
            });

        return false;
    });

    // Link by ajax
    
           // ANULADO POR AHORA, el "VER" habre en ventana nueva

     $(document).on('click', 'a[data-remote="1"]', function(event) {
         event.stopPropagation();
         $.ajax({
                type: "GET",
                url: $(this).attr('href'),
                success: function(data)
                {
                    eval(data);
                }
             });

         return false;
    });

});