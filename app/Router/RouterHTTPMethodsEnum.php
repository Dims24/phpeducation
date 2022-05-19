<?php

namespace Router;

enum RouterHTTPMethodsEnum: string
{
    case Get = 'GET';
    case Post = 'POST';
    case Put = 'PUT';
    case Delete = 'DELETE';
}