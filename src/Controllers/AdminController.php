<?php

namespace App\Controllers;

use App\Core\Controller\AbstractController;
use App\Core\Request;
use App\Core\Database;
use App\Repositories\LeadRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\PageRepository;

class AdminController extends AbstractController
{
    public function loginForm(Request $request): \App\Core\Response
    {
        if ($_SESSION['admin_logged_in'] ?? false) {
            return $this->redirect('/admin');
        }
        return $this->render('pages/admin/login', [], 'layouts/admin');
    }

    public function login(Request $request): \App\Core\Response
    {
        $username = $request->body('username', '');
        $password = $request->body('password', '');

        $admin = Database::instance()->fetchOne(
            'SELECT * FROM admins WHERE username = ?',
            [$username]
        );

        if ($admin && password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];

            return $this->json(['success' => true, 'redirect' => '/admin']);
        }

        return $this->json(['success' => false, 'error' => 'Неверные логин или пароль'], 401);
    }

    public function logout(Request $request): \App\Core\Response
    {
        unset($_SESSION['admin_logged_in'], $_SESSION['admin_id'], $_SESSION['admin_username']);
        session_destroy();
        return $this->redirect('/admin/login');
    }

    public function dashboard(Request $request): \App\Core\Response
    {
        $leadRepo = new LeadRepository();
        $serviceRepo = new ServiceRepository();

        $data = [
            'leadsNew' => $leadRepo->countByStatus('new'),
            'leadsTotal' => $leadRepo->countByStatus(),
            'servicesCount' => count($serviceRepo->findAllActive()),
            'recentLeads' => $leadRepo->findAll('created_at DESC'),
        ];

        return $this->render('pages/admin/dashboard', $data, 'layouts/admin');
    }

    public function services(Request $request): \App\Core\Response
    {
        $repo = new ServiceRepository();
        $services = $repo->findAll();

        return $this->render('pages/admin/services', ['services' => $services], 'layouts/admin');
    }

    public function saveService(Request $request, ?string $id = null): \App\Core\Response
    {
        $repo = new ServiceRepository();

        $data = [
            'slug' => $request->body('slug'),
            'title' => $request->body('title'),
            'description' => $request->body('description'),
            'icon' => $request->body('icon'),
            'meta_title' => $request->body('meta_title', ''),
            'meta_description' => $request->body('meta_description'),
            'sort_order' => (int) $request->body('sort_order', 0),
            'is_active' => (int) $request->body('is_active', 1),
        ];

        if ($id) {
            $repo->update((int) $id, $data);
        } else {
            $repo->create($data);
        }

        return $this->redirect('/admin/services');
    }

    public function deleteService(Request $request, string $id): \App\Core\Response
    {
        $repo = new ServiceRepository();
        $repo->delete((int) $id);
        return $this->redirect('/admin/services');
    }

    public function pages(Request $request): \App\Core\Response
    {
        $repo = new PageRepository();
        $pages = $repo->findAll();

        return $this->render('pages/admin/pages', ['pages' => $pages], 'layouts/admin');
    }

    public function savePage(Request $request, ?string $id = null): \App\Core\Response
    {
        $repo = new PageRepository();

        $data = [
            'slug' => $request->body('slug'),
            'title' => $request->body('title'),
            'subtitle' => $request->body('subtitle'),
            'content' => $request->body('content'),
            'meta_title' => $request->body('meta_title', ''),
            'meta_description' => $request->body('meta_description'),
            'section' => $request->body('section'),
            'sort_order' => (int) $request->body('sort_order', 0),
            'is_active' => (int) $request->body('is_active', 1),
        ];

        if ($id) {
            $repo->update((int) $id, $data);
        } else {
            $repo->create($data);
        }

        return $this->redirect('/admin/pages');
    }

    public function deletePage(Request $request, string $id): \App\Core\Response
    {
        $repo = new PageRepository();
        $repo->delete((int) $id);
        return $this->redirect('/admin/pages');
    }
}
