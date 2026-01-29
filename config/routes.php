<?php

return [
    // Home routes
    '/' => 'PhotoController@index',
    '/home' => 'PhotoController@index',
    
    // Album routes
    '/albums' => 'AlbumController@index',
    '/albums/create' => 'AlbumController@create',
    '/albums/store' => 'AlbumController@store',
    '/albums/{id}' => 'AlbumController@show',
    '/albums/{id}/edit' => 'AlbumController@edit',
    '/albums/{id}/update' => 'AlbumController@update',
    '/albums/{id}/delete' => 'AlbumController@delete',
    
    // Photo routes
    '/photos' => 'PhotoController@index',
    '/photos/upload' => 'PhotoController@upload',
    '/photos/store' => 'PhotoController@store',
    '/photos/{id}' => 'PhotoController@show',
    '/photos/{id}/edit' => 'PhotoController@edit',
    '/photos/{id}/update' => 'PhotoController@update',
    '/photos/{id}/delete' => 'PhotoController@delete',
    '/photos/{id}/lightbox' => 'PhotoController@lightbox',
    
    // User authentication routes
    '/users/login' => 'UserController@login',
    '/users/authenticate' => 'UserController@authenticate',
    '/users/logout' => 'UserController@logout',
    '/users/register' => 'UserController@register',
    '/users/store' => 'UserController@store',
    '/users/profile' => 'UserController@profile',
    '/users/{id}' => 'UserController@show',
    
    // Favorite routes
    '/favorites' => 'FavoriteController@index',
    '/favorites/add/{photoId}' => 'FavoriteController@add',
    '/favorites/remove/{photoId}' => 'FavoriteController@remove',
    
    // Tag routes
    '/tags' => 'TagController@index',
    '/tags/{name}' => 'TagController@show',
    
    // Comment routes
    '/comments/store' => 'CommentController@store',
    '/comments/{id}/delete' => 'CommentController@delete',
    
    // Admin routes
    '/admin/dashboard' => 'AdminController@dashboard',
    '/admin/users' => 'AdminController@users',
    '/admin/albums' => 'AdminController@albums',
    '/admin/photos' => 'AdminController@photos',
    '/admin/comments' => 'AdminController@comments',
    '/admin/settings' => 'AdminController@settings',
    '/admin/logs' => 'AdminController@logs',
    '/admin/users/{id}/edit' => 'AdminController@editUser',
    '/admin/users/{id}/activate' => 'AdminController@activateUser',
    '/admin/users/{id}/deactivate' => 'AdminController@deactivateUser',
    '/admin/users/{id}/delete' => 'AdminController@deleteUser',
    '/admin/comments/{id}/approve' => 'AdminController@approveComment',
    '/admin/comments/{id}/unapprove' => 'AdminController@unapproveComment',
    '/admin/comments/{id}/delete' => 'AdminController@deleteComment',
    '/admin/cleanup/logs' => 'AdminController@cleanupLogs',
    
    // API routes
    '/api/photos' => 'ApiController@photos',
    '/api/albums' => 'ApiController@albums',
    '/api/search' => 'ApiController@search',
    '/api/photos/{id}' => 'ApiController@photoDetails',
    '/api/albums/{id}' => 'ApiController@albumDetails',
    '/api/users/{id}' => 'ApiController@userProfile',
    '/api/tags' => 'ApiController@tags',
    '/api/photos/upload' => 'ApiController@uploadPhoto',
    '/api/photos/{id}/favorite' => 'ApiController@toggleFavorite',
    '/api/photos/{id}/comments' => 'ApiController@getComments',
    '/api/comments/store' => 'ApiController@storeComment',
];
