<?php

namespace App\Controllers;

use App\Core\Controller\AbstractController;
use App\Core\Request;
use App\Repositories\ServiceRepository;
use App\Repositories\PageRepository;

class HomeController extends AbstractController
{
    public function index(Request $request): \App\Core\Response
    {
        $serviceRepo = new ServiceRepository();
        $pageRepo = new PageRepository();

        $services = $serviceRepo->findAllActive();
        $hero = $pageRepo->findBySection('hero');
        $advantages = $pageRepo->findBySection('advantages');
        $reviews = $pageRepo->findBySection('reviews');
        $contacts = $pageRepo->findBySection('contacts');

        $data = [
            'services' => $services,
            'hero' => $hero[0] ?? null,
            'advantages' => $advantages,
            'reviews' => $reviews,
            'contacts' => $contacts,
        ];

        return $this->render('pages/home', $data, 'layouts/main');
    }

    public function service(Request $request, string $slug): \App\Core\Response
    {
        $serviceRepo = new ServiceRepository();
        $service = $serviceRepo->findBySlug($slug);

        if (!$service) {
            return $this->render('pages/404', [], 'layouts/main')->setStatusCode(404);
        }

        return $this->render('pages/service', ['service' => $service], 'layouts/main');
    }
}
