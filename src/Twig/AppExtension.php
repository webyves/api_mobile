<?php
namespace App\Twig;

use App\Twig\AppRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('myHTML', array(AppRuntime::class, 'myHTML'), array('is_safe' => array('html'))),
        );
    }
}
