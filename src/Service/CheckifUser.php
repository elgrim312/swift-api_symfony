<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 26/04/18
 * Time: 18:21
 */

namespace App\Service;


use App\Repository\UserRepository;
use Symfony\Component\Serializer\SerializerInterface;

class CheckifUser
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUser($token)
    {
        $user = $this->userRepository->findOneByApiKey($token);

        if (is_null($user)) {
            return false;
        }

        return $user;
    }
}