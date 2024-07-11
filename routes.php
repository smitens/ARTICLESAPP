<?php
return [
    ['GET', '/', 'IndexController'],

    ['GET', '/articles', 'Article\GetAllController'],
    ['GET', '/article/{id:\d+}', 'Article\GetByIdController'],

    ['GET', '/article/create', 'Article\CreateFormController'],
    ['POST', '/article/create', 'Article\CreateController'],

    ['GET', '/article/{id:\d+}/edit', 'Article\UpdateFormController'],
    ['POST', '/article/{id:\d+}/edit', 'Article\UpdateController'],

    ['GET', '/article/{id:\d+}/delete', 'Article\DeleteFormController'],
    ['POST', '/article/{id:\d+}/delete', 'Article\DeleteController'],

    ['POST', '/article/{id:\d+}/like', 'LikeController'],

    ['POST', '/comment/{id:\d+}/delete', 'Comment\DeleteCommentController'],
    ['POST', '/article/{id:\d+}/comment', 'Comment\CreateCommentController'],

    ['POST', '/comment/{id:\d+}/like', 'LikeController'],
];
