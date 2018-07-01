<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BlogPost;

class PostController extends BaseController {

    public  function getIndex() {
        // admin/post or admin/posts/index
        $blogPosts = BlogPost::all();
        return $this->render('admin/posts.twig', ['blogPosts' => $blogPosts]);
    }

    public function getCreate() {
        // admin/posts/create
        return $this->render('admin/insert-post.twig');
    }

    public function postCreate() {
        // admin/posts/create
        $blogPosts = new BlogPost([
                'title' => $_POST['title'],
                'content' => $_POST['content']
        ]);
        $blogPosts->save();
        $result = true;
        return $this->render('admin/insert-post.twig', ['result' => $result]);
    }
}