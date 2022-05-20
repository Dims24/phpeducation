<?php

namespace Foundation\HTTP;

enum HTTPMethodsEnum: string
{
    case Get = 'GET';
    case Post = 'POST';
    case Put = 'PUT';
    case Delete = 'DELETE';
}