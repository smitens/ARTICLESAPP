<?php

return [

    ['GET', '/', 'IndexController'],

    ['GET', '/articles', 'GetAllController'],
    ['GET', '/article/{id:\d+}', 'GetByIdController'],

    ['GET', '/article/create', 'CreateFormController'],
    ['POST', '/article/create', 'CreateController'],

    ['GET', '/article/{id:\d+}/edit', 'UpdateFormController'],
    ['POST', '/article/{id:\d+}/edit', 'UpdateController'],

    ['GET', '/article/{id:\d+}/delete', 'DeleteFormController'],
    ['POST', '/article/{id:\d+}/delete', 'DeleteController'],
];
