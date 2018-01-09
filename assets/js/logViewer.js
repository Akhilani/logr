/**
 * View logs
 * @class logViewer
 */
var app = angular.module("logViewer", []);
/**
 *Log Controller
 * @class logCtrl
 * @memberOf logViewer
 * @param  {object} $http    {@link http://docs.angularjs.org/api/ng.$http}
 * @param (object) $scope
 */
app.controller("logCtrl", function($scope, $http) {

    /**
     * Reset variables before making new requests.
     * @function resetVariables
     * @memberOf logCtrl
     */
    $scope.resetVariables = function () {
        $scope.logs = [];
        $scope.msg = '';
        $scope.path = '';
        $scope.startingPoint = 0;
        $scope.logCount = 10;
        $scope.totalLogs = 0;
    }

    /**
     * Gets filePath and makes request after resetting variables
     * @function getLogs
     * @memberOf logCtrl
     */
    $scope.getLogs = function () {
        $scope.resetVariables();
        $scope.path = $scope.filePath;
        $scope.makeRequest();
        $scope.startingPoint += 10;
    }

    /**
     * Makes request to the backend PHP
     * Checks if filePath is available and makes request.
     * Sets response to proper model variables
     * @function makeRequest
     * @memberOf logCtrl
     */
    $scope.makeRequest = function () {
        if($scope.notUndefined($scope.path) && $scope.path !== ''){
            $http({
                url: "/response.php",
                method: "GET",
                params: { "path": $scope.path, "start": $scope.startingPoint, "count": $scope.logCount },
                headers : { 'Content-Type': 'application/json' }
            }).then(function successCallback(response) {
                if ($scope.notUndefined(response.data.logs)) {
                    $scope.logs = response.data.logs;
                    $scope.totalLogs = response.data.totalLogs;
                } else {
                    $scope.msg = $scope.strings.noLog;
                }

            });
        } else {
            $scope.msg = $scope.strings.noPath;
        }
    }

    /**
     * Go to next page
     * sets increments startingPoint by 10 and calls makeRequest() method
     * if startingPoint is already the page before last page, sets startingPoint to last page -10
     * @function nextPage
     * @memberOf logCtrl
     */
    $scope.nextPage = function () {
        if($scope.startingPoint + 10 > ($scope.totalLogs - 10)) {
            $scope.startingPoint = $scope.totalLogs - 10;
        } else {
            $scope.startingPoint += 10;
        }
        $scope.makeRequest();
    }

    /**
     * Go to previous page
     * sets decrements startingPoint by 10 and calls makeRequest() method
     * if startingPoint is already less than 10, sets startingPoint to 0
     * @function previousPage
     * @memberOf logCtrl
     */
    $scope.previousPage = function () {
        if($scope.startingPoint < 10 ) {
            $scope.startingPoint = 0;
        } else {
            $scope.startingPoint -= 10;
        }
        $scope.makeRequest();
    }

    /**
     * Go to first page
     * sets startingPoint to 0 and calls makeRequest() method
     * @function firstPage
     * @memberOf logCtrl
     */
    $scope.firstPage = function () {
        $scope.startingPoint = 0;
        $scope.makeRequest();
    }

    /**
     * Go to last page
     * sets startingPoint to last log -10 and calls makeRequest() method
     * @function lastPage
     * @memberOf logCtrl
     */
    $scope.lastPage = function () {
        $scope.startingPoint = $scope.totalLogs - 10;
        $scope.makeRequest();
    }

    /**
     * Check if passed variable if undefined
     * @function notUndefined
     * @memberOf logCtrl
     * @param x
     * @returns {boolean}
     */
    $scope.notUndefined = function (x) {
        return (typeof x !== 'undefined');
    }

    /**
     * Variable to store error messages
     * @field strings
     * @memberOf logCtrl
     * @type {{noLog: string, noPath: string}}
     */
    $scope.strings = {
        "noLog" : "No log found. File empty or incorrect path.",
        "noPath" : "Please provide path to find log file."
    };
});