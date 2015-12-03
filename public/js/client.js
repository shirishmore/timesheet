$(function() {
    /*Client delete confirm and actual redirect code*/
    $("a.delete-client").click(function(){
        var deleteConfirm = confirm("Are you sure you want to delete this client?");
        var clientId = $(this).data('id');
        if (deleteConfirm == true) {
            window.location.replace(baseUrl + 'clients/delete/' + clientId);
        }
    });

})();
