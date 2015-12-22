myApp.controller('projectController', ['$scope', 'projectFactory', '$routeParams', 'snackbar', '$location', 'action', 'clientFactory', 'estimateFactory', 'timeEntry', 'commentFactory',

    function($scope, projectFactory, $routeParams, snackbar, $location, action, clientFactory, estimateFactory, timeEntry, commentFactory) {

        /*loading all projects*/
        if (action && action.projects != undefined) {
            action.projects.success(function(response) {
                console.log('all projects', response);
                $scope.projects = response;
            });
        }

        if (action && action.clients != undefined) {
            action.clients.success(function(response) {
                console.log('all clients', response);
                $scope.clients = response;
            });
        }

        /*Loading the comments for a project*/
        if (action && action.comments != undefined) {
            $scope.singleProjectId = $routeParams.pid;
            action.comments.success(function(response) {
                console.log('all comments', response);
                $scope.singleProject = response;
                $scope.comments = response;
            });
        }

        /*load single project data*/
        if ($routeParams.id) {
            $scope.singleProjectId = $routeParams.id;
            projectFactory.getProjectById($routeParams.id).success(function(response) {
                console.log('Single project', response);
                $scope.singleProject = response;
                $scope.singleProject.hours_allocated = 0;
                $scope.singleProject.hours_consumed = 0;

                angular.forEach(response.estimates, function(estimate, key) {
                    $scope.singleProject.hours_allocated += estimate.hours_allocated;
                    $scope.singleProject.hours_consumed += estimate.hours_consumed;
                });

                $scope.singleProject.percent_complete = $scope.singleProject.hours_consumed / $scope.singleProject.hours_allocated * 100;
                $scope.singleProject.percent_complete = parseFloat($scope.singleProject.percent_complete).toFixed(2);

                $scope.showSingleProject = true;
            });
        }

        /*When looking at an individual estimate*/
        if ($routeParams.estimateId) {
            /*Get the estimate details by id*/
            estimateFactory.getEstimateById($routeParams.estimateId).success(function(response) {
                console.log('Need to load estimate', response);
                $scope.singleEstimate = response;

                /*Get the project details by id*/
                projectFactory.getProjectById(response.project_id).success(function(response) {
                    console.log('Single project', response);
                    $scope.singleProject = response;
                    $scope.showSingleEstimate = true;

                    /*Get time entries for the estimate*/
                    timeEntry.getEntriesForEstimate($scope.singleEstimate.id).success(function(response) {
                        $scope.estimateTimes = response;
                        $scope.estimateTimes.total = 0;
                        angular.forEach(response, function(estimate, key) {
                            $scope.estimateTimes.total += estimate.time;
                        });

                        $scope.estimateTimes.total = parseFloat($scope.estimateTimes.total).toPrecision(2);
                        console.log('Time entries', response);
                    });
                });
            });
        }

        angular.extend($scope, {
            singleProject: {},
            showSingleProject: false,
            showSingleEstimate: false,
            newEstimateFormSubmit: false,
            projectEstimte: {},
            singleEstimate: {},
            newProject: {}
        });

        angular.extend($scope, {
            saveComment: function(addCommentForm) {
                if (addCommentForm.$valid) {
                    console.log($scope.newComment, $routeParams.pid);
                    var commentData = {
                        comment: $scope.newComment,
                        project_id: $routeParams.pid
                    };
                    commentFactory.saveComment(commentData).success(function(response) {
                        console.log(response);
                        $scope.singleProject = response;
                        $scope.comments = response;
                        $scope.newComment = "";
                    });
                } else {
                    $scope.newEstimateFormSubmit = true;
                    snackbar.create("Your form has errors!!", 1000);
                }
            },
            deleteProject: function() {
                var r = confirm("This will delete the project and it's time. Ok?");
                if (r === true) {
                    projectFactory.deleteProject($routeParams.id).success(function(response) {
                        $location.path('/projects');
                        snackbar.create("Project deleted", 1000);
                    });
                }
            },
            editEstiate: function(editEstimateForm) {
                if (editEstimateForm.$valid) {
                    var estimateData = {
                        id: $scope.singleEstimate.id,
                        desc: $scope.singleEstimate.desc,
                        hours_allocated: $scope.singleEstimate.hours_allocated,
                        status: $scope.singleEstimate.status
                    };

                    estimateFactory.updateEstimate(estimateData).success(function(response) {
                        console.log('estimate edited', response);
                        $location.path('/projects/' + $scope.singleProject.id);
                        snackbar.create("Estimate saved", 1000);
                    });
                } else {
                    $scope.newEstimateFormSubmit = true;
                    snackbar.create("Your form has errors!!", 1000);
                }
            },
            addNewProject: function(addProjectForm) {
                if (addProjectForm.$valid) {
                    console.log($scope.newProject);
                    var newProjectData = {
                        name: $scope.newProject.name,
                        client: $scope.newProject.client_id[0].id
                    };
                    projectFactory.saveNewProject(newProjectData).success(function(response) {
                        console.log('save new project', response);
                        $location.path('/projects');
                        snackbar.create("Project added", 1000);
                    })
                } else {
                    $scope.newEstimateFormSubmit = true;
                    snackbar.create("Your form has errors!!", 1000);
                }
            },
            saveProjectEstimate: function(addProjectEstimateForm) {
                if (addProjectEstimateForm.$valid) {
                    console.log('$scope.projectEstimte', $scope.projectEstimte);
                    var estimateData = {
                        project_id: $routeParams.id,
                        desc: $scope.projectEstimte.desc,
                        hours_allocated: $scope.projectEstimte.hours_allocated,
                    };

                    projectFactory.saveProjectEstimate(estimateData).success(function(response) {
                        console.log(response);
                        $location.path('/projects/' + $routeParams.id);
                        snackbar.create("Estimate added", 1000);
                    });
                } else {
                    $scope.newEstimateFormSubmit = true;
                    snackbar.create("Your form has errors!!", 1000);
                }
            }
        });
    }
]);
