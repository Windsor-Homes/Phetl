<?php

namespace Windsor\Phetl\Enums;

enum StringCaseType: string
{
    case LOWER = 'lower';
    case UPPER = 'upper';
    case TITLE = 'title';
    case SNAKE = 'snake';
    case CAMEL = 'camel';
    case PASCAL = 'pascal';
    case KEBAB = 'kebab';

}