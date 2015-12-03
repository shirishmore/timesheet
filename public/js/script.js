(function() {
    /*Populating the estimate drop down for a project on Time tracker add page*/
    $("#project-select").change(function() {
        var projectId = $(this).val();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: "POST",
            url: baseUrl + 'project/get-estimates',
            cache: false,
            data: {
                project_id: projectId
            }
        }).done(function(result) {
            $("#estimate-wrapper").html(result);
        });
    });

    /*User delete confirm and actual redirect code*/
    $('a.user-delete').click(function() {
        var deleteConfirm = confirm("Are you sure?");
        var userId = $(this).data('user-id');
        if (deleteConfirm == true) {
            window.location.replace(baseUrl + 'user/delete/' + userId);
        }
    });

    $('.delete-tracker').click(function() {
        var deleteConfirm = confirm("Are you sure?");
        if (deleteConfirm == true) {
            var trackerId = $(this).data('tracker-id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: baseUrl + 'time-tracker-delete',
                cache: false,
                data: {
                    trackerId: trackerId
                }
            }).success(function(result) {
                $("#tracker-" + trackerId).remove();
            });
        }
    });
})();
