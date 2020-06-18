<?php

include_once __DIR__ . '/../app/Http/Resources/Constants.php';

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/**
 * @apiDefine UnauthorizedError
 * @apiError (Error 401) {String}  result    Request result status.
 * @apiError (Error 401) {String}  access    Request access status.
 * @apiError (Error 401) {String}  message   Response message.
 * @apiError (Error 401) {String}  error     Response error.
 * @apiErrorExample {json} Error-Response:
 *     HTTP/1.1 401 Not Found
 *     {
 *          "result": "FAILED",
 *          "access": "DENIED",
 *          "message": "Unauthorized",
 *          "error": "No token field in headers."
 *      }
 */

$router->group(['prefix' => config('app.api_prefix')], function () use ($router) {
    /**
     * 
     * @api {get} / Get the API information.
     * @apiName GetRoot
     * @apiGroup Root
     * @apiVersion  0.1.0
     * 
     * 
     * @apiSuccessExample {String} Success-Response:
     *     HTTP/1.1 200 OK
     *     "This API is for Capstone, the current version of the API is 'v1'"
     * 
     */
    $router->get('/', function () use ($router) {
        return API_INFO;
    });

    $router->group(['prefix' => 'v1', 'middleware' => 'auth'], function () use ($router) {

        /**
         * 
         * @api {get} /member      1.Get all members profile details.
         * @apiName @getAll
         * @apiGroup Member
         * @apiVersion 0.1.0
         * 
         * 
         * @apiHeader  {String}    Authorization           Authentication token, format: 'JWT example-token'.
         * 
         * @apiSuccess {String}    result                  Request result status
         * @apiSuccess {String}    access                 Response message, this field should be hidden if there is no message.
         * @apiSuccess {String}    message                 
         * @apiSuccess {String}    error                 
         * @apiSuccess {Object[]}    members 
         * 
         * 
         * 
         * @apiSuccessExample Success-Response:
         *     HTTP/1.1 200 OK
         *     {
         *          "result": "SUCCESS",
         *          "access": "ALLOWED",
         *          "message": "message",
         *          "error": "error",
         *          "members": [{
         *              "Id": 1,
         *              "Title": "Title",
         *              "FirstNames": "FirstNames",
         *              "MiddleNames": "MiddleNames",
         *              "LastNames": "LastNames",
         *              "KnownAs": "KnownAs",
         *              "MailName": "MailName",
         *              "DOB": "01/10/1949",
         *              "PhotoFileName": "PhotoFileName",
         *              "Address": {
         *                  "Id": 1,
         *                  "Active": true,
         *                  "StartDate": "StartDate",
         *                  "EndDate": "EndDate",
         *                  "Building": "Building",
         *                  "Street": "Street",
         *                  "TownCity": "TownCity",
         *                  "PostCode": "PostCode",
         *                  "Country": "Country"
         *              },
         *              "History": [{
         *                  "Id": 1,
         *                  "Member_ID": 2,
         *                  "Group_ID": 3,
         *                  "Member_End_Reason_ID": 4,
         *                  "StartDate": "10/06/2020",
         *                  "EndDate": "15/06/2020",
         *                  "Capitation": "Capitation",
         *                  "Notes": "Notes",
         *                  "EndReason": {
         *                      "Id": 1,
         *                      "Name": "Name",
         *                      "Description": "Description"
         *                  }
         *              }]
         *          }]
         *      }
         * 
         * @apiUse UnauthorizedError
         */
        $router->get('/member', 'MemberController@getAll');

        /**
         * 
         * @api {get} /member/{ID} 2.Get the specific member profile detail by given the id.
         * @apiName @getMember
         * @apiGroup Member
         * @apiVersion 0.1.0
         * 
         * 
         * @apiHeader  {String}    Authorization           Authentication token, format: 'JWT example-token'.
         * 
         * @apiSuccess {String}    result                  Request result status
         * @apiSuccess {String}    message                 Response message, this field should be hidden if there is no message.
         * @apiSuccess {Object}    profile                 Profile
         * @apiSuccess {String}    profile.Picture         Picture name
         * @apiSuccess {String}    profile.FullName        Full name of the user
         * @apiSuccess {String}    profile.Username        Username of the user
         * @apiSuccess {String}    profile.Address         Address of the user
         * @apiSuccess {String}    profile.Suburb          Suburb
         * @apiSuccess {String}    profile.CityOrTown      City Or Town
         * @apiSuccess {Number}    profile.Postcode        Postcode
         * 
         * @apiSuccessExample Success-Response:
         *     HTTP/1.1 200 OK
         *     {
         *          "result": "SUCCESS",
         *          "access": "ALLOWED",
         *          "message": "message",
         *          "error": "error",
         *          "member": {
         *              "Id": 1,
         *              "Title": "Title",
         *              "FirstNames": "FirstNames",
         *              "MiddleNames": "MiddleNames",
         *              "LastNames": "LastNames",
         *              "KnownAs": "KnownAs",
         *              "MailName": "MailName",
         *              "DOB": "01/10/1949",
         *              "PhotoFileName": "PhotoFileName",
         *              "Address": {
         *                  "Id": 1,
         *                  "Active": true,
         *                  "StartDate": "StartDate",
         *                  "EndDate": "EndDate",
         *                  "Building": "Building",
         *                  "Street": "Street",
         *                  "TownCity": "TownCity",
         *                  "PostCode": "PostCode",
         *                  "Country": "Country"
         *              },
         *              "History": [{
         *                  "Id": 1,
         *                  "Member_ID": 2,
         *                  "Group_ID": 3,
         *                  "Member_End_Reason_ID": 4,
         *                  "StartDate": "10/06/2020",
         *                  "EndDate": "15/06/2020",
         *                  "Capitation": "Capitation",
         *                  "Notes": "Notes",
         *                  "EndReason": {
         *                      "Id": 1,
         *                      "Name": "Name",
         *                      "Description": "Description"
         *                  }
         *              }]
         *          }
         *      }
         * 
         * @apiUse UnauthorizedError
         */
        $router->get('/member/{id:[\d]+}', 'MemberController@getMember');
    });
});
