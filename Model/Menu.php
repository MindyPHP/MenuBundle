<?php

/*
 * This file is part of MenuBundle.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\MenuBundle\Model;

use Mindy\Orm\Fields\CharField;
use Mindy\Orm\TreeModel;

/**
 * Class Menu.
 *
 * @property string $slug
 * @property string $name
 * @property string $url
 */
class Menu extends TreeModel
{
    public static function getFields()
    {
        return array_merge(parent::getFields(), [
            'slug' => [
                'class' => CharField::class,
                'null' => true,
            ],
            'name' => [
                'class' => CharField::class,
            ],
            'url' => [
                'class' => CharField::class,
                'null' => true,
            ],
        ]);
    }

    public function __toString()
    {
        return (string) $this->name;
    }
}
