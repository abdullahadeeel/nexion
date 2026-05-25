<?php

namespace App\Controllers;

use Phpify\Core\Controller\BaseController;
use Phpify\Core\Http\Request;

class HomeController extends BaseController
{
    public function index(Request $request)
    {
        return $this->render('home', [
            'title' => 'Welcome to phpify',
            'features' => ['Routing', 'Middleware', 'ORM', 'Template Engine']
        ]);
    }

    public function show(Request $request, $id)
    {
        return $this->json([
            'user_id' => $id,
            'message' => 'User details fetched successfully'
        ]);
    }
}
