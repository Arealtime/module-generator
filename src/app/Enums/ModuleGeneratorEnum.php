<?php

namespace Arealtime\ModuleGenerator\App\Enums;

/**
 * Defines available actions for the module generator command.
 */
enum ModuleGeneratorEnum: string
{
    case Generate = 'generate';
    case Help = 'help';
    case List = 'list';
    case Remove = 'reomve';
}
