$(function() {
    /*Role delete confirm and actual redirect code*/
    $("a.delete-role").click(function(){
        var deleteConfirm = confirm("Are you sure you want to delete this role?");
        var roleId = $(this).data('id');
        if (deleteConfirm == true) {
            window.location.replace(baseUrl + 'role/delete/' + roleId);
        }
    });

})();