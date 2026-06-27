<?php

namespace App\Controllers;

use App\Core\Controller\AbstractController;
use App\Core\Request;
use App\Repositories\LeadRepository;

class LeadController extends AbstractController
{
    public function submit(Request $request): \App\Core\Response
    {
        $name = trim($request->body('name', ''));
        $phone = trim($request->body('phone', ''));
        $serviceType = trim($request->body('service_type', ''));
        $email = trim($request->body('email', ''));
        $deviceBrand = trim($request->body('device_brand', ''));
        $deviceModel = trim($request->body('device_model', ''));
        $message = trim($request->body('message', ''));
        $honeypot = trim($request->body('website', ''));

        if ($honeypot !== '') {
            return $this->json(['success' => true]);
        }

        $errors = [];

        if ($name === '') {
            $errors[] = 'Укажите ваше имя';
        }

        if ($phone === '') {
            $errors[] = 'Укажите номер телефона';
        } elseif (!preg_match('/^[\+\d\s\-\(\)]{7,20}$/', $phone)) {
            $errors[] = 'Неверный формат телефона';
        }

        if ($serviceType === '') {
            $errors[] = 'Выберите тип услуги';
        }

        if (!empty($errors)) {
            return $this->json(['success' => false, 'errors' => $errors], 422);
        }

        $repo = new LeadRepository();
        $id = $repo->create([
            'name' => $name,
            'phone' => $phone,
            'email' => $email ?: null,
            'service_type' => $serviceType,
            'device_brand' => $deviceBrand ?: null,
            'device_model' => $deviceModel ?: null,
            'message' => $message ?: null,
            'source' => 'site',
            'ip' => $request->ip(),
        ]);

        return $this->json([
            'success' => true,
            'message' => 'Спасибо! Мы свяжемся с вами в ближайшее время.',
        ]);
    }

    public function adminList(Request $request): \App\Core\Response
    {
        $repo = new LeadRepository();
        $status = $request->query('status');
        $leads = $repo->findAll('created_at DESC', $status ?: null);

        $countNew = $repo->countByStatus('new');
        $countContacted = $repo->countByStatus('contacted');
        $countClosed = $repo->countByStatus('closed');

        $data = [
            'leads' => $leads,
            'currentStatus' => $status,
            'countNew' => $countNew,
            'countContacted' => $countContacted,
            'countClosed' => $countClosed,
        ];

        return $this->render('pages/admin/leads', $data, 'layouts/admin');
    }

    public function updateStatus(Request $request, string $id): \App\Core\Response
    {
        $status = $request->body('status', '');

        if (!in_array($status, ['new', 'contacted', 'closed'])) {
            return $this->json(['success' => false, 'error' => 'Invalid status'], 422);
        }

        $repo = new LeadRepository();
        $repo->updateStatus((int) $id, $status);

        return $this->json(['success' => true]);
    }

    public function delete(Request $request, string $id): \App\Core\Response
    {
        $repo = new LeadRepository();
        $repo->delete((int) $id);

        return $this->json(['success' => true]);
    }
}
