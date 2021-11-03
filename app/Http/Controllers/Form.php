<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RetailCrm\Api\Interfaces\ClientExceptionInterface;
use RetailCrm\Api\Enum\CountryCodeIso3166;
use RetailCrm\Api\Enum\Customers\CustomerType;
use RetailCrm\Api\Factory\SimpleClientFactory;
use RetailCrm\Api\Interfaces\ApiExceptionInterface;
use RetailCrm\Api\Model\Entity\Orders\Delivery\OrderDeliveryAddress;
use RetailCrm\Api\Model\Entity\Orders\Delivery\SerializedOrderDelivery;
use RetailCrm\Api\Model\Entity\Orders\Items\Offer;
use RetailCrm\Api\Model\Entity\Orders\Items\OrderProduct;
use RetailCrm\Api\Model\Entity\Orders\Items\PriceType;
use RetailCrm\Api\Model\Entity\Orders\Items\Unit;
use RetailCrm\Api\Model\Entity\Orders\Order;
use RetailCrm\Api\Model\Entity\Orders\Payment;
use RetailCrm\Api\Model\Entity\Orders\SerializedRelationCustomer;
use RetailCrm\Api\Model\Request\Orders\OrdersCreateRequest;
use RetailCrm\Api\Model\Request\BySiteRequest;
use RetailCrm\Api\Enum\ByIdentifier;


class Form extends Controller
{
    public function send()
    {
        //Достаём данные из формы
        $InputSender = $_POST['name'];
        $InputComment = $_POST['comment'];
        $InputArticul = $_POST['Articul'];
        $InputBrand = $_POST['Brand'];

        //Обрабатываем ФИО
        $InputSender = explode(' ',$InputSender);
        if (isset($InputSender[1])) {
            $firstName = $InputSender[1];
        }
        if (isset($InputSender[2])) {
            $patronymic = $InputSender[2];
        }
        $lastName = $InputSender[0];


        //Подключение к API
        $client = \RetailCrm\Api\Factory\SimpleClientFactory::createClient('https://superposuda.retailcrm.pro', 'QlnRWTTWw9lv3kjxy1A8byjUmBQedYqb');
        $request         = new OrdersCreateRequest();
        $order           = new Order();
        $offer           = new Offer();
        $item            = new OrderProduct();

        /*
            //Обработка продукта по артикулу и бренду
        */

        //Создаем offer
        $offer->name        =  'some offer';
        $offer->displayName =  'some offer';
        $offer->id          =  'some id';
        $offer->article     =  $InputArticul;

        //Создаем item
        $item->offer        =  $offer;
        $item->productName  =  'some name';
        $item->id           =  'some id';

        //Создаем order
        $order->items           =  [$item];
        $order->number          =  '17051995';
        $order->orderType       =  'fizik';
        $order->orderMethod     =  'test';
        $order->firstName       =  $firstName;
        if (isset($lastName)) {
            $order->lastName    =  $lastName;
        }
        if (isset($patronymic)) {
            $order->patronymic  =  $patronymic;
        }
        $order->status          =  'trouble';
        $order->customerComment =  $InputComment;

        $request->order         =  $order;
        //Название магазина
        $request->site          =  'test';

        //Обработка запроса

        dd($request);
    }
}
