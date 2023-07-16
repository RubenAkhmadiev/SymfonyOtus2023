<?php

namespace App\GraphQL\Service;

use App\Controller\Telegram\Dto\OrderPaymentDto;
use App\Entity\Order;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class OrderService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function createOrder(User $user, OrderPaymentDto $orderPaymentDto): ?int
    {
        $conn = $this->entityManager->getConnection();

        $order = new Order();
        $order->setUserId($user);
        $order->setNumber(random_int(10, 100));
        $order->setSum($orderPaymentDto->sum);
        $order->setCreationDate(new DateTime());
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        foreach ($orderPaymentDto->itemIds as $itemId) {
            $sql = <<<SQL
            insert into public.item_order (item_id, order_id)
            values (:item_id, :order_id)
        SQL;

            $conn->executeQuery($sql, ['item_id' => $itemId, 'order_id' => $order->getId()]);
        }

        return $order->getId();
    }

    public function userOrders(int $userId): ?array
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->find($userId);

        $arrayOrders = [];
        foreach ($user->getOrders() as $order) {
            $arrayOrders[] = $order->toArray();
        }

        return $arrayOrders;
    }
}
