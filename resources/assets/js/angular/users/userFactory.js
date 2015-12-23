myApp.factory('userFactory', ['$http', '$cookies',
    function($http, $cookies) {
        var userFactory = {};

        userFactory.logoutUser = function() {
            $cookies.remove('userObj');
            return $http.get(baseUrl + 'logout');
        }

        userFactory.getUserList = function() {
            return $http.get(baseUrl + 'api/get-user-list');
        }

        userFactory.getUserObj = function() {
            return $http.get(baseUrl + 'api/get-user_data');
        }

        userFactory.getUserListByRole = function() {
            /*Code for loading users by role id*/
            var role = [1,3];
            var jsonData=JSON.stringify(role);

            return $http({
                headers: {
                    'Content-Type': 'application/json'
                },
                url: baseUrl + 'api/get-user-list-by-role',
                method: 'POST',
                data:  jsonData
            });
        }

        return userFactory;
    }
]);
