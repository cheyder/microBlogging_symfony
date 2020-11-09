<?php


namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    /**
     * @return array|TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('price', [$this, 'priceFilter'])
        ];
    }

    /**
     * @param $number
     * @return string
     */
    public function priceFilter($number): string
    {
        return '$'.number_format($number, 2, '.', ',');
    }
}
