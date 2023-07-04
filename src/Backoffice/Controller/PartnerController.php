<?php

namespace App\Backoffice\Controller;

use App\Backoffice\RequestDto\Partner\{IndexRequestDto};
use App\Backoffice\View\Table;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PartnerController extends AbstractController
{
    use ValidateTrait;

    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validator,
    ) {
        $this->setValidator($validator);
    }

    #[Route(
        path: '/admin/partners',
        name: 'app_backoffice_partners_index',
        methods: ['GET']
    )]
    public function index(Request $request): Response
    {
        $dto = $this->validate($request, IndexRequestDto::class);

        $body = [
            ['a','b','c'],
            ['c','v','h'],
        ];

        return $this->render('backoffice/pages/partners/index.html.twig', [
            'partners' => (new Table())
                ->setHeader('1')
                ->setHeader('2')
                ->setHeader('3')
                ->setData($body)
        ]);
    }
}
