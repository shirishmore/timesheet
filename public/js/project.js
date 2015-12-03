$(function() {
    /*Project delete confirm and actual redirect code*/
    $("a.delete-project").click(function(){
        var deleteConfirm = confirm("Are you sure you want to delete this project?");
        var projectId = $(this).data('id');
        if (deleteConfirm == true) {
            window.location.replace(baseUrl + 'project/delete/' + projectId);
        }
    });

})();
