<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\MenuBundle\Library;

use Mindy\Bundle\MenuBundle\Model\Menu;
use Mindy\Template\Library\AbstractLibrary;
use Mindy\Template\TemplateEngine;

class MenuLibrary extends AbstractLibrary
{
    /**
     * @var TemplateEngine
     */
    protected $template;

    /**
     * MenuLibrary constructor.
     *
     * @param TemplateEngine $template
     */
    public function __construct(TemplateEngine $template)
    {
        $this->template = $template;
    }

    /**
     * @return array
     */
    public function getHelpers()
    {
        return [
            'render_menu' => function ($id, $template = 'menu/menu.html') {
                return $this->renderMenu($id, $template);
            },
            'get_menu' => function ($id) {
                return $this->getMenu($id);
            },
        ];
    }

    /**
     * @param string|int $id
     *
     * @return array
     */
    protected function getMenu($id): array
    {
        $menu = Menu::objects()->get(['pk' => $id]);
        if ($menu === null) {
            return [];
        }

        return $menu
            ->objects()
            ->descendants()
            ->asTree()
            ->all();
    }

    /**
     * @param string|int $id
     * @param string     $template
     *
     * @return null|string
     */
    protected function renderMenu($id, $template = 'menu/menu.html')
    {
        $items = $this->getMenu($id);

        if (empty($items)) {
            return null;
        }

        return $this->template->render($template, [
            'items' => $items,
        ]);
    }
}
