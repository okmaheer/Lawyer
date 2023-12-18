<?php
/**
 * Breadcrumbs registration
 *
 * @category Doctry
 *
 * @package Doctry
 * @author  Amentotech <theamentotech@gmail.com>
 * @license http://www.amentotech.com Amentotech
 * @link    http://www.amentotech.com
 */
Breadcrumbs::for(
    'home', function ($trail) {
        $trail->push(trans('lang.home'), route('home'));
    }
);

Breadcrumbs::for(
    'searchResults', function ($trail) {
        $trail->parent('home');
        $trail->push(trans('lang.search_results'), route('searchResults'));
    }
);

Breadcrumbs::for(
    'showPage', function ($trail, $page) {
        $trail->parent('home');
        if (!empty($page)) {
            $trail->push($page->title, route('showPage', ['slug' => $page->slug]));
        }
    }
);

Breadcrumbs::for(
    'articleListing', function ($trail) {
        $trail->parent('home');
        $trail->push(trans('lang.article_listing'), route('articleListing'));
    }
);

Breadcrumbs::for(
    'showArticle', function ($trail, $article) {
        $trail->parent('articleListing');
        if (!empty($article)) {
            $trail->push($article->title, route('articleDetail', ['slug' => $article->slug]));
        }
    }
);

Breadcrumbs::for(
    'userListing', function ($trail) {
        $trail->parent('home');
        $trail->push(trans('lang.users'), route('userListing'));
    }
);

Breadcrumbs::for(
    'userProfile', function ($trail, $user) {
        $trail->parent('home');
        $trail->push(trans('lang.profile'), route('userProfile', ['slug' => $user->slug]));
    }
);

Breadcrumbs::for(
    'forumQuestions', function ($trail) {
        $trail->parent('home');
        $trail->push(trans('lang.health_forum'), route('forumQuestions'));
    }
);

Breadcrumbs::for(
    'forumAnswers', function ($trail, $slug) {
        $trail->parent('forumQuestions');
        $trail->push(trans('lang.health_forum'), route('getForumAnswers' , ['slug' => $slug]));
    }
);
