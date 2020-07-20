<?php

$menu_array = [
    0 => [
        'anchor' => 'Главная',
        'link'   => '#'
    ],
    1 => [
        'anchor' => 'Новости',
        'link'   => '#',
        'submenu' => [
            0 => [
                'anchor' => 'Новости о спорте',
                'link'   => '#'
            ],
            1 => [
                'anchor' => 'Новости о политеке',
                'link'   => '#'
            ],
            2 => [
                'anchor' => 'Новости о мире',
                'link'   => '#'
            ],
        ]
    ],
    2 => [
        'anchor' => 'Контакты',
        'link'   => '#'
    ],
    3 => [
        'anchor' => 'Справка',
        'link'   => '#'
    ]
];

function get_menu($menu_array){
    $result = '<nav>' . PHP_EOL;
    foreach ($menu_array as $item) {
        if(isset($item['link']) && $item['link'] != ''){
            $result.= "<div><a href='{$item['link']}'><span>{$item['anchor']}</span></a>" . PHP_EOL;
        } else {
            $result.= "<div>{$item['anchor']}" . PHP_EOL;
        }
        if(isset($item['submenu'])){
            $result .= '<div>' . PHP_EOL;
            foreach($item['submenu'] as $subitem){
                $result.= "<a href='{$subitem['link']}'>{$subitem['anchor']}</a>" .PHP_EOL;
            }
            $result .= '</div>' . PHP_EOL;
        }
        $result .= '</div>' . PHP_EOL;
    }
    return $result . '</nav>';
}

$template = file_get_contents('./index.html');
$template = str_replace('{MENU}', get_menu($menu_array), $template);

echo $template;