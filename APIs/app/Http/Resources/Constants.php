<?php
define('API_INFO', 'This API is for Capstone, the current version of the API is \'v1\'');
define('PDF_Http_Content_Type', 'application/pdf');
define('JSON_Http_Content_Type', 'application/json');
define('Audio_Http_Content_Type', 'audio/mpeg');
define('JPEG_Http_Content_Type', 'image/jpeg');
define('PNG_Http_Content_Type', 'image/png');

define('AUTHORIZATION_HEADER', 'Authorization');
define('AUTHORIZATION_TYPE', 'JWT');

define('UNAUTHORIZED_MESSAGE', 'Unauthorized');
define('SERVER_FAULT_MESSAGE', 'Server fault was happened.');
define('MEMBER_NOT_FOUND_MESSAGE', 'No member data for the ID: %d.');
define('DEGREE_NOT_FOUND_MESSAGE', 'No degree data for the ID: %d.');
define('DIVISION_NOT_FOUND_MESSAGE', 'No division data for the ID: %d.');
define('DISTRICT_NOT_FOUND_MESSAGE', 'No district data for the ID: %d.');
define('GROUP_NOT_FOUND_MESSAGE', 'No group data for the ID: %d.');
define('CHRONOLOGY_NOT_FOUND_MESSAGE', 'No chronology data for the ID: %d.');
define('HISTORY_NOT_FOUND_MESSAGE', 'No history data for the ID: %d.');
define('GLOSSARY_NOT_FOUND_MESSAGE', 'No glossary data for the ID: %d.');
define('NO_DEGREE_MATCHES_DEGREE_HISTORY_MESSAGE', 'No Degree data matches a Degree History with ID %d');
define('NO_DEGREE_MATCHES_CEREMONIAL_MESSAGE', 'No Degree data matches a Ceremonial with ID %d');
define('NO_ORDER_MATCHES_DEGREE_MESSAGE', 'No Order data matches a Degree with ID %d');

define('TOKEN_MISSING_EXCEPTION_MESSAGE', 'No token field in headers.');
define('EMPTY_TOKEN_EXCEPTION_MESSAGE', 'Empty token.');
define('INVALID_TOKEN_EXCEPTION_MESSAGE', 'Invalid token.');
define('INVALID_TOKEN_EXCEPTION_WRONG_NUMBER_MESSAGE', 'Wrong number of segments');
define('MEMBER_NOT_FOUND_EXCEPTION_MESSAGE', 'No member data for the ID. The Request ID is %d.');
define('DEGREE_NOT_FOUND_EXCEPTION_MESSAGE', 'No degree data for the ID. The Request ID is %d.');
define('DIVISION_NOT_FOUND_EXCEPTION_MESSAGE', 'No division data for the ID. The Request ID is %d.');
define('DISTRICT_NOT_FOUND_EXCEPTION_MESSAGE', 'No district data for the ID. The Request ID is %d.');
define('GROUP_NOT_FOUND_EXCEPTION_MESSAGE', 'No group data for the ID. The Request ID is %d.');
define('CHRONOLOGY_NOT_FOUND_EXCEPTION_MESSAGE', 'No chronology data for the ID. The Request ID is %d.');
define('HISTORY_NOT_FOUND_EXCEPTION_MESSAGE', 'No history data for the ID. The Request ID is %d.');
define('GLOSSARY_NOT_FOUND_EXCEPTION_MESSAGE', 'No glossary data for the ID. The Request ID is %d.');

define('RESPONSE_FIELD_RESULT', 'result');
define('RESPONSE_FIELD_ACCESS', 'access');
define('RESPONSE_FIELD_MESSAGE', 'message');
define('RESPONSE_FIELD_ERROR', 'error');
define('RESPONSE_FIELD_DATA', 'data');
define('RESPONSE_FIELD_DATA_MEMBERS', 'members');
define('RESPONSE_FIELD_DATA_MEMBER', 'member');
define('RESPONSE_FIELD_DATA_DIVISIONS', 'divisions');
define('RESPONSE_FIELD_DATA_DIVISION', 'division');
define('RESPONSE_FIELD_DATA_CEREMONIALS', 'ceremonials');
define('RESPONSE_FIELD_DATA_REGALIA', 'regalia');
define('RESPONSE_FIELD_DATA_DEGREES', 'degrees');
define('RESPONSE_FIELD_DATA_DEGREE', 'degree');
define('RESPONSE_FIELD_DATA_DISTRICTS', 'districts');
define('RESPONSE_FIELD_DATA_DISTRICT', 'district');
define('RESPONSE_FIELD_DATA_GROUPS', 'groups');
define('RESPONSE_FIELD_DATA_GROUP', 'group');
define('RESPONSE_FIELD_DATA_CHRONOLOGIES', 'chronologies');
define('RESPONSE_FIELD_DATA_CHRONOLOGY', 'chronology');
define('RESPONSE_FIELD_DATA_HISTORIES', 'histories');
define('RESPONSE_FIELD_DATA_HISTORY', 'history');
define('RESPONSE_FIELD_DATA_GLOSSARIES', 'glossaries');
define('RESPONSE_FIELD_DATA_GLOSSARY', 'glossary');

define('SUCCESS_RESULT_RESPONSE', 'SUCCESS');
define('FAILED_RESULT_RESPONSE', 'FAILED');
define('ALLOWED_ACCESS_RESPONSE', 'ALLOWED');
define('DENIED_ACCESS_RESPONSE', 'DENIED');
